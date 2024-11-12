<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoUploadController;
use App\Http\Controllers\VideoReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\Video;
use App\Models\User;
use \App\Mail\VideoInferenceCompletionMail;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/inference/{video_id}', function (string $video_id) {
    $user = User::find(Video::find($video_id)->user_id);
    $video = Video::find($video_id);
    Mail::to($user->email)->queue(new VideoInferenceCompletionMail(
        $user->name,
        $video->filename,
        $video->predicted_class,
        $video->prediction_probability
    ));
//    TODO: Cleanup this return, this return is causing the FastAPI to wait for Laravel App to notify it back upon completion
    return response()->json([
        'video_id' => $video_id,
        'user_id' => $user->id,
        'user_name' => $user->name,
        'result' => $video->predicted_class,
        'prediction_probability' => $video->prediction_probability,
        'user_email' => $user->email,
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
