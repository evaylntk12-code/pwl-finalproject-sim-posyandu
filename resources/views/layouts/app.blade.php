<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | SIM Posyandu Balita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    @include('partials.navbar')

    <div class="wrap">
        {{-- Sidebar menyesuaikan area: route ortu.* = Orang Tua, selain itu = Kader --}}
        @if (request()->routeIs('ortu.*'))
            @include('partials.sidebar-ortu')
        @else
            @include('partials.sidebar-kader')
        @endif

        <main>
            @if (session('success'))
                <div class="alert alert-sukses"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    @include('partials.footer')

<script src="{{ asset('js/sandi.js') }}"></script>
</body>
</html>
