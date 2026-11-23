@php
    // [nama route, ikon, label, pola route untuk menu aktif (boleh lebih dari satu)]
    $menu = [
        ['bidan.dashboard',         'fa-house',        'Dashboard',          'bidan.dashboard'],
        ['bidan.kader.index',       'fa-user-nurse',   'Akun Kader',         'bidan.kader.*'],
        ['bidan.balita.index',      'fa-baby',         'Data Balita',        'bidan.balita.*'],
        ['bidan.pemeriksaan.index', 'fa-weight-scale', 'Pemeriksaan',        'bidan.pemeriksaan.*'],
        ['bidan.jadwal.index',      'fa-syringe',      'Jadwal & Imunisasi', ['bidan.jadwal.*', 'bidan.imunisasi.*']],
        ['bidan.laporan.index',     'fa-file-lines',   'Laporan',            'bidan.laporan.*'],
    ];
@endphp

<nav class="side">
    @foreach ($menu as [$rute, $ikon, $label, $pola])
        <a href="{{ route($rute) }}" class="{{ request()->routeIs(...(array) $pola) ? 'on' : '' }}">
            <i class="fa-solid {{ $ikon }}"></i> {{ $label }}
        </a>
    @endforeach
</nav>