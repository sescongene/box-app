<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::post('/products', [ProductController::class, 'checkBoxes'])->name('products.checkBoxes');
