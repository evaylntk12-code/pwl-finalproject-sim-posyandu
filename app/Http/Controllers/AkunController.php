<?php

namespace App\Http\Controllers;

class AkunController extends Controller
{
    // Dipakai bersama oleh Kader dan Orang Tua (layout menyesuaikan dari nama route).
    public function sandi()
    {
        return view('akun.sandi');
    }
}
