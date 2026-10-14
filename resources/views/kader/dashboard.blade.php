@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2 class="judul">Halo, Kader!</h2>

{{-- Kartu statistik --}}
<div class="grid">
    <div class="card stat">
        <div class="ikon"><i class="fa-solid fa-baby"></i></div>
        <div><b>{{ $jumlahBalita }}</b><span>Jumlah balita</span></div>
    </div>

    <div class="card stat">
        <div class="ikon pink"><i class="fa-solid fa-weight-scale"></i></div>
        <div><b>{{ $jumlahPemeriksaan }}</b><span>Pemeriksaan bulan ini</span></div>
    </div>

    <div class="card stat">
        <div class="ikon {{ $jumlahPerluPerhatian > 0 ? 'alert' : '' }}">
            <i class="fa-solid {{ $jumlahPerluPerhatian > 0 ? 'fa-triangle-exclamation' : 'fa-circle-check' }}"></i>
        </div>
        <div><b>{{ $jumlahPerluPerhatian }}</b><span>Perlu perhatian gizi</span></div>
    </div>
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
            Tambahkan jadwal di menu Jadwal & Imunisasi.
        @endif
    </div>

    <div class="card pink-card">
        <span><i class="fa-solid fa-syringe"></i> Imunisasi Terjadwal</span>
        @if ($imunisasiTerjadwal->count() > 0)
            <h3>{{ $imunisasiTerjadwal->count() }} balita</h3>
            <ul class="daftar">
                @foreach ($imunisasiTerjadwal as $i)
                    <li>{{ $i->toddler->name }} · {{ $i->vaccine_type }}</li>
                @endforeach
            </ul>
        @else
            <h3>Belum ada imunisasi terjadwal</h3>
        @endif
        <a class="tautan" href="{{ route('kader.jadwal.index') }}">Lihat semua →</a>
    </div>
</div>
@endsection