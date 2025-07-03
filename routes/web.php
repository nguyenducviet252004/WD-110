<?php

use App\Http\Controllers\Admin\VoucherController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\CategoryController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\VoucherController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin/categories')->name('admin.categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{category:slug}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category:slug}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category:slug}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('show');
    // new
});



Route::prefix('admin/vouchers')->name('admin.vouchers.')->group(function () {
    Route::get('/', [VoucherController::class, 'index'])->name('index');
    Route::get('/create', [VoucherController::class, 'create'])->name('create');
    Route::post('/', [VoucherController::class, 'store'])->name('store');
    Route::get('/{id}', [VoucherController::class, 'show'])->name('show');
    Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('edit');
    Route::put('/{voucher}', [VoucherController::class, 'update'])->name('update');

    Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
    // Route::get('/voucher/{id}/toggle-status', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggleStatus');
});

// Product routes
Route::prefix('admin')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    //Routes quản lí categories
    Route::resource('categories', CategoriesController::class);
    Route::post('/categories/{category}/toggle-status', [CategoriesController::class, 'toggleStatus'])->name('categories.toggle-status');
    // Routes cho quản lý voucher
    Route::resource('vouchers', VoucherController::class);
    Route::post('/vouchers/{voucher}/toggle-status', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggle-status');

    // Routes cho quản lý màu sắc
    Route::resource('colors', ColorController::class);

    // Routes cho quản lý kích thước
    Route::resource('sizes', SizeController::class);
});



