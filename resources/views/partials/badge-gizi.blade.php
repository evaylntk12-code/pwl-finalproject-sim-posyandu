{{-- Parameter: $status --}}
@if ($status === 'Gizi Baik')
    <span class="badge">Gizi Baik</span>
@else
    <span class="badge pink">{{ $status }}</span>
@endif
