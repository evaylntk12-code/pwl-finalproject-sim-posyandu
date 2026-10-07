@extends('layouts.app')

@section('title', 'Data Pemeriksaan')

@section('content')
<h2 class="judul">Data Pemeriksaan</h2>

{{-- Nanti diganti gambar asli: <img src="{{ asset('img/acuan-gizi.png') }}"> --}}
<div class="ref">🖼️ Gambar Tabel Acuan Gizi</div>

<div class="bar">
    <span class="badge">📅 Bulan berjalan: Oktober 2026</span>
    @if (count($pemeriksaan) > 0)
        <a class="btn" href="{{ route('kader.pemeriksaan.create') }}">+ Tambah Pemeriksaan</a>
    @endif
</div>

<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama</th><th>Tanggal</th><th>BB</th><th>TB</th>
                <th>L. Kepala</th><th>L. Lengan</th><th>Status Gizi</th><th>Catatan</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pemeriksaan as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p['balita'] }}</td>
                    <td>{{ date('d-m-Y', strtotime($p['tanggal'])) }}</td>
                    <td>{{ $p['bb'] }}</td>
                    <td>{{ $p['tb'] }}</td>
                    <td>{{ $p['lk'] }}</td>
                    <td>{{ $p['ll'] }}</td>
                    <td>@include('partials.badge-gizi', ['status' => $p['status_gizi']])</td>
                    <td>{{ $p['catatan'] }}</td>
                    <td class="aksi">
                        <a class="btn btn-outline btn-sm" href="{{ route('kader.pemeriksaan.edit', $p['id']) }}">Ubah</a>
                        <a class="btn btn-danger btn-sm" href="#">Hapus</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        @include('partials.empty', [
                            'ikon'   => '🧸',
                            'judul'  => 'Belum ada pemeriksaan bulan ini',
                            'teks'   => 'Mulai catat hasil penimbangan dan pengukuran balita bulan Oktober 2026.',
                            'rute'   => route('kader.pemeriksaan.create'),
                            'tombol' => '+ Tambah Pemeriksaan',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="hint">ℹ️ Halaman ini hanya menampilkan pemeriksaan bulan berjalan. Data bulan sebelumnya otomatis terkunci dan bisa dilihat di
    <a class="tautan" href="{{ route('kader.laporan.index') }}">Laporan Bulanan</a>.</p>
@endsection
