<?php

namespace App\Services;

use App\Events\AntrianDiperbarui;
use App\Models\DaftarPoli;

class AntrianService
{
    // Nomor yang sedang dilayani per jadwal = nomor terkecil yang belum diperiksa.
    // Hasil: [id_jadwal => nomor]. Satu query untuk semua jadwal.
    public static function nomorPerJadwal(): array
    {
        return DaftarPoli::doesntHave('periksa')
            ->selectRaw('id_jadwal, MIN(no_antrian) as nomor')
            ->groupBy('id_jadwal')
            ->pluck('nomor', 'id_jadwal')
            ->map(fn ($n) => (int) $n)
            ->all();
    }

    public static function nomorUntuk(int $idJadwal): ?int
    {
        $nomor = DaftarPoli::where('id_jadwal', $idJadwal)
            ->doesntHave('periksa')
            ->min('no_antrian');

        return $nomor === null ? null : (int) $nomor;
    }

    // Pendaftaran milik pasien yang belum diperiksa (antrian aktif).
    public static function antrianAktif(int $idPasien): ?DaftarPoli
    {
        return DaftarPoli::where('id_pasien', $idPasien)
            ->doesntHave('periksa')
            ->with('jadwalPeriksa.dokter.poli')
            ->latest()
            ->first();
    }

    // Kirim update ke semua dashboard pasien lewat Reverb.
    // Jika Reverb mati, error hanya dicatat agar pendaftaran/pemeriksaan tidak gagal.
    public static function siarkan(int $idJadwal): void
    {
        try {
            AntrianDiperbarui::dispatch($idJadwal, self::nomorUntuk($idJadwal));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}