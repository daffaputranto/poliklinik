<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Notifications\PembayaranDiverifikasi;

class PembayaranController extends Controller
{
    // Tagihan yang sudah ada bukti bayar. Yang menunggu verifikasi tampil paling atas.
    public function index()
    {
        $pembayarans = Pembayaran::whereIn('status', [Pembayaran::MENUNGGU, Pembayaran::LUNAS])
            ->with('periksa.daftarPoli.pasien')
            ->orderByRaw("CASE status WHEN 'menunggu_verifikasi' THEN 0 ELSE 1 END")
            ->latest('uploaded_at')
            ->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verifikasi(Pembayaran $pembayaran)
    {
        if ($pembayaran->status !== Pembayaran::MENUNGGU) {
            return back()->with('error', 'Pembayaran ini tidak sedang menunggu verifikasi.');
        }

        $pembayaran->update([
            'status' => Pembayaran::LUNAS,
            'verified_at' => now(),
        ]);

        // Notifikasi ke pasien (satu kali, karena status hanya bisa berubah sekali).
        $pembayaran->load('periksa.daftarPoli.pasien');
        $pembayaran->periksa->daftarPoli->pasien?->notify(new PembayaranDiverifikasi($pembayaran));

        return back()->with('success', 'Pembayaran berhasil diverifikasi menjadi lunas.');
    }
}