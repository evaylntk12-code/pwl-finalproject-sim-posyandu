@php $role = ['kader' => 'Kader', 'bidan' => 'Bidan', 'ortu' => 'Orang Tua'][auth()->user()->areaPrefix()]; @endphp

<header class="top">
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
        SIM Posyandu Balita
    </div>

    <details class="dd">
        <summary>
            <i class="fa-solid fa-circle-user"></i>
            {{ auth()->user()->name }} <small class="muted">({{ $role }})</small>
            <i class="fa-solid fa-chevron-down caret"></i>
        </summary>
        <div class="dd-menu">
            <a href="{{ route(auth()->user()->areaPrefix() . '.sandi') }}">
                <i class="fa-solid fa-key"></i> Ganti Kata Sandi
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
            </form>
        </div>
    </details>
</header>