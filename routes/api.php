<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Http\Controllers\SearchController;

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//  return $request->user();
//});

Route::prefix('auth')->group(function() {
  /** Public routes */
  Route::post('/sign-up', [AuthController::class, 'signUp']);
  Route::post('/sign-in', [AuthController::class, 'signIn']);

  Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
  Route::get('/reset-password/link', [AuthController::class, 'resetPasswordPage']);
  Route::post('/reset-password/link', [AuthController::class, 'resetPassword']);

  /** Protected routes */
  Route::middleware('auth:api')->get('/sign-out', [AuthController::class, 'signOut']);
});

Route::middleware('auth:api')->get('/search', [SearchController::class, 'index']);
