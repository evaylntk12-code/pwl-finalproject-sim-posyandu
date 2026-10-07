@extends('layouts.app')

@section('title', 'Jadwal & Imunisasi')

@section('content')
<h2 class="judul">Jadwal & Imunisasi</h2>

<div class="bar">
    <a class="btn" href="{{ route('kader.jadwal.create') }}">+ Tambah Jadwal</a>
    <a class="btn btn-pink" href="{{ route('kader.jadwal.imunisasi.create') }}">+ Catat Imunisasi</a>
</div>

{{-- ===== Daftar Jadwal Kegiatan ===== --}}
<h3 class="sub">📅 Daftar Jadwal Kegiatan</h3>
<div class="tb">
    <table>
        <thead>
            <tr><th>No</th><th>Tanggal</th><th>Waktu</th><th>Keterangan</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($jadwal as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d-m-Y', strtotime($j['tanggal'])) }}</td>
                    <td>{{ $j['waktu'] }}</td>
                    <td>{{ $j['keterangan'] }}</td>
                    <td>
                        @if ($j['status'] === 'Selesai')
                            <span class="badge">Selesai</span>
                        @else
                            <span class="badge pink">Terjadwal</span>
                        @endif
                    </td>
                    <td class="aksi">
                        @if ($j['status'] === 'Terjadwal')
                            <a class="btn btn-outline btn-sm" href="{{ route('kader.jadwal.edit', $j['id']) }}">Ubah</a>
                            <a class="btn btn-danger btn-sm" href="#">Hapus</a>
                            <a class="btn btn-sm" href="#">✔ Selesai</a>
                        @else
                            <span class="kunci">🔒 Terkunci</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        @include('partials.empty', [
                            'ikon'   => '📅',
                            'judul'  => 'Belum ada jadwal kegiatan',
                            'teks'   => 'Tambahkan jadwal posyandu berikutnya agar orang tua bisa melihatnya.',
                            'rute'   => route('kader.jadwal.create'),
                            'tombol' => '+ Tambah Jadwal',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ===== Daftar Imunisasi ===== --}}
<h3 class="sub">💉 Daftar Imunisasi</h3>
<div class="tb">
    <table>
        <thead>
            <tr><th>Balita</th><th>Jenis</th><th>Tanggal</th><th>Imunisasi Berikutnya</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($imunisasi as $i)
                <tr>
                    <td>{{ $i['balita'] }}</td>
                    <td>{{ $i['jenis'] }}</td>
                    <td>{{ date('d-m-Y', strtotime($i['tanggal'])) }}</td>
                    <td>{{ date('d-m-Y', strtotime($i['berikutnya'])) }}</td>
                    <td>
                        @if ($i['status'] === 'Selesai')
                            <span class="badge">Selesai</span>
                        @else
                            <span class="badge pink">Terjadwal</span>
                        @endif
                    </td>
                    <td class="aksi">
                        @if ($i['status'] === 'Terjadwal')
                            <a class="btn btn-outline btn-sm" href="{{ route('kader.jadwal.imunisasi.edit', $i['id']) }}">Ubah</a>
                            <a class="btn btn-danger btn-sm" href="#">Hapus</a>
                            <a class="btn btn-sm" href="#">✔ Selesai</a>
                        @else
                            <span class="kunci">🔒 Terkunci</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        @include('partials.empty', [
                            'ikon'   => '💉',
                            'judul'  => 'Belum ada catatan imunisasi',
                            'teks'   => 'Catat imunisasi balita saat kegiatan posyandu berlangsung.',
                            'rute'   => route('kader.jadwal.imunisasi.create'),
                            'tombol' => '+ Catat Imunisasi',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="hint">🔒 Data berstatus Selesai terkunci: tidak bisa diubah atau dihapus.</p>
@endsection
