@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="auth-card">
    <div class="auth-logo bulat"><i class="fa-solid fa-key"></i></div>
    <h1>Lupa Kata Sandi</h1>
    <p class="auth-sub">
        Hubungi kader Posyandu untuk mereset kata sandimu. Kader akan memberikan kata sandi sementara,
        dan kamu diminta menggantinya saat masuk.
    </p>

    <a class="btn-masuk" href="{{ route('login') }}">Kembali ke login</a>
</div>
@endsection