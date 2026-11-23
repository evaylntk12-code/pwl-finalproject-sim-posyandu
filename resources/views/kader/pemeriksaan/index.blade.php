@extends('layouts.app')

@section('title', 'Data Pemeriksaan')

@section('content')
@php
    $adaGambar = file_exists(public_path('img/acuan-gizi.png'));
    $area  = auth()->user()->areaPrefix(); // kader atau bidan
    $tulis = $area === 'kader';            // hanya kader yang boleh menambah/ubah/hapus
@endphp

<h2 class="judul">Data Pemeriksaan</h2>

{{-- Gambar tabel acuan gizi (klik untuk memperbesar) --}}
@if ($adaGambar)
    <button type="button" class="acuan" title="Klik untuk memperbesar"
            onclick="document.getElementById('acuan-gizi').showModal()">
        <img src="{{ asset('img/acuan-gizi.png') }}" alt="Tabel acuan status gizi">
        <span class="acuan-zoom"><i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar</span>
    </button>

    <dialog id="acuan-gizi" class="lebar" onclick="if (event.target === this) this.close()">
        <div class="modal-head"><h3>Tabel Acuan Status Gizi</h3></div>
        <div class="modal-body">
            <img class="acuan-besar" src="{{ asset('img/acuan-gizi.png') }}" alt="Tabel acuan status gizi">
        </div>
        <div class="modal-foot">
            <form method="dialog"><button class="btn">Tutup</button></form>
        </div>
    </dialog>
@else
    <div class="ref"><i class="fa-solid fa-image"></i> Gambar Tabel Acuan Gizi</div>
@endif

<div class="bar">
    <span class="info-bulan"><i class="fa-solid fa-calendar-days"></i> Bulan berjalan: {{ $bulanLabel }}</span>
    @if ($tulis)
        <a class="btn" href="{{ route('kader.pemeriksaan.create') }}">+ Tambah Pemeriksaan</a>
    @endif
</div>

<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama Balita</th><th>Tanggal</th>
                <th>BB (kg)</th><th>TB (cm)</th><th>L. Kepala (cm)</th><th>L. Lengan (cm)</th>
                <th>Status Gizi</th><th>Catatan</th>@if ($tulis)<th>Aksi</th>@endif
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
                    @if ($tulis)
                    <td class="aksi">
                        <a class="btn btn-outline btn-ikon" href="{{ route('kader.pemeriksaan.edit', $p) }}"
                        title="Ubah data" aria-label="Ubah pemeriksaan {{ $p->toddler->name }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <form method="POST" action="{{ route('kader.pemeriksaan.destroy', $p) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-ikon" title="Hapus data"
                                    aria-label="Hapus pemeriksaan {{ $p->toddler->name }}"
                                    onclick="return confirm('Hapus data pemeriksaan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $tulis ? 10 : 9 }}">
                        @include('partials.empty', [
                            'ikon'  => 'fa-weight-scale',
                            'judul' => 'Belum ada pemeriksaan bulan ini',
                            'teks'  => $tulis ? 'Klik tombol + Tambah Pemeriksaan di atas untuk mencatat pemeriksaan pertama bulan ' . $bulanLabel . '.' : 'Pemeriksaan diisi oleh kader.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="hint"><i class="fa-solid fa-circle-info"></i> Halaman ini hanya menampilkan pemeriksaan bulan berjalan. Data bulan sebelumnya otomatis terkunci dan bisa dilihat di
    <a class="tautan" href="{{ route($area . '.laporan.index') }}">Laporan Bulanan</a>.</p>
@endsection