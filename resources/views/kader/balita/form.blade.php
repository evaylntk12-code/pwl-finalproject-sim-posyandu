@extends('layouts.app')

@section('title', $balita ? 'Ubah Balita' : 'Tambah Balita')

@section('content')
@php $ubah = ! is_null($balita); @endphp

<h2 class="judul">{{ $ubah ? 'Ubah Data Balita & Wali' : 'Tambah Balita & Akun Orang Tua' }}</h2>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        Data belum bisa disimpan. Periksa isian yang bertanda merah.
    </div>
@endif

<form method="POST" action="{{ $ubah ? route('kader.balita.update', $balita) : route('kader.balita.store') }}">
    @csrf
    @if ($ubah)
        @method('PUT')
    @endif

    <div class="g2">
        <fieldset>
            <legend><i class="fa-solid fa-baby"></i> Data Balita</legend>

            <div class="fg">
                <label for="name">Nama balita</label>
                <input type="text" id="name" name="name" value="{{ old('name', $balita?->name) }}"
                       class="@error('name') is-invalid @enderror">
                @error('name') <div class="pesan-error">{{ $message }}</div> @enderror
            </div>

            <div class="fg">
                <label for="birth_date">Tanggal lahir</label>
                <input type="date" id="birth_date" name="birth_date"
                       value="{{ old('birth_date', $balita?->birth_date->format('Y-m-d')) }}"
                       min="{{ now()->subYears(5)->format('Y-m-d') }}"
                       max="{{ now()->format('Y-m-d') }}"
                       class="@error('birth_date') is-invalid @enderror">
                @error('birth_date') <div class="pesan-error">{{ $message }}</div> @enderror
            </div>

            <div class="fg">
                <label for="gender">Jenis kelamin</label>
                <select id="gender" name="gender" class="@error('gender') is-invalid @enderror">
                    <option value="">Pilih...</option>
                    <option value="L" @selected(old('gender', $balita?->gender) === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('gender', $balita?->gender) === 'P')>Perempuan</option>
                </select>
                @error('gender') <div class="pesan-error">{{ $message }}</div> @enderror
            </div>
        </fieldset>

        <fieldset>
            <legend><i class="fa-solid fa-user-group"></i> Data Wali</legend>

            <div class="fg">
                <label for="guardian_name">Nama wali</label>
                <input type="text" id="guardian_name" name="guardian_name"
                       value="{{ old('guardian_name', $balita?->user->name) }}"
                       class="@error('guardian_name') is-invalid @enderror">
                @error('guardian_name') <div class="pesan-error">{{ $message }}</div> @enderror
            </div>

            <div class="fg">
                <label for="phone">No. HP</label>
                <input type="tel" id="phone" name="phone" placeholder="08xx-xxxx-xxxx"
                       value="{{ old('phone', $balita?->user->phone) }}"
                       class="@error('phone') is-invalid @enderror">
                @error('phone') <div class="pesan-error">{{ $message }}</div> @enderror
            </div>

            <div class="fg">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" rows="2"
                          class="@error('address') is-invalid @enderror">{{ old('address', $balita?->user->address) }}</textarea>
                @error('address') <div class="pesan-error">{{ $message }}</div> @enderror
            </div>
        </fieldset>
    </div>

    <fieldset>
        <legend><i class="fa-solid fa-key"></i> Akun Orang Tua</legend>
        <div class="g2">
            <div class="fg">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $balita?->user->email) }}"
                       class="@error('email') is-invalid @enderror">
                @error('email') <div class="pesan-error">{{ $message }}</div> @enderror
            </div>

            {{-- Kata sandi awal hanya ada di form tambah --}}
            @unless ($ubah)
                <div class="fg">
                    <label for="password">Kata sandi awal</label>
                    @include('partials.input-sandi', ['nama' => 'password', 'placeholder' => 'Minimal 8 karakter', 'auto' => 'new-password'])
                    @error('password') <div class="pesan-error">{{ $message }}</div> @enderror
                </div>
            @endunless
        </div>

        @if ($ubah)
            <p class="hint"><i class="fa-solid fa-circle-info"></i> Kata sandi tidak diubah di sini. Untuk mereset, pakai tombol <b><i class="fa-solid fa-key"></i> Reset Sandi</b> di daftar balita.</p>
        @else
            <p class="hint"><i class="fa-solid fa-circle-info"></i> Orang tua akan diminta mengganti kata sandi awal saat masuk pertama.</p>
        @endif
    </fieldset>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-outline" href="{{ route('kader.balita.index') }}">Batal</a>
</form>
@endsection