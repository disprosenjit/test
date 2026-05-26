<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactsController;

// Public routes
Route::get('/', fn () => view('home'));
Route::get('/about', fn () => view('about'));
Route::get('/services', fn () => view('services'));
Route::get('/privacy', fn () => view('privacy'));
Route::get('/terms', fn () => view('terms'));

Route::get('/contact', fn () => view('contact'))->name('contact.form');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Admin — guest routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Admin — protected routes
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', fn () => redirect()->route('admin.contacts.index'));
        Route::get('contacts', [ContactsController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [ContactsController::class, 'show'])->name('contacts.show');
        Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])->name('contacts.destroy');
        Route::patch('contacts/{contact}/read', [ContactsController::class, 'markRead'])->name('contacts.read');
    });
});
