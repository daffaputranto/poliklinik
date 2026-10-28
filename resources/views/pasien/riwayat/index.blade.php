<x-layouts.app title="Riwayat Pendaftaran">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Riwayat Pendaftaran Poli</h2>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Poli</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Jadwal</th>
                            <th class="px-6 py-4">Tgl Daftar</th>
                            <th class="px-6 py-4">No Antrian</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($riwayats as $r)
                        <tr class="border-t border-slate-100 hover:bg-slate-50">
                            <td class="px-6 py-4">{{ $r->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $r->jadwalPeriksa->dokter->nama }}</td>
                            <td class="px-6 py-4">
                                {{ $r->jadwalPeriksa->hari }}
                                ({{ \Carbon\Carbon::parse($r->jadwalPeriksa->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($r->jadwalPeriksa->jam_selesai)->format('H:i') }})
                            </td>
                            <td class="px-6 py-4">{{ $r->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $r->no_antrian }}</td>
                            <td class="px-6 py-4">
                                @if($r->periksa)
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Sudah Diperiksa</span>
                                @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($r->periksa)
                                <a href="{{ route('pasien.riwayat.show', $r->id) }}"
                                    class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                                    <i class="fas fa-eye text-xs"></i> Detail
                                </a>
                                @else
                                <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada riwayat pendaftaran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>