@extends('layouts.app')

@section('title', 'Dashboard Bidan')

@section('content')
<h2 class="judul">Halo, Bidan!</h2>

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
        <div class="ikon {{ $perluPerhatian->count() > 0 ? 'alert' : '' }}">
            <i class="fa-solid {{ $perluPerhatian->count() > 0 ? 'fa-triangle-exclamation' : 'fa-circle-check' }}"></i>
        </div>
        <div><b>{{ $perluPerhatian->count() }}</b><span>Perlu perhatian gizi</span></div>
    </div>

    <div class="card stat">
        <div class="ikon"><i class="fa-solid fa-user-nurse"></i></div>
        <div><b>{{ $jumlahKader }}</b><span>Akun kader</span></div>
    </div>
</div>

<div class="grid">
    {{-- Balita yang perlu tindak lanjut gizi (hanya dilihat, diisi oleh kader) --}}
    <div class="card jadwal-card">
        <span><i class="fa-solid fa-triangle-exclamation"></i> Perlu Perhatian Gizi Bulan Ini</span>
        @if ($perluPerhatian->count() > 0)
            <ul class="daftar">
                @foreach ($perluPerhatian as $p)
                    <li>{{ $p->toddler->name }} · {{ $p->nutrition_status }}</li>
                @endforeach
            </ul>
        @else
            <h3>Tidak ada</h3>
        @endif
        <a class="tautan" href="{{ route('bidan.pemeriksaan.index') }}">Lihat pemeriksaan →</a>
    </div>

    {{-- Imunisasi menunggu disahkan bidan --}}
    <div class="card pink-card">
        <span><i class="fa-solid fa-syringe"></i> Imunisasi Menunggu Disahkan</span>
        @if ($imunisasiTerjadwal->count() > 0)
            <h3>{{ $imunisasiTerjadwal->count() }} balita</h3>
            <ul class="daftar">
                @foreach ($imunisasiTerjadwal as $i)
                    <li>{{ $i->toddler->name }} · {{ $i->vaccine_type }}</li>
                @endforeach
            </ul>
        @else
            <h3>Tidak ada imunisasi yang menunggu</h3>
        @endif
        <a class="tautan" href="{{ route('bidan.jadwal.index') }}">Buka Jadwal & Imunisasi →</a>
    </div>
</div>
@endsection