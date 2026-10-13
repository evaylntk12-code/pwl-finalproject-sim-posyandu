@extends('layouts.app')

@section('title', 'Jadwal & Imunisasi')

@section('content')
<h2 class="judul">Jadwal & Imunisasi</h2>

<div class="bar">
    <a class="btn" href="{{ route('kader.jadwal.create') }}">+ Tambah Jadwal</a>
    <a class="btn btn-pink" href="{{ route('kader.jadwal.imunisasi.create') }}">+ Catat Imunisasi</a>
</div>

{{-- ===== Daftar Jadwal Kegiatan ===== --}}
<h3 class="sub"><i class="fa-solid fa-calendar-days"></i> Daftar Jadwal Kegiatan</h3>
<div class="tb">
    <table>
        <thead>
            <tr><th>No</th><th>Tanggal</th><th>Waktu</th><th>Keterangan</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($jadwal as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d-m-Y', strtotime($j['tanggal'])) }}</td>
                    <td>{{ $j['waktu'] }}</td>
                    <td>{{ $j['keterangan'] }}</td>
                    <td class="aksi">
                        <a class="btn btn-outline btn-ikon" href="{{ route('kader.jadwal.edit', $j['id']) }}"
                           title="Ubah data" aria-label="Ubah jadwal {{ $j['keterangan'] }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <button type="button" class="btn btn-outline btn-sm btn-reset"
                                onclick="document.getElementById('selesai-jadwal-{{ $j['id'] }}').showModal()">
                            <i class="fa-solid fa-circle-check"></i> Selesai
                        </button>

                        {{-- Tahap UI: tombol Hapus masih placeholder. Diganti form DELETE pada pertemuan CRUD. --}}
                        <a class="btn btn-danger btn-ikon" href="#"
                           title="Hapus data" aria-label="Hapus jadwal {{ $j['keterangan'] }}">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        @include('partials.empty', [
                            'ikon'  => 'fa-calendar-days',
                            'judul' => 'Tidak ada jadwal kegiatan',
                            'teks'  => 'Klik tombol + Tambah Jadwal di atas untuk menambahkan jadwal posyandu berikutnya.',
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
                <th>Imunisasi Berikutnya</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($imunisasi as $i)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $i['balita'] }}</td>
                    <td>{{ $i['jenis'] }}</td>
                    <td>{{ date('d-m-Y', strtotime($i['tanggal'])) }}</td>
                    <td>{{ date('d-m-Y', strtotime($i['berikutnya'])) }}</td>
                    <td class="aksi">
                        <a class="btn btn-outline btn-ikon" href="{{ route('kader.jadwal.imunisasi.edit', $i['id']) }}"
                           title="Ubah data" aria-label="Ubah imunisasi {{ $i['balita'] }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <button type="button" class="btn btn-outline btn-sm btn-reset"
                                onclick="document.getElementById('selesai-imunisasi-{{ $i['id'] }}').showModal()">
                            <i class="fa-solid fa-circle-check"></i> Selesai
                        </button>

                        <a class="btn btn-danger btn-ikon" href="#"
                           title="Hapus data" aria-label="Hapus imunisasi {{ $i['balita'] }}">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        @include('partials.empty', [
                            'ikon'  => 'fa-syringe',
                            'judul' => 'Tidak ada imunisasi yang menunggu',
                            'teks'  => 'Klik tombol + Catat Imunisasi di atas untuk mencatat imunisasi balita.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="hint"><i class="fa-solid fa-circle-info"></i> Data yang sudah ditandai Selesai otomatis pindah ke
    <a class="tautan" href="{{ route('kader.laporan.index') }}">Laporan</a> dan tidak bisa diubah lagi.</p>

{{-- ===== Popup konfirmasi Selesai: satu per baris ===== --}}
@foreach ($jadwal as $j)
    <dialog id="selesai-jadwal-{{ $j['id'] }}" onclick="if (event.target === this) this.close()">
        <div class="modal-head">
            <h3><i class="fa-solid fa-circle-check"></i> Tandai selesai?</h3>
        </div>
        <div class="modal-body">
            <p class="konfirmasi">Jadwal <b>{{ $j['keterangan'] }}</b> ({{ date('d-m-Y', strtotime($j['tanggal'])) }})
                akan dipindahkan ke Laporan dan tidak bisa diubah lagi.</p>
        </div>
        <div class="modal-foot">
            <form method="dialog"><button class="btn btn-outline">Batal</button></form>
            {{-- Tahap UI: tombol ini masih link. Diganti form PATCH (status = selesai) pada pertemuan CRUD. --}}
            <a class="btn" href="{{ route('kader.jadwal.index') }}">Ya, Selesai</a>
        </div>
    </dialog>
@endforeach

@foreach ($imunisasi as $i)
    <dialog id="selesai-imunisasi-{{ $i['id'] }}" onclick="if (event.target === this) this.close()">
        <div class="modal-head">
            <h3><i class="fa-solid fa-circle-check"></i> Tandai selesai?</h3>
        </div>
        <div class="modal-body">
            <p class="konfirmasi">Imunisasi <b>{{ $i['jenis'] }}</b> untuk <b>{{ $i['balita'] }}</b>
                akan dipindahkan ke Laporan dan tidak bisa diubah lagi.</p>
        </div>
        <div class="modal-foot">
            <form method="dialog"><button class="btn btn-outline">Batal</button></form>
            <a class="btn" href="{{ route('kader.jadwal.index') }}">Ya, Selesai</a>
        </div>
    </dialog>
@endforeach
@endsection