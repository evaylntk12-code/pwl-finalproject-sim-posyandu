@php $isOrtu = request()->routeIs('ortu.*'); @endphp

<header class="top">
    <div class="logo">
        <span class="logo-ikon">🌼</span>
        SIM Posyandu Balita
    </div>

    <details class="dd">
        <summary>{{ $isOrtu ? 'Nama Orang Tua' : 'Nama Kader' }} ▾</summary>
        <div class="dd-menu">
            <a href="{{ route($isOrtu ? 'ortu.sandi' : 'kader.sandi') }}">🔑 Ganti Kata Sandi</a>
            <a href="{{ route('login') }}">🚪 Logout</a>
        </div>
    </details>
</header>
