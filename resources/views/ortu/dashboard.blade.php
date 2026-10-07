@extends('layouts.app')

@section('title', 'Dashboard Anak')

@section('content')
<h2 class="judul">Dashboard Anak</h2>

<div class="card profil-anak">
    <div class="avatar">{{ $anak['jk'] === 'P' ? '👧' : '👦' }}</div>
    <div>
        <h3>{{ $anak['nama'] }}</h3>
        Lahir {{ date('d-m-Y', strtotime($anak['tgl_lahir'])) }} · {{ $anak['jk'] === 'P' ? 'Perempuan' : 'Laki-laki' }}
    </div>
</div>

<div class="grid">
    <div class="card jadwal-card">
        <span>📅 Jadwal Posyandu Terdekat</span>
        @if ($jadwal)
            <h3>{{ date('d-m-Y', strtotime($jadwal['tanggal'])) }}</h3>
            {{ $jadwal['waktu'] }} WIB
        @else
            <h3>Belum ada jadwal</h3>
        @endif
    </div>

    <div class="card pink-card">
        <span>💉 Imunisasi Berikutnya</span>
        @if ($imunisasi)
            <h3>{{ $imunisasi['jenis'] }}</h3>
            {{ date('d-m-Y', strtotime($imunisasi['berikutnya'])) }}
        @else
            <h3>Belum ada jadwal imunisasi</h3>
        @endif
    </div>
</div>
@endsection
