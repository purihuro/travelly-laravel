<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBookingRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::query()
            ->withCount(['items', 'destinationItems', 'culinary'])
            ->with('accommodation')
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load('items.travelPackage', 'destinationItems', 'accommodation', 'transportation', 'culinary');

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return redirect()->route('admin.bookings.edit', $booking)->with('status', 'Data booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('status', 'Booking berhasil dihapus.');
    }
}
