<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get current authenticated admin data
     */
    public function getCurrentAdmin(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Get admin data from database
            $admin = Admin::find($user->id);
            
            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $admin->id,
                    'nama' => $admin->nama,
                    'email' => $admin->email,
                    'no_wa' => $admin->no_wa,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Get Current Admin Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get admin data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout admin - revoke all tokens
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            
            if ($user) {
                // Revoke all tokens
                $user->tokens()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Admin Logout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
