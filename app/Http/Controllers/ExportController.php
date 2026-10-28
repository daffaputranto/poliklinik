<?php

namespace App\Http\Controllers;

use App\Exports\DokterExport;
use App\Exports\JadwalPeriksaExport;
use App\Exports\ObatExport;
use App\Exports\PasienExport;
use App\Exports\RiwayatPasienExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function dokter()
    {
        return Excel::download(new DokterExport, 'data-dokter.xlsx');
    }

    public function pasien()
    {
        return Excel::download(new PasienExport, 'data-pasien.xlsx');
    }

    public function obat()
    {
        return Excel::download(new ObatExport, 'data-obat.xlsx');
    }

    public function jadwalPeriksa()
    {
        return Excel::download(new JadwalPeriksaExport(Auth::id()), 'jadwal-periksa.xlsx');
    }

    public function riwayatPasien()
    {
        return Excel::download(new RiwayatPasienExport(Auth::id()), 'riwayat-pasien.xlsx');
    }
}