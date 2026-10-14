<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        // Kalau sudah login, langsung ke dashboard sesuai perannya
        if (Auth::check()) {
            return $this->dashboardUntuk(Auth::user());
        }

        return view('auth.login');
    }

    public function lupa()
    {
        return view('auth.lupa');
    }

    public function proses(Request $request)
    {
        $kredensial = $request->only('email', 'password');

        // Auth::attempt mencocokkan email dan kata sandi dengan tabel users
        if (! Auth::attempt($kredensial)) {
            return back()
                ->withInput($request->only('email'))
                ->with('gagal', 'Email atau kata sandi salah.');
        }

        $request->session()->regenerate();

        return $this->dashboardUntuk(Auth::user());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function dashboardUntuk($user)
    {
        return $user->role === 'kader'
            ? redirect()->route('kader.dashboard')
            : redirect()->route('ortu.dashboard');
    }
}