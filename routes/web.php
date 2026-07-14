<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProductionLogController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

// ============ AUTH ROUTES (Manual) ============
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// ============ Protected Routes ============
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
        Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
        Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
        Route::get('/purchases', [ReportController::class, 'purchaseReport'])->name('purchases');
        Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
        Route::get('/stock-ledger/{item}', [ReportController::class, 'stockLedger'])->name('stock-ledger');
    });
    
    // Modules
    Route::resource('categories', CategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('items', ItemController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update']);
    Route::resource('purchase-returns', PurchaseReturnController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('recipes', RecipeController::class);
    Route::resource('production-logs', ProductionLogController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('sales', SaleController::class)->except(['edit', 'update']);
    Route::resource('sale-returns', SaleReturnController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('expenses', ExpenseController::class);

    // Sale thermal receipt
    Route::get('/sales/{id}/thermal', [SaleController::class, 'thermalReceipt'])->name('sales.thermal');

    // Internal AJAX/API endpoints (authenticated)
    Route::get('/api/items/purchasable/{supplierId}', function ($supplierId) {
        return \App\Models\Item::where('is_purchasable', true)->where('is_active', true)->get(['id', 'name']);
    });

    Route::get('/api/recipe/{id}/batch-qty', function ($id) {
        $recipe = \App\Models\Recipe::with('batchUnit')->findOrFail($id);
        return response()->json([
            'batch_qty' => $recipe->batch_qty,
            'unit' => $recipe->batchUnit->short_name,
        ]);
    });

    Route::get('/api/sale/{invoice}', function ($invoice) {
        $sale = \App\Models\Sale::with('items.item')->where('invoice_no', $invoice)->firstOrFail();
        return response()->json([
            'invoice_no' => $sale->invoice_no,
            'sale_date' => $sale->sale_date,
            'customer_name' => $sale->customer->name ?? 'Walk-in',
            'total_amount' => $sale->total_amount,
            'items' => $sale->items->map(function ($item) {
                return [
                    'item_id' => $item->item_id,
                    'name' => $item->item->name,
                    'qty' => $item->qty,
                    'unit_price' => $item->unit_price,
                ];
            }),
        ]);
    });

    // Language Switcher
    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'hi'])) {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }
        return back();
    })->name('lang');
});

// Frontend Routes (public landing page)
Route::get("/", [App\Http\Controllers\FrontendController::class, "index"])->name("frontend.home");
Route::post("/demo-request", [App\Http\Controllers\FrontendController::class, "submitDemo"])->name("demo.request");

// Admin Frontend Settings
Route::middleware(["auth"])->prefix("admin/frontend")->name("admin.frontend.")->group(function () {
    Route::get("/", [App\Http\Controllers\FrontendSettingsController::class, "index"])->name("settings");
    Route::post("/settings", [App\Http\Controllers\FrontendSettingsController::class, "updateSettings"])->name("settings.update");
    Route::post("/featured/add", [App\Http\Controllers\FrontendSettingsController::class, "addFeaturedProduct"])->name("featured.add");
    Route::post("/featured/remove/{id}", [App\Http\Controllers\FrontendSettingsController::class, "removeFeaturedProduct"])->name("featured.remove");
    Route::post("/testimonial/add", [App\Http\Controllers\FrontendSettingsController::class, "addTestimonial"])->name("testimonial.add");
    Route::post("/testimonial/delete/{id}", [App\Http\Controllers\FrontendSettingsController::class, "deleteTestimonial"])->name("testimonial.delete");
    Route::post("/demo/update/{id}", [App\Http\Controllers\FrontendSettingsController::class, "updateDemoStatus"])->name("demo.update");
});

