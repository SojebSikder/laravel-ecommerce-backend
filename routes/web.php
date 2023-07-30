<?php

use App\Http\Controllers\Web\Admin\Auth\AuthController;
use App\Http\Controllers\Web\Admin\Auth\UserController;
use App\Http\Controllers\Web\Admin\Category\CategoryController;
use App\Http\Controllers\Web\Admin\Checkout\CheckoutController;
use App\Http\Controllers\Web\Admin\Cms\Footer\FooterController;
use App\Http\Controllers\Web\Admin\Cms\Footer\FooterItemController;
use App\Http\Controllers\Web\Admin\Cms\Page\PageController;
use App\Http\Controllers\Web\Admin\Coupon\CouponController;
use App\Http\Controllers\Web\Admin\Customer\CustomerController;
use App\Http\Controllers\Web\Admin\Customer\RoleController;
use App\Http\Controllers\Web\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Web\Admin\Marketing\CustomMail\CustomMailController;
use App\Http\Controllers\Web\Admin\Order\OrderController;
use App\Http\Controllers\Web\Admin\Order\OrderDraft\OrderDraftController;
use App\Http\Controllers\Web\Admin\Order\StatusController;
use App\Http\Controllers\Web\Admin\Payment\PaymentProviderController;
use App\Http\Controllers\Web\Admin\Product\ManufacturerController;
use App\Http\Controllers\Web\Admin\Product\ProductController;
use App\Http\Controllers\Web\Admin\Product\ProductDetailsController;
use App\Http\Controllers\Web\Admin\Setting\SettingController;
use App\Http\Controllers\Web\Admin\Shipping\ShippingController;
use App\Http\Controllers\Web\Admin\Shipping\ShippingZoneController;
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

Route::get('/', function () {
    // return view('welcome');
    return redirect('/dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // user
    Route::get('user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::resource('user', UserController::class);

    /**
     * Product
     */
    // product image
    Route::put('product/image/{id}', [ProductController::class, 'updateImage'])->name('product.image.update');
    Route::delete('product/image/{id}/delete', [ProductController::class, 'deleteImage'])->name('product.image.destroy');

    // product details
    Route::get('product/details/{id}/status', [ProductDetailsController::class, 'status'])->name('product.details.status');
    Route::get('product/{id}/details/create', [ProductDetailsController::class, 'create'])->name('product.details.create');
    Route::post('product/{id}/details', [ProductDetailsController::class, 'store'])->name('product.details.store');
    Route::get('product/details/{id}/edit', [ProductDetailsController::class, 'edit'])->name('product.details.edit');
    Route::put('product/details/{id}', [ProductDetailsController::class, 'update'])->name('product.details.update');
    Route::delete('product/details/{id}', [ProductDetailsController::class, 'destroy'])->name('product.details.destroy');

    // product
    Route::get('product/{id}/status', [ProductController::class, 'status'])->name('product.status');
    Route::resource('product', ProductController::class);

    // Category
    Route::get('category/{id}/status', [CategoryController::class, 'status'])->name('category.status');
    Route::get('category/{id}/subcategory', [CategoryController::class, 'subcategory'])->name('category.subcategory');
    Route::resource('category', CategoryController::class);

    // Manufacturer
    Route::get('manufacturer/{id}/status', [ManufacturerController::class, 'status'])->name('manufacturer.status');
    Route::resource('manufacturer', ManufacturerController::class);

    // sales
    Route::prefix('order')->group(function () {
        Route::get('invoice-view/{id}', [OrderController::class, 'invoice'])->name('order_invoice');
        Route::post('destroy/{id}', [OrderController::class, 'destroy'])->name('order_destroy');

        // order status
        Route::post('status/payment/{id}', [OrderController::class, 'paymentStatus'])->name('payment_status');
        Route::post('status/fulfillment/{id}', [OrderController::class, 'fulfillmentStatus'])->name('fulfillment_status');
        Route::post('status/{id}', [OrderController::class, 'status'])->name('order_status');
        Route::delete('status/{id}', [OrderController::class, 'destroyStatus'])->name('order_status.destroy');
    });
    Route::resource('checkout', CheckoutController::class);
    Route::resource('order', OrderController::class);
    // draft
    Route::get('/order/{id}/invoice', [OrderController::class, 'view_invoice'])->name('order.invoice.index');
    Route::get('/order/{id}/invoice/generate', [OrderController::class, 'invoice'])->name('order.invoice.generate');
    Route::put('/order/{id}/user/shipping/details', [OrderController::class, 'saveUserShippingDetails'])->name('order.user.shipping.details');
    Route::resource('order', OrderController::class);
    Route::get('/order-draft/{id}/product/create', [OrderDraftController::class, 'create_product'])->name('order-draft.product.create');
    Route::post('/order-draft/{id}/product/store', [OrderDraftController::class, 'store_item'])->name('order-draft.product.store');
    Route::resource('order-draft', OrderDraftController::class);

    // promotions
    Route::get('coupon/{id}/status', [CouponController::class, 'status'])->name('coupon.status');
    Route::resource('coupon', CouponController::class);
    Route::resource('marketing/sendmail', CustomMailController::class);

    // customer
    Route::get('customer/{id}/status', [CustomerController::class, 'status'])->name('customer.status');
    Route::resource('customer', CustomerController::class);
    Route::resource('role', RoleController::class);

    //cms
    // page
    Route::get('page/{id}/status', [PageController::class, 'status'])->name('page.status');
    Route::resource('page', PageController::class);
    // footer
    Route::get('footer/{id}/status', [FooterController::class, 'status'])->name('footer.status');
    Route::put('footer/{id}/sort', [FooterController::class, 'sortOrder'])->name('footer.sort');
    Route::resource('footer', FooterController::class);
    Route::get('footer-item/{id}/status', [FooterItemController::class, 'status'])->name('footer-item.status');
    Route::resource('footer-item', FooterItemController::class);

    // setting
    Route::get('setting/order/status/{id}/status', [StatusController::class, 'status'])->name('status.status');
    Route::resource('setting/order/status', StatusController::class);

    Route::get('setting/payment-provider/{id}/status', [PaymentProviderController::class, 'status'])->name('payment-provider.status');
    Route::resource('setting/payment-provider', PaymentProviderController::class);

    // payment
    Route::get('setting/shipping-zone/{shipping_id}/payment-provider/{shipping_zone_id}/edit', [ShippingZoneController::class, 'paymentProviderEdit']);
    Route::put('setting/shipping-zone/payment-provider/{id}', [ShippingZoneController::class, 'paymentProviderUpdate'])->name('payment-provider.update');
    // address
    Route::get('setting/shipping-zone/{shipping_id}/address/{shipping_zone_id}/edit', [ShippingZoneController::class, 'addressEdit']);
    Route::put('setting/shipping-zone/address/{id}', [ShippingZoneController::class, 'addressUpdate'])->name('shipping.shipping-zone.update');
    Route::resource('setting/shipping-zone', ShippingZoneController::class);

    Route::get('setting/shipping/{id}/status', [ShippingController::class, 'status'])->name('shipping.status');
    Route::resource('setting/shipping', ShippingController::class);

    Route::resource('setting', SettingController::class);
});


Route::middleware('guest')->group(function () {

    Route::get('login', [AuthController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthController::class, 'store']);
});

Route::middleware('auth')->group(function () {

    Route::get('logout', [AuthController::class, 'destroy'])
        ->name('logout');

    // Route::resource('user', UserController::class);
});
