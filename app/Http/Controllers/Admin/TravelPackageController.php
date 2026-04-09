<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTravelPackageRequest;
use App\Http\Requests\Admin\UpdateTravelPackageRequest;
use App\Models\TravelPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TravelPackageController extends Controller
{
    public function index(Request $request): View
    {
        $packages = TravelPackage::query()
            ->withCount(['galleryImages', 'bookingItems'])
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
            })
            ->when($request->string('category')->toString(), fn ($query, $category) => $query->where('category', $category))
            ->when($request->string('status')->toString(), function ($query, $status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                }

                if ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = TravelPackage::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values()
            ->all();

        return view('admin.travel-packages.index', compact('packages', 'categories'));
    }

    public function create(): View
    {
        return view('admin.travel-packages.create');
    }

    public function store(StoreTravelPackageRequest $request): RedirectResponse
    {
        $travelPackage = TravelPackage::query()->create($this->validatedData($request));
        $this->syncGalleryImages($request, $travelPackage);

        return redirect()->route('admin.travel-packages.index')->with('status', 'Paket wisata berhasil ditambahkan.');
    }

    public function show(TravelPackage $travelPackage): View
    {
        $travelPackage->loadCount('bookingItems');
        $travelPackage->load('galleryImages');

        return view('admin.travel-packages.show', compact('travelPackage'));
    }

    public function edit(TravelPackage $travelPackage): View
    {
        $travelPackage->load('galleryImages');

        return view('admin.travel-packages.edit', compact('travelPackage'));
    }

    public function update(UpdateTravelPackageRequest $request, TravelPackage $travelPackage): RedirectResponse
    {
        $travelPackage->update($this->validatedData($request, $travelPackage));
        $this->syncGalleryImages($request, $travelPackage);

        return redirect()->route('admin.travel-packages.edit', $travelPackage)->with('status', 'Paket wisata berhasil diperbarui.');
    }

    public function destroy(TravelPackage $travelPackage): RedirectResponse
    {
        if ($travelPackage->featured_image && Storage::disk('public')->exists($travelPackage->featured_image)) {
            Storage::disk('public')->delete($travelPackage->featured_image);
        }

        foreach ($travelPackage->galleryImages as $galleryImage) {
            if ($galleryImage->image_path && Storage::disk('public')->exists($galleryImage->image_path)) {
                Storage::disk('public')->delete($galleryImage->image_path);
            }
        }

        $travelPackage->delete();

        return redirect()->route('admin.travel-packages.index')->with('status', 'Paket wisata berhasil dihapus.');
    }

    private function validatedData(StoreTravelPackageRequest|UpdateTravelPackageRequest $request, ?TravelPackage $travelPackage = null): array
    {
        $data = $request->validated();

        if ($request->boolean('remove_featured_image') && $travelPackage?->featured_image && Storage::disk('public')->exists($travelPackage->featured_image)) {
            Storage::disk('public')->delete($travelPackage->featured_image);
            $data['featured_image'] = null;
        }

        if ($request->hasFile('featured_image_upload')) {
            if ($travelPackage?->featured_image && Storage::disk('public')->exists($travelPackage->featured_image)) {
                Storage::disk('public')->delete($travelPackage->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image_upload')->store('travel-packages', 'public');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        unset($data['featured_image_upload'], $data['gallery_images_upload'], $data['remove_featured_image'], $data['remove_gallery_images']);

        return $data;
    }

    private function syncGalleryImages(StoreTravelPackageRequest|UpdateTravelPackageRequest $request, TravelPackage $travelPackage): void
    {
        $removeGalleryImages = collect($request->input('remove_gallery_images', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        if ($removeGalleryImages->isNotEmpty()) {
            $travelPackage->galleryImages()
                ->whereIn('id', $removeGalleryImages)
                ->get()
                ->each(function ($galleryImage) {
                    if ($galleryImage->image_path && Storage::disk('public')->exists($galleryImage->image_path)) {
                        Storage::disk('public')->delete($galleryImage->image_path);
                    }

                    $galleryImage->delete();
                });
        }

        if ($request->hasFile('gallery_images_upload')) {
            $existingCount = $travelPackage->galleryImages()->count();

            foreach ($request->file('gallery_images_upload') as $index => $image) {
                $travelPackage->galleryImages()->create([
                    'image_path' => $image->store('travel-packages/gallery', 'public'),
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }
    }
}
