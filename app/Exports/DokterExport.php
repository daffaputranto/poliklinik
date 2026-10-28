<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DokterExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection(): \Illuminate\Support\Collection
    {
        return User::where('role', 'dokter')->with('poli')->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Dokter', 'Email', 'No. KTP', 'No. HP', 'Alamat', 'Poli'];
    }

    public function map($dokter): array
    {
        static $no = 0;

        return [
            ++$no,
            $dokter->nama,
            $dokter->email,
            (string) $dokter->no_ktp,
            (string) $dokter->no_hp,
            $dokter->alamat,
            $dokter->poli?->nama_poli ?? '-',
        ];
    }
}