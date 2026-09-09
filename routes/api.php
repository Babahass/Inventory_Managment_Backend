<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

// Public auth routes — rate-limited to prevent brute force
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/register', function () {
    return response()->json([
        'message' => 'Register endpoint is working'
    ]);
});

Route::get('/checker', function () {
    return "backend is working";
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard & Reports (Both Staff and Admin)
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Transactions (Both Staff and Admin can view and record sales/purchases)
    Route::apiResource('transactions', TransactionController::class)
        ->only(['index', 'store', 'show']);

    // -------------------------------------------------------------------------
    // Read-Only Routes (Both Staff and Admin can view products & metadata)
    // -------------------------------------------------------------------------
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    Route::get('/suppliers', [SupplierController::class, 'index']);
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show']);

    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // -------------------------------------------------------------------------
    // Admin-Only Routes (CRUD operations reserved strictly for Admin)
    // -------------------------------------------------------------------------
    Route::middleware(CheckAdmin::class)->group(function () {
        
        // User Management
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}/role', [UserController::class, 'updateRole']);
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate']);
        Route::post('/users/{user}/activate', [UserController::class, 'activate']);

        // Categories Management
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::patch('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // Suppliers Management
        Route::post('/suppliers', [SupplierController::class, 'store']);
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update']);
        Route::patch('/suppliers/{supplier}', [SupplierController::class, 'update']);
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy']);

        // Products Management
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::patch('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });
      Route::middleware(CheckAdmin::class)->group(function () {
        Route::get('/reports', [ReportController::class, 'index']);
    });
});


// <?php

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\CategoryController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\SupplierController;
// use App\Http\Controllers\TransactionController;
// use Illuminate\Support\Facades\Route;

// // Public auth routes — rate-limited to prevent brute force
// Route::middleware('throttle:10,1')->group(function () {
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);
// });
// Route::get('/register', function () {
//     return response()->json([
//         'message' => 'Register endpoint is working'
//     ]);
// });
// Route::get('/checker',function(){
//     return "backnd is working";
// });

// // Protected routes
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/me', [AuthController::class, 'me']);

//     Route::get('/dashboard', [DashboardController::class, 'index']);

//     Route::apiResource('categories', CategoryController::class);
//     Route::apiResource('suppliers', SupplierController::class);

//     // low-stock must come BEFORE apiResource to avoid {product} capturing it
//     Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
//     Route::apiResource('products', ProductController::class);

//     Route::apiResource('transactions', TransactionController::class)
//         ->only(['index', 'store', 'show']);
// });
