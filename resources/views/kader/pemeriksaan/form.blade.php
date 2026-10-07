@extends('layouts.app')

@section('title', $pemeriksaan ? 'Ubah Pemeriksaan' : 'Tambah Pemeriksaan')

@section('content')
<h2 class="judul">{{ $pemeriksaan ? 'Ubah' : 'Tambah' }} Pemeriksaan</h2>

<div class="card form-card">
    <div class="fg"><label for="balita">Balita</label>
        <select id="balita">
            <option value="">Pilih...</option>
            @foreach ($daftarBalita as $b)
                <option value="{{ $b['id'] }}" @selected(($pemeriksaan['balita'] ?? '') === $b['nama'])>{{ $b['nama'] }}</option>
            @endforeach
        </select></div>

    <div class="fg"><label for="tanggal">Tanggal pemeriksaan</label>
        <input type="date" id="tanggal" value="{{ $pemeriksaan['tanggal'] ?? '' }}"></div>
    <div class="fg"><label for="bb">Berat badan (kg)</label>
        <input type="number" step="0.1" id="bb" value="{{ $pemeriksaan['bb'] ?? '' }}"></div>
    <div class="fg"><label for="tb">Tinggi badan (cm)</label>
        <input type="number" step="0.1" id="tb" value="{{ $pemeriksaan['tb'] ?? '' }}"></div>
    <div class="fg"><label for="lk">Lingkar kepala (cm)</label>
        <input type="number" step="0.1" id="lk" value="{{ $pemeriksaan['lk'] ?? '' }}"></div>
    <div class="fg"><label for="ll">Lingkar lengan (cm)</label>
        <input type="number" step="0.1" id="ll" value="{{ $pemeriksaan['ll'] ?? '' }}"></div>

    <div class="fg"><label for="gizi">Status gizi</label>
        <select id="gizi">
            <option value="">Pilih...</option>
            @foreach (['Gizi Baik', 'Gizi Kurang', 'Gizi Lebih'] as $g)
                <option @selected(($pemeriksaan['status_gizi'] ?? '') === $g)>{{ $g }}</option>
            @endforeach
        </select></div>

    <div class="fg"><label for="catatan">Catatan</label>
        <textarea id="catatan" rows="3">{{ ($pemeriksaan['catatan'] ?? '') === '-' ? '' : ($pemeriksaan['catatan'] ?? '') }}</textarea></div>

    <p class="hint">ℹ️ Tanggal hanya bisa di bulan berjalan. Data otomatis terkunci setelah pergantian bulan.</p>

    {{-- Tahap UI: Simpan masih link. Diganti form POST pada pertemuan CRUD. --}}
    <a class="btn" href="{{ route('kader.pemeriksaan.index') }}">Simpan</a>
    <a class="btn btn-outline" href="{{ route('kader.pemeriksaan.index') }}">Batal</a>
</div>
@endsection
