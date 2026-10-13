@extends('layouts.app')

@section('title', 'Dashboard Anak')

@section('content')
<h2 class="judul">Dashboard Anak</h2>

{{-- Profil anak --}}
<div class="card profil-anak">
    <div class="avatar {{ $anak['jk'] === 'L' ? 'laki' : '' }}">
        <i class="fa-solid {{ $anak['jk'] === 'P' ? 'fa-child-dress' : 'fa-child' }}"></i>
    </div>
    <div>
        <h3>{{ $anak['nama'] }}</h3>
        <span class="muted">{{ $anak['usia'] }} bulan</span> ·
        <span class="badge {{ $anak['jk'] === 'P' ? 'pink' : '' }}">
            {{ $anak['jk'] === 'P' ? 'Perempuan' : 'Laki-laki' }}
        </span>
        <div class="muted">Lahir {{ date('d-m-Y', strtotime($anak['tgl_lahir'])) }}</div>
    </div>
</div>

{{-- Status gizi terakhir --}}
<div class="card gizi-card">
    <div class="gizi-judul"><i class="fa-solid fa-heart-pulse"></i> Status Gizi Terakhir</div>

    @if ($pemeriksaan)
        <div class="gizi-isi">
            <div class="gizi-status">
                @include('partials.badge-gizi', ['status' => $pemeriksaan['status_gizi']])
                <div class="muted">Diperiksa {{ date('d-m-Y', strtotime($pemeriksaan['tanggal'])) }}</div>
            </div>

            <dl class="ukur">
                <div><dt>Berat badan</dt><dd>{{ $pemeriksaan['bb'] }} kg</dd></div>
                <div><dt>Tinggi badan</dt><dd>{{ $pemeriksaan['tb'] }} cm</dd></div>
                <div><dt>Lingkar kepala</dt><dd>{{ $pemeriksaan['lk'] }} cm</dd></div>
                <div><dt>Lingkar lengan</dt><dd>{{ $pemeriksaan['ll'] }} cm</dd></div>
            </dl>
        </div>

        @if ($pemeriksaan['catatan'] !== '-')
            <p class="gizi-catatan"><i class="fa-solid fa-note-sticky"></i> {{ $pemeriksaan['catatan'] }}</p>
        @endif
    @else
        <p class="muted">Belum ada pemeriksaan. Hasilnya akan muncul di sini setelah anak diperiksa oleh kader.</p>
    @endif
</div>

{{-- Jadwal dan imunisasi --}}
<div class="grid">
    <div class="card jadwal-card">
        <span><i class="fa-solid fa-calendar-days"></i> Jadwal Posyandu Terdekat</span>
        @if ($jadwal)
            <h3>{{ date('d-m-Y', strtotime($jadwal['tanggal'])) }}</h3>
            {{ $jadwal['waktu'] }} WIB · {{ $jadwal['keterangan'] }}
        @else
            <h3>Belum ada jadwal</h3>
            Jadwal berikutnya akan diinformasikan oleh kader.
        @endif
    </div>

    <div class="card pink-card">
        <span><i class="fa-solid fa-syringe"></i> Imunisasi Berikutnya</span>
        @if ($imunisasi)
            <h3>{{ date('d-m-Y', strtotime($imunisasi['tanggal'])) }}</h3>
            Imunisasi {{ $imunisasi['jenis'] }}
        @else
            <h3>Belum ada jadwal</h3>
            Tidak ada imunisasi yang menunggu saat ini.
        @endif
    </div>
</div>
@endsection