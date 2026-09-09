<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get all users (Admin only)
     */
    public function index(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'is_active', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    /**
     * Get user details (Admin only - view other users)
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user->only(['id', 'name', 'email', 'role', 'is_active', 'created_at']));
    }

    /**
     * Update user role (Admin only)
     */
    public function updateRole(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,staff',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'User role updated successfully',
            'user' => $user->only(['id', 'name', 'email', 'role']),
        ]);
    }

    /**
     * Deactivate user (Admin only)
     */
    public function deactivate(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot deactivate your own account'], 422);
        }

        $user->update(['is_active' => false]);

        return response()->json(['message' => 'User deactivated successfully']);
    }

    /**
     * Activate user (Admin only)
     */
    public function activate(User $user): JsonResponse
    {
        $user->update(['is_active' => true]);

        return response()->json(['message' => 'User activated successfully']);
    }
}
