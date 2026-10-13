{{-- Parameter: $status --}}
@if ($status === 'Gizi Baik')
    <span class="badge gizi">Gizi Baik</span>
@else
    <span class="badge gizi pink">{{ $status }}</span>
@endif