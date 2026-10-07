@php
    // [nama route, ikon, label, pola route untuk menu aktif]
    $menu = [
        ['kader.dashboard',         '🏠', 'Dashboard',          'kader.dashboard'],
        ['kader.balita.index',      '👶', 'Data Balita & Wali', 'kader.balita.*'],
        ['kader.pemeriksaan.index', '⚖️', 'Pemeriksaan',        'kader.pemeriksaan.*'],
        ['kader.jadwal.index',      '💉', 'Jadwal & Imunisasi', 'kader.jadwal.*'],
        ['kader.laporan.index',     '📄', 'Laporan',            'kader.laporan.*'],
    ];
@endphp

<nav class="side">
    @foreach ($menu as [$rute, $ikon, $label, $pola])
        <a href="{{ route($rute) }}" class="{{ request()->routeIs($pola) ? 'on' : '' }}">
            <span>{{ $ikon }}</span> {{ $label }}
        </a>
    @endforeach
</nav>
