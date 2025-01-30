<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BuyTicketsController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin Authentication Routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// User Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// User Ticket Booking Routes
Route::middleware('auth')->group(function () {
    Route::get('/buy-tickets', [BuyTicketsController::class, 'index'])->name('buy-tickets.index');
    Route::get('/buy-tickets/{id}', [BuyTicketsController::class, 'getSeats'])->name('buy-tickets.getSeats');
    Route::post('/buy-tickets/book-seat', [BuyTicketsController::class, 'bookSeat'])->name('buy-tickets.book-seat');
});
