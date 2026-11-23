<?php

namespace App\Http\Controllers\Concerns;

trait MembuatSandiSementara
{
    // 8 karakter acak, tanpa karakter yang mudah tertukar (0/O, 1/I)
    private function sandiSementara(): string
    {
        $huruf = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        return collect(range(1, 8))
            ->map(fn () => $huruf[random_int(0, strlen($huruf) - 1)])
            ->implode('');
    }
}