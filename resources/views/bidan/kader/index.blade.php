@extends('layouts.app')

@section('title', 'Akun Kader')

@section('content')
<h2 class="judul">Daftar Akun Kader</h2>

<div class="bar">
    <form method="GET" action="{{ route('bidan.kader.index') }}" class="cari">
        <div class="fg fg-cari">
            <label for="q">Cari</label>
            <input type="text" id="q" name="q" value="{{ $q }}" placeholder="Nama / email kader">
        </div>
        <button class="btn" type="submit">Terapkan</button>
        <a class="btn btn-outline" href="{{ route('bidan.kader.index') }}">Reset</a>
    </form>
    <a class="btn" href="{{ route('bidan.kader.create') }}">+ Tambah Kader</a>
</div>

<p class="hint">
    @if ($kader->total() > 0)
        Menampilkan {{ $kader->firstItem() }}-{{ $kader->lastItem() }} dari {{ $kader->total() }} kader.
    @else
        Tidak ada data yang ditampilkan.
    @endif
    @if ($q !== '')
        Hasil pencarian: <strong>{{ $q }}</strong>
    @endif
</p>

<div class="tb">
    <table>
        <thead>
            <tr><th>No</th><th>Nama Kader</th><th>Email</th><th>No. HP</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($kader as $k)
                <tr>
                    <td>{{ $kader->firstItem() + $loop->index }}</td>
                    <td>{{ $k->name }}</td>
                    <td>{{ $k->email }}</td>
                    <td>{{ $k->phone ?: '-' }}</td>
                    <td class="aksi">
                        <a class="btn btn-outline btn-ikon" href="{{ route('bidan.kader.edit', $k) }}"
                           title="Ubah data" aria-label="Ubah data {{ $k->name }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <form method="POST" action="{{ route('bidan.kader.reset-sandi', $k) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline btn-sm btn-reset"
                                    onclick="return confirm('Reset kata sandi akun kader ini?')">
                                <i class="fa-solid fa-key"></i> Reset Sandi
                            </button>
                        </form>

                        <form method="POST" action="{{ route('bidan.kader.destroy', $k) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-ikon" title="Hapus akun"
                                    aria-label="Hapus akun {{ $k->name }}"
                                    onclick="return confirm('Hapus akun kader ini? Riwayat pemeriksaan yang pernah ia catat tetap tersimpan.')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        @if ($q !== '')
                            @include('partials.empty', [
                                'ikon'   => 'fa-magnifying-glass',
                                'judul'  => 'Data tidak ditemukan',
                                'teks'   => 'Tidak ada kader yang cocok dengan pencarian. Coba kata kunci lain.',
                                'rute'   => route('bidan.kader.index'),
                                'tombol' => 'Reset Pencarian',
                            ])
                        @else
                            @include('partials.empty', [
                                'ikon'  => 'fa-user-nurse',
                                'judul' => 'Belum ada akun kader',
                                'teks'  => 'Klik tombol + Tambah Kader di atas untuk membuat akun kader pertama.',
                            ])
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $kader->links('partials.pagination') }}

{{-- Popup kata sandi sementara: hanya muncul sekali setelah Reset Sandi --}}
@if (session('sandi_sementara'))
    <dialog id="sandi-sementara">
        <div class="modal-head">
            <h3><i class="fa-solid fa-key"></i> Kata sandi sementara</h3>
        </div>
        <div class="modal-body">
            <p class="konfirmasi">Akun <b>{{ session('sandi_untuk') }}</b>. Berikan kata sandi ini kepada kader.
                Kata sandi hanya tampil sekali dan harus diganti saat masuk pertama.</p>
            <div class="sandi-kotak">{{ session('sandi_sementara') }}</div>
        </div>
        <div class="modal-foot">
            <form method="dialog"><button class="btn">Selesai</button></form>
        </div>
    </dialog>
    <script>document.getElementById('sandi-sementara').showModal();</script>
@endif
@endsection