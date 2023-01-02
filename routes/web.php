<?php

use App\Http\Controllers\Web\Admin\Auth\AuthController;
use App\Http\Controllers\Web\Admin\Category\CategoryController;
use App\Http\Controllers\Web\Admin\Dashboard\DashboardController;
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
    return view('welcome');
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // setting
    Route::resource('setting', SettingController::class);
});


Route::middleware('guest')->group(function () {

    Route::get('login', [AuthController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthController::class, 'store']);
});

Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthController::class, 'destroy'])
        ->name('logout');

    Route::resource('user', UserController::class);
});
