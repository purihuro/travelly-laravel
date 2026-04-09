<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccommodationRequest;
use App\Http\Requests\Admin\UpdateAccommodationRequest;
use App\Models\Accommodation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AccommodationController extends Controller
{
    public function index(Request $request): View
    {
        $accommodations = Accommodation::query()
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
            })
            ->when($request->string('type')->toString(), fn ($query, $type) => $query->where('type', $type))
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

        return view('admin.accommodations.index', [
            'accommodations' => $accommodations,
            'types' => ['hotel', 'villa', 'homestay'],
        ]);
    }

    public function create(): View
    {
        return view('admin.accommodations.create');
    }

    public function store(StoreAccommodationRequest $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        Accommodation::query()->create($data);

        return redirect()->route('admin.accommodations.index')->with('status', 'Penginapan berhasil ditambahkan.');
    }

    public function show(Accommodation $accommodation): View
    {
        $accommodation->loadCount('bookingAccommodations');
        $accommodation->load([
            'bookingAccommodations' => fn ($query) => $query->with('booking')->latest()->limit(10),
        ]);

        return view('admin.accommodations.show', compact('accommodation'));
    }

    public function edit(Accommodation $accommodation): View
    {
        return view('admin.accommodations.edit', compact('accommodation'));
    }

    public function update(UpdateAccommodationRequest $request, Accommodation $accommodation): RedirectResponse
    {
        $data = $this->validatedData($request, $accommodation);

        $accommodation->update($data);

        return redirect()->route('admin.accommodations.edit', $accommodation)->with('status', 'Data penginapan berhasil diperbarui.');
    }

    public function destroy(Accommodation $accommodation): RedirectResponse
    {
        if ($accommodation->image && str_starts_with($accommodation->image, 'storage/accommodations/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $accommodation->image));
        }

        $accommodation->delete();

        return redirect()->route('admin.accommodations.index')->with('status', 'Penginapan berhasil dihapus.');
    }

    private function validatedData(StoreAccommodationRequest|UpdateAccommodationRequest $request, ?Accommodation $accommodation = null): array
    {
        $data = $request->validated();

        if ($request->boolean('remove_image') && $accommodation?->image && str_starts_with($accommodation->image, 'storage/accommodations/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $accommodation->image));
            $data['image'] = null;
        }

        if ($request->hasFile('image_upload')) {
            if ($accommodation?->image && str_starts_with($accommodation->image, 'storage/accommodations/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $accommodation->image));
            }

            $path = $request->file('image_upload')->store('accommodations', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active');
        unset($data['image_upload'], $data['remove_image']);

        return $data;
    }
}
