<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\WebResetPasswordController;

// Homepage route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Messages Routes
Route::get('/messages', [MessageController::class, 'index'])->name('messages.index'); // Messaging interface
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store'); // Store a new message
Route::post('/messages/start', [MessageController::class, 'startConversation'])->name('messages.startConversation');
Route::post('/conversations/start', [MessageController::class, 'startConversation'])->name('conversations.start');
Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');

require __DIR__.'/auth.php'; // Includes registration, login, and password reset routes



// Password Reset Routes
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'sendResetLinkEmail')->name('password.email');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    Route::post('password/reset', 'reset')->name('password.update');
});

// Web-Specific Reset Password Routes (if needed separately)
Route::controller(WebResetPasswordController::class)->group(function () {
    Route::get('web/password/reset/{token}', 'showResetForm')->name('web.password.reset');
    Route::post('web/password/reset', 'reset')->name('web.password.update');
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
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');

});

Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

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
