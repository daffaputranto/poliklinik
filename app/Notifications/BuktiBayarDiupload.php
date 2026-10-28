<?php

namespace App\Notifications;

use App\Models\Pembayaran;
use Illuminate\Notifications\Notification;

// Diterima dokter ketika pasien mengupload bukti bayar.
class BuktiBayarDiupload extends Notification
{
    public function __construct(public Pembayaran $pembayaran)
    {
    }

    // Disimpan lewat database channel (tabel notifications).
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $namaPasien = $this->pembayaran->periksa->daftarPoli->pasien->nama ?? 'Pasien';

        return [
            'pesan' => "{$namaPasien} telah mengupload bukti pembayaran.",
            'id_pembayaran' => $this->pembayaran->id,
        ];
    }
}