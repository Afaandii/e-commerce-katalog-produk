<?php

use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // categories management
    Route::get('/category', [CategoriesController::class, 'index'])->name('category-index');
    Route::get('/category-create', [CategoriesController::class, 'create'])->name('category-create');
    Route::post('/category-store', [CategoriesController::class, 'store'])->name('category-store');
});
