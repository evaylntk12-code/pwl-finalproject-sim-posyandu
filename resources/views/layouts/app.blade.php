<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | SIM Posyandu Balita</title>
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
            @yield('content')
        </main>
    </div>

    @include('partials.footer')

</body>
</html>
