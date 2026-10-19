@extends('layouts.app')

@section('title', $pemeriksaan ? 'Ubah Pemeriksaan' : 'Tambah Pemeriksaan')

@section('content')
@php
    $ubah = ! is_null($pemeriksaan);

    // Kolom angka: nama field => [label, step]
    $angka = [
        'weight'             => ['Berat badan (kg)', '0.01'],
        'height'             => ['Tinggi badan (cm)', '0.1'],
        'head_circumference' => ['Lingkar kepala (cm)', '0.1'],
        'arm_circumference'  => ['Lingkar lengan (cm)', '0.1'],
    ];
@endphp

<h2 class="judul">{{ $ubah ? 'Ubah' : 'Tambah' }} Pemeriksaan</h2>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        Data belum bisa disimpan. Periksa isian yang bertanda merah.
    </div>
@endif

<form method="POST"
      action="{{ $ubah ? route('kader.pemeriksaan.update', $pemeriksaan) : route('kader.pemeriksaan.store') }}"
      class="card form-card">
    @csrf
    @if ($ubah)
        @method('PUT')
    @endif

    <div class="fg">
        <label for="toddler_id">Balita</label>
        <select id="toddler_id" name="toddler_id" class="@error('toddler_id') is-invalid @enderror">
            <option value="">Pilih...</option>
            @foreach ($daftarBalita as $b)
                <option value="{{ $b->id }}"
                    @selected((string) old('toddler_id', $pemeriksaan?->toddler_id) === (string) $b->id)>
                    {{ $b->name }}
                </option>
            @endforeach
        </select>
        @error('toddler_id') <div class="pesan-error">{{ $message }}</div> @enderror
    </div>

    <div class="fg">
        <label for="examination_date">Tanggal pemeriksaan</label>
        <input type="date" id="examination_date" name="examination_date"
               value="{{ old('examination_date', $pemeriksaan?->examination_date->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
               min="{{ now()->startOfMonth()->format('Y-m-d') }}"
               max="{{ now()->format('Y-m-d') }}"
               class="@error('examination_date') is-invalid @enderror">
        @error('examination_date') <div class="pesan-error">{{ $message }}</div> @enderror
    </div>

    @foreach ($angka as $nama => [$label, $step])
        <div class="fg">
            <label for="{{ $nama }}">{{ $label }}</label>
            <input type="number" step="{{ $step }}" id="{{ $nama }}" name="{{ $nama }}"
                   value="{{ old($nama, $pemeriksaan?->$nama) }}"
                   class="@error($nama) is-invalid @enderror">
            @error($nama) <div class="pesan-error">{{ $message }}</div> @enderror
        </div>
    @endforeach

    <div class="fg">
        <label for="nutrition_status">Status gizi</label>
        <select id="nutrition_status" name="nutrition_status" class="@error('nutrition_status') is-invalid @enderror">
            <option value="">Pilih...</option>
            @foreach (['Gizi Baik', 'Gizi Kurang', 'Gizi Lebih'] as $g)
                <option value="{{ $g }}" @selected(old('nutrition_status', $pemeriksaan?->nutrition_status) === $g)>{{ $g }}</option>
            @endforeach
        </select>
        @error('nutrition_status') <div class="pesan-error">{{ $message }}</div> @enderror
    </div>

    <div class="fg">
        <label for="notes">Catatan (opsional)</label>
        <textarea id="notes" name="notes" rows="3" class="@error('notes') is-invalid @enderror">{{ old('notes', $pemeriksaan?->notes) }}</textarea>
        @error('notes') <div class="pesan-error">{{ $message }}</div> @enderror
    </div>

    <p class="hint"><i class="fa-solid fa-circle-info"></i> Tanggal hanya bisa di bulan berjalan. Data otomatis terkunci setelah pergantian bulan.</p>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-outline" href="{{ route('kader.pemeriksaan.index') }}">Batal</a>
</form>
@endsection