<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/auth/google/redirect', [AuthenticateController::class, 'googleRedirect']);
    Route::get('/auth/google/callback', [AuthenticateController::class, 'googleCallback']);
    Route::get('/auth/facebook/redirect', [AuthenticateController::class, 'facebookRedirect']);
    Route::get('/auth/facebook/callback', [AuthenticateController::class, 'facebookCallback']);
});
