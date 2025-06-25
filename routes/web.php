<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

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