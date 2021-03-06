<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\OAuthController;

Route::prefix('auth')->group(function() {
  Route::post('/sign-up', [AuthController::class, 'signUp']);
  Route::post('/sign-in', [AuthController::class, 'signIn']);

  Route::prefix('oauth')->group(function () {
    Route::post('/google', [OAuthController::class, 'google']);
  });

  Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
  Route::get('/reset-password', [AuthController::class, 'resetPasswordPage']);
  Route::post('/reset-password', [AuthController::class, 'resetPassword']);

  Route::middleware('auth:api')->get('/sign-out', [AuthController::class, 'signOut']);
});

Route::get('/user/{id}', [UserController::class, 'show']);

Route::middleware('auth:api')->get('/search', [SearchController::class, 'index']);

Route::middleware('auth:api')->get('/locations/{id}', [LocationsController::class, 'show']);
