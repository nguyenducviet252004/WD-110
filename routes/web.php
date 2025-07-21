<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\QuanliReviewController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ThongkeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UservoucherController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\OrderController;

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

Route::controller(AccountController::class)->group(function () {
    // Đăng ký
    Route::get('register', 'register')->name('register.form');
    Route::post('register', 'register_')->name('register');
    // Đăng nhập
    Route::get('login', 'login')->name('login.form');
    Route::post('login', 'login_')->name('login');
    // Quên mật khẩu
    Route::get('password/forgot', 'rspassword')->name('password.forgot.form');
    Route::post('password/forgot', 'rspassword_')->name('password.forgot');
    // Đặt lại mật khẩu
    Route::get('password/reset/{token}', 'updatepassword')->name('password.reset');
    Route::post('password/reset', 'updatepassword_')->name('password.update');
    // Xác thực email
    Route::get('/verify', 'verify')->name('verify')->middleware('auth');
    Route::get('/verify/{id}/{hash}', 'verifydone')->name('verification.verify');

    // Đăng xuất
    Route::post('logout', 'logout')->name('logout');
});

// Route cho Admin
Route::controller(AdminController::class)->middleware(['token.auth', 'admin'])->group(function () {
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);

    Route::resource('vouchers', VoucherController::class);
    Route::resource('review', QuanliReviewController::class);
    Route::resource('blog', BlogController::class);
    Route::resource('orders', OrderController::class);

    // Thống kê
    Route::get('/search', [AdminController::class, 'search'])->name('search');
    Route::get('/thongke/account', [ThongkeController::class, 'account'])->name('thongke.account');
    Route::get('/thongke/orders', [ThongkeController::class, 'orders'])->name('thongke.orders');
});


Route::controller(UserController::class)->middleware(['token.auth', 'user'])->group(function () {

    Route::resource('uservouchers', UservoucherController::class);
    Route::post('/order/{id}/cancel', [UservoucherController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/order/{id}/confirm-receive', [UservoucherController::class, 'confirmReceiveOrder'])->name('order.confirmReceive');

    Route::resource('userorder', UserOrderController::class);
    Route::patch('/orders/{orderId}/done',  [UserOrderController::class, 'done'])->name('done');
});
