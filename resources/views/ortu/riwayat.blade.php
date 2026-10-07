@extends('layouts.app')

@section('title', 'Riwayat Pemeriksaan')

@section('content')
<h2 class="judul">Riwayat Pemeriksaan</h2>

{{-- Orang tua hanya melihat data, jadi tidak ada kolom Aksi --}}
<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Tanggal</th><th>BB</th><th>TB</th>
                <th>L. Kepala</th><th>L. Lengan</th><th>Status Gizi</th><th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayat as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d-m-Y', strtotime($p['tanggal'])) }}</td>
                    <td>{{ $p['bb'] }}</td>
                    <td>{{ $p['tb'] }}</td>
                    <td>{{ $p['lk'] }}</td>
                    <td>{{ $p['ll'] }}</td>
                    <td>@include('partials.badge-gizi', ['status' => $p['status_gizi']])</td>
                    <td>{{ $p['catatan'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        @include('partials.empty', [
                            'ikon'  => '📈',
                            'judul' => 'Belum ada riwayat pemeriksaan',
                            'teks'  => 'Hasil penimbangan dan pengukuran anak akan muncul di sini setelah kader melakukan pemeriksaan.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
