<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request - verifies credentials against database
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Authenticate against database
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Role-based redirects
            $user = Auth::user();
            $redirectRoute = match($user->role) {
                'admin' => 'admin.reports',
                'faculty' => 'faculty.explorer',
                'student' => 'research.index',
                default => 'dashboard',
            };
            
            return redirect()->intended(route($redirectRoute))
                ->with('success', 'Login successful! Credentials verified from database.');
        }

        return back()->withErrors(['email' => 'Invalid credentials. Please check your email and password.'])
            ->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration - saves all credentials to database
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,faculty,admin',
            'department' => 'nullable|string|max:255',
            'student_id' => 'nullable|string|max:255',
        ]);

        // Create user with all credentials saved to database
        $user = \App\Models\User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'full_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']), // Encrypted password saved to DB
            'role' => $validated['role'],
            'department' => $validated['department'] ?? 'Not specified',
            'student_id' => $validated['student_id'] ?? null,
            'email_verified_at' => now(), // Auto-verify on registration
        ]);

        // Auto-login and redirect
        Auth::login($user);
        return redirect()->route('dashboard')
            ->with('success', 'Registration successful! Your credentials have been saved and you are now logged in.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
