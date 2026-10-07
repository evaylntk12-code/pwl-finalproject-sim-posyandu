@extends('layouts.app')

@section('title', $balita ? 'Ubah Balita' : 'Tambah Balita')

@section('content')
@php $ubah = ! is_null($balita); @endphp

<h2 class="judul">{{ $ubah ? 'Ubah Data Balita & Wali' : 'Tambah Balita & Akun Orang Tua' }}</h2>

<div class="g2">
    <fieldset>
        <legend>👶 Data Balita</legend>
        <div class="fg"><label for="nama">Nama balita</label>
            <input type="text" id="nama" value="{{ $balita['nama'] ?? '' }}"></div>
        <div class="fg"><label for="lahir">Tanggal lahir</label>
            <input type="date" id="lahir" value="{{ $balita['tgl_lahir'] ?? '' }}"></div>
        <div class="fg"><label for="jk">Jenis kelamin</label>
            <select id="jk">
                <option value="">Pilih...</option>
                <option value="L" @selected(($balita['jk'] ?? '') === 'L')>Laki-laki</option>
                <option value="P" @selected(($balita['jk'] ?? '') === 'P')>Perempuan</option>
            </select></div>
    </fieldset>

    <fieldset>
        <legend>👪 Data Wali</legend>
        <div class="fg"><label for="wali">Nama wali</label>
            <input type="text" id="wali" value="{{ $balita['wali'] ?? '' }}"></div>
        <div class="fg"><label for="hp">No. HP / alamat</label>
            <input type="text" id="hp" value="{{ $balita['hp'] ?? '' }}"></div>
    </fieldset>
</div>

<fieldset>
    <legend>🔑 Akun Orang Tua</legend>
    <div class="g2">
        <div class="fg"><label for="email">Email</label>
            <input type="email" id="email" value="{{ $balita['email'] ?? '' }}"></div>

        {{-- Kata sandi awal hanya ada di form tambah --}}
        @unless ($ubah)
            <div class="fg"><label for="sandi">Kata sandi awal</label>
                <input type="password" id="sandi" placeholder="Dibuat oleh kader"></div>
        @endunless
    </div>

    @if ($ubah)
        <p class="hint">ℹ️ Kata sandi tidak diubah di sini. Untuk mereset, pakai tombol <b>🔑 Reset Sandi</b> di daftar balita.</p>
    @else
        <p class="hint">ℹ️ Orang tua akan diminta mengganti kata sandi awal saat masuk pertama.</p>
    @endif
</fieldset>

{{-- Tahap UI: Simpan masih link. Diganti form POST pada pertemuan CRUD. --}}
<a class="btn" href="{{ route('kader.balita.index') }}">Simpan</a>
<a class="btn btn-outline" href="{{ route('kader.balita.index') }}">Batal</a>
@endsection
