<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Log login activity
            $user = Auth::user();
            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => 'Login ke sistem',
                'lokasi' => $user->penempatan ?? '-',
            ]);

            return match ($user->role) {
                'admin'   => redirect()->intended(route('admin.dashboard')),
                'petugas' => redirect()->intended(route('petugas.dashboard')),
                'owner'   => redirect()->intended(route('owner.dashboard')),
                default   => redirect()->intended('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log logout activity
        if ($user) {
            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => 'Logout dari sistem',
                'lokasi' => $user->penempatan ?? '-',
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}