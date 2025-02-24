<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Video;
use App\Jobs\ProcessVideoJob;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\VideoProcessingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors(),
            'message' => 'Validation failed'
        ], 422);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }
    
    $token = $user->createToken('device')->plainTextToken;

    return response()->json([
        'message' => 'Authentication successful',
        'token' => $token,
    ], 200);
});

Route::post('/auth/logout', function(Request $request) {
    $request->user()->currentAccessToken()->delete();
    
    return response()->json([
        'message' => 'Logged out successfully'
    ], 200);
})->middleware('auth:sanctum');

// New route for downloading video
Route::post('/video/upload', [VideoProcessingController::class, 'upload'])
    ->middleware('auth:sanctum');

Route::post('/video/download', [VideoProcessingController::class, 'download'])
    ->middleware('auth:sanctum');

// Route to handle video completion notification
Route::post('/inference/{video_id}', function (Request $request, $video_id) {
    try {

        Log::info('---Received results of '.$video_id.' and are following: '.$request->input('classification').'---');
        $video = Video::findOrFail($video_id);
        
        // Update video with results from FastAPI
        $video->update([
            'video_status' => Video::STATUS_COMPLETED,
            'predicted_class' => $request->input('classification'),
            'prediction_probability' => $request->input('probability')
        ]);

    } catch (\Exception $e) {
        Log::error('Error handling video completion', [
            'video_id' => $video_id,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});