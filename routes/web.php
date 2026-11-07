<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\PortfolioController;
use App\Models\Profile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::post('/kontak', [ContactMessageController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::get('/cv', function () {
    $path = Profile::query()->value('cv_path');
    abort_unless($path && Storage::disk('public')->exists($path), 404, 'CV belum tersedia.');

    return Storage::disk('public')->download($path, 'CV.pdf');
})->name('cv.download');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::post('/media', [MediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{asset}', [MediaController::class, 'destroy'])->name('media.destroy');
        Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('/account', [AccountController::class, 'update'])->name('account.update');

        Route::get('/{resource}', [ResourceController::class, 'index'])->name('resources.index');
        Route::get('/{resource}/create', [ResourceController::class, 'create'])->name('resources.create');
        Route::post('/{resource}', [ResourceController::class, 'store'])->name('resources.store');
        Route::get('/{resource}/{record}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/{resource}/{record}', [ResourceController::class, 'update'])->name('resources.update');
        Route::delete('/{resource}/{record}', [ResourceController::class, 'destroy'])->name('resources.destroy');
    });
});
