<?php

use App\Http\Controllers\BrandProductController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TypeProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// manage crud api categories
Route::get('/v1/categories', [CategoriesController::class, 'index']);
Route::post('/v1/create-categories', [CategoriesController::class, 'store']);
Route::put('/v1/update-categories/{id}', [CategoriesController::class, 'update']);
Route::delete('/v1/delete-categories/{id}', [CategoriesController::class, 'destroy']);

// manage crud api brand product
Route::get('/v1/brand-product', [BrandProductController::class, 'index']);
Route::post('/v1/create-brand-product', [BrandProductController::class, 'store']);
Route::put('/v1/update-brand-product/{id}', [BrandProductController::class, 'update']);
Route::delete('/v1/delete-brand-product/{id}', [BrandProductController::class, 'destroy']);

// manage crud api type product
Route::get('/v1/type-product', [TypeProductController::class, 'index']);
Route::post('/v1/create-type-product', [TypeProductController::class, 'store']);
Route::put('/v1/update-type-product/{id}', [TypeProductController::class, 'update']);
Route::delete('/v1/delete-type-product/{id}', [TypeProductController::class, 'destroy']);

// manage crud api product
Route::get('/v1/product', [ProductController::class, 'index']);
Route::post('/v1/create-product', [ProductController::class, 'store']);
Route::put('/v1/update-product/{id}', [ProductController::class, 'update']);
Route::delete('/v1/delete-product/{id}', [ProductController::class, 'destroy']);

// manage auth roles
Route::get('/v1/role', [RolesController::class, 'index']);
Route::post('/v1/create-role', [RolesController::class, 'store']);
Route::put('/v1/update-role/{id}', [RolesController::class, 'update']);
Route::delete('/v1/delete-role/{id}', [RolesController::class, 'destroy']);

// manage auth users
// Route::get('/v1/users', [RolesController::class, 'getUsers']);