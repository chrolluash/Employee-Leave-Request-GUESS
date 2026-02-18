<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Check user role and redirect accordingly
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }
            
            if ($user->role === 'manager') {
                // Managers default to admin dashboard (they can access employee dashboard too)
                return redirect()->intended(route('admin.dashboard'));
            }
            
            if ($user->role === 'hr') {
                // HR default to admin dashboard (they can access employee dashboard too)
                return redirect()->intended(route('admin.dashboard'));
            }

            // Regular employees
            return redirect()->intended(route('employee.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}