@extends('layouts.app')

@section('title', $jadwal ? 'Ubah Jadwal' : 'Tambah Jadwal')

@section('content')
@php $ubah = ! is_null($jadwal); @endphp

<h2 class="judul">{{ $ubah ? 'Ubah' : 'Tambah' }} Jadwal Kegiatan</h2>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        Data belum bisa disimpan. Periksa isian yang bertanda merah.
    </div>
@endif

<form method="POST" action="{{ $ubah ? route('kader.jadwal.update', $jadwal) : route('kader.jadwal.store') }}">
    @csrf
    @if ($ubah)
        @method('PUT')
    @endif

    <fieldset class="form-card">
        <legend><i class="fa-solid fa-calendar-days"></i> Jadwal Kegiatan</legend>

        <div class="fg">
            <label for="activity_date">Tanggal</label>
            <input type="date" id="activity_date" name="activity_date"
                   value="{{ old('activity_date', $jadwal?->activity_date->format('Y-m-d')) }}"
                   class="@error('activity_date') is-invalid @enderror">
            @error('activity_date') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
            <label for="start_time">Jam mulai</label>
            <input type="time" id="start_time" name="start_time"
                   value="{{ old('start_time', $ubah ? substr($jadwal->start_time, 0, 5) : '') }}"
                   class="@error('start_time') is-invalid @enderror">
            @error('start_time') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
            <label for="end_time">Jam selesai (opsional)</label>
            <input type="time" id="end_time" name="end_time"
                   value="{{ old('end_time', $ubah && $jadwal->end_time ? substr($jadwal->end_time, 0, 5) : '') }}"
                   class="@error('end_time') is-invalid @enderror">
            @error('end_time') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
            <label for="description">Keterangan</label>
            <input type="text" id="description" name="description"
                   value="{{ old('description', $jadwal?->description) }}"
                   class="@error('description') is-invalid @enderror">
            @error('description') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>
    </fieldset>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-outline" href="{{ route('kader.jadwal.index') }}">Batal</a>
</form>
@endsection