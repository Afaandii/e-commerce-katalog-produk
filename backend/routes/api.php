<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\BrandProductController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TypeProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// login and register
Route::post('/v1/auth/register', [AuthenticateController::class, 'register']);
Route::post('/v1/auth/login', [AuthenticateController::class, 'login']);

Route::middleware(['auth:sanctum', 'ensureToken'])->group(function () {
    // manage crud api categories
    Route::get('/v1/categories', [CategoriesController::class, 'index']);
    Route::post('/v1/create-categories', [CategoriesController::class, 'store']);
    Route::get('/v1/edit-categories/{id}', [CategoriesController::class, 'edit']);
    Route::put('/v1/update-categories/{id}', [CategoriesController::class, 'update']);
    Route::delete('/v1/delete-categories/{id}', [CategoriesController::class, 'destroy']);

    // manage crud api brand product
    Route::get('/v1/brand-product', [BrandProductController::class, 'index']);
    Route::post('/v1/create-brand-product', [BrandProductController::class, 'store']);
    Route::get('/v1/edit-brand-product/{id}', [BrandProductController::class, 'edit']);
    Route::put('/v1/update-brand-product/{id}', [BrandProductController::class, 'update']);
    Route::delete('/v1/delete-brand-product/{id}', [BrandProductController::class, 'destroy']);

    // manage crud api type product
    Route::get('/v1/type-product', [TypeProductController::class, 'index']);
    Route::post('/v1/create-type-product', [TypeProductController::class, 'store']);
    Route::get('/v1/edit-type-product/{id}', [TypeProductController::class, 'edit']);
    Route::put('/v1/update-type-product/{id}', [TypeProductController::class, 'update']);
    Route::delete('/v1/delete-type-product/{id}', [TypeProductController::class, 'destroy']);

    // manage crud api product
    Route::get('/v1/product', [ProductController::class, 'index']);
    Route::get('/v1/create-product', [ProductController::class, 'create']);
    Route::post('/v1/store-product', [ProductController::class, 'store']);
    Route::get('/v1/edit-product/{id}', [ProductController::class, 'edit']);
    Route::put('/v1/update-product/{id}', [ProductController::class, 'update']);
    Route::delete('/v1/delete-product/{id}', [ProductController::class, 'destroy']);

    // manage crud api product image
    Route::get('/v1/product-image', [ProductImageController::class, 'index']);
    Route::get('/v1/create-product-image', [ProductImageController::class, 'create']);
    Route::post('/v1/store-product-image', [ProductImageController::class, 'store']);
    Route::get('/v1/edit-product-image/{id}', [ProductImageController::class, 'edit']);
    Route::put('/v1/update-product-image/{id}', [ProductImageController::class, 'update']);
    Route::delete('/v1/delete-product-image/{id}', [ProductImageController::class, 'destroy']);

    // manage auth roles
    Route::get('/v1/role', [RolesController::class, 'index']);
    Route::post('/v1/create-role', [RolesController::class, 'store']);
    Route::get('/v1/edit-role/{id}', [RolesController::class, 'edit']);
    Route::put('/v1/update-role/{id}', [RolesController::class, 'update']);
    Route::delete('/v1/delete-role/{id}', [RolesController::class, 'destroy']);

    // logout action and manage user
    Route::get('/v1/auth/user-all', [AuthenticateController::class, 'getAllUser']);
    Route::get('/v1/auth/edit-user-role/{id}', [AuthenticateController::class, 'getUserRoleById']);
    Route::put('/v1/auth/update-user-role/{id}', [AuthenticateController::class, 'updateRoleUser']);
    Route::delete('/v1/auth/delete-user/{id}', [AuthenticateController::class, 'destroy']);
    Route::get('/v1/auth/user', [AuthenticateController::class, 'getUser']);
    Route::put('/v1/auth/user-update', [AuthenticateController::class, 'updateUser']);
    Route::post('/v1/auth/logout', [AuthenticateController::class, 'logout']);
});
