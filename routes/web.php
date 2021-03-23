<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

Route::get('/{any}', [FrontendController::class, 'index'])->where('any', '.*');
