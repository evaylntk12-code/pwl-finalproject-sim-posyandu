@extends('layouts.app')

{{-- Judul halaman = nama file default saat disimpan sebagai PDF --}}
@section('title', $judulCetak . ' - ' . $periode)

@section('content')
@php
    $tabs = [
        'pemeriksaan' => ['fa-weight-scale',  'Pemeriksaan'],
        'imunisasi'   => ['fa-syringe',       'Imunisasi'],
        'kegiatan'    => ['fa-calendar-days', 'Kegiatan Posyandu'],
    ];
@endphp

<h2 class="judul">Laporan Bulanan</h2>

{{-- Kop laporan: hanya tampil saat dicetak --}}
<div class="cetak-kop">
    <h2>SIM Posyandu Balita</h2>
    <h3>{{ $judulCetak }}</h3>
    <dl class="info cetak-info">
        <dt>Periode</dt><dd>: {{ $periode }}</dd>
        @if ($tab !== 'kegiatan')
            <dt>Balita</dt><dd>: {{ $namaBalitaDipilih }}</dd>
        @endif
        <dt>Dicetak pada</dt><dd>: {{ now()->format('d-m-Y H:i') }}</dd>
        <dt>Dicetak oleh</dt><dd>: {{ auth()->user()->name }}</dd>
    </dl>
</div>

{{-- Filter: tab tetap terbawa --}}
<form method="GET" action="{{ route('kader.laporan.index') }}" class="bar">
    <input type="hidden" name="tab" value="{{ $tab }}">

    <div class="fg"><label for="bulan">Bulan</label>
        <select id="bulan" name="bulan">
            @foreach ($daftarBulan as $nilai => $label)
                <option value="{{ $nilai }}" @selected($bulan === (string) $nilai)>{{ $label }}</option>
            @endforeach
        </select></div>

    <div class="fg"><label for="tahun">Tahun</label>
        <select id="tahun" name="tahun">
            @foreach ($daftarTahun as $t)
                <option value="{{ $t }}" @selected($tahun === $t)>{{ $t }}</option>
            @endforeach
        </select></div>

    {{-- Balita hanya relevan untuk tab Pemeriksaan dan Imunisasi --}}
    @if ($tab !== 'kegiatan')
        <div class="fg"><label for="balita">Balita</label>
            <select id="balita" name="balita">
                <option value="semua">Semua balita</option>
                @foreach ($daftarBalita as $b)
                    <option value="{{ $b->id }}" @selected($balitaDipilih === (string) $b->id)>{{ $b->name }}</option>
                @endforeach
            </select></div>
    @endif

    <button class="btn" type="submit">Tampilkan</button>
    <button class="btn btn-outline" type="button" onclick="window.print()">
        <i class="fa-solid fa-print"></i> Cetak PDF
    </button>
</form>

{{-- Tab: bulan, tahun, dan balita ikut terbawa --}}
<nav class="tabs">
    @foreach ($tabs as $kode => [$ikon, $label])
        <a class="tab {{ $tab === $kode ? 'on' : '' }}"
           href="{{ route('kader.laporan.index', ['tab' => $kode, 'bulan' => $bulan, 'tahun' => $tahun, 'balita' => $balitaDipilih]) }}">
            <i class="fa-solid {{ $ikon }}"></i> {{ $label }}
            <span class="jml">{{ $jumlah[$kode] }}</span>
        </a>
    @endforeach
</nav>

{{-- ===== Tab Pemeriksaan ===== --}}
@if ($tab === 'pemeriksaan')
<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama Balita</th><th>Tanggal</th><th>BB (kg)</th><th>TB (cm)</th>
                <th>L. Kepala (cm)</th><th>L. Lengan (cm)</th><th>Status Gizi</th><th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pemeriksaan as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->toddler->name }}</td>
                    <td>{{ $p->examination_date->format('d-m-Y') }}</td>
                    <td>{{ $p->weight }}</td>
                    <td>{{ $p->height }}</td>
                    <td>{{ $p->head_circumference }}</td>
                    <td>{{ $p->arm_circumference }}</td>
                    <td>@include('partials.badge-gizi', ['status' => $p->nutrition_status])</td>
                    <td>{{ $p->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        @include('partials.empty', [
                            'ikon'  => 'fa-weight-scale',
                            'judul' => 'Tidak ada data pemeriksaan',
                            'teks'  => 'Coba pilih bulan, tahun, atau balita yang lain.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ===== Tab Imunisasi ===== --}}
@elseif ($tab === 'imunisasi')
<div class="tb">
    <table>
        <thead>
            <tr><th>No</th><th>Nama Balita</th><th>Jenis</th><th>Tanggal</th><th>Imunisasi Berikutnya</th></tr>
        </thead>
        <tbody>
            @forelse ($imunisasi as $i)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $i->toddler->name }}</td>
                    <td>{{ $i->vaccine_type }}</td>
                    <td>{{ $i->immunization_date->format('d-m-Y') }}</td>
                    <td>{{ $i->next_date?->format('d-m-Y') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        @include('partials.empty', [
                            'ikon'  => 'fa-syringe',
                            'judul' => 'Tidak ada data imunisasi',
                            'teks'  => 'Imunisasi muncul di sini setelah ditandai Selesai. Coba pilih periode atau balita yang lain.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ===== Tab Kegiatan Posyandu ===== --}}
@else
<div class="tb">
    <table>
        <thead>
            <tr><th>No</th><th>Tanggal</th><th>Waktu</th><th>Keterangan</th></tr>
        </thead>
        <tbody>
            @forelse ($kegiatan as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->activity_date->format('d-m-Y') }}</td>
                    <td>{{ $j->time_range }}</td>
                    <td>{{ $j->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        @include('partials.empty', [
                            'ikon'  => 'fa-calendar-days',
                            'judul' => 'Tidak ada kegiatan posyandu',
                            'teks'  => 'Kegiatan muncul di sini setelah ditandai Selesai. Coba pilih periode yang lain.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif

<p class="hint"><i class="fa-solid fa-circle-info"></i> Laporan bersifat arsip: data hanya bisa dilihat dan dicetak, tidak bisa diubah.</p>
@endsection