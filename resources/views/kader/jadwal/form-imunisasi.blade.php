@extends('layouts.app')

@section('title', $imunisasi ? 'Ubah Imunisasi' : 'Catat Imunisasi')

@section('content')
<h2 class="judul">{{ $imunisasi ? 'Ubah Catatan Imunisasi' : 'Catat Imunisasi' }}</h2>

<fieldset class="form-card">
    <legend><i class="fa-solid fa-syringe"></i> Catatan Imunisasi</legend>
    <div class="fg"><label for="balita">Balita</label>
        <select id="balita">
            <option value="">Pilih...</option>
            @foreach ($daftarBalita as $b)
                <option value="{{ $b->id }}" @selected(($imunisasi?->toddler_id) === $b->id)>{{ $b->name }}</option>
            @endforeach
        </select></div>
    <div class="fg"><label for="jenis">Jenis imunisasi</label>
        <input type="text" id="jenis" value="{{ $imunisasi?->vaccine_type }}"></div>
    <div class="fg"><label for="tgl">Tanggal imunisasi</label>
        <input type="date" id="tgl" value="{{ $imunisasi?->immunization_date->format('Y-m-d') }}"></div>
    <div class="fg"><label for="next">Jadwal imunisasi berikutnya</label>
        <input type="date" id="next" value="{{ $imunisasi?->next_date?->format('Y-m-d') }}"></div>
    <p class="hint"><i class="fa-solid fa-circle-info"></i> Kalau jadwal imunisasi berikutnya berubah, cukup ubah tanggal ini.</p>
</fieldset>

{{-- Tahap UI: Simpan masih link. Diganti form POST pada pertemuan CRUD. --}}
<a class="btn" href="{{ route('kader.jadwal.index') }}">Simpan</a>
<a class="btn btn-outline" href="{{ route('kader.jadwal.index') }}">Batal</a>
@endsection