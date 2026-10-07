@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-logo">🌼</div>
    <h1>Selamat Datang</h1>
    <p class="auth-sub">Jaga tumbuh kembang si kecil dengan sistem Posyandu Sehat Ceria</p>

    <form method="POST" action="{{ route('login.proses') }}">
        @csrf
        <label for="email">Alamat Email</label>
        <input type="email" id="email" name="email" placeholder="Masukkan email Anda">

        <label for="password">Kata Sandi</label>
        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi">

        <div class="lupa"><a href="{{ route('lupa-sandi') }}">Lupa kata sandi?</a></div>

        <button class="btn-masuk" type="submit">Masuk</button>
    </form>

    <p class="auth-foot">Belum punya akun? Hubungi kader Posyandu</p>
    <p class="auth-demo">Sementara (belum ada autentikasi): email berisi "ortu" masuk sebagai Orang Tua, selain itu sebagai Kader.</p>
</div>
@endsection
