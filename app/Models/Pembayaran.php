<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = ['id_periksa', 'bukti_bayar', 'status', 'uploaded_at', 'verified_at'];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public const BELUM_BAYAR = 'belum_bayar';
    public const MENUNGGU = 'menunggu_verifikasi';
    public const LUNAS = 'lunas';

    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }
}