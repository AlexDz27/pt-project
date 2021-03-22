<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//  return $request->user();
//});

/**
 * Public routes
 */
Route::post('/sign-up', [AuthController::class, 'signUp']);
Route::post('/sign-in', [AuthController::class, 'signIn']);

/**
 * Protected routes
 */
Route::middleware('auth:api')->post('/sign-out', [AuthController::class, 'signOut']);
