<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SpecialRequestController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
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
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
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
    // Themes Management
    Route::get('themes', [\App\Http\Controllers\Admin\ThemeController::class, 'index'])->name('themes.index');
    Route::post('themes', [\App\Http\Controllers\Admin\ThemeController::class, 'store'])->name('themes.store');
    Route::post('themes/activate', [\App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('themes.activate');
    Route::delete('themes/{theme}', [\App\Http\Controllers\Admin\ThemeController::class, 'destroy'])->name('themes.destroy');
    
    // Theme Editor
    Route::get('themes/{theme}/edit', [\App\Http\Controllers\Admin\ThemeController::class, 'edit'])->name('themes.edit');
    Route::post('themes/{theme}/files', [\App\Http\Controllers\Admin\ThemeController::class, 'storeFile'])->name('themes.files.store');
    Route::put('themes/{theme}/files', [\App\Http\Controllers\Admin\ThemeController::class, 'updateFile'])->name('themes.files.update');
    Route::delete('themes/{theme}/files', [\App\Http\Controllers\Admin\ThemeController::class, 'destroyFile'])->name('themes.files.destroy');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
