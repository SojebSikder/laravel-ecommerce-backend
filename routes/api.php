<?php

use App\Http\Controllers\Api\App\Auth\AuthController;
use App\Http\Controllers\Api\App\Cart\CartController;
use App\Http\Controllers\Api\App\Category\CategoryController;
use App\Http\Controllers\Api\App\Checkout\CheckoutController;
use App\Http\Controllers\Api\App\Cms\Footer\FooterController;
use App\Http\Controllers\Api\App\Cms\Menu\MenuController;
use App\Http\Controllers\Api\App\Cms\Page\PageController;
use App\Http\Controllers\Api\App\Cms\Sitemap\SitemapController;
use App\Http\Controllers\Api\App\Coupon\CouponController;
use App\Http\Controllers\Api\App\Order\OrderController;
use App\Http\Controllers\Api\App\Payment\PaymentController;
use App\Http\Controllers\Api\App\Product\ProductController;
use App\Http\Controllers\Api\App\Review\ReviewController;
use App\Http\Controllers\Api\App\Setting\Setting\SettingController;
use App\Http\Controllers\Api\App\Shipping\ShippingController;
use App\Http\Controllers\Api\App\User\UserFavoriteProductController;
use App\Models\Review\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth provider. facebook, google
Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToSocialAuth']);
Route::post('/auth/{provider}/callback', [AuthController::class, 'handleSocialAuthCallback']);
// phone varification
Route::post('/verify/phone/send-otp', [AuthController::class, 'send_phone_verification_code']);
Route::post('/verify/phone/verify', [AuthController::class, 'verify_phone']);
// email varification
Route::post('/verify/email/send-otp', [AuthController::class, 'send_email_verification_code']);
Route::get('/verify/email/{code}', [AuthController::class, 'emailVerify'])->name('email.verify');

// User
Route::get("/user", [AuthController::class, 'index']);
Route::get("user/me", [AuthController::class, 'me']);
Route::post("/auth/login", [AuthController::class, 'login']);
Route::post("/auth/register", [AuthController::class, 'register']);
Route::get("/user/logout", [AuthController::class, 'logout']);
Route::put("/user/update", [AuthController::class, 'updateUser']);
// Forgot password
Route::post('/forgot-password', [AuthController::class, 'sendMail']);
Route::post('/recover-password', [AuthController::class, 'recover']);
// Product
Route::get("/product/productWithCategory", [ProductController::class, 'productWithCategories']);
Route::get("/product/productWithCategory/{id}", [ProductController::class, 'productWithCategory']);
Route::get("/product/search", [ProductController::class, 'search']);
Route::get("/product/show/{id}/{slug}", [ProductController::class, 'showOne']);
Route::get("/product/trending", [ProductController::class, 'trending']);
Route::get("/product/{id}/rating/me", [ProductController::class, 'showRating']);
Route::get("/product/trending", [ProductController::class, 'trending']);
Route::resource("product", ProductController::class);

// Review
Route::resource("review", ReviewController::class);

Route::resource("category", CategoryController::class);

// order
Route::resource("checkout", CheckoutController::class);
Route::post("/order/status/check", [OrderController::class, 'orderStatus']);
Route::resource("order", OrderController::class);

// payment
Route::post("/payment/pay", [PaymentController::class, 'payment']);
Route::post("/payment/stripe_webhook", [PaymentController::class, 'stripe_webhook']);


// cart
Route::get("cart-count", [CartController::class, 'cartCount']);
Route::resource("cart", CartController::class);

Route::resource('coupon', CouponController::class);

// favorite product
Route::resource("user-favorite-product", UserFavoriteProductController::class);

// page
Route::resource('page', PageController::class);
Route::resource('footer', FooterController::class);
Route::resource("menu", MenuController::class);
// sitemap
Route::get('sitemap.xml', [SitemapController::class, 'index']);
Route::resource('sitemap', SitemapController::class);
// setting
Route::resource('shipping', ShippingController::class);
Route::resource('setting', SettingController::class);
