<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Models\User;
// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification Routes
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Article Routes
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
// Protected Article Routes
Auth::routes(['verify' => true]);
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/articles/{article}/rate', [ArticleController::class, 'rate'])->name('articles.rate');
    Route::post('/articles/{article}/comment', [ArticleController::class, 'comment'])->name('articles.comment');
});

// Explore Routes
Route::get('/explore', [DestinationController::class, 'index'])->name('explore.index');
Route::get('/explore/{destination:slug}', [DestinationController::class, 'show'])->name('explore.show');

// Protected Destination Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/explore/{destination:slug}/rate', [DestinationController::class, 'rate'])->name('explore.rate');
    Route::post('/explore/{destination:slug}/comment', [DestinationController::class, 'comment'])->name('explore.comment');
});

Route::get('/staff-registration', [NewsletterController::class, 'showForm'])->name('newsletter.form');
Route::post('/staff-registration', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');


// Admin and Staff Routes
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Destinations
    Route::get('/destinations', [AdminController::class, 'destinationsIndex'])->name('admin.destinations.index');
    Route::get('/destinations/create', [AdminController::class, 'createDestination'])->name('admin.destinations.create');
    Route::post('/destinations', [AdminController::class, 'storeDestination'])->name('admin.destinations.store');
    Route::get('/destinations/{destination}/edit', [AdminController::class, 'editDestination'])->name('admin.destinations.edit');
    Route::put('/destinations/{destination}', [AdminController::class, 'updateDestination'])->name('admin.destinations.update');
    Route::delete('/destinations/{destination}', [AdminController::class, 'destroyDestination'])->name('admin.destinations.destroy');
    
    // Categories
    Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('admin.categories.index');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    
    // Admin Articles
    Route::get('/articles', [AdminController::class, 'articlesIndex'])->name('admin.articles.index');
    Route::get('/articles/create', [AdminController::class, 'createArticle'])->name('admin.articles.create');
    Route::post('/articles', [AdminController::class, 'storeArticle'])->name('admin.articles.store');
    Route::get('/articles/{article}/edit', [AdminController::class, 'editArticle'])->name('admin.articles.edit');
    Route::put('/articles/{article}', [AdminController::class, 'updateArticle'])->name('admin.articles.update');
    Route::delete('/articles/{article}', [AdminController::class, 'destroyArticle'])->name('admin.articles.destroy');
});

// Admin-only Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/staff-registrations', [AdminController::class, 'staffRegistrations'])->name('admin.staff-registrations');
    Route::post('/staff-registrations/{id}/approve', [AdminController::class, 'approveStaff'])->name('admin.staff.approve');
    Route::post('/staff-registrations/{id}/reject', [AdminController::class, 'rejectStaff'])->name('admin.staff.reject');
});

// Debug Routes (remove in production)
Route::get('/check-auth', function () {
    return Auth::check() ? 'Logged in' : 'Not logged in';
});

Route::get('/check-password/{email}', function($email) {
    $user = User::where('email', $email)->first();
    if (!$user) {
        return "User not found";
    }
    return "Password hash: " . $user->password;
});

