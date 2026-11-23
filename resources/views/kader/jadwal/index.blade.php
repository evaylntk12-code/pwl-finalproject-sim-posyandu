@extends('layouts.app')

@section('title', 'Jadwal & Imunisasi')

@section('content')
@php
    $area  = auth()->user()->areaPrefix(); // kader atau bidan
    $kader = $area === 'kader';            // kelola jadwal kegiatan: hanya kader
    $bidan = $area === 'bidan';            // kelola imunisasi (tambah, ubah, hapus, selesai): hanya bidan
@endphp
<h2 class="judul">Jadwal & Imunisasi</h2>

@if ($kader)
    <div class="bar">
        <a class="btn" href="{{ route('kader.jadwal.create') }}">+ Tambah Jadwal</a>
    </div>
@endif
@if ($bidan)
    <div class="bar">
        <a class="btn btn-pink" href="{{ route('bidan.imunisasi.create') }}">+ Jadwalkan Imunisasi</a>
    </div>
@endif

{{-- ===== Daftar Jadwal Kegiatan ===== --}}
<h3 class="sub"><i class="fa-solid fa-calendar-days"></i> Daftar Jadwal Kegiatan</h3>
<div class="tb">
    <table>
        <thead>
            <tr><th>No</th><th>Tanggal</th><th>Waktu</th><th>Keterangan</th>@if ($kader)<th>Aksi</th>@endif</tr>
        </thead>
        <tbody>
            @forelse ($jadwal as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $j->activity_date->format('d-m-Y') }}</td>
                    <td>{{ $j->time_range }}</td>
                    <td>{{ $j->description }}</td>
                    @if ($kader)
                    <td class="aksi">
                        <a class="btn btn-outline btn-ikon" href="{{ route('kader.jadwal.edit', $j) }}"
                           title="Ubah data" aria-label="Ubah jadwal {{ $j->description }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <button type="button" class="btn btn-outline btn-sm btn-reset"
                                onclick="document.getElementById('selesai-jadwal-{{ $j->id }}').showModal()">
                            <i class="fa-solid fa-circle-check"></i> Selesai
                        </button>

                        <form method="POST" action="{{ route('kader.jadwal.destroy', $j) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-ikon" title="Hapus data"
                                    aria-label="Hapus jadwal {{ $j->description }}"
                                    onclick="return confirm('Hapus jadwal kegiatan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $kader ? 5 : 4 }}">
                        @include('partials.empty', [
                            'ikon'  => 'fa-calendar-days',
                            'judul' => 'Tidak ada jadwal kegiatan',
                            'teks'  => $kader ? 'Klik tombol + Tambah Jadwal di atas untuk menambahkan jadwal posyandu berikutnya.' : 'Jadwal kegiatan diisi oleh kader.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ===== Daftar Imunisasi ===== --}}
<h3 class="sub"><i class="fa-solid fa-syringe"></i> Daftar Imunisasi</h3>
<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama Balita</th><th>Jenis</th><th>Tanggal</th>
                <th>Imunisasi Berikutnya</th>@if ($bidan)<th>Aksi</th>@endif
            </tr>
        </thead>
        <tbody>
            @forelse ($imunisasi as $i)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $i->toddler->name }}</td>
                    <td>{{ $i->vaccine_type }}</td>
                    <td>{{ $i->immunization_date->format('d-m-Y') }}</td>
                    <td>{{ $i->next_date?->format('d-m-Y') ?? '-' }}</td>
                    @if ($bidan)
                    <td class="aksi">
                        <a class="btn btn-outline btn-ikon" href="{{ route('bidan.imunisasi.edit', $i) }}"
                           title="Ubah jadwal" aria-label="Ubah imunisasi {{ $i->toddler->name }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <button type="button" class="btn btn-outline btn-sm btn-reset"
                                onclick="document.getElementById('selesai-imunisasi-{{ $i->id }}').showModal()">
                            <i class="fa-solid fa-circle-check"></i> Selesai
                        </button>

                        <form method="POST" action="{{ route('bidan.imunisasi.destroy', $i) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-ikon" title="Hapus jadwal"
                                    aria-label="Hapus imunisasi {{ $i->toddler->name }}"
                                    onclick="return confirm('Hapus jadwal imunisasi ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $bidan ? 6 : 5 }}">
                        @include('partials.empty', [
                            'ikon'  => 'fa-syringe',
                            'judul' => 'Tidak ada imunisasi yang menunggu',
                            'teks'  => $bidan ? 'Klik tombol + Jadwalkan Imunisasi di atas untuk menjadwalkan imunisasi balita.' : 'Jadwal imunisasi diisi oleh bidan.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="hint"><i class="fa-solid fa-circle-info"></i> Data yang sudah ditandai Selesai otomatis pindah ke
    <a class="tautan" href="{{ route($area . '.laporan.index') }}">Laporan</a> dan tidak bisa diubah lagi.</p>

{{-- ===== Popup konfirmasi Selesai: satu per baris ===== --}}
@if ($kader)
@foreach ($jadwal as $j)
    <dialog id="selesai-jadwal-{{ $j->id }}" onclick="if (event.target === this) this.close()">
        <div class="modal-head">
            <h3><i class="fa-solid fa-circle-check"></i> Tandai selesai?</h3>
        </div>
        <div class="modal-body">
            <p class="konfirmasi">Jadwal <b>{{ $j->description }}</b> ({{ $j->activity_date->format('d-m-Y') }})
                akan dipindahkan ke Laporan dan tidak bisa diubah lagi.</p>
        </div>
        <div class="modal-foot">
            <form method="dialog"><button class="btn btn-outline">Batal</button></form>
            <form method="POST" action="{{ route('kader.jadwal.selesai', $j) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn">Ya, Selesai</button>
            </form>
        </div>
    </dialog>
@endforeach
@endif

@if ($bidan)
@foreach ($imunisasi as $i)
    <dialog id="selesai-imunisasi-{{ $i->id }}" onclick="if (event.target === this) this.close()">
        <div class="modal-head">
            <h3><i class="fa-solid fa-circle-check"></i> Tandai selesai?</h3>
        </div>
        <div class="modal-body">
            <p class="konfirmasi">Imunisasi <b>{{ $i->vaccine_type }}</b> untuk <b>{{ $i->toddler->name }}</b>
                akan dipindahkan ke Laporan dan tidak bisa diubah lagi.</p>
        </div>
        <div class="modal-foot">
            <form method="dialog"><button class="btn btn-outline">Batal</button></form>
            <form method="POST" action="{{ route('bidan.imunisasi.selesai', $i) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn">Ya, Selesai</button>
            </form>
        </div>
    </dialog>
@endforeach
@endif
@endsection