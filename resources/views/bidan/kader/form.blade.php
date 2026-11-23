@extends('layouts.app')

@section('title', $kader ? 'Ubah Kader' : 'Tambah Kader')

@section('content')
@php $ubah = ! is_null($kader); @endphp

<h2 class="judul">{{ $ubah ? 'Ubah Data Kader' : 'Tambah Akun Kader' }}</h2>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        Data belum bisa disimpan. Periksa isian yang bertanda merah.
    </div>
@endif

<form method="POST" action="{{ $ubah ? route('bidan.kader.update', $kader) : route('bidan.kader.store') }}" class="card form-card">
    @csrf
    @if ($ubah)
        @method('PUT')
    @endif

    <div class="fg">
        <label for="name">Nama kader</label>
        <input type="text" id="name" name="name" value="{{ old('name', $kader?->name) }}"
               class="@error('name') is-invalid @enderror">
        @error('name') <div class="pesan-error">{{ $message }}</div> @enderror
    </div>

    <div class="fg">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $kader?->email) }}"
               class="@error('email') is-invalid @enderror">
        @error('email') <div class="pesan-error">{{ $message }}</div> @enderror
    </div>

    <div class="fg">
        <label for="phone">No. HP</label>
        <input type="tel" id="phone" name="phone" placeholder="08xx-xxxx-xxxx"
               value="{{ old('phone', $kader?->phone) }}"
               class="@error('phone') is-invalid @enderror">
        @error('phone') <div class="pesan-error">{{ $message }}</div> @enderror
    </div>

    @unless ($ubah)
        <div class="fg">
            <label for="password">Kata sandi awal</label>
            @include('partials.input-sandi', ['nama' => 'password', 'placeholder' => 'Minimal 8 karakter', 'auto' => 'new-password'])
            @error('password') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>
        <p class="hint"><i class="fa-solid fa-circle-info"></i> Kader akan diminta mengganti kata sandi awal saat masuk pertama.</p>
    @else
        <p class="hint"><i class="fa-solid fa-circle-info"></i> Kata sandi tidak diubah di sini. Untuk mereset, pakai tombol <b>Reset Sandi</b> di daftar kader.</p>
    @endunless

    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-outline" href="{{ route('bidan.kader.index') }}">Batal</a>
</form>
@endsection