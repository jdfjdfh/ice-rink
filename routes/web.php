<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/book', [BookingController::class, 'store'])->name('book.store');

Route::get('/payment/pending/{booking}', [BookingController::class, 'pending'])->name('payment.pending');
Route::get('/payment/success/{booking}', [BookingController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel/{booking}', [BookingController::class, 'cancel'])->name('payment.cancel');
Route::get('/payment/check/{booking}', [BookingController::class, 'checkStatus'])->name('payment.check');
Route::post('/payment/webhook', [BookingController::class, 'webhook'])->name('payment.webhook');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::post('/skates', [AdminController::class, 'skatesStore'])->name('skates.store');
    Route::put('/skates/{skate}', [AdminController::class, 'skatesUpdate'])->name('skates.update');
    Route::delete('/skates/{skate}', [AdminController::class, 'skatesDestroy'])->name('skates.destroy');
});

Route::redirect('/admin', '/admin/dashboard');
