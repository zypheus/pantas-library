<?php

namespace App\Http\Controllers;

use App\Support\AuthRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            $role = auth()->user()->role;

            if (! in_array($role, ['admin', 'staff', 'developer', 'student', 'faculty'], true)) {
                return redirect()->route('login')->with('error', 'Unauthorized role.');
            }

            return redirect()->to(AuthRedirect::defaultUrl($role));
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

            if (! in_array($user->role, ['admin', 'staff', 'developer', 'student', 'faculty'], true)) {
                Auth::logout();

                return redirect()->route('login')->with('error', 'Unauthorized role.');
            }

            return AuthRedirect::afterLogin($user, $request);
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
