<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Controllers\AuthController;

Route::get('/', function () {
    return view('landing');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard & Scoped Routes
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Order Routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [App\Modules\Sms\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('/search', [App\Modules\Sms\Controllers\OrderController::class, 'search'])->name('orders.search');
        Route::post('/purchase', [App\Modules\Sms\Controllers\OrderController::class, 'purchase'])->name('orders.purchase');
        Route::get('/{id}', [App\Modules\Sms\Controllers\OrderController::class, 'show'])->name('orders.show');
        Route::get('/{id}/check', [App\Modules\Sms\Controllers\OrderController::class, 'checkSms'])->name('orders.check');
    });

    // Rental Routes
    Route::prefix('rentals')->group(function () {
        Route::get('/', function () { return view('rentals.index'); })->name('rentals.index');
    });

    // Wallet Routes
    Route::prefix('wallet')->group(function () {
        Route::get('/deposit', [App\Http\Controllers\WalletController::class, 'deposit'])->name('wallet.deposit');
        Route::post('/deposit/{gateway}', [App\Http\Controllers\WalletController::class, 'initializePayment'])->name('wallet.payment.init');
        Route::get('/callback/{gateway}', [App\Http\Controllers\WalletController::class, 'paymentCallback'])->name('wallet.callback');
    });


    // Sub-accounts Routes
    Route::prefix('subaccounts')->group(function () {
        Route::get('/', function () { return view('subaccounts.index'); })->name('subaccounts.index');
    });

    // Support Routes
    Route::prefix('support')->group(function () {
        Route::get('/', function () { return view('support.index'); })->name('support.index');
    });

    // Referral Routes
    Route::prefix('referrals')->group(function () {
        Route::get('/', function () { return view('referrals.index'); })->name('referrals.index');
    });

    // ADMIN ROUTES
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Tenants Management
        Route::resource('tenants', App\Http\Controllers\Admin\TenantController::class)->except(['show', 'destroy']);
        Route::get('tenants/{tenant}', [App\Http\Controllers\Admin\TenantController::class, 'show'])->name('tenants.show');
        Route::post('tenants/{tenant}/toggle-status', [App\Http\Controllers\Admin\TenantController::class, 'toggleStatus'])->name('tenants.toggle-status');
        Route::post('tenants/{tenant}/make-default', [App\Http\Controllers\Admin\TenantController::class, 'makeDefault'])->name('tenants.make-default');
        
        // Users Management
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::get('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::post('users/{user}/adjust-wallet', [App\Http\Controllers\Admin\UserController::class, 'adjustWallet'])->name('users.adjust-wallet');
        Route::post('users/{user}/toggle-ban', [App\Http\Controllers\Admin\UserController::class, 'toggleBan'])->name('users.toggle-ban');
        Route::post('users/{user}/assign-role', [App\Http\Controllers\Admin\UserController::class, 'assignRole'])->name('users.assign-role');
        Route::post('users/{user}/remove-role', [App\Http\Controllers\Admin\UserController::class, 'removeRole'])->name('users.remove-role');
        Route::post('users/{user}/toggle-2fa', [App\Http\Controllers\Admin\UserController::class, 'toggle2FA'])->name('users.toggle-2fa');
        Route::get('users/{user}/permissions', [App\Http\Controllers\Admin\UserController::class, 'permissions'])->name('users.permissions');
        Route::post('users/{user}/sync-permissions', [App\Http\Controllers\Admin\UserController::class, 'syncPermissions'])->name('users.sync-permissions');
        
        // Providers Management
        Route::resource('providers', App\Http\Controllers\Admin\ProviderController::class)->except(['show']);
        Route::get('providers/{provider}', [App\Http\Controllers\Admin\ProviderController::class, 'show'])->name('providers.show');
        Route::post('providers/{provider}/toggle-status', [App\Http\Controllers\Admin\ProviderController::class, 'toggleStatus'])->name('providers.toggle-status');
        Route::get('providers/{provider}/numbers', [App\Http\Controllers\Admin\ProviderController::class, 'numbers'])->name('providers.numbers');
        Route::post('providers/{provider}/sync-numbers', [App\Http\Controllers\Admin\ProviderController::class, 'syncNumbers'])->name('providers.sync-numbers');
        Route::post('providers/{provider}/update-rates', [App\Http\Controllers\Admin\ProviderController::class, 'updateRates'])->name('providers.update-rates');
        
        // Pricing Management
        Route::prefix('pricing')->name('pricing.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PricingController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\PricingController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\PricingController::class, 'store'])->name('store');
            Route::get('/{pricing}/edit', [App\Http\Controllers\Admin\PricingController::class, 'edit'])->name('edit');
            Route::put('/{pricing}', [App\Http\Controllers\Admin\PricingController::class, 'update'])->name('update');
            Route::delete('/{pricing}', [App\Http\Controllers\Admin\PricingController::class, 'destroy'])->name('destroy');
            Route::post('/{pricing}/toggle-status', [App\Http\Controllers\Admin\PricingController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/bulk-update', [App\Http\Controllers\Admin\PricingController::class, 'bulkUpdate'])->name('bulk-update');
            Route::get('/import', [App\Http\Controllers\Admin\PricingController::class, 'import'])->name('import');
            Route::post('/import-process', [App\Http\Controllers\Admin\PricingController::class, 'importProcess'])->name('import-process');
            Route::get('/export', [App\Http\Controllers\Admin\PricingController::class, 'export'])->name('export');
        });
        
        // Settings Management
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
            Route::put('/general', [App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('update-general');
            Route::put('/payment', [App\Http\Controllers\Admin\SettingsController::class, 'updatePayment'])->name('update-payment');
            Route::put('/notification', [App\Http\Controllers\Admin\SettingsController::class, 'updateNotification'])->name('update-notification');
            Route::put('/security', [App\Http\Controllers\Admin\SettingsController::class, 'updateSecurity'])->name('update-security');
            Route::put('/email', [App\Http\Controllers\Admin\SettingsController::class, 'updateEmail'])->name('update-email');
            Route::post('/cache/clear', [App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('clear-cache');
            Route::post('/backup', [App\Http\Controllers\Admin\SettingsController::class, 'createBackup'])->name('create-backup');
            Route::get('/logs', [App\Http\Controllers\Admin\SettingsController::class, 'viewLogs'])->name('logs');
            Route::get('/system-info', [App\Http\Controllers\Admin\SettingsController::class, 'systemInfo'])->name('system-info');
        });
        
        // Analytics & Dashboard
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('index');
            Route::get('/dashboard', [App\Http\Controllers\Admin\AnalyticsController::class, 'dashboard'])->name('dashboard');
            Route::get('/revenue', [App\Http\Controllers\Admin\AnalyticsController::class, 'revenue'])->name('revenue');
            Route::get('/usage', [App\Http\Controllers\Admin\AnalyticsController::class, 'usage'])->name('usage');
            Route::get('/numbers', [App\Http\Controllers\Admin\AnalyticsController::class, 'numbers'])->name('numbers');
            Route::get('/orders', [App\Http\Controllers\Admin\AnalyticsController::class, 'orders'])->name('orders');
            Route::get('/real-time', [App\Http\Controllers\Admin\AnalyticsController::class, 'realTime'])->name('real-time');
        });
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/earnings', [App\Http\Controllers\Admin\ReportsController::class, 'earnings'])->name('earnings');
            Route::get('/usage', [App\Http\Controllers\Admin\ReportsController::class, 'usage'])->name('usage');
            Route::get('/number-performance', [App\Http\Controllers\Admin\ReportsController::class, 'numberPerformance'])->name('number-performance');
            Route::get('/order-analytics', [App\Http\Controllers\Admin\ReportsController::class, 'orderAnalytics'])->name('order-analytics');
            Route::get('/export', [App\Http\Controllers\Admin\ReportsController::class, 'export'])->name('export');
            Route::post('/export/generate', [App\Http\Controllers\Admin\ReportsController::class, 'generateExport'])->name('generate-export');
            Route::get('/schedule', [App\Http\Controllers\Admin\ReportsController::class, 'schedule'])->name('schedule');
            Route::post('/schedule/store', [App\Http\Controllers\Admin\ReportsController::class, 'storeSchedule'])->name('store-schedule');
            Route::delete('/schedule/{schedule}', [App\Http\Controllers\Admin\ReportsController::class, 'deleteSchedule'])->name('delete-schedule');
            Route::get('/download/{file}', [App\Http\Controllers\Admin\ReportsController::class, 'download'])->name('download');
        });
        
        // Bulk Operations
        Route::prefix('bulk')->name('bulk.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BulkOperationsController::class, 'index'])->name('index');
            Route::post('/import-numbers', [App\Http\Controllers\Admin\BulkOperationsController::class, 'importNumbers'])->name('import-numbers');
            Route::post('/export-orders', [App\Http\Controllers\Admin\BulkOperationsController::class, 'exportOrders'])->name('export-orders');
            Route::post('/cancel-orders', [App\Http\Controllers\Admin\BulkOperationsController::class, 'cancelOrders'])->name('cancel-orders');
            Route::post('/refund', [App\Http\Controllers\Admin\BulkOperationsController::class, 'refund'])->name('refund');
            Route::get('/status/{batchId}', [App\Http\Controllers\Admin\BulkOperationsController::class, 'status'])->name('status');
            Route::get('/template/download', [App\Http\Controllers\Admin\BulkOperationsController::class, 'downloadTemplate'])->name('download-template');
            Route::post('/validate', [App\Http\Controllers\Admin\BulkOperationsController::class, 'validate'])->name('validate');
        });
        
        // Provider Rates Management (additional)
        Route::prefix('provider-rates')->name('provider-rates.')->group(function () {
            Route::get('/{provider}', [App\Http\Controllers\Admin\ProviderController::class, 'rates'])->name('index');
            Route::post('/{provider}', [App\Http\Controllers\Admin\ProviderController::class, 'storeRates'])->name('store');
            Route::put('/{provider}/{rate}', [App\Http\Controllers\Admin\ProviderController::class, 'updateRate'])->name('update');
            Route::delete('/{provider}/{rate}', [App\Http\Controllers\Admin\ProviderController::class, 'deleteRate'])->name('destroy');
            Route::post('/{provider}/bulk-upload', [App\Http\Controllers\Admin\ProviderController::class, 'bulkUploadRates'])->name('bulk-upload');
        });
        
        // Numbers Management (if needed)
        Route::prefix('numbers')->name('numbers.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\NumberController::class, 'index'])->name('index');
            Route::get('/{number}', [App\Http\Controllers\Admin\NumberController::class, 'show'])->name('show');
            Route::post('/{number}/release', [App\Http\Controllers\Admin\NumberController::class, 'release'])->name('release');
            Route::post('/{number}/extend', [App\Http\Controllers\Admin\NumberController::class, 'extend'])->name('extend');
            Route::get('/inventory', [App\Http\Controllers\Admin\NumberController::class, 'inventory'])->name('inventory');
        });
    });
});

