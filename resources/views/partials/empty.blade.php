{{-- Tampilan data kosong. Parameter: $ikon, $judul, $teks, (opsional) $rute & $tombol --}}
<div class="empty">
    <div class="empty-ikon">{{ $ikon }}</div>
    <h3>{{ $judul }}</h3>
    <p>{{ $teks }}</p>
    @isset($rute)
        <a class="btn" href="{{ $rute }}">{{ $tombol }}</a>
    @endisset
</div>
