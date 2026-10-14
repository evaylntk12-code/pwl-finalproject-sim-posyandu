@php $isOrtu = request()->routeIs('ortu.*'); @endphp

<header class="top">
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
        SIM Posyandu Balita
    </div>

    <details class="dd">
        <summary>
            <i class="fa-solid fa-circle-user"></i>
            {{ auth()->user()->name }}
            <i class="fa-solid fa-chevron-down caret"></i>
        </summary>
        <div class="dd-menu">
            <a href="{{ route($isOrtu ? 'ortu.sandi' : 'kader.sandi') }}">
                <i class="fa-solid fa-key"></i> Ganti Kata Sandi
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
            </form>
        </div>
    </details>
</header>
