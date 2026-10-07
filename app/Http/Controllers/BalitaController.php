<?php

namespace App\Http\Controllers;

use App\Support\DummyData;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $balita = DummyData::balita();

        if ($q) {
            $balita = array_filter($balita, fn ($b) => stripos($b['nama'], $q) !== false);
        }

        return view('kader.balita.index', compact('balita'));
    }

    public function create()
    {
        return view('kader.balita.form', ['balita' => null]);
    }

    public function edit(int $id)
    {
        $balita = DummyData::cari(DummyData::balita(), $id);
        abort_if(is_null($balita), 404);

        return view('kader.balita.form', compact('balita'));
    }
}
