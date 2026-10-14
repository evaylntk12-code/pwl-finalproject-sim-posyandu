@extends('layouts.app')

@section('title', $pemeriksaan ? 'Ubah Pemeriksaan' : 'Tambah Pemeriksaan')

@section('content')
<h2 class="judul">{{ $pemeriksaan ? 'Ubah' : 'Tambah' }} Pemeriksaan</h2>

<div class="card form-card">
    <div class="fg"><label for="balita">Balita</label>
        <select id="balita">
            <option value="">Pilih...</option>
            @foreach ($daftarBalita as $b)
                <option value="{{ $b->id }}" @selected(($pemeriksaan?->toddler_id) === $b->id)>{{ $b->name }}</option>
            @endforeach
        </select></div>

    <div class="fg"><label for="tanggal">Tanggal pemeriksaan</label>
        <input type="date" id="tanggal" value="{{ $pemeriksaan?->examination_date->format('Y-m-d') }}"></div>
    <div class="fg"><label for="bb">Berat badan (kg)</label>
        <input type="number" step="0.1" id="bb" value="{{ $pemeriksaan?->weight }}"></div>
    <div class="fg"><label for="tb">Tinggi badan (cm)</label>
        <input type="number" step="0.1" id="tb" value="{{ $pemeriksaan?->height }}"></div>
    <div class="fg"><label for="lk">Lingkar kepala (cm)</label>
        <input type="number" step="0.1" id="lk" value="{{ $pemeriksaan?->head_circumference }}"></div>
    <div class="fg"><label for="ll">Lingkar lengan (cm)</label>
        <input type="number" step="0.1" id="ll" value="{{ $pemeriksaan?->arm_circumference }}"></div>

    <div class="fg"><label for="gizi">Status gizi</label>
        <select id="gizi">
            <option value="">Pilih...</option>
            @foreach (['Gizi Baik', 'Gizi Kurang', 'Gizi Lebih'] as $g)
                <option @selected(($pemeriksaan?->nutrition_status) === $g)>{{ $g }}</option>
            @endforeach
        </select></div>

    <div class="fg"><label for="catatan">Catatan</label>
        <textarea id="catatan" rows="3">{{ $pemeriksaan?->notes }}</textarea></div>

    <p class="hint"><i class="fa-solid fa-circle-info"></i> Tanggal hanya bisa di bulan berjalan. Data otomatis terkunci setelah pergantian bulan.</p>

    {{-- Tahap UI: Simpan masih link. Diganti form POST pada pertemuan CRUD. --}}
    <a class="btn" href="{{ route('kader.pemeriksaan.index') }}">Simpan</a>
    <a class="btn btn-outline" href="{{ route('kader.pemeriksaan.index') }}">Batal</a>
</div>
@endsection