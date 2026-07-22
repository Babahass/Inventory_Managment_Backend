<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with(['product', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $data['user_id'] = $request->user()->id;

        // Fix: initialize $transaction before closure so it's always defined
        $transaction = null;

        DB::transaction(function () use (&$data, &$transaction) {
            $product = Product::lockForUpdate()->findOrFail($data['product_id']);

            // Fix: throw ValidationException instead of abort() so frontend
            //      receives a proper { errors: { quantity: [...] } } response
            if ($data['type'] === 'out' && $product->quantity < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => ['Insufficient stock. Available: ' . $product->quantity],
                ]);
            }

            $delta = $data['type'] === 'in' ? $data['quantity'] : -$data['quantity'];
            $product->increment('quantity', $delta);

            $transaction = Transaction::create($data);
        });

        // $transaction is guaranteed non-null here (exception thrown otherwise)
        return response()->json(
            $transaction->load(['product', 'user']),
            201
        );
    }

    public function show(Transaction $transaction): JsonResponse
    {
        return response()->json($transaction->load(['product', 'user']));
    }
}
