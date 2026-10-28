<?php

namespace App\Exports;

use App\Models\Periksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatPasienExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private int $idDokter)
    {
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return Periksa::whereHas('daftarPoli.jadwalPeriksa', fn ($q) => $q->where('id_dokter', $this->idDokter))
            ->with(['daftarPoli.pasien', 'detailPeriksas.obat'])
            ->orderByDesc('tgl_periksa')
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Periksa', 'Nama Pasien', 'No. Antrian', 'Keluhan', 'Catatan', 'Obat', 'Biaya (Rp)'];
    }

    public function map($periksa): array
    {
        static $no = 0;

        return [
            ++$no,
            $periksa->tgl_periksa,
            $periksa->daftarPoli->pasien->nama ?? '-',
            $periksa->daftarPoli->no_antrian,
            $periksa->daftarPoli->keluhan,
            $periksa->catatan,
            $periksa->detailPeriksas->pluck('obat.nama_obat')->filter()->implode(', '),
            $periksa->biaya_periksa,
        ];
    }
}