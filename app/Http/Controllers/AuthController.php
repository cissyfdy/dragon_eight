<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Attempt login with username or email
        $credentials = $request->only('username', 'password');
        
        // Try to login with username first
        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        if (Auth::attempt([$loginField => $request->username, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            
            // Role-based redirect
            $user = Auth::user();
            
            if ($user->role === 'murid') {
                return redirect()->route('murid.dashboard')->with('success', 'Login berhasil! Selamat datang murid.');
            } elseif ($user->role === 'pelatih') {
                return redirect()->route('pelatih.dashboard')->with('success', 'Login berhasil! Selamat datang pelatih.');
            } else {
                // Default to admin dashboard
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang admin.');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,murid,pelatih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'murid', // Default role is murid
        ]);

        Auth::login($user);

        // Role-based redirect after registration
        if ($user->role === 'murid') {
            return redirect()->route('murid.dashboard')->with('success', 'Registrasi berhasil! Selamat datang murid.');
        } elseif ($user->role === 'pelatih') {
            return redirect()->route('pelatih.dashboard')->with('success', 'Registrasi berhasil! Selamat datang pelatih.');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Registrasi berhasil! Selamat datang admin.');
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }

    /**
     * Show login page (duplicate method - keeping for compatibility)
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Murid Dashboard
     */
    public function muridDashboard()
    {
        $user = Auth::user();
        
        // Pastikan user memiliki data murid
        $murid = $user->murid;
        
        if (!$murid) {
            // Jika user belum memiliki data murid, redirect atau buat data baru
            return redirect()->back()->with('error', 'Data murid tidak ditemukan');
        }
        
        return view('murid.dashboard', compact('murid'));
    }

    /**
     * Pelatih Dashboard
     */
    public function pelatihDashboard()
    {
        $user = Auth::user();
        $pelatih = $user->pelatih; // Get pelatih data through relationship
        
        return view('pelatih.dashboard', compact('pelatih'));
    }
}