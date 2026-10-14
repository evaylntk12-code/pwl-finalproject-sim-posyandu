@extends('layouts.app')

@section('title', $jadwal ? 'Ubah Jadwal' : 'Tambah Jadwal')

@section('content')
<h2 class="judul">{{ $jadwal ? 'Ubah' : 'Tambah' }} Jadwal Kegiatan</h2>

<fieldset class="form-card">
    <legend><i class="fa-solid fa-calendar-days"></i> Jadwal Kegiatan</legend>
    <div class="fg"><label for="tanggal">Tanggal</label>
        <input type="date" id="tanggal" value="{{ $jadwal?->activity_date->format('Y-m-d') }}"></div>
    <div class="fg"><label for="mulai">Jam mulai</label>
        <input type="time" id="mulai" value="{{ $jadwal ? substr($jadwal->start_time, 0, 5) : '' }}"></div>
    <div class="fg"><label for="selesai">Jam selesai (opsional)</label>
        <input type="time" id="selesai" value="{{ $jadwal?->end_time ? substr($jadwal->end_time, 0, 5) : '' }}"></div>
    <div class="fg"><label for="ket">Keterangan</label>
        <input type="text" id="ket" value="{{ $jadwal?->description }}"></div>
</fieldset>

{{-- Tahap UI: Simpan masih link. Diganti form POST pada pertemuan CRUD. --}}
<a class="btn" href="{{ route('kader.jadwal.index') }}">Simpan</a>
<a class="btn btn-outline" href="{{ route('kader.jadwal.index') }}">Batal</a>
@endsection