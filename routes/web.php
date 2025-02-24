<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoProcessingController;
use App\Http\Controllers\VideoReportController;
use Illuminate\Support\Facades\Route;
use App\Models\Video;
use App\Models\User;
use \App\Mail\VideoInferenceCompletionMail;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');

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

    Route::post('/videos', [VideoProcessingController::class, 'upload'])->name('videos.upload');
    Route::get('/reports', [VideoReportController::class, 'index'])->name('reports.index');
    Route::get('/report/{id}', [VideoReportController::class, 'show'])->name('reports.show');
});

Route::get('/frame/{video}/{type}/{filename}', function ($video, $type, $filename) {
    // Authorize access
    $video = Video::findOrFail($video);
    if (Auth::id() !== $video->user_id) {
        abort(403);
    }

    // Determine disk based on type
    $disk = $type === 'visualized' ? 'gradcam_frames' : 'frames';

    // Check if file exists
    if (!Storage::disk($disk)->exists($filename)) {
        abort(404);
    }

    // Stream the file
    return Response::file(Storage::disk($disk)->path($filename));
})->name('frame.show')->middleware('auth');

require __DIR__.'/auth.php';
