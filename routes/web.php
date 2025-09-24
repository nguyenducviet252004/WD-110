<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ManagerUserController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LogoBannerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\QuanliReviewController;
use App\Http\Controllers\ThongkeController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UservoucherController;

use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ChatAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Đây là nơi bạn có thể đăng ký các route web cho ứng dụng của bạn.
| Các route này được tải bởi RouteServiceProvider và tất cả chúng
| sẽ được gán vào nhóm "web" middleware. Hãy tạo nên điều gì đó tuyệt vời!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin chat page
Route::controller(ChatAdminController::class)->middleware(['token.auth', 'admin'])->group(function () {
    Route::get('/admin/chat', 'index')->name('admin.chat');
});

Route::get('/test', function () {
    return view('test');
});

// Các route cho Account
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
    Route::get('/admin/dashboard',  'admin')->name('admin.dashboard');
    Route::get('/admin/dashboard-test',  'admin')->name('admin.dashboard.test');

    // Đổi mật khẩu
    Route::get('/admin/change-password', 'changepass')->name('admin.changepass.form');
    Route::post('/admin/change-password', 'changepass_')->name('admin.password.change');
    // Cập nhật tài khoản
    Route::get('/admin/edit', 'edit')->name('admin.edit');
    Route::post('/admin/update', 'update')->name('admin.update');

    //banner va blog
    Route::resource('logo_banners', LogoBannerController::class);
    Route::resource('blog', BlogController::class);

    //cac route con lai
    Route::resource('sizes', SizeController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('vouchers', VoucherController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('products', ProductController::class);
    Route::delete('/products/{product}/galleries/{gallery}', [ProductController::class, 'destroyGalleryImage'])->name('products.destroyGalleryImage');

    // Toggle product status
    Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

    // Product Variants routes
    Route::prefix('products/{product}')->group(function () {
        Route::get('/variants', [ProductVariantController::class, 'index'])->name('product-variants.index');
        Route::get('/variants/create', [ProductVariantController::class, 'create'])->name('product-variants.create');
        Route::post('/variants', [ProductVariantController::class, 'store'])->name('product-variants.store');
        Route::get('/variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('product-variants.edit');
        Route::put('/variants/{variant}', [ProductVariantController::class, 'update'])->name('product-variants.update');
        Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('product-variants.destroy');
        Route::post('/variants/bulk-create', [ProductVariantController::class, 'bulkCreate'])->name('product-variants.bulk-create');
    });

    Route::resource('review', QuanliReviewController::class);
    //quan li account, route nay chi co quyen admin
    Route::resource('managers', ManagerUserController::class)
        ->middleware(['auth', 'admin']);
    //route thong ke
    Route::get('/search', [AdminController::class, 'search'])->name('search');

    Route::get('/thongke/account', [ThongkeController::class, 'account'])->name('thongke.account');
    Route::get('/thongke/orders', [ThongkeController::class, 'orders'])->name('thongke.orders');
    Route::get('/thongke/topproduct', [ThongkeController::class, 'topproduct'])->name('thongke.topproduct');

    Route::get('/thongke/tonkho', [ThongkeController::class, 'tonkho'])->name('thongke.tonkho');
    Route::get('/thongke/khachhang', [ThongkeController::class, 'khachhang'])->name('thongke.khachhang');
    Route::get('/thongke/voucher', [ThongkeController::class, 'voucher'])->name('thongke.voucher');
    Route::get('/thongke/tiledon', [ThongkeController::class, 'tiledon'])->name('thongke.tiledon');


});

// Route cho User
Route::controller(UserController::class)->middleware(['token.auth', 'user'])->group(function () {
    Route::get('/user/dashboard', 'user')->name('user.dashboard');
    // Đổi mật khẩu
    Route::get('/user/change-password', 'changepass')->name('user.changepass.form');
    Route::post('/user/change-password', 'changepass_')->name('user.password.change');
    // Cập nhật tài khoản
    Route::get('/user/edit', 'edit')->name('user.edit');
    Route::post('/user/update', 'update')->name('user.update');
    //địa chỉ
    Route::resource('address', AddressController::class);
    Route::patch('ship-addresses/{id}/set-default',  [AddressController::class, 'setDefault'])->name('address.set-default');

    Route::resource('uservouchers', UservoucherController::class);
    Route::post('/order/{id}/cancel', [UservoucherController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/order/{id}/confirm-receive', [UservoucherController::class, 'confirmReceiveOrder'])->name('order.confirmReceive');

    Route::resource('userorder', UserOrderController::class);
    Route::patch('/orders/{orderId}/done',  [UserOrderController::class, 'done'])->name('done');

    Route::post('user/review/{order}', [ReviewController::class, 'store'])->name('review.store');


});


Route::middleware('web')->group(function () {
    Route::post('payment/result', [PaymentController::class, 'handlePaymentResult'])->name('payment.result');
});

// Debug route để kiểm tra dữ liệu địa chỉ (có thể xóa sau khi fix)
Route::get('/debug-address/{orderId}', function ($orderId) {
    $order = \App\Models\Order::with('shipAddress')->find($orderId);

    if (!$order) {
        return response()->json(['error' => 'Order not found']);
    }

    $shipAddress = \App\Models\Ship_address::find($order->ship_address_id);

    return response()->json([
        'order_id' => $order->id,
        'ship_address_id' => $order->ship_address_id,
        'order_ship_address' => $order->shipAddress,
        'direct_ship_address' => $shipAddress,
        'address_info' => $order->getShippingAddressInfo(),
        'has_complete_address' => $order->hasCompleteShippingAddress()
    ]);
})->name('debug.address');
