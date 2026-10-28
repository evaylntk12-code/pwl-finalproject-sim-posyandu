{{-- Navigasi halaman (pagination) sesuai gaya aplikasi. Dipanggil: {{ $data->links('partials.pagination') }} --}}
@if ($paginator->hasPages())
 <nav class="pagination" role="navigation" aria-label="Navigasi halaman">
 {{-- Tombol sebelumnya --}}
 @if ($paginator->onFirstPage())
 <span class="hal nonaktif">&laquo; Sebelumnya</span>
 @else
 <a class="hal" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Sebelumnya</a>
 @endif
 {{-- Nomor halaman --}}
 @foreach ($elements as $element)
 @if (is_string($element))
 <span class="hal nonaktif">{{ $element }}</span>
 @endif
 @if (is_array($element))
 @foreach ($element as $page => $url)
 @if ($page == $paginator->currentPage())
 <span class="hal aktif" aria-current="page">{{ $page }}</span>
 @else
 <a class="hal" href="{{ $url }}">{{ $page }}</a>
 @endif
 @endforeach
 @endif
 @endforeach
 {{-- Tombol berikutnya --}}
 @if ($paginator->hasMorePages())
 <a class="hal" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya &raquo;</a>
 @else
 <span class="hal nonaktif">Berikutnya &raquo;</span>
 @endif
 </nav>
@endif