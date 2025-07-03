<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\ProductController;
// use App\Http\Controllers\API\ProductVariantController;

use App\Http\Controllers\Api\AccountController;
use Illuminate\Support\Facades\Route;



use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductVariantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AccountController::class, 'register']);
Route::post('/login', [AccountController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AccountController::class, 'logout']);
    Route::get('/check-auth', [AccountController::class, 'checkAuth']);

    // Member routes
    Route::middleware(['role:member'])->group(function () {
        Route::get('/address', [AccountController::class, 'address']);
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Category management routes
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{slug}', [CategoryController::class, 'show']);
    Route::put('/{slug}', [CategoryController::class, 'update']);
    Route::delete('/{slug}', [CategoryController::class, 'destroy']);
    Route::get('/{slug}/products', [CategoryController::class, 'products']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::put('/{product}', [ProductController::class, 'update']);
    Route::delete('/{product}', [ProductController::class, 'destroy']);

    // Product variant routes
    Route::get('/{product}/variants', [ProductVariantController::class, 'index']);
    Route::post('/{product}/variants', [ProductVariantController::class, 'store']);
    Route::match(['put', 'patch'], '/{product}/variants/{variant}', [ProductVariantController::class, 'update']);
    Route::delete('/{product}/variants/{variant}', [ProductVariantController::class, 'destroy']);
    Route::get('/{product}/variants/{variant}', [ProductVariantController::class, 'show']);
});


    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        // Add admin-specific routes here
    });
});
