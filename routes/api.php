<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\FCMController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\LocalizationController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\MemberShipController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentInformationController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\PointTransactionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TagLineController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Middleware\AcceptApiToken;
use Illuminate\Support\Facades\Route;


// Upload Image
Route::post('media', [MediaController::class, 'storeMedia']);
Route::delete('media/{slug}', [MediaController::class, 'deleteMedia']);


Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('send-otp', [AuthController::class, 'sendOTP']);
    Route::post('verify-otp', [AuthController::class, 'verifyOTP']);

    Route::post('forgot-password/send-otp', [ForgotPasswordController::class, 'sendOTP']);
    Route::post('forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOTP']);
    Route::post('forgot-password', [ForgotPasswordController::class, 'createNewPassword']);

    Route::get('/categories', [CategoryController::class, 'index']);

    // Route::withoutMiddleware(AcceptApiToken::class)->group(function () {
    Route::get('/localization', [LocalizationController::class, 'index'])->withoutMiddleware(AcceptApiToken::class);
    Route::get('/languages', [LanguageController::class, 'index'])->withoutMiddleware(AcceptApiToken::class);
    // });

    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/banners/{id}', [BannerController::class, 'show']);

    Route::get('/promotions', [PromotionController::class, 'index']);
    Route::get('/promotions/{id}', [PromotionController::class, 'show']);

    Route::get('/memberships', [MemberShipController::class, 'index']);
    Route::get('/memberships/{id}', [MemberShipController::class, 'show']);

    Route::get('tag-lines', [TagLineController::class, 'index']);

    Route::get('/payment-methods', [PaymentMethodController::class, 'index']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/auth', [AuthController::class, 'index']);
        Route::post('/auth/update', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

        Route::post('/change-credential', [AuthController::class, 'chnageCredential']);
        Route::post('/verify-change-credential', [AuthController::class, 'verifyCredential']);

        Route::get('/point-level', [AuthController::class, 'getPointLevel']);
        Route::get('/points-transaction', [PointTransactionController::class, 'index']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders/checkout', [OrderController::class, 'store']);
        Route::post('/products/wishlist/{id}', [ProductController::class, 'toggleWishlist']);
        Route::get('/my-wishlist', [ProductController::class, 'wishlist']);

        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::get('/products/{id}/related', [ProductController::class, 'related']);

        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'read']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);

        Route::post('/allow-notification', [FCMController::class, 'save'])->name('token.save');
        Route::post('/update-token', [AuthController::class, 'tokenUpdate']);
    });

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/{id}/related', [ProductController::class, 'related']);

    Route::post('/promotion-notify', [PromotionController::class, 'notify'])->name('promotions.send-notify');
    Route::post('/promotion-test-notify', [PromotionController::class, 'testNoti'])->name('promotions.test-notify');

    Route::get('settings', [SettingController::class, 'index']);

    Route::get('/payment-info', [PaymentInformationController::class, 'index']);

    Route::withoutMiddleware([AcceptApiToken::class])->group(function () {
        Route::post('/generate-token', [TokenController::class, 'generateToken']);
        Route::post('/validate-token', [TokenController::class, 'validateToken']);
        Route::post('/orders/validate-item', [OrderController::class, 'validateItem'])->name('orders.validate-item');
        Route::get('/customer-search', [CustomerController::class, 'filterByKeyword'])->name('customers.search-by-keyword');
        Route::get('/products-search', [ProductController::class, 'filterByKeyword'])->name('products.search-by-keyword');
        Route::get('/category-search', [CategoryController::class, 'filterByKeyword'])->name('categories.search-by-keyword');
    });


    Route::get('/check-api-token', [NotificationController::class, 'getToken']);
});
