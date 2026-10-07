@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="auth-card">
    <div class="auth-logo">🔑</div>
    <h1>Lupa Kata Sandi</h1>
    <p class="auth-sub">Masukkan email akunmu, nanti kami kirim tautan untuk membuat kata sandi baru</p>

    {{-- Tahap UI: tombol masih berupa link. Diganti form POST pada pertemuan authentication. --}}
    <label for="email">Alamat Email</label>
    <input type="email" id="email" placeholder="Masukkan email Anda">

    <a class="btn-masuk" href="{{ route('login') }}">Kirim Tautan Reset</a>
    <p class="auth-foot"><a href="{{ route('login') }}">← Kembali ke login</a></p>
    <p class="auth-demo">Tidak bisa buka email? Minta kader Posyandu mereset kata sandimu.</p>
</div>
@endsection
