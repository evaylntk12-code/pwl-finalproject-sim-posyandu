<?php

namespace App\Support;

/**
 * Data sementara (array) untuk tahap UI / Blade.
 * Akan diganti dengan Model Eloquent + database pada Pertemuan 4.
 */
class DummyData
{
    // Bulan yang dianggap "bulan berjalan" (format Y-m)
    const BULAN_BERJALAN = '2026-10';

    public static function balita(): array
    {
        return [
            ['id' => 1, 'nama' => 'Nama Balita 1', 'tgl_lahir' => '2025-03-12', 'jk' => 'P', 'wali' => 'Nama Wali 1', 'hp' => '0812-3456-7890', 'email' => 'wali1@email.com'],
            ['id' => 2, 'nama' => 'Nama Balita 2', 'tgl_lahir' => '2024-08-20', 'jk' => 'L', 'wali' => 'Nama Wali 2', 'hp' => '0813-1111-2222', 'email' => 'wali2@email.com'],
            ['id' => 3, 'nama' => 'Nama Balita 3', 'tgl_lahir' => '2025-01-05', 'jk' => 'P', 'wali' => 'Nama Wali 3', 'hp' => '0857-3333-4444', 'email' => 'wali3@email.com'],
        ];
    }

    public static function pemeriksaan(): array
    {
        return [
            ['id' => 1, 'balita' => 'Nama Balita 1', 'tanggal' => '2026-10-11', 'bb' => 9.5, 'tb' => 74, 'lk' => 45, 'll' => 14, 'status_gizi' => 'Gizi Baik', 'catatan' => '-'],
            ['id' => 2, 'balita' => 'Nama Balita 2', 'tanggal' => '2026-10-11', 'bb' => 10.2, 'tb' => 78, 'lk' => 46, 'll' => 14.5, 'status_gizi' => 'Gizi Baik', 'catatan' => '-'],
            ['id' => 3, 'balita' => 'Nama Balita 3', 'tanggal' => '2026-10-11', 'bb' => 7.4, 'tb' => 68, 'lk' => 43, 'll' => 12.5, 'status_gizi' => 'Gizi Kurang', 'catatan' => 'Perlu pantauan'],
            ['id' => 4, 'balita' => 'Nama Balita 1', 'tanggal' => '2026-09-13', 'bb' => 9.2, 'tb' => 73, 'lk' => 45, 'll' => 14, 'status_gizi' => 'Gizi Baik', 'catatan' => '-'],
            ['id' => 5, 'balita' => 'Nama Balita 2', 'tanggal' => '2026-09-13', 'bb' => 9.9, 'tb' => 77, 'lk' => 46, 'll' => 14.2, 'status_gizi' => 'Gizi Baik', 'catatan' => '-'],
        ];
    }

    public static function jadwal(): array
    {
        return [
            ['id' => 1, 'tanggal' => '2026-10-11', 'waktu' => '09.00 - 12.00', 'keterangan' => 'Penimbangan dan imunisasi', 'status' => 'Terjadwal'],
            ['id' => 2, 'tanggal' => '2026-09-13', 'waktu' => '09.00 - 12.00', 'keterangan' => 'Penimbangan rutin', 'status' => 'Selesai'],
        ];
    }

    public static function imunisasi(): array
    {
        return [
            ['id' => 1, 'balita' => 'Nama Balita 1', 'jenis' => 'Campak', 'tanggal' => '2026-10-11', 'berikutnya' => '2026-11-11', 'status' => 'Terjadwal'],
            ['id' => 2, 'balita' => 'Nama Balita 2', 'jenis' => 'DPT', 'tanggal' => '2026-09-13', 'berikutnya' => '2026-10-11', 'status' => 'Selesai'],
        ];
    }

    /** Cari satu baris berdasarkan id; null jika tidak ada. */
    public static function cari(array $data, int $id): ?array
    {
        foreach ($data as $baris) {
            if ($baris['id'] === $id) {
                return $baris;
            }
        }
        return null;
    }
}
