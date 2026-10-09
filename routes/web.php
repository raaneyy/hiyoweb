<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\EnsureAdminLoggedIn;
use App\Http\Middleware\EnsureReaderVerified;
use Illuminate\Support\Facades\Route;

// Public Landing & Login Routes
Route::get('/', [AuthController::class, 'landing'])->name('landing');
Route::get('/home', [AuthController::class, 'landing'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login/reader', [AuthController::class, 'verifyReader'])->name('login.reader');
Route::post('/login/admin', [AuthController::class, 'verifyAdmin'])->name('login.admin');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Interactive Reader Flipbook Route (Protected for verified readers or admin)
Route::middleware(EnsureReaderVerified::class)->group(function () {
    Route::get('/diary/{diary}', [DiaryController::class, 'show'])->name('diary.show');
});

// Admin Form and Management Routes (Protected for Admin)
Route::prefix('admin')->name('admin.')->middleware(EnsureAdminLoggedIn::class)->group(function () {
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
});
