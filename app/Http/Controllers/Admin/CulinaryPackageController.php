<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCulinaryPackageRequest;
use App\Http\Requests\Admin\UpdateCulinaryPackageRequest;
use App\Models\CulinaryPackage;
use App\Models\CulinaryVenue;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CulinaryPackageController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('admin.culinary-venues.index');
    }

    public function create(Request $request): View
    {
        $culinaryVenues = CulinaryVenue::query()->orderBy('name')->get();
        $defaultVenueId = null;

        $requestedVenueId = $request->integer('culinary_venue_id');
        if ($requestedVenueId > 0 && $culinaryVenues->contains('id', $requestedVenueId)) {
            $defaultVenueId = $requestedVenueId;
        }

        return view('admin.culinary-packages.create', compact('culinaryVenues', 'defaultVenueId'));
    }

    public function store(StoreCulinaryPackageRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = $this->preparePackagePayload($request);
            $package = CulinaryPackage::query()->create($data);
            $this->syncPackageGalleries($request, $package);
        });

        $returnVenueId = $request->integer('return_venue_id');
        if ($returnVenueId > 0) {
            return redirect()->route('admin.culinary-venues.edit', $returnVenueId)->with('status', 'Paket kuliner berhasil ditambahkan.');
        }

        return redirect()->route('admin.culinary-packages.index')->with('status', 'Paket kuliner berhasil ditambahkan.');
    }

    public function show(CulinaryPackage $culinaryPackage): View
    {
        $culinaryPackage->load('culinaryVenue', 'galleries');
        $culinaryPackage->loadCount('bookingCulinaries');
        $culinaryPackage->load([
            'bookingCulinaries' => fn ($query) => $query->with('booking')->latest()->limit(10),
        ]);

        return view('admin.culinary-packages.show', compact('culinaryPackage'));
    }

    public function edit(CulinaryPackage $culinaryPackage): View
    {
        $culinaryPackage->load('galleries');
        $culinaryVenues = CulinaryVenue::query()->orderBy('name')->get();

        return view('admin.culinary-packages.edit', compact('culinaryPackage', 'culinaryVenues'));
    }

    public function update(UpdateCulinaryPackageRequest $request, CulinaryPackage $culinaryPackage): RedirectResponse
    {
        DB::transaction(function () use ($request, $culinaryPackage): void {
            $data = $this->preparePackagePayload($request);
            $culinaryPackage->update($data);
            $this->syncPackageGalleries($request, $culinaryPackage);
        });

        return redirect()->route('admin.culinary-packages.edit', $culinaryPackage)->with('status', 'Paket kuliner berhasil diperbarui.');
    }

    public function quickUpdate(Request $request, CulinaryPackage $culinaryPackage): RedirectResponse
    {
        $validated = $request->validate([
            'price_per_person' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'return_venue_id' => ['nullable', 'integer', 'exists:culinary_venues,id'],
        ]);

        $culinaryPackage->update([
            'price_per_person' => (float) $validated['price_per_person'],
            'discount_amount' => 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        $returnVenueId = (int) ($validated['return_venue_id'] ?? $culinaryPackage->culinary_venue_id);

        return redirect()->route('admin.culinary-venues.edit', $returnVenueId)->with('status', 'Paket kuliner berhasil diperbarui.');
    }

    public function destroy(Request $request, CulinaryPackage $culinaryPackage): RedirectResponse
    {
        $returnVenueId = $request->integer('return_venue_id');

        try {
            $culinaryPackage->load('galleries');
            foreach ($culinaryPackage->galleries as $gallery) {
                if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
            }

            $culinaryPackage->delete();
        } catch (QueryException $exception) {
            if ($returnVenueId > 0) {
                return redirect()->route('admin.culinary-venues.edit', $returnVenueId)
                    ->with('error', 'Paket kuliner tidak bisa dihapus karena sudah terkait data booking.');
            }

            return redirect()->route('admin.culinary-packages.index')
                ->with('error', 'Paket kuliner tidak bisa dihapus karena sudah terkait data booking.');
        }

        if ($returnVenueId > 0) {
            return redirect()->route('admin.culinary-venues.edit', $returnVenueId)
                ->with('status', 'Paket kuliner berhasil dihapus.');
        }

        return redirect()->route('admin.culinary-packages.index')->with('status', 'Paket kuliner berhasil dihapus.');
    }

    private function preparePackagePayload(StoreCulinaryPackageRequest|UpdateCulinaryPackageRequest $request): array
    {
        $data = $request->validated();
        $data['culinary_venue_id'] = $this->resolveCulinaryVenueId($data);
        $data['is_active'] = $request->boolean('is_active');
        $data['discount_amount'] = 0;
        $data['min_people'] = max(1, (int) ($data['min_people'] ?? 1));
        $data['max_people'] = isset($data['max_people']) && $data['max_people'] !== '' ? (int) $data['max_people'] : null;
        unset(
            $data['venue_name'],
            $data['venue_location'],
            $data['venue_description']
        );

        return $data;
    }

    private function resolveCulinaryVenueId(array $data): int
    {
        if (! empty($data['culinary_venue_id'])) {
            return (int) $data['culinary_venue_id'];
        }

        $venueName = trim((string) ($data['venue_name'] ?? ''));

        $existing = CulinaryVenue::query()
            ->whereRaw('LOWER(name) = ?', [Str::lower($venueName)])
            ->first();

        if ($existing) {
            return (int) $existing->id;
        }

        $baseSlug = Str::slug($venueName);
        if ($baseSlug === '') {
            $baseSlug = 'rumah-makan';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (CulinaryVenue::query()->where('slug', $slug)->exists()) {
            $counter++;
            $slug = $baseSlug . '-' . $counter;
        }

        $venue = CulinaryVenue::query()->create([
            'name' => $venueName,
            'slug' => $slug,
            'location' => $data['venue_location'] ?? null,
            'description' => $data['venue_description'] ?? null,
            'is_active' => true,
        ]);

        return (int) $venue->id;
    }

    private function syncPackageGalleries(Request $request, CulinaryPackage $package): void
    {
        $removeIds = collect($request->input('remove_gallery_image_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();

        if ($removeIds->isNotEmpty()) {
            $galleries = $package->galleries()->whereIn('id', $removeIds->all())->get();
            foreach ($galleries as $gallery) {
                if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                    Storage::disk('public')->delete($gallery->image_path);
                }

                $gallery->delete();
            }
        }

        if (! $request->hasFile('gallery_images')) {
            return;
        }

        $nextSort = (int) $package->galleries()->max('sort_order') + 1;
        foreach ((array) $request->file('gallery_images', []) as $uploadedImage) {
            if (! $uploadedImage) {
                continue;
            }

            $path = $uploadedImage->store('culinary-packages', 'public');
            $package->galleries()->create([
                'image_path' => $path,
                'sort_order' => $nextSort++,
            ]);
        }
    }
}
