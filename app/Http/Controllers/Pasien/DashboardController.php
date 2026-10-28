<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use App\Services\AntrianService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $antrianAktif = AntrianService::antrianAktif(Auth::id());

        $jadwals = JadwalPeriksa::with('dokter.poli')
            ->orderByRaw("CASE hari
                WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3
                WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6
                ELSE 7 END")
            ->orderBy('jam_mulai')
            ->get();

        $nomorSekarang = AntrianService::nomorPerJadwal();

        return view('pasien.dashboard', compact('antrianAktif', 'jadwals', 'nomorSekarang'));
    }
}