@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2 class="judul">Halo, Kader! 👋</h2>

<div class="grid">
    <div class="card stat">
        <div class="ikon">👶</div>
        <div><b>{{ $jumlahBalita }}</b><span>Jumlah balita</span></div>
    </div>
    <div class="card stat">
        <div class="ikon pink">⚖️</div>
        <div><b>{{ $jumlahPemeriksaan }}</b><span>Pemeriksaan bulan ini</span></div>
    </div>
    <div class="card stat">
        <div class="ikon">🌱</div>
        <div><b>{{ $jumlahGiziBaik }}</b><span>Status gizi baik</span></div>
    </div>
</div>

<div class="card jadwal-card">
    <span>📅 Jadwal Posyandu Terdekat</span>
    @if ($jadwal)
        <h3>{{ date('d-m-Y', strtotime($jadwal['tanggal'])) }} · {{ $jadwal['waktu'] }} WIB</h3>
        {{ $jadwal['keterangan'] }}
    @else
        <h3>Belum ada jadwal</h3>
    @endif
</div>
@endsection
