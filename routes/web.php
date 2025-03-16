<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\TemplateController;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\MemberShipController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PaymentInfoController;
use App\Http\Controllers\Admin\LocalizationController;
use App\Http\Controllers\Admin\PaymentAccountController;
use App\Http\Controllers\Admin\AttributeOptionController;
use App\Http\Controllers\Admin\ExcelController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ReportingController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagLineController;
use App\Http\Controllers\Admin\TransactionController;


// Auth::routes(['register' => false]);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth:sanctum', config('jetstream.auth_session'), 'verified'], 'prefix' => 'admin'], function () {

    Route::group(['as' => 'admin.'], function () {

        Route::post('generate-attribute', [AttributeOptionController::class, 'generateAttribute'])->name('attribute-options.generate');
        Route::post('attribute-options/select-value', [AttributeOptionController::class, 'getSelectdAttribute'])->name('attribute-options.selectd');

        Route::get('products/export', [ProductController::class, 'export'])->name('products.export');

        Route::get('{slug?}/excel-import', [ExcelController::class, 'index'])->name('excel-import.index');

        Route::post('{slug?}/excel-import', [ExcelController::class, 'store'])->name('excel-import.store');


        Route::post('product-vairations', [ProductController::class, 'storeVairation'])->name('product-vairations.store');
        Route::put('product-vairations/{id}', [ProductController::class, 'updateVairation'])->name('product-vairations.update');
        Route::delete('product-vairations/{id}', [ProductController::class, 'removeVairation'])->name('product-vairations.remove');

        Route::delete('translates/{id}', [TranslateController::class, 'destroy'])->name('translates.delete');

        Route::get('orders/{id}/download', [OrderController::class, 'download'])->name('orders.pdf-download');

        Route::post('orders/{id}/resend/{name?}', [OrderController::class, 'resend'])->where('name', 'confirmation|cancel')->name('orders.resend');

        Route::resources([
            'roles'             => RoleController::class,
            'permissions'       => PermissionController::class,
            'products'          => ProductController::class,
            'categories'        => CategoryController::class,
            'attributes'        => AttributeController::class,
            'attribute-options' => AttributeOptionController::class,
            'languages'         => LanguageController::class,
            'customers'         => CustomerController::class,
            'memberships'       => MemberShipController::class,
            'localizations'     => LocalizationController::class,
            'tags'              => TagController::class,
            'notifications'        => PromotionController::class,
            'pages'             => PageController::class,
            'users'             => UserController::class,
            'profiles'          => ProfileController::class,
            'banners'           => BannerController::class,
            'payments'          => PaymentController::class,
            'orders'            => OrderController::class,
            'payment-infos'     => PaymentInfoController::class,
            'payment-accounts'  => PaymentAccountController::class,
            'activity-logs'     => ActivityLogController::class,
            'tag-lines'         => TagLineController::class,
            'templates'         => TemplateController::class
        ]);

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'store'])->name('settings.store');

        Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::put('transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
        Route::delete('transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

        Route::post('localizations/handle-delete', [LocalizationController::class, 'handleDelete'])->name('localizations.handle-delete');
        Route::post('localizations/handle-update', [LocalizationController::class, 'handleUpdate'])->name('localizations.handle-update');

        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::post('messages', [MessageController::class, 'update'])->name('messages.update');

        Route::get('/reports', [ReportingController::class, 'index'])->name('reportings.index');
        Route::get('/order-export', [ReportingController::class, "export"])->name('order.export');
        Route::get('/order-report/{type}', [ReportingController::class, 'reportOrderType']);
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
