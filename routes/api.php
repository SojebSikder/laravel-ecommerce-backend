<?php

use App\Http\Controllers\Api\App\Auth\AuthController;
use App\Http\Controllers\Api\App\Product\ProductController;
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
Route::post("/user/login", [AuthController::class, 'login']);
Route::post("/user/register", [AuthController::class, 'register']);
Route::get("/user/logout", [AuthController::class, 'logout']);
Route::put("/user/update", [AuthController::class, 'updateUser']);
// Forgot password
Route::post('/forgot-password', [AuthController::class, 'sendMail']);
Route::post('/recover-password', [AuthController::class, 'recover']);
// Product
Route::get("/product/search", [ProductController::class, 'search']);
Route::get("/product/show/{id}/{slug}", [ProductController::class, 'showOne']);
Route::get("/product/trending", [ProductController::class, 'trending']);
Route::get("/product/{id}/rating/me", [ProductController::class, 'showRating']);
Route::resource("product", ProductController::class);
