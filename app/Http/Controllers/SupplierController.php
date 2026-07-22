<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Supplier::withCount('products')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email',
            'phone'   => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        return response()->json(Supplier::create($data), 201);
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return response()->json($supplier->load('products'));
    }

    public function update(Request $request, Supplier $supplier): JsonResponse
    {
        $data = $request->validate([
            'name'    => 'sometimes|required|string|max:255',
            'email'   => 'nullable|email',
            'phone'   => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        $supplier->update($data);
        return response()->json($supplier);
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $supplier->delete();
        return response()->json(['message' => 'Supplier deleted']);
    }
}
