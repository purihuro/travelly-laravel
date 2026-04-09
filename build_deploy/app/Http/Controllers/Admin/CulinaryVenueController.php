<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCulinaryVenueRequest;
use App\Http\Requests\Admin\UpdateCulinaryVenueRequest;
use App\Models\CulinaryMenuItem;
use App\Models\CulinaryVenue;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CulinaryVenueController extends Controller
{
    public function index(Request $request): View
    {
        $culinaryVenues = CulinaryVenue::query()
            ->withCount('culinaryPackages')
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
            })
            ->when($request->string('status')->toString(), function ($query, $status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                }

                if ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.culinary-venues.index', compact('culinaryVenues'));
    }

    public function create(): View
    {
        return view('admin.culinary-venues.create');
    }

    public function store(StoreCulinaryVenueRequest $request): RedirectResponse
    {
        CulinaryVenue::query()->create($this->validatedData($request));

        return redirect()->route('admin.culinary-venues.index')->with('status', 'Rumah makan berhasil ditambahkan.');
    }

    public function show(CulinaryVenue $culinaryVenue): View
    {
        $culinaryVenue->loadCount('culinaryPackages', 'bookingCulinaries');
        $culinaryVenue->load([
            'culinaryPackages' => fn ($query) => $query->latest()->limit(10),
            'bookingCulinaries' => fn ($query) => $query->with('booking')->latest()->limit(10),
        ]);

        return view('admin.culinary-venues.show', compact('culinaryVenue'));
    }

    public function edit(CulinaryVenue $culinaryVenue): View
    {
        $culinaryVenue->loadCount('culinaryPackages', 'bookingCulinaries');
        $culinaryVenue->load([
            'culinaryPackages' => fn ($query) => $query->latest()->limit(20),
            'bookingCulinaries' => fn ($query) => $query->with('booking')->latest()->limit(10),
        ]);

        $culinaryVenues = CulinaryVenue::query()->orderBy('name')->get();
        $menuItemsByVenue = CulinaryMenuItem::query()
            ->with('culinaryVenue:id,name')
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('culinary_venue_id');
        $defaultVenueId = (int) $culinaryVenue->id;

        return view('admin.culinary-venues.edit', compact('culinaryVenue', 'culinaryVenues', 'menuItemsByVenue', 'defaultVenueId'));
    }

    public function update(UpdateCulinaryVenueRequest $request, CulinaryVenue $culinaryVenue): RedirectResponse
    {
        $culinaryVenue->update($this->validatedData($request, $culinaryVenue));

        return redirect()->route('admin.culinary-venues.edit', $culinaryVenue)->with('status', 'Rumah makan berhasil diperbarui.');
    }

    public function destroy(CulinaryVenue $culinaryVenue): RedirectResponse
    {
        $culinaryVenue->loadCount('culinaryPackages', 'bookingCulinaries');

        if (($culinaryVenue->culinary_packages_count ?? 0) > 0 || ($culinaryVenue->booking_culinaries_count ?? 0) > 0) {
            return redirect()->route('admin.culinary-venues.index')
                ->with('error', 'Rumah makan tidak bisa dihapus karena masih punya paket atau riwayat booking.');
        }

        try {
            if ($culinaryVenue->image && str_starts_with($culinaryVenue->image, 'storage/culinary-venues/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $culinaryVenue->image));
            }

            $culinaryVenue->delete();
        } catch (QueryException $exception) {
            return redirect()->route('admin.culinary-venues.index')
                ->with('error', 'Rumah makan tidak bisa dihapus karena masih terkait data lain.');
        }

        return redirect()->route('admin.culinary-venues.index')->with('status', 'Rumah makan berhasil dihapus.');
    }

    private function validatedData(StoreCulinaryVenueRequest|UpdateCulinaryVenueRequest $request, ?CulinaryVenue $culinaryVenue = null): array
    {
        $data = $request->validated();

        if ($request->boolean('remove_image') && $culinaryVenue?->image && str_starts_with($culinaryVenue->image, 'storage/culinary-venues/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $culinaryVenue->image));
            $data['image'] = null;
        }

        if ($request->hasFile('image_upload')) {
            if ($culinaryVenue?->image && str_starts_with($culinaryVenue->image, 'storage/culinary-venues/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $culinaryVenue->image));
            }

            $path = $request->file('image_upload')->store('culinary-venues', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active');
        unset($data['image_upload'], $data['remove_image']);

        return $data;
    }
}
