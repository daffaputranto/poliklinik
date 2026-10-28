<?php

namespace App\Notifications;

use App\Models\Pembayaran;
use Illuminate\Notifications\Notification;

// Diterima pasien ketika admin memverifikasi pembayaran menjadi lunas.
class PembayaranDiverifikasi extends Notification
{
    public function __construct(public Pembayaran $pembayaran)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pesan' => 'Pembayaran Anda telah diverifikasi dan berstatus lunas.',
            'id_pembayaran' => $this->pembayaran->id,
        ];
    }
}