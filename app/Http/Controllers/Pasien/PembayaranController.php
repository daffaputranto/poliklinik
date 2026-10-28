<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Periksa;
use App\Notifications\BuktiBayarDiupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $tagihans = Periksa::whereHas('daftarPoli', fn ($q) => $q->where('id_pasien', Auth::id()))
            ->with(['daftarPoli.jadwalPeriksa.dokter.poli', 'pembayaran'])
            ->latest('tgl_periksa')
            ->get();

        return view('pasien.pembayaran.index', compact('tagihans'));
    }

    public function upload(Request $request, string $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'bukti_bayar.required' => 'Foto bukti pembayaran wajib diupload.',
            'bukti_bayar.image'    => 'Bukti pembayaran harus berupa gambar.',
            'bukti_bayar.mimes'    => 'Format bukti pembayaran harus JPG atau PNG.',
            'bukti_bayar.max'      => 'Ukuran bukti pembayaran maksimal 2 MB.',
        ]);

        // Hanya pemeriksaan milik pasien yang login.
        $periksa = Periksa::whereHas('daftarPoli', fn ($q) => $q->where('id_pasien', Auth::id()))
            ->findOrFail($id);

        // Tagihan dibuat otomatis jika belum ada.
        $pembayaran = Pembayaran::firstOrCreate(
            ['id_periksa' => $periksa->id],
            ['status' => Pembayaran::BELUM_BAYAR]
        );

        // Upload hanya sekali, sekaligus menjamin hanya satu notifikasi terkirim.
        if ($pembayaran->status !== Pembayaran::BELUM_BAYAR) {
            return back()->with('error', 'Bukti pembayaran sudah diupload atau pembayaran sudah lunas.');
        }

        $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');

        $pembayaran->update([
            'bukti_bayar' => $path,
            'status' => Pembayaran::MENUNGGU,
            'uploaded_at' => now(),
        ]);

        // Notifikasi ke dokter yang memeriksa.
        $pembayaran->load('periksa.daftarPoli.pasien', 'periksa.daftarPoli.jadwalPeriksa.dokter');
        $dokter = $pembayaran->periksa->daftarPoli->jadwalPeriksa->dokter;
        $dokter?->notify(new BuktiBayarDiupload($pembayaran));

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}