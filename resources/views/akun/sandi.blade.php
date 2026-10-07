@extends('layouts.app')

@section('title', 'Ganti Kata Sandi')

@section('content')
@php $kembali = request()->routeIs('ortu.*') ? route('ortu.dashboard') : route('kader.dashboard'); @endphp

<h2 class="judul">Ganti Kata Sandi</h2>

<div class="card form-card">
    <div class="fg"><label for="lama">Kata sandi lama</label><input type="password" id="lama"></div>
    <div class="fg"><label for="baru">Kata sandi baru</label><input type="password" id="baru"></div>
    <div class="fg"><label for="konf">Konfirmasi kata sandi baru</label><input type="password" id="konf"></div>

    {{-- Tahap UI: Simpan masih link. Diganti form POST pada pertemuan CRUD/authentication. --}}
    <a class="btn" href="{{ $kembali }}">Simpan</a>
    <a class="btn btn-outline" href="{{ $kembali }}">Batal</a>
</div>
@endsection
