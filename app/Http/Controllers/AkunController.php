<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    // Dipakai bersama oleh Kader dan Orang Tua (layout menyesuaikan dari nama route)
    public function sandi(Request $request)
    {
        return view('akun.sandi', [
            'wajib' => $request->user()->must_change_password,
        ]);
    }

    public function perbarui(Request $request)
    {
        $request->validate([
            // current_password: mencocokkan dengan kata sandi akun yang sedang login
            'password_lama' => ['required', 'current_password'],
            // confirmed: harus sama dengan kolom password_confirmation
            'password'      => ['required', 'string', 'min:8', 'confirmed', 'different:password_lama'],
        ], [
            'required'         => ':attribute wajib diisi.',
            'string'           => ':attribute harus berupa teks.',
            'min'              => ':attribute minimal :min karakter.',
            'current_password' => 'Kata sandi lama salah.',
            'confirmed'        => 'Konfirmasi kata sandi tidak cocok.',
            'different'        => 'Kata sandi baru harus berbeda dari kata sandi lama.',
        ], [
            'password_lama' => 'Kata sandi lama',
            'password'      => 'Kata sandi baru',
        ]);

        $request->user()->update([
            'password'             => Hash::make($request->input('password')),
            'must_change_password' => false, // kewajiban ganti sandi selesai
        ]);

        $tujuan = $request->user()->dashboardRoute();

        return redirect()
            ->route($tujuan)
            ->with('success', 'Kata sandi berhasil diganti.');
    }
}