@extends('layouts.app')

@section('title', 'Data Pemeriksaan')

@section('content')
@php $adaGambar = file_exists(public_path('img/acuan-gizi.png')); @endphp

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
    <a class="btn" href="{{ route('kader.pemeriksaan.create') }}">+ Tambah Pemeriksaan</a>
</div>

<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama Balita</th><th>Tanggal</th>
                <th>BB (kg)</th><th>TB (cm)</th><th>L. Kepala (cm)</th><th>L. Lengan (cm)</th>
                <th>Status Gizi</th><th>Catatan</th><th>Aksi</th>
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
                    <td class="aksi">
                        <a class="btn btn-outline btn-ikon" href="{{ route('kader.pemeriksaan.edit', $p->id) }}"
                           title="Ubah data" aria-label="Ubah pemeriksaan {{ $p->toddler->name }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        {{-- Tahap UI: tombol Hapus masih placeholder. Diganti form DELETE pada pertemuan CRUD. --}}
                        <a class="btn btn-danger btn-ikon" href="#"
                           title="Hapus data" aria-label="Hapus pemeriksaan {{ $p->toddler->name }}">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        @include('partials.empty', [
                            'ikon'  => 'fa-weight-scale',
                            'judul' => 'Belum ada pemeriksaan bulan ini',
                            'teks'  => 'Klik tombol + Tambah Pemeriksaan di atas untuk mencatat pemeriksaan pertama bulan ' . $bulanLabel . '.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="hint"><i class="fa-solid fa-circle-info"></i> Halaman ini hanya menampilkan pemeriksaan bulan berjalan. Data bulan sebelumnya otomatis terkunci dan bisa dilihat di
    <a class="tautan" href="{{ route('kader.laporan.index') }}">Laporan Bulanan</a>.</p>
@endsection