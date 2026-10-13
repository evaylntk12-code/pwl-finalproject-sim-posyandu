@php
    $menu = [
        ['ortu.dashboard', 'fa-house',      'Dashboard',           'ortu.dashboard'],
        ['ortu.riwayat',   'fa-chart-line', 'Riwayat Pemeriksaan', 'ortu.riwayat'],
    ];
@endphp

<nav class="side">
    @foreach ($menu as [$rute, $ikon, $label, $pola])
        <a href="{{ route($rute) }}" class="{{ request()->routeIs($pola) ? 'on' : '' }}">
            <i class="fa-solid {{ $ikon }}"></i> {{ $label }}
        </a>
    @endforeach
</nav>