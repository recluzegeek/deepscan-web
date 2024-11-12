<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoUploadController;
use App\Http\Controllers\VideoReportController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'flash' => [
            'message' => session()->get('files_upload_success')
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/videos', [VideoUploadController::class, 'store'])->name('videos.store');
    Route::get('/reports', [VideoReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';
