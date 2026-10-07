@extends('layouts.app')

@section('title', $imunisasi ? 'Ubah Imunisasi' : 'Catat Imunisasi')

@section('content')
<h2 class="judul">{{ $imunisasi ? 'Ubah Catatan Imunisasi' : 'Catat Imunisasi' }}</h2>

<fieldset class="form-card">
    <legend>💉 Catatan Imunisasi</legend>
    <div class="fg"><label for="balita">Balita</label>
        <select id="balita">
            <option value="">Pilih...</option>
            @foreach ($daftarBalita as $b)
                <option @selected(($imunisasi['balita'] ?? '') === $b['nama'])>{{ $b['nama'] }}</option>
            @endforeach
        </select></div>
    <div class="fg"><label for="jenis">Jenis imunisasi</label>
        <input type="text" id="jenis" value="{{ $imunisasi['jenis'] ?? '' }}"></div>
    <div class="fg"><label for="tgl">Tanggal imunisasi</label>
        <input type="date" id="tgl" value="{{ $imunisasi['tanggal'] ?? '' }}"></div>
    <div class="fg"><label for="next">Jadwal imunisasi berikutnya</label>
        <input type="date" id="next" value="{{ $imunisasi['berikutnya'] ?? '' }}"></div>
    <p class="hint">ℹ️ Kalau jadwal imunisasi berikutnya berubah, cukup ubah tanggal ini.</p>
</fieldset>

{{-- Tahap UI: Simpan masih link. Diganti form POST pada pertemuan CRUD. --}}
<a class="btn" href="{{ route('kader.jadwal.index') }}">Simpan</a>
<a class="btn btn-outline" href="{{ route('kader.jadwal.index') }}">Batal</a>
@endsection
