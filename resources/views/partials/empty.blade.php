{{-- Parameter: $ikon (kelas Font Awesome), $judul, $teks, (opsional) $rute & $tombol --}}
<div class="empty">
    <div class="empty-ikon"><i class="fa-solid {{ $ikon }}"></i></div>
    <h3>{{ $judul }}</h3>
    <p>{{ $teks }}</p>
    @isset($rute)
        <a class="btn" href="{{ $rute }}">{{ $tombol }}</a>
    @endisset
</div>