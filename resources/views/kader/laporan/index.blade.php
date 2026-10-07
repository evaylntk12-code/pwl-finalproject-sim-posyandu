@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')
<h2 class="judul">Laporan Bulanan</h2>

<form method="GET" action="{{ route('kader.laporan.index') }}" class="bar">
    <div class="fg"><label for="bulan">Bulan</label>
        <select id="bulan" name="bulan">
            @foreach ($daftarBulan as $nilai => $label)
                <option value="{{ $nilai }}" @selected($bulan === $nilai)>{{ $label }}</option>
            @endforeach
        </select></div>

    <div class="fg"><label for="balita">Balita</label>
        <select id="balita" name="balita">
            <option value="semua">Semua balita</option>
            @foreach ($daftarBalita as $b)
                <option value="{{ $b['nama'] }}" @selected($balitaDipilih === $b['nama'])>{{ $b['nama'] }}</option>
            @endforeach
        </select></div>

    <button class="btn" type="submit">Tampilkan</button>
    <button class="btn btn-outline" type="button" onclick="window.print()">🖨️ Cetak PDF</button>
</form>

<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Nama</th><th>Tanggal</th><th>BB</th><th>TB</th>
                <th>L. Kepala</th><th>L. Lengan</th><th>Status Gizi</th><th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $p)
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
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        @include('partials.empty', [
                            'ikon'  => '📄',
                            'judul' => 'Tidak ada data pada periode ini',
                            'teks'  => 'Coba pilih bulan atau balita yang lain.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="hint">📄 Laporan bersifat arsip: data hanya bisa dilihat dan dicetak, tidak bisa diubah.</p>
@endsection
