<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use App\Services\AntrianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PoliController extends Controller
{
    public function get()
    {
        $user = Auth::user();
        $polis = Poli::all();
        $jadwal = JadwalPeriksa::with('dokter', 'dokter.poli')->get();

        return view('pasien.daftar', [
            'user' => $user,
            'polis' => $polis,
            'jadwals' => $jadwal,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_poli' => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'nullable|string',
        ]);

        $idPasien = Auth::id();

        // Ketentuan: pasien tidak boleh mendaftar ke dua poli sekaligus.
        // Pendaftaran baru hanya boleh setelah pemeriksaan sebelumnya selesai.
        if (AntrianService::antrianAktif($idPasien)) {
            return back()->withInput()->withErrors([
                'id_jadwal' => 'Anda masih memiliki antrian aktif. Pendaftaran baru hanya bisa dilakukan setelah pemeriksaan selesai.',
            ]);
        }

        // Jadwal harus milik dokter dari poli yang dipilih.
        $jadwal = JadwalPeriksa::with('dokter')->findOrFail($request->id_jadwal);
        if ((int) $jadwal->dokter?->id_poli !== (int) $request->id_poli) {
            return back()->withInput()->withErrors([
                'id_jadwal' => 'Jadwal tidak sesuai dengan poli yang dipilih.',
            ]);
        }

        DB::transaction(function () use ($request, $idPasien) {
            $noTerakhir = DaftarPoli::where('id_jadwal', $request->id_jadwal)->max('no_antrian');

            DaftarPoli::create([
                'id_pasien' => $idPasien,
                'id_jadwal' => $request->id_jadwal,
                'keluhan' => $request->keluhan ?? '',
                'no_antrian' => ((int) $noTerakhir) + 1,
            ]);
        });

        // Kirim update nomor antrian ke dashboard pasien (real-time).
        AntrianService::siarkan((int) $request->id_jadwal);

        return redirect()
            ->back()
            ->with('message', 'Berhasil Mendaftar ke Poli')
            ->with('type', 'success');
    }
}