<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\CulinaryPackage;
use App\Models\TravelPackage;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'packages' => TravelPackage::query()->where('is_active', true)->count(),
            'active_culinary_packages' => CulinaryPackage::query()->where('is_active', true)->count(),
            'pending_bookings' => Booking::query()->where('booking_status', 'pending')->count(),
            'unread_messages' => ContactMessage::query()->where('status', 'new')->count(),
            'active_hotels' => Accommodation::query()->where('is_active', true)->where('type', 'hotel')->count(),
            'active_villas' => Accommodation::query()->where('is_active', true)->where('type', 'villa')->count(),
            'active_homestays' => Accommodation::query()->where('is_active', true)->where('type', 'homestay')->count(),
        ];

        $bookingChart = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->format('d M'),
                'count' => Booking::query()->whereDate('created_at', $date)->count(),
            ];
        })->all();

        $messageChart = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->format('d M'),
                'count' => ContactMessage::query()->whereDate('created_at', $date)->count(),
            ];
        })->all();

        $bookingChartMax = max(1, collect($bookingChart)->max('count'));
        $messageChartMax = max(1, collect($messageChart)->max('count'));

        $recentBookings = Booking::query()->latest()->limit(5)->get();
        $recentMessages = ContactMessage::query()->latest()->limit(5)->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'bookingChart',
            'messageChart',
            'bookingChartMax',
            'messageChartMax',
            'recentBookings',
            'recentMessages',
        ));
    }
}
