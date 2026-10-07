<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function lupa()
    {
        return view('auth.lupa');
    }

    // SEMENTARA (belum ada autentikasi): arahkan berdasarkan isi email.
    // Akan diganti dengan Auth + role pada pertemuan authentication.
    public function proses(Request $request)
    {
        $email = strtolower((string) $request->input('email'));

        return str_contains($email, 'ortu')
            ? redirect()->route('ortu.dashboard')
            : redirect()->route('kader.dashboard');
    }
}
