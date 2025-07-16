<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\QuanliReviewController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;


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

// Route cho Admin
Route::controller(AdminController::class)->middleware(['token.auth', 'admin'])->group(function () {
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);

    Route::resource('vouchers', VoucherController::class);
    Route::resource('review', QuanliReviewController::class);
    Route::resource('blog', BlogController::class);
});
