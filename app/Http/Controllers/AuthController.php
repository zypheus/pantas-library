<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            $role = auth()->user()->role;
            return match ($role) {
                'admin', 'staff' => redirect()->route('book.index'),
                'developer' => redirect()->route('developer.dashboard'),
                'student', 'faculty' => redirect()->route('landing'),
                default => redirect()->route('login')->with('error', 'Unauthorized role.'),
            };
        }
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

            $user = Auth::user();

            return match ($user->role) {
                'admin', 'staff' => redirect()->intended(route('book.index')),
                'developer' => redirect()->intended(route('developer.dashboard')),
                'student', 'faculty' => redirect()->intended(route('landing')),
                default => redirect()->route('login')->with('error', 'Unauthorized role.'),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $home = route('home');

        if ($request->header('X-Inertia')) {
            return Inertia::location($home);
        }

        return redirect()->to($home);
    }
}
