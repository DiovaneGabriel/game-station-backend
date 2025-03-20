<?php

use App\Http\Controllers\SaveController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function ($router) {
        Route::post('save', [SaveController::class, 'save']);
        Route::get('load', [SaveController::class, 'load']);
    });
