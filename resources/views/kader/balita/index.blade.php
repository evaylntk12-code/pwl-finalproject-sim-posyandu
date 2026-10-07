@extends('layouts.app')

@section('title', 'Data Balita & Wali')

@section('content')
<h2 class="judul">Daftar Balita</h2>

<div class="bar">
    <form method="GET" action="{{ route('kader.balita.index') }}" class="cari">
        <div class="fg">
            <label for="q">Cari balita</label>
            <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Nama balita...">
        </div>
        <button class="btn" type="submit">Cari</button>
    </form>
    <a class="btn" href="{{ route('kader.balita.create') }}">+ Tambah Balita</a>
</div>

<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama Balita</th><th>Tgl Lahir</th><th>JK</th><th>Nama Wali</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($balita as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b['nama'] }}</td>
                    <td>{{ date('d-m-Y', strtotime($b['tgl_lahir'])) }}</td>
                    <td>{{ $b['jk'] }}</td>
                    <td>{{ $b['wali'] }}</td>
                    <td class="aksi">
                        <a class="btn btn-outline btn-sm" href="{{ route('kader.balita.edit', $b['id']) }}">Ubah</a>
                        {{-- Tahap UI: tombol di bawah masih placeholder. Diganti form POST/DELETE pada pertemuan CRUD. --}}
                        <a class="btn btn-soft btn-sm" href="#">🔑 Reset Sandi</a>
                        <a class="btn btn-danger btn-sm" href="#">Hapus</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        @include('partials.empty', [
                            'ikon'   => '👶',
                            'judul'  => 'Belum ada data balita',
                            'teks'   => 'Tambahkan balita pertama beserta akun orang tuanya.',
                            'rute'   => route('kader.balita.create'),
                            'tombol' => '+ Tambah Balita',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
