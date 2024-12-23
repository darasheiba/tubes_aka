<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/products', function () {
    $products = \App\Models\Product::all();
    return view('products.index', compact('products'));
});

Route::resource('products', ProductController::class);

Route::get('/search', [ProductController::class, 'searchIterative']);

Route::get('/analyze-performance', [ProductController::class, 'analyzePerformance']);

// Route::get('/export-csv', [ProductController::class, 'exportToCsv']);

Route::get('/performance-test', [ProductController::class, 'analyzePerformanceCombined'])->name('performance.test');
Route::post('/export-csv', [ProductController::class, 'exportToCsv'])->name('export.csv');
