@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-logo"><img src="{{ asset('img/logo.png') }}" alt="Logo Posyandu"></div>
    <h1>Selamat Datang</h1>
    <p class="auth-sub">Pantau tumbuh kembang buah hati, dengan tenang dan mudah</p>

    <form method="POST" action="{{ route('login.proses') }}">
        @csrf
            @if (session('gagal'))
                <p class="auth-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('gagal') }}</p>
            @endif
        <label for="email">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda">

        <label for="password">Kata Sandi</label>
        <input type="password" id="password" name="password" placeholder="Masukkan kata sandi">

        <div class="lupa"><a href="{{ route('lupa-sandi') }}">Lupa kata sandi?</a></div>

        <button class="btn-masuk" type="submit">Masuk</button>
    </form>

    <p class="auth-foot">Belum punya akun? Hubungi kader Posyandu</p>
    <p class="auth-demo">Akun demo (kata sandi: password): kader@email.com atau wali1@email.com</p>
</div>
@endsection
