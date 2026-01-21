<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        // TODO: Implement OTP sending via SMS
        // For now, generate a simple OTP (in production, use proper OTP service)
        $otp = rand(100000, 999999);
        
        // Store OTP in session (in production, store in cache/redis with expiration)
        session([
            'otp' => $otp,
            'otp_phone' => $request->phone,
            'otp_expires' => now()->addMinutes(5)
        ]);

        // In production, send OTP via SMS
        // For development, return OTP in response
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // Remove this in production
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        // Verify OTP
        $sessionOtp = session('otp');
        $sessionPhone = session('otp_phone');
        $otpExpires = session('otp_expires');

        if (!$sessionOtp || !$sessionPhone || !$otpExpires) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kadaluarsa'
            ], 400);
        }

        if (now()->greaterThan($otpExpires)) {
            session()->forget(['otp', 'otp_phone', 'otp_expires']);
            return response()->json([
                'success' => false,
                'message' => 'OTP sudah kadaluarsa'
            ], 400);
        }

        if ($request->otp != $sessionOtp || $request->phone != $sessionPhone) {
            return response()->json([
                'success' => false,
                'message' => 'OTP salah'
            ], 400);
        }

        // Find or create admin user
        $user = User::where('phone', $request->phone)
            ->where('role', 'admin')
            ->first();

        if (!$user) {
            // Create admin user if doesn't exist
            $user = User::create([
                'name' => 'Admin',
                'phone' => $request->phone,
                'email' => 'admin@kuotaumroh.id',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password') // Default password
            ]);
        }

        // Login user
        Auth::login($user);

        // Clear OTP session
        session()->forget(['otp', 'otp_phone', 'otp_expires']);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
