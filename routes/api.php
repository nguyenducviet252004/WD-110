<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductVariantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
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

// Color and Size management routes
Route::prefix('variants')->group(function () {
    Route::get('/colors', [ProductVariantController::class, 'colors']);
    Route::post('/colors', [ProductVariantController::class, 'storeColor']);
    Route::get('/sizes', [ProductVariantController::class, 'sizes']);
    Route::post('/sizes', [ProductVariantController::class, 'storeSize']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::middleware('admin')->prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('{id}', [UserController::class, 'show']);
            Route::put('{id}', [UserController::class, 'update']);
            Route::delete('{id}', [UserController::class, 'destroy']);
        });
    });
});
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/dashboard', function () {
    return response()->json(['message' => 'Chào Admin!']);
});

Route::middleware(['auth:sanctum', 'role:member'])->get('/user/profile', function () {
    return response()->json(auth()->user());
});

Route::middleware('auth:sanctum')->prefix('cart')->group(function () {
    Route::get('/',               [CartController::class, 'index']);
    Route::post('/add',           [CartController::class, 'add']);
    Route::put('/update/{id}',    [CartController::class, 'update']);
    Route::delete('/remove/{id}', [CartController::class, 'destroy']);
    Route::delete('/clear',       [CartController::class, 'clear']);
});
