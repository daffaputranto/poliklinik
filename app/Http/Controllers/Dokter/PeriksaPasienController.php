<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\Periksa;
use App\Services\AntrianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        // Hanya pendaftaran pada jadwal milik dokter ini, dan belum pernah diperiksa.
        $daftar = DaftarPoli::whereHas('jadwalPeriksa', fn ($q) => $q->where('id_dokter', Auth::id()))
            ->doesntHave('periksa')
            ->findOrFail($request->id_daftar_poli);

        $obatIds = json_decode($request->obat_json, true);

        DB::transaction(function () use ($request, $daftar, $obatIds) {
            $periksa = Periksa::create([
                'id_daftar_poli' => $daftar->id,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $request->biaya_periksa + 150000,
            ]);

            if (is_array($obatIds)) {
                foreach ($obatIds as $idObat) {
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $idObat,
                    ]);
                }
            }

            // Tagihan dibuat otomatis setelah pemeriksaan selesai.
            Pembayaran::create([
                'id_periksa' => $periksa->id,
                'status' => Pembayaran::BELUM_BAYAR,
            ]);
        });

        // Nomor yang sedang dilayani maju ke antrian berikutnya (real-time).
        AntrianService::siarkan($daftar->id_jadwal);

        return redirect()
            ->route('periksa-pasien.index')
            ->with('success', 'Data periksa berhasil disimpan.');
    }
}