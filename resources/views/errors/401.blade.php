@extends('layouts.auth')

@section('title', '401 Belum Login')

@section('content')
<div class="auth-card">
    <div class="auth-logo bulat"><i class="fa-solid fa-lock"></i></div>
    <h1>401</h1>
    <p class="auth-sub">Anda belum login</p>
    <p class="auth-foot"><a href="{{ route('login') }}">Ke halaman login</a></p>
</div>
@endsection