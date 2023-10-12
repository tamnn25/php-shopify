<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [ProductController::class, 'getAll'])->middleware(['verify.shopify'])->name('home');

Route::get('/products', [ProductController::class, 'getAll'])->name('products');

Route::controller(ProductController::class)->group(function(){
    Route::get('clone-products', 'index')->name('clone-products');    
    Route::get('export/csv', 'exportCSVFile')->name('export.csv');
});

Route::get('/api/products', [ProductController::class, 'getProduct'])->name('getProduct');

Route::get('/ajax-table', function(){
    return view('ajax.home_ajax');
});