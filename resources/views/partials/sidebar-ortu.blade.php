@php
    $menu = [
        ['ortu.dashboard', '🏠', 'Dashboard',           'ortu.dashboard'],
        ['ortu.riwayat',   '📈', 'Riwayat Pemeriksaan', 'ortu.riwayat'],
    ];
@endphp

<nav class="side">
    @foreach ($menu as [$rute, $ikon, $label, $pola])
        <a href="{{ route($rute) }}" class="{{ request()->routeIs($pola) ? 'on' : '' }}">
            <span>{{ $ikon }}</span> {{ $label }}
        </a>
    @endforeach
</nav>
