<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $lowStockCount = Product::whereColumn('quantity', '<=', 'low_stock_threshold')->count();
        $totalValue = Product::selectRaw('SUM(price * quantity) as value')->value('value') ?? 0;

        $recentTransactions = Transaction::with(['product', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        $lowStockProducts = Product::with('category')
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->limit(5)
            ->get();

        $topProducts = Product::orderByDesc('quantity')->limit(5)->get();

        return response()->json([
            'stats' => [
                'total_products'   => $totalProducts,
                'total_categories' => $totalCategories,
                'total_suppliers'  => $totalSuppliers,
                'low_stock_count'  => $lowStockCount,
                'total_value'      => round($totalValue, 2),
            ],
            'recent_transactions' => $recentTransactions,
            'low_stock_products'  => $lowStockProducts,
            'top_products'        => $topProducts,
        ]);
    }
}
