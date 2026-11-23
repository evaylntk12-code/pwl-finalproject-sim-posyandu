@extends('layouts.auth')

@section('title', '403 Akses Ditolak')

@section('content')
<div class="auth-card">
    <div class="auth-logo bulat"><i class="fa-solid fa-ban"></i></div>
    <h1>403</h1>
    <p class="auth-sub">Akses ditolak</p>
    <p class="auth-sub">{{ $exception->getMessage() ?: 'Anda tidak berhak membuka halaman ini.' }}</p>

    <p class="auth-foot">
        @auth
            <a href="{{ route(auth()->user()->dashboardRoute()) }}">Kembali ke dashboard</a>
        @else
            <a href="{{ route('login') }}">Ke halaman login</a>
        @endauth
    </p>
</div>
@endsection