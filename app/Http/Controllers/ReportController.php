<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Transaction;

class ReportController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $totalValue = $products->sum(function ($product) {
            return $product->quantity * $product->price;
        });

        $lowStockProducts = $products->filter(function ($product) {
            return $product->quantity <= $product->low_stock_threshold;
        })->values();

        return response()->json([
            'total_products' => Product::count(),

            'total_categories' => Category::count(),

            'total_suppliers' => Supplier::count(),

            'total_inventory_value' => $totalValue,

            'low_stock_count' => $lowStockProducts->count(),

            'total_stock_in' => Transaction::where('type', 'in')
                ->sum('quantity'),

            'total_stock_out' => Transaction::where('type', 'out')
                ->sum('quantity'),

            'low_stock_products' => $lowStockProducts,

            'recent_transactions' => Transaction::with([
                'product',
                'user'
            ])
                ->latest()
                ->take(20)
                ->get(),
        ]);
    }
}