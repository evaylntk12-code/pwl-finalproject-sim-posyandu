{{-- Kolom kata sandi dengan tombol mata.
     Parameter: $nama (wajib), $placeholder (opsional), $auto (opsional, untuk autocomplete) --}}
<div class="sandi-wrap">
    <input type="password" id="{{ $nama }}" name="{{ $nama }}"
           @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
           autocomplete="{{ $auto ?? 'off' }}"
           class="@error($nama) is-invalid @enderror">
    <button type="button" class="mata" aria-pressed="false"
            aria-label="Tampilkan kata sandi" title="Tampilkan atau sembunyikan kata sandi">
        <i class="fa-solid fa-eye"></i>
    </button>
</div>