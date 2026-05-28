<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SpecialRequestController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ContactInquiryController;
use App\Http\Controllers\Admin\SpecialRequestController as AdminSpecialRequestController;
use App\Http\Controllers\Admin\InvestmentInquiryController;
use Illuminate\Support\Facades\Route;

// Geocoding proxy (server-side, avoids CORS)
Route::get('/geocoding/reverse', [GeocodingController::class, 'reverse'])->name('geocoding.reverse');
Route::get('/geocoding/search',  [GeocodingController::class, 'search'])->name('geocoding.search');

// Frontend Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/map', [MapController::class, 'index'])->name('map');

// Properties
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/filter', [PropertyController::class, 'filter'])->name('properties.filter');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// Availability calendar (public)
Route::get('/properties/{property}/availability', [BookingController::class, 'availability'])->name('properties.availability');

// Bookings (auth required)
Route::middleware('auth')->group(function () {
    Route::post('/properties/{property}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Contact
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Special Requests
Route::get('/special-requests', [SpecialRequestController::class, 'create'])->name('special-requests.create');
Route::post('/special-requests', [SpecialRequestController::class, 'store'])->name('special-requests.store');

// Investors
Route::get('/investors', [PageController::class, 'investors'])->name('investors.index');
Route::get('/investors/inquiry', [InvestmentController::class, 'create'])->name('investors.create');
Route::post('/investors/inquiry', [InvestmentController::class, 'store'])->name('investors.store');

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Properties Management
    Route::resource('properties', AdminPropertyController::class);
    Route::post('properties/{property}/images', [AdminPropertyController::class, 'uploadImages'])->name('properties.images.store');
    Route::delete('properties/{property}/images/{image}', [AdminPropertyController::class, 'deleteImage'])->name('properties.images.destroy');

    // Contact Inquiries
    Route::get('inquiries', [ContactInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}', [ContactInquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('inquiries/{inquiry}/status', [ContactInquiryController::class, 'updateStatus'])->name('inquiries.updateStatus');
    Route::delete('inquiries/{inquiry}', [ContactInquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Special Requests
    Route::get('requests', [AdminSpecialRequestController::class, 'index'])->name('requests.index');
    Route::get('requests/{request}', [AdminSpecialRequestController::class, 'show'])->name('requests.show');
    Route::patch('requests/{specialRequest}/status', [AdminSpecialRequestController::class, 'updateStatus'])->name('requests.updateStatus');
    Route::delete('requests/{request}', [AdminSpecialRequestController::class, 'destroy'])->name('requests.destroy');

    // Investment Inquiries
    Route::get('investments', [InvestmentInquiryController::class, 'index'])->name('investments.index');
    Route::get('investments/{inquiry}', [InvestmentInquiryController::class, 'show'])->name('investments.show');
    Route::patch('investments/{inquiry}/status', [InvestmentInquiryController::class, 'updateStatus'])->name('investments.updateStatus');
    Route::delete('investments/{inquiry}', [InvestmentInquiryController::class, 'destroy'])->name('investments.destroy');

    // Bookings
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
