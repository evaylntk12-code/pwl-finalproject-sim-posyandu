@extends('layouts.app')

@section('title', $imunisasi ? 'Ubah Imunisasi' : 'Catat Imunisasi')

@section('content')
@php $ubah = ! is_null($imunisasi); @endphp

<h2 class="judul">{{ $ubah ? 'Ubah Catatan Imunisasi' : 'Catat Imunisasi' }}</h2>

@if ($errors->any())
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        Data belum bisa disimpan. Periksa isian yang bertanda merah.
    </div>
@endif

<form method="POST" action="{{ $ubah ? route('kader.imunisasi.update', $imunisasi) : route('kader.imunisasi.store') }}">
    @csrf
    @if ($ubah)
        @method('PUT')
    @endif

    <fieldset class="form-card">
        <legend><i class="fa-solid fa-syringe"></i> Catatan Imunisasi</legend>

        <div class="fg">
            <label for="toddler_id">Balita</label>
            <select id="toddler_id" name="toddler_id" class="@error('toddler_id') is-invalid @enderror">
                <option value="">Pilih...</option>
                @foreach ($daftarBalita as $b)
                    <option value="{{ $b->id }}"
                        @selected((string) old('toddler_id', $imunisasi?->toddler_id) === (string) $b->id)>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>
            @error('toddler_id') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
            <label for="vaccine_type">Jenis imunisasi</label>
            <input type="text" id="vaccine_type" name="vaccine_type"
                   value="{{ old('vaccine_type', $imunisasi?->vaccine_type) }}"
                   class="@error('vaccine_type') is-invalid @enderror">
            @error('vaccine_type') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
            <label for="immunization_date">Tanggal imunisasi</label>
            <input type="date" id="immunization_date" name="immunization_date"
                   value="{{ old('immunization_date', $imunisasi?->immunization_date->format('Y-m-d')) }}"
                   class="@error('immunization_date') is-invalid @enderror">
            @error('immunization_date') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>

        <div class="fg">
            <label for="next_date">Jadwal imunisasi berikutnya (opsional)</label>
            <input type="date" id="next_date" name="next_date"
                   value="{{ old('next_date', $imunisasi?->next_date?->format('Y-m-d')) }}"
                   class="@error('next_date') is-invalid @enderror">
            @error('next_date') <div class="pesan-error">{{ $message }}</div> @enderror
        </div>

        <p class="hint"><i class="fa-solid fa-circle-info"></i> Kalau jadwal imunisasi berikutnya berubah, cukup ubah tanggal ini.</p>
    </fieldset>

    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-outline" href="{{ route('kader.jadwal.index') }}">Batal</a>
</form>
@endsection