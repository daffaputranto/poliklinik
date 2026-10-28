<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;

class RiwayatPendaftaranController extends Controller
{
    public function index()
    {
        // Eager loading: jumlah query tetap, berapa pun banyaknya pendaftaran.
        $riwayats = DaftarPoli::where('id_pasien', Auth::id())
            ->with(['jadwalPeriksa.dokter.poli', 'periksa'])
            ->latest()
            ->get();

        return view('pasien.riwayat.index', compact('riwayats'));
    }

    public function show(string $id)
    {
        // Dibatasi ke pendaftaran milik pasien yang login.
        $daftar = DaftarPoli::where('id_pasien', Auth::id())
            ->with([
                'jadwalPeriksa.dokter.poli',
                'periksa.detailPeriksas.obat',
                'periksa.pembayaran',
            ])
            ->findOrFail($id);

        if (! $daftar->periksa) {
            return redirect()->route('pasien.riwayat.index')
                ->with('error', 'Pendaftaran ini belum diperiksa oleh dokter.');
        }

        return view('pasien.riwayat.show', compact('daftar'));
    }
}