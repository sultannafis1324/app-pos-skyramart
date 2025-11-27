<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReceiptTemplateController;
use App\Http\Controllers\LossReportController;
use App\Http\Controllers\CustomerMessageTemplateController;
use App\Http\Controllers\StoreSettingController;


// Form "Lupa Password"
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

// Kirim email reset password
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Form reset password (klik dari email)
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

// Simpan password baru
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // API endpoints for dashboard
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/api/dashboard/chart', [DashboardController::class, 'getChartData']);
    Route::get('/api/dashboard/quick-stats', [DashboardController::class, 'getQuickStats']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ===== ADMIN ONLY =====
    Route::middleware('role:admin')->group(function () {
        // Users CRUD - Admin only
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Categories - Admin only
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Suppliers - Admin only
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        // Purchases - Admin only
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
        Route::get('/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
        Route::put('/purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
        Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

        // Reports - Admin only
        Route::get('/report/profit', [ReportController::class, 'profit'])->name('report.profit');
        Route::get('/report/profit/pdf', [ReportController::class, 'profitPdf'])->name('report.profit-pdf');
        Route::get('/report/profit/excel', [ReportController::class, 'profitExcel'])->name('report.profit-excel');
        Route::get('/report/sales', [ReportController::class, 'report'])->name('report.sales');
        Route::get('/report/sales/pdf', [ReportController::class, 'exportPdf'])->name('report.sales-pdf');
        Route::get('/report/sales/excel', [ReportController::class, 'exportExcel'])->name('report.sales-excel');
        Route::get('/report/purchases', [ReportController::class, 'purchases'])->name('report.purchases');
        Route::get('/report/purchases/pdf', [ReportController::class, 'purchasesPdf'])->name('report.purchases-pdf');
        Route::get('/report/purchases/excel', [ReportController::class, 'purchasesExcel'])->name('report.purchases-excel');
        Route::get('/report/loss', [LossReportController::class, 'index'])->name('report.loss');
        Route::get('/report/loss/pdf', [LossReportController::class, 'exportPdf'])->name('report.loss-pdf');
        Route::get('/report/loss/excel', [LossReportController::class, 'exportExcel'])->name('report.loss-excel');
    
    });

    // ===== ADMIN & CASHIER (View Products & Stock History) =====
    Route::middleware('role:admin,cashier')->group(function () {
        // Products - View only
        Route::get('/products', function () {
            return view('products.index');
        })->name('products.index');
        Route::get('/products/{product}/show', function ($product) {
            return view('products.show', ['productId' => $product]); 
        })->name('products.show');
        
        // Stock Histories - View only
        Route::get('/stock-histories', [StockHistoryController::class, 'index'])->name('stock-histories.index');
        Route::get('/stock-histories/{stockHistory}', [StockHistoryController::class, 'show'])->name('stock-histories.show');

        // Tambahkan setelah route products yang sudah ada
        Route::get('/products/{product}/barcode/print', [ProductController::class, 'printBarcodes'])
            ->name('products.barcode.print');
        Route::get('/products/{product}/barcode/image', [ProductController::class, 'getBarcodeImage'])
            ->name('products.barcode.image');
    });

    // ===== ADMIN ONLY (Edit Products & Stock History) =====
    Route::middleware('role:admin')->group(function () {
        Route::get('/products/create', function () {
            return view('products.create');
        })->name('products.create');
        Route::get('/products/{product}/edit', function ($product) {
            return view('products.edit', ['productId' => $product]);
        })->name('products.edit');
        
        Route::get('/stock-histories/create', [StockHistoryController::class, 'create'])->name('stock-histories.create');
        Route::post('/stock-histories', [StockHistoryController::class, 'store'])->name('stock-histories.store');
        Route::get('/stock-histories/{stockHistory}/edit', [StockHistoryController::class, 'edit'])->name('stock-histories.edit');
        Route::put('/stock-histories/{stockHistory}', [StockHistoryController::class, 'update'])->name('stock-histories.update');
        Route::delete('/stock-histories/{stockHistory}', [StockHistoryController::class, 'destroy'])->name('stock-histories.destroy');
    });

    // Promotions Routes - Admin Only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('promotions', PromotionController::class);
    Route::post('/promotions/check-code', [PromotionController::class, 'checkCode'])->name('promotions.check-code');
});

// API Routes
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/promotions/active', [PromotionController::class, 'apiActive']);
    Route::get('/promotions/product/{product}', [PromotionController::class, 'apiForProduct']);
});

    // ===== Sales Routes =====
    // Sales - Create hanya Cashier (harus di atas {sale})
    Route::middleware('role:cashier')->group(function () {
        Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
        Route::post('/api/sales/{sale}/cancel', [SaleController::class, 'cancelSaleApi']);
        Route::post('/api/sales/{sale}/change-payment-method', [SaleController::class, 'changePaymentMethod']);
        Route::get('/sales/check-expired', [SaleController::class, 'checkExpiredPayments'])->name('sales.check-expired');
        Route::get('/sales/{sale}/payment-info', [SaleController::class, 'getSaleWithPayment'])->name('sales.payment-info');
        Route::post('/sales/customers/quick-create', [SaleController::class, 'storeCustomerFromSales'])
    ->name('sales.customers.quick-create');
    });

    Route::middleware(['auth', 'role:admin,cashier'])->group(function () {
    // Printer endpoints
    Route::get('/api/printer/ports', [SaleController::class, 'getPrinterPorts'])
        ->name('sales.printer.ports');
    
    Route::post('/api/printer/test', [SaleController::class, 'testThermalPrinter'])
        ->name('sales.printer.test');
    
    // Print thermal - CRITICAL: Must be BEFORE /sales/{sale}
    Route::post('/sales/{sale}/print-thermal', [SaleController::class, 'printThermal'])
        ->name('sales.print-thermal');
});

    // Sales - View untuk Admin & Cashier
    Route::middleware('role:admin,cashier')->group(function () {
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('/sales/{sale}/receipt', [SaleController::class, 'printReceipt'])->name('sales.receipt');
        Route::get('/sales/{sale_id}/payment-return', [SaleController::class, 'paymentReturn'])->name('sales.payment-return');
        Route::get('api/sales/{sale}/check-payment', [SaleController::class, 'checkPaymentStatus']);
    });

    // Sales - Edit, Delete hanya Cashier
    Route::middleware('role:cashier')->group(function () {
        Route::get('/sales/{sale}/edit-status', [SaleController::class, 'editStatus'])->name('sales.edit-status');
        Route::put('/sales/{sale}/status', [SaleController::class, 'updateStatus'])->name('sales.update-status');
        Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
        Route::post('/sales/search-barcode', [SaleController::class, 'searchByBarcode'])->name('sales.search-barcode');
    });

    // ===== Customers Routes =====
    // Customers - Create hanya Cashier (harus di atas {customer})
    Route::middleware('role:cashier')->group(function () {
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::post('customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customers.bulkDelete');
        Route::post('customers/bulk-status', [CustomerController::class, 'bulkStatusUpdate'])->name('customers.bulkStatus');
    });

    // Customers - View untuk Admin & Cashier
    Route::middleware('role:admin,cashier')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });

    // Customers - Edit, Delete hanya Cashier
    Route::middleware('role:cashier')->group(function () {
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::delete('customers/{customer}/photo', [CustomerController::class, 'deletePhoto'])->name('customers.deletePhoto');
        Route::post('customers/{customer}/sync', [CustomerController::class, 'syncCustomerData'])->name('customers.syncCustomerData');
    });
});

// ===== API Routes =====
Route::middleware(['auth', 'web'])->prefix('api')->group(function () {
    // Products API
    Route::middleware('role:admin,cashier')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/by-supplier', [ProductController::class, 'getBySupplier']);
        Route::get('/products/{product}', [ProductController::class, 'show']);
        Route::get('/products/expired', [ProductController::class, 'expiredProducts']);
        Route::get('/products/near-expiry', [ProductController::class, 'nearExpiryProducts']);
        Route::get('/products/{product}/promotions', [ProductController::class, 'getPromotions']);
    });
    
    Route::middleware('role:admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });
    
    // Categories API - Admin only
    Route::middleware('role:admin,cashier')->group(function () {
        Route::get('/categories', [CategoryController::class, 'apiIndex']);
    });
    
    // Suppliers API - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'apiIndex']);
    });
    
    // Customers API
    Route::middleware('role:admin,cashier')->group(function () {
        Route::get('/customers', [CustomerController::class, 'apiIndex']);
    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
    // Template Management - Unified Index
    Route::get('/templates', [ReceiptTemplateController::class, 'unifiedIndex'])->name('templates.index');
    
    // Receipt Templates
    Route::prefix('receipt-templates')->group(function () {
        Route::get('/{id}/edit', [ReceiptTemplateController::class, 'edit'])->name('receipt-templates.edit');
        Route::put('/{id}', [ReceiptTemplateController::class, 'update'])->name('receipt-templates.update');
        Route::get('/{id}/preview', [ReceiptTemplateController::class, 'preview'])->name('receipt-templates.preview');
        Route::post('/{id}/reset', [ReceiptTemplateController::class, 'reset'])->name('receipt-templates.reset');
    });
    
    // Customer Message Templates
    Route::prefix('customer-message-templates')->group(function () {
        Route::get('/{id}/edit', [CustomerMessageTemplateController::class, 'edit'])->name('customer-message-templates.edit');
        Route::put('/{id}', [CustomerMessageTemplateController::class, 'update'])->name('customer-message-templates.update');
        Route::get('/{id}/preview', [CustomerMessageTemplateController::class, 'preview'])->name('customer-message-templates.preview');
        Route::post('/{id}/reset', [CustomerMessageTemplateController::class, 'reset'])->name('customer-message-templates.reset');
    });
});

    Route::middleware(['auth'])->group(function () {
    Route::get('/store-settings', [StoreSettingController::class, 'index'])
        ->name('store-settings.index');
    Route::put('/store-settings', [StoreSettingController::class, 'update'])
        ->name('store-settings.update');
    Route::delete('/store-settings/logo', [StoreSettingController::class, 'deleteLogo'])
        ->name('store-settings.delete-logo');
    Route::get('/store-settings/preview', [StoreSettingController::class, 'preview'])
        ->name('store-settings.preview');
});
    
    // Sales API
    Route::middleware('role:admin,cashier')->group(function () {
        Route::get('/sales', [SaleController::class, 'apiIndex']);
        Route::get('/sales/{sale}', [SaleController::class, 'show']);
        Route::get('/sales/search-products', [SaleController::class, 'searchProducts']);
        Route::get('/sales/search-customers', [SaleController::class, 'searchCustomers']);
        Route::get('/sales/check-stock/{product}', [SaleController::class, 'checkProductStock']);
        Route::get('sales/{sale}/check-payment', [SaleController::class, 'checkPaymentStatus']);
        Route::get('sales/{sale}/edit-status', [SaleController::class, 'editStatus']);
    });
    
    Route::middleware('role:cashier')->group(function () {
        Route::post('/sales', [SaleController::class, 'store']);
        Route::delete('/sales/{sale}', [SaleController::class, 'destroy']);
        Route::put('sales/{sale}/status', [SaleController::class, 'updateStatus']);
    });

    // Purchases API - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/purchases', [PurchaseController::class, 'apiIndex']);
        Route::post('/purchases', [PurchaseController::class, 'apiStore']);
        Route::get('/purchases/{purchase}', [PurchaseController::class, 'apiShow']);
        Route::put('/purchases/{purchase}', [PurchaseController::class, 'apiUpdate']);
        Route::delete('/purchases/{purchase}', [PurchaseController::class, 'apiDestroy']);
    });
    
    // Stock Histories API
    Route::middleware('role:admin,cashier')->group(function () {
        Route::get('/stock-histories', [StockHistoryController::class, 'index']);
        Route::get('/stock-histories/{stockHistory}', [StockHistoryController::class, 'show']);
        Route::get('/products/{product}/stock-histories', [StockHistoryController::class, 'getByProduct']);
    });
    
    Route::middleware('role:admin')->group(function () {
        Route::post('/stock-histories', [StockHistoryController::class, 'store']);
        Route::put('/stock-histories/{stockHistory}', [StockHistoryController::class, 'update']);
        Route::delete('/stock-histories/{stockHistory}', [StockHistoryController::class, 'destroy']);
        Route::get('/stock-histories/export/pdf', [StockHistoryController::class, 'exportPdf'])->name('stock-histories.export-pdf');
        Route::get('/stock-histories/export/excel', [StockHistoryController::class, 'exportExcel'])->name('stock-histories.export-excel');
    });
});

// Indonesia Location API
Route::get('/api/cities/{province_id}', function ($province_id) {
    $province = \Laravolt\Indonesia\Models\Province::find($province_id);
    if (!$province) return response()->json([]);
    
    $cities = \Laravolt\Indonesia\Models\City::where('province_code', $province->code)
        ->orderBy('name')
        ->get(['id', 'name']);
    
    return response()->json($cities);
});

Route::get('/api/districts/{city_id}', function ($city_id) {
    $city = \Laravolt\Indonesia\Models\City::find($city_id);
    if (!$city) return response()->json([]);
    
    $districts = \Laravolt\Indonesia\Models\District::where('city_code', $city->code)
        ->orderBy('name')
        ->get(['id', 'name']);
    
    return response()->json($districts);
});

Route::get('/api/villages/{district_id}', function ($district_id) {
    $district = \Laravolt\Indonesia\Models\District::find($district_id);
    if (!$district) return response()->json([]);
    
    $villages = \Laravolt\Indonesia\Models\Village::where('district_code', $district->code)
        ->orderBy('name')
        ->get(['id', 'name']);
    
    return response()->json($villages);
});

require __DIR__.'/auth.php';

Route::get('/sales/{sale}/download-receipt', [SaleController::class, 'downloadReceipt'])->name('sales.downloadReceipt');