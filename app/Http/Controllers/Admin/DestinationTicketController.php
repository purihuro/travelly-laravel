<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationTicketRequest;
use App\Http\Requests\Admin\UpdateDestinationTicketRequest;
use App\Models\DestinationTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DestinationTicketController extends Controller
{
    public function index(Request $request): View
    {
        $destinationTickets = DestinationTicket::query()
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

        return view('admin.destination-tickets.index', compact('destinationTickets'));
    }

    public function create(): View
    {
        return view('admin.destination-tickets.create');
    }

    public function store(StoreDestinationTicketRequest $request): RedirectResponse
    {
        DestinationTicket::query()->create($this->validatedData($request));

        return redirect()->route('admin.destination-tickets.index')->with('status', 'Destinasi wisata berhasil ditambahkan.');
    }

    public function show(DestinationTicket $destinationTicket): View
    {
        $destinationTicket->loadCount('bookingDestinationItems');
        $destinationTicket->load([
            'bookingDestinationItems' => fn ($query) => $query->with('booking')->latest()->limit(10),
        ]);

        return view('admin.destination-tickets.show', compact('destinationTicket'));
    }

    public function edit(DestinationTicket $destinationTicket): View
    {
        return view('admin.destination-tickets.edit', compact('destinationTicket'));
    }

    public function update(UpdateDestinationTicketRequest $request, DestinationTicket $destinationTicket): RedirectResponse
    {
        $destinationTicket->update($this->validatedData($request, $destinationTicket));

        return redirect()->route('admin.destination-tickets.edit', $destinationTicket)->with('status', 'Destinasi wisata berhasil diperbarui.');
    }

    public function destroy(DestinationTicket $destinationTicket): RedirectResponse
    {
        if ($destinationTicket->image && str_starts_with($destinationTicket->image, 'storage/destination-tickets/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $destinationTicket->image));
        }

        $destinationTicket->delete();

        return redirect()->route('admin.destination-tickets.index')->with('status', 'Destinasi wisata berhasil dihapus.');
    }

    private function validatedData(StoreDestinationTicketRequest|UpdateDestinationTicketRequest $request, ?DestinationTicket $destinationTicket = null): array
    {
        $data = $request->validated();

        if ($request->boolean('remove_image') && $destinationTicket?->image && str_starts_with($destinationTicket->image, 'storage/destination-tickets/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $destinationTicket->image));
            $data['image'] = null;
        }

        if ($request->hasFile('image_upload')) {
            if ($destinationTicket?->image && str_starts_with($destinationTicket->image, 'storage/destination-tickets/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $destinationTicket->image));
            }

            $path = $request->file('image_upload')->store('destination-tickets', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['image_upload'], $data['remove_image']);

        return $data;
    }
}
