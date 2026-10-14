<?php

namespace App\Http\Controllers;

use App\Models\Toddler;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        // with('user') mengambil data wali sekaligus (menghindari query berulang per baris)
        $balita = Toddler::with('user')
            ->when($q, fn ($query) => $query->where('name', 'like', '%' . $q . '%'))
            ->orderBy('name')
            ->get();

        return view('kader.balita.index', compact('balita'));
    }

    public function create()
    {
        return view('kader.balita.form', ['balita' => null]);
    }

    public function edit(int $id)
    {
        // findOrFail: otomatis halaman 404 kalau id tidak ada
        $balita = Toddler::with('user')->findOrFail($id);

        return view('kader.balita.form', compact('balita'));
    }
}