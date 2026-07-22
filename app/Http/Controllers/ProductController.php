<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%$q%")
                   ->orWhere('sku', 'like', "%$q%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('low_stock') && $request->low_stock === 'true') {
            $query->whereColumn('quantity', '<=', 'low_stock_threshold');
        }

        $products = $query->orderBy('name')->paginate(20);
        return response()->json($products);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'sku'                 => 'required|string|unique:products',
            'description'         => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'quantity'            => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'category_id'         => 'nullable|exists:categories,id',
            'supplier_id'         => 'nullable|exists:suppliers,id',
        ]);

        return response()->json(
            Product::create($data)->load(['category', 'supplier']),
            201
        );
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load(['category', 'supplier', 'transactions.user']));
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'name'                => 'sometimes|required|string|max:255',
            'sku'                 => 'sometimes|required|string|unique:products,sku,' . $product->id,
            'description'         => 'nullable|string',
            'price'               => 'sometimes|required|numeric|min:0',
            'quantity'            => 'sometimes|required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'category_id'         => 'nullable|exists:categories,id',
            'supplier_id'         => 'nullable|exists:suppliers,id',
        ]);

        $product->update($data);
        return response()->json($product->load(['category', 'supplier']));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function lowStock(): JsonResponse
    {
        $products = Product::with(['category', 'supplier'])
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->get();

        return response()->json($products);
    }
}
