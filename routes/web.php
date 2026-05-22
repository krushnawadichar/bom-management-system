<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\PurchaseIntentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Guest routes - Authentication
// routes/web.php - Add these routes with the authentication routes

// Guest routes - Authentication
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register routes
    Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [LoginController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
});;

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
     Route::resource('users', UserController::class)->middleware('role:admin');
    // Projects
    Route::resource('projects', ProjectController::class);
    
    // BOM Management
    Route::prefix('boms')->name('boms.')->group(function () {
        Route::get('/', [BomController::class, 'index'])->name('index');
        Route::get('/create', [BomController::class, 'create'])->name('create');
        Route::post('/', [BomController::class, 'store'])->name('store');
        Route::get('/{id}', [BomController::class, 'show'])->name('show');
        Route::get('/{id}/line-items', [BomController::class, 'getLineItems'])->name('line-items');
        Route::get('/{id}/status', [BomController::class, 'getStatus'])->name('status');
    });
    
    // Purchase Intents
    Route::prefix('purchase-intents')->name('purchase-intents.')->group(function () {
        Route::get('/', [PurchaseIntentController::class, 'index'])->name('index');
        Route::get('/{id}', [PurchaseIntentController::class, 'show'])->name('show');
        Route::post('/{id}/acknowledge', [PurchaseIntentController::class, 'acknowledge'])->name('acknowledge');
        Route::post('/{id}/po-raised', [PurchaseIntentController::class, 'markPoRaised'])->name('po-raised');
        Route::post('/batch-acknowledge', [PurchaseIntentController::class, 'batchAcknowledge'])->name('batch-acknowledge');
    });
    
    // Allocations
    Route::prefix('allocations')->name('allocations.')->group(function () {
        Route::get('/', [AllocationController::class, 'index'])->name('index');
        Route::get('/{id}', [AllocationController::class, 'show'])->name('show');
        Route::post('/{id}/acknowledge', [AllocationController::class, 'acknowledge'])->name('acknowledge');
    });
    
    // Inventory
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
        Route::post('/{id}/update', [InventoryController::class, 'update'])->name('update');
    });
    
    // Sample BOM Template Download
    Route::get('/sample-bom-template', function() {
        // Create a simple Excel file if not exists
        return response()->json(['message' => 'Download sample template - Implement Excel export'], 200);
    })->name('sample-bom-template');
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/boms/{id}/status', [BomController::class, 'getStatus']);
    Route::get('/purchase-intents', [PurchaseIntentController::class, 'index']);
    Route::get('/allocations', [AllocationController::class, 'apiIndex']);
    Route::get('/inventory', [InventoryController::class, 'apiIndex']);
});