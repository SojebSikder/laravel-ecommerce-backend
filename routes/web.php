<?php

use App\Http\Controllers\Web\Admin\Auth\AuthController;
use App\Http\Controllers\Web\Admin\Auth\UserController;
use App\Http\Controllers\Web\Admin\Category\CategoryController;
use App\Http\Controllers\Web\Admin\Cms\Footer\FooterController;
use App\Http\Controllers\Web\Admin\Cms\Footer\FooterItemController;
use App\Http\Controllers\Web\Admin\Cms\Page\PageController;
use App\Http\Controllers\Web\Admin\Coupon\CouponController;
use App\Http\Controllers\Web\Admin\Customer\CustomerController;
use App\Http\Controllers\Web\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Web\Admin\Order\OrderController;
use App\Http\Controllers\Web\Admin\Product\ManufacturerController;
use App\Http\Controllers\Web\Admin\Product\ProductController;
use App\Http\Controllers\Web\Admin\Setting\SettingController;
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
    Route::resource('order', OrderController::class);

    // promotions
    Route::get('coupon/{id}/status', [CouponController::class, 'status'])->name('coupon.status');
    Route::resource('coupon', CouponController::class);

    // customer
    Route::get('customer/{id}/status', [CustomerController::class, 'status'])->name('customer.status');
    Route::resource('customer', CustomerController::class);

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
