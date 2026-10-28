<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PasienExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection(): \Illuminate\Support\Collection
    {
        return User::where('role', 'pasien')->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Pasien', 'Email', 'No. KTP', 'No. HP', 'No. RM', 'Alamat'];
    }

    public function map($pasien): array
    {
        static $no = 0;

        return [
            ++$no,
            $pasien->nama,
            $pasien->email,
            (string) $pasien->no_ktp,
            (string) $pasien->no_hp,
            $pasien->no_rm,
            $pasien->alamat,
        ];
    }
}