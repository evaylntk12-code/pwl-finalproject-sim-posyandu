@extends('layouts.app')

@section('title', 'Dashboard Anak')

@section('content')
<h2 class="judul">Dashboard Anak</h2>

{{-- Profil anak --}}
<div class="card profil-anak">
    <div class="avatar {{ $anak->gender === 'L' ? 'laki' : '' }}">
        <i class="fa-solid {{ $anak->gender === 'P' ? 'fa-child-dress' : 'fa-child' }}"></i>
    </div>
    <div>
        <h3>{{ $anak->name }}</h3>
        <span class="muted">{{ $anak->usia }} bulan</span> ·
        <span class="badge {{ $anak->gender === 'P' ? 'pink' : '' }}">
            {{ $anak->gender === 'P' ? 'Perempuan' : 'Laki-laki' }}
        </span>
        <div class="muted">Lahir {{ $anak->birth_date->format('d-m-Y') }}</div>
    </div>
</div>

{{-- Status gizi terakhir --}}
<div class="card gizi-card">
    <div class="gizi-judul"><i class="fa-solid fa-heart-pulse"></i> Status Gizi Terakhir</div>

    @if ($pemeriksaan)
        <div class="gizi-isi">
            <div class="gizi-status">
                @include('partials.badge-gizi', ['status' => $pemeriksaan->nutrition_status])
                <div class="muted">Diperiksa {{ $pemeriksaan->examination_date->format('d-m-Y') }}</div>
            </div>

            <dl class="ukur">
                <div><dt>Berat badan</dt><dd>{{ $pemeriksaan->weight }} kg</dd></div>
                <div><dt>Tinggi badan</dt><dd>{{ $pemeriksaan->height }} cm</dd></div>
                <div><dt>Lingkar kepala</dt><dd>{{ $pemeriksaan->head_circumference }} cm</dd></div>
                <div><dt>Lingkar lengan</dt><dd>{{ $pemeriksaan->arm_circumference }} cm</dd></div>
            </dl>
        </div>

        @if ($pemeriksaan->notes)
            <p class="gizi-catatan"><i class="fa-solid fa-note-sticky"></i> {{ $pemeriksaan->notes }}</p>
        @endif
    @else
        <p class="muted">Belum ada pemeriksaan. Hasilnya akan muncul di sini setelah anak diperiksa oleh kader.</p>
    @endif
</div>

{{-- Jadwal dan imunisasi (dua kartu ini harus berada di dalam div.grid) --}}
<div class="grid">
    <div class="card jadwal-card">
        <span><i class="fa-solid fa-calendar-days"></i> Jadwal Posyandu Terdekat</span>
        @if ($jadwal)
            <h3>{{ $jadwal->activity_date->format('d-m-Y') }}</h3>
            {{ $jadwal->time_range }} WIB · {{ $jadwal->description }}
        @else
            <h3>Belum ada jadwal</h3>
            Jadwal berikutnya akan diinformasikan oleh kader.
        @endif
    </div>

    <div class="card pink-card">
        <span><i class="fa-solid fa-syringe"></i> Imunisasi Berikutnya</span>
        @if ($imunisasi)
            <h3>{{ $imunisasi->immunization_date->format('d-m-Y') }}</h3>
            Imunisasi {{ $imunisasi->vaccine_type }}
        @else
            <h3>Belum ada jadwal</h3>
            Tidak ada imunisasi yang menunggu saat ini.
        @endif
    </div>
</div>
@endsection