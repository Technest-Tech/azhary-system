<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Teacher;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:admin,teacher',
        ]);

        // Try admin authentication first
        if ($credentials['user_type'] === 'admin') {
            if (Auth::guard('admin')->attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ], $request->boolean('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            }
        }
        
        // Try teacher authentication
        if ($credentials['user_type'] === 'teacher') {
            if (Auth::guard('teacher')->attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ], $request->boolean('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('/teacher/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Logout from both guards
        Auth::guard('admin')->logout();
        Auth::guard('teacher')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
