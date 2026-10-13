@php $isOrtu = request()->routeIs('ortu.*'); @endphp

<header class="top">
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
        SIM Posyandu Balita
    </div>

    <details class="dd">
        <summary>
            <i class="fa-solid fa-circle-user"></i>
            {{ $isOrtu ? 'Nama Orang Tua' : 'Nama Kader' }}
            <i class="fa-solid fa-chevron-down caret"></i>
        </summary>
        <div class="dd-menu">
            <a href="{{ route($isOrtu ? 'ortu.sandi' : 'kader.sandi') }}">
                <i class="fa-solid fa-key"></i> Ganti Kata Sandi
            </a>
            {{-- Tahap UI: Logout masih link. Diganti form POST pada pertemuan authentication. --}}
            <a href="{{ route('login') }}">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </details>
</header>
