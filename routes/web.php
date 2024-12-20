<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\WebForgotPasswordController;
use App\Http\Controllers\Auth\WebResetPasswordController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// Homepage route
Route::get('/', [HomeController::class, 'index'])->name('home');

// massage route
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
Route::get('/messages/{id}', [MessageController::class, 'show']);

// Authentication routes (Breeze and custom)
require __DIR__.'/auth.php'; // Includes registration, login, and password reset routes

// Password reset routes
Route::controller(WebForgotPasswordController::class)->group(function () {
    Route::get('password/reset', 'showLinkRequestForm')->name('web.password.request');
    Route::post('password/email', 'sendResetLinkEmail')->name('web.password.email');
});
Route::controller(WebResetPasswordController::class)->group(function () {
    Route::get('password/reset/{token}', 'showResetForm')->name('web.password.reset');
    Route::post('password/reset', 'reset')->name('web.password.update');
});


// Google Login routes
Route::controller(AuthController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('google.login');
    Route::get('auth/google/callback', 'handleGoogleCallback')->name('google.callback');
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login')->name('login.post');
    Route::post('logout', 'logout')->name('logout');
});

// Initial registration route
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Detailed registration routes
Route::get('/register/details', [AuthController::class, 'showDetailedRegistrationForm'])->name('register.details');
Route::post('/register/details', [AuthController::class, 'storeDetailedRegistration'])->name('register.details.store');

// Profile routes
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [HomeController::class, 'profile'])->name('index'); // Profile overview
    Route::get('/edit', [HomeController::class, 'editProfile'])->name('edit'); // Edit profile form
    Route::put('/{id}', [HomeController::class, 'updateProfile'])->name('update'); // Update profile
    Route::post('/add-product', [HomeController::class, 'addProduct'])->name('addProduct'); // Add product
});


// Category routes
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

// Product routes
Route::middleware('auth')->prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index'); // List products
    Route::get('/add', [ProductController::class, 'create'])->name('add'); // Add product form
    Route::post('/', [ProductController::class, 'store'])->name('store'); // Store product
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit'); // Edit product form
    Route::put('/{product}', [ProductController::class, 'update'])->name('update'); // Update product
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy'); // Delete product
    Route::get('/{id}', [ProductController::class, 'show'])->name('show'); // Show product
    Route::post('/{id}/review', [ProductController::class, 'storeReview'])->name('storeReview'); // Store review
});

// Dashboard and settings
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::get('/settings', [HomeController::class, 'settings'])->name('settings');
});

// Other pages
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Fallback route for 404 errors
Route::fallback(function () {
    return view('errors.404');
});
