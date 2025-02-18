<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

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