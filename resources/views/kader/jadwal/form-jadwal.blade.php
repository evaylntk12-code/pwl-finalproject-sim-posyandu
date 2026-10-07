@extends('layouts.app')

@section('title', $jadwal ? 'Ubah Jadwal' : 'Tambah Jadwal')

@section('content')
<h2 class="judul">{{ $jadwal ? 'Ubah' : 'Tambah' }} Jadwal Kegiatan</h2>

<fieldset class="form-card">
    <legend>📅 Jadwal Kegiatan</legend>
    <div class="fg"><label for="tanggal">Tanggal</label>
        <input type="date" id="tanggal" value="{{ $jadwal['tanggal'] ?? '' }}"></div>
    <div class="fg"><label for="waktu">Waktu</label>
        <input type="text" id="waktu" placeholder="09.00 - 12.00" value="{{ $jadwal['waktu'] ?? '' }}"></div>
    <div class="fg"><label for="ket">Keterangan</label>
        <input type="text" id="ket" value="{{ $jadwal['keterangan'] ?? '' }}"></div>
</fieldset>

{{-- Tahap UI: Simpan masih link. Diganti form POST pada pertemuan CRUD. --}}
<a class="btn" href="{{ route('kader.jadwal.index') }}">Simpan</a>
<a class="btn btn-outline" href="{{ route('kader.jadwal.index') }}">Batal</a>
@endsection
