<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ObatExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection(): \Illuminate\Support\Collection
    {
        return Obat::orderBy('nama_obat')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Obat', 'Kemasan', 'Stok', 'Harga (Rp)'];
    }

    public function map($obat): array
    {
        static $no = 0;

        return [++$no, $obat->nama_obat, $obat->kemasan, $obat->stok, $obat->harga];
    }
}