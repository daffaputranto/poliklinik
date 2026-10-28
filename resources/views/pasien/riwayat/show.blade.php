<x-layouts.app title="Detail Pemeriksaan">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pasien.riwayat.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Detail Pemeriksaan</h2>
    </div>

    @php $periksa = $daftar->periksa; @endphp

    <div class="w-full flex flex-col gap-5">

        <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
            <div class="card-body p-0">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800">Informasi Pemeriksaan</h3>
                </div>
                <div class="px-6 py-5 flex flex-col gap-3 text-sm">
                    <div class="flex gap-2"><span class="w-36 text-slate-500 font-medium">Poli</span>
                        <span>{{ $daftar->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</span></div>
                    <div class="flex gap-2"><span class="w-36 text-slate-500 font-medium">Dokter</span>
                        <span>{{ $daftar->jadwalPeriksa->dokter->nama }}</span></div>
                    <div class="flex gap-2"><span class="w-36 text-slate-500 font-medium">No. Antrian</span>
                        <span>{{ $daftar->no_antrian }}</span></div>
                    <div class="flex gap-2"><span class="w-36 text-slate-500 font-medium">Keluhan</span>
                        <span>{{ $daftar->keluhan }}</span></div>
                    <div class="flex gap-2"><span class="w-36 text-slate-500 font-medium">Tanggal Periksa</span>
                        <span>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') }}</span></div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
            <div class="card-body p-0">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800">Catatan Dokter</h3>
                </div>
                <div class="px-6 py-5 text-sm text-slate-600">
                    {{ $periksa->catatan ?: 'Tidak ada catatan' }}
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
            <div class="card-body p-0">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800">Obat yang Diresepkan</h3>
                </div>
                @if($periksa->detailPeriksas->count() > 0)
                <table class="table table-zebra w-full">
                    <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Nama Obat</th>
                            <th class="px-6 py-4">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periksa->detailPeriksas as $i => $detail)
                        <tr>
                            <td class="px-6 py-4 text-slate-500">{{ $i + 1 }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $detail->obat->nama_obat }}</td>
                            <td class="px-6 py-4 text-slate-500">Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="px-6 py-5 text-sm text-slate-400">Tidak ada obat yang diresepkan</div>
                @endif
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
            <div class="card-body px-6 py-5 flex items-center justify-between">
                <span class="font-bold text-slate-700">Total Biaya</span>
                <span class="text-2xl font-bold text-[#2d4499]">
                    Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                </span>
            </div>
        </div>

    </div>

</x-layouts.app>