@extends('layouts.app')

@section('title', 'Ganti Kata Sandi')

@section('content')
@php
    $area   = auth()->user()->areaPrefix(); // kader, bidan, atau ortu
    $aksi   = route($area . '.sandi.perbarui');
    $batal  = route($area . '.dashboard');

    // nama field => [label, atribut autocomplete]
    $kolom = [
        'password_lama'         => ['Kata sandi lama', 'current-password'],
        'password'              => ['Kata sandi baru', 'new-password'],
        'password_confirmation' => ['Konfirmasi kata sandi baru', 'new-password'],
    ];
@endphp

<h2 class="judul">Ganti Kata Sandi</h2>

@if ($wajib)
    <div class="alert alert-error">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Anda masih memakai kata sandi sementara. Ganti kata sandi dulu sebelum melanjutkan.
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        Kata sandi belum bisa diganti. Periksa isian yang bertanda merah.
    </div>
@endif

<form method="POST" action="{{ $aksi }}" class="card form-card">
    @csrf
    @method('PUT')

    @foreach ($kolom as $nama => [$label, $auto])
        <div class="fg">
            <label for="{{ $nama }}">{{ $label }}</label>
            @include('partials.input-sandi', ['nama' => $nama, 'auto' => $auto])
            @error($nama) <div class="pesan-error">{{ $message }}</div> @enderror
        </div>
    @endforeach

    <p class="hint"><i class="fa-solid fa-circle-info"></i> Minimal 8 karakter dan harus berbeda dari kata sandi lama.</p>

    <button class="btn" type="submit">Simpan</button>

    {{-- Selama kewajiban ganti sandi belum selesai, tombol Batal disembunyikan --}}
    @unless ($wajib)
        <a class="btn btn-outline" href="{{ $batal }}">Batal</a>
    @endunless
</form>
@endsection