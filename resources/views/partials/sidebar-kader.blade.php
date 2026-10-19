@php
    // [nama route, ikon, label, pola route untuk menu aktif (boleh lebih dari satu)]
    $menu = [
        ['kader.dashboard',         'fa-house',        'Dashboard',          'kader.dashboard'],
        ['kader.balita.index',      'fa-baby',         'Data Balita & Wali', 'kader.balita.*'],
        ['kader.pemeriksaan.index', 'fa-weight-scale', 'Pemeriksaan',        'kader.pemeriksaan.*'],
        ['kader.jadwal.index',      'fa-syringe',      'Jadwal & Imunisasi', ['kader.jadwal.*', 'kader.imunisasi.*']],
        ['kader.laporan.index',     'fa-file-lines',   'Laporan',            'kader.laporan.*'],
    ];
@endphp

<nav class="side">
    @foreach ($menu as [$rute, $ikon, $label, $pola])
        <a href="{{ route($rute) }}" class="{{ request()->routeIs(...(array) $pola) ? 'on' : '' }}">
            <i class="fa-solid {{ $ikon }}"></i> {{ $label }}
        </a>
    @endforeach
</nav>