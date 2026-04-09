<?php

use App\Http\Controllers\Admin\AboutContentController;
use App\Http\Controllers\Admin\AccommodationController as AdminAccommodationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CulinaryPackageController as AdminCulinaryPackageController;
use App\Http\Controllers\Admin\CulinaryVenueController as AdminCulinaryVenueController;
use App\Http\Controllers\Admin\DestinationTicketController as AdminDestinationTicketController;
use App\Http\Controllers\Admin\ContactContentController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\FooterContentController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\TravelPackageController as AdminTravelPackageController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/cart', [PageController::class, 'cart'])->name('cart');
Route::post('/cart/add', [PageController::class, 'addTourPackageToCart'])->name('cart.add');
Route::patch('/cart/{slug}', [PageController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/{slug}', [PageController::class, 'removeCartItem'])->name('cart.remove');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [PageController::class, 'placeOrder'])->name('checkout.place');
Route::get('/checkout/summary/{booking}', [PageController::class, 'checkoutSummary'])->name('checkout.summary');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/destinations', [PageController::class, 'destinations'])->name('destinations');
Route::post('/destinations', [PageController::class, 'saveDestinations'])->name('destinations.save');
Route::get('/product/{slug?}', [PageController::class, 'productSingle'])->name('product.single');
Route::get('/accommodations', [PageController::class, 'accommodations'])->name('accommodations');
Route::post('/accommodations', [PageController::class, 'saveAccommodation'])->name('accommodations.save');
Route::get('/shop', [PageController::class, 'shop'])->name('shop');
Route::get('/shop/package/{package:slug}', [PageController::class, 'shopDetail'])->name('shop.detail');
Route::post('/shop/package', [PageController::class, 'addPackageToCart'])->name('shop.package.add');
Route::get('/trip-builder', [PageController::class, 'tripBuilder'])->name('trip-builder');
Route::post('/shop/summary', [PageController::class, 'finalizeSelections'])->name('shop.summary.submit');
Route::post('/shop/destinations', [PageController::class, 'saveDestinations'])->name('shop.destinations.submit');
Route::get('/transportations', [PageController::class, 'transportations'])->name('transportations');
Route::post('/transportations', [PageController::class, 'saveTransportation'])->name('transportations.save');
Route::get('/culinaries', [PageController::class, 'culinaries'])->name('culinaries');
Route::post('/culinaries', [PageController::class, 'saveCulinary'])->name('culinaries.save');
Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'admin', 'admin.timeout'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/homepage', [HomepageContentController::class, 'edit'])->name('homepage.edit');
        Route::put('/homepage', [HomepageContentController::class, 'update'])->name('homepage.update');
        Route::get('/content/about', [AboutContentController::class, 'edit'])->name('content.about.edit');
        Route::put('/content/about', [AboutContentController::class, 'update'])->name('content.about.update');
        Route::get('/content/contact', [ContactContentController::class, 'edit'])->name('content.contact.edit');
        Route::put('/content/contact', [ContactContentController::class, 'update'])->name('content.contact.update');
        Route::get('/content/footer', [FooterContentController::class, 'edit'])->name('content.footer.edit');
        Route::put('/content/footer', [FooterContentController::class, 'update'])->name('content.footer.update');
        Route::resource('travel-packages', AdminTravelPackageController::class);
        Route::resource('destination-tickets', AdminDestinationTicketController::class);
        Route::resource('accommodations', AdminAccommodationController::class);
        Route::resource('culinary-venues', AdminCulinaryVenueController::class);
        Route::resource('culinary-packages', AdminCulinaryPackageController::class);
        Route::patch('culinary-packages/{culinary_package}/quick-update', [AdminCulinaryPackageController::class, 'quickUpdate'])->name('culinary-packages.quick-update');
        Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        Route::resource('contacts', AdminContactMessageController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
