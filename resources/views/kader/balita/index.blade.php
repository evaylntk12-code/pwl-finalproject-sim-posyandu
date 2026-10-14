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
                <th>No</th><th>Nama Balita</th><th>Tgl Lahir</th><th>Usia</th>
                <th>Jenis Kelamin</th><th>Nama Wali</th><th>No. HP</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($balita as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->name }}</td>
                    <td>{{ $b->birth_date->format('d-m-Y') }}</td>
                    <td>{{ $b->usia }} bulan</td>
                    <td>{{ $b->gender === 'P' ? 'Perempuan' : 'Laki-laki' }}</td>
                    <td>{{ $b->user->name }}</td>
                    <td>{{ $b->user->phone }}</td>
                    <td class="aksi">
                        <button type="button" class="btn btn-outline btn-ikon"
                                title="Lihat detail" aria-label="Lihat detail {{ $b->name }}"
                                onclick="document.getElementById('detail-{{ $b->id }}').showModal()">
                            <i class="fa-solid fa-eye"></i>
                        </button>

                        <a class="btn btn-outline btn-ikon" href="{{ route('kader.balita.edit', $b->id) }}"
                           title="Ubah data" aria-label="Ubah data {{ $b->name }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        {{-- Tahap UI: dua tombol di bawah masih placeholder. Diganti form POST/DELETE pada pertemuan CRUD. --}}
                        <a class="btn btn-outline btn-sm btn-reset" href="#">
                            <i class="fa-solid fa-key"></i> Reset Sandi
                        </a>

                        <a class="btn btn-danger btn-ikon" href="#"
                           title="Hapus data" aria-label="Hapus data {{ $b->name }}">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        @include('partials.empty', [
                            'ikon'  => 'fa-baby',
                            'judul' => 'Belum ada data balita',
                            'teks'  => 'Klik tombol + Tambah Balita di atas untuk menambahkan balita pertama beserta akun orang tuanya.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Popup detail: satu per balita --}}
@foreach ($balita as $b)
    <dialog id="detail-{{ $b->id }}" onclick="if (event.target === this) this.close()">
        <div class="modal-head">
            <div class="avatar {{ $b->gender === 'L' ? 'laki' : '' }}">
                <i class="fa-solid {{ $b->gender === 'P' ? 'fa-child-dress' : 'fa-child' }}"></i>
            </div>
            <div>
                <h3>{{ $b->name }}</h3>
                <span class="badge {{ $b->gender === 'P' ? 'pink' : '' }}">
                    {{ $b->gender === 'P' ? 'Perempuan' : 'Laki-laki' }}
                </span>
            </div>
        </div>

        <div class="modal-body">
            <dl class="info">
                <dt>Tanggal lahir</dt><dd>{{ $b->birth_date->format('d-m-Y') }}</dd>
                <dt>Usia</dt><dd>{{ $b->usia }} bulan</dd>
                <dt>Nama wali</dt><dd>{{ $b->user->name }}</dd>
                <dt>No. HP</dt><dd>{{ $b->user->phone }}</dd>
                <dt>Alamat</dt><dd>{{ $b->user->address }}</dd>
                <dt>Email</dt><dd>{{ $b->user->email }}</dd>
            </dl>
        </div>

        <div class="modal-foot">
            <form method="dialog"><button class="btn">Tutup</button></form>
        </div>
    </dialog>
@endforeach
@endsection