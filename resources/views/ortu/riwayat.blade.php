@extends('layouts.app')

@section('title', 'Riwayat Pemeriksaan')

@section('content')
<h2 class="judul">Riwayat Pemeriksaan</h2>
<p class="muted sub-judul"><i class="fa-solid fa-child"></i> {{ $anak->name }}</p>

{{-- Filter tahun: hanya muncul kalau data mencakup lebih dari satu tahun --}}
@if (count($daftarTahun) > 1)
    <form method="GET" action="{{ route('ortu.riwayat') }}" class="bar">
        <div class="fg"><label for="tahun">Tahun</label>
            <select id="tahun" name="tahun">
                <option value="semua">Semua tahun</option>
                @foreach ($daftarTahun as $t)
                    <option value="{{ $t }}" @selected($tahun === $t)>{{ $t }}</option>
                @endforeach
            </select></div>
        <button class="btn" type="submit">Tampilkan</button>
    </form>
@endif

{{-- Orang tua hanya melihat data, jadi tidak ada kolom Aksi --}}
<div class="tb">
    <table>
        <thead>
            <tr>
                <th>No</th><th>Tanggal</th><th>BB (kg)</th><th>TB (cm)</th>
                <th>L. Kepala (cm)</th><th>L. Lengan (cm)</th><th>Status Gizi</th><th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayat as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->examination_date->format('d-m-Y') }}</td>
                    <td>{{ $p->weight }}</td>
                    <td>{{ $p->height }}</td>
                    <td>{{ $p->head_circumference }}</td>
                    <td>{{ $p->arm_circumference }}</td>
                    <td>@include('partials.badge-gizi', ['status' => $p->nutrition_status])</td>
                    <td>{{ $p->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        @include('partials.empty', [
                            'ikon'  => 'fa-chart-line',
                            'judul' => 'Belum ada riwayat pemeriksaan',
                            'teks'  => 'Hasil penimbangan dan pengukuran anak akan muncul di sini setelah kader melakukan pemeriksaan.',
                        ])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="hint"><i class="fa-solid fa-circle-info"></i> Data diisi oleh kader posyandu setiap pemeriksaan dan tidak bisa diubah di sini.</p>
@endsection