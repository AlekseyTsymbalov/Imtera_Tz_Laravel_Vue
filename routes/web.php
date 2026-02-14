<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\YandexSettingController;
use App\Http\Controllers\ReviewController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::middleware(['verified'])->group(function () {
        Route::get('/settings', [YandexSettingController::class, 'edit'])->name('settings.edit');
        Route::patch('/settings', [YandexSettingController::class, 'update'])->name('settings.update');
    });

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/sync', [ReviewController::class, 'sync'])->name('reviews.sync');
});

require __DIR__.'/auth.php';