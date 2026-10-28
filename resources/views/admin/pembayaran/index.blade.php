<x-layouts.app title="Verifikasi Pembayaran">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Verifikasi Pembayaran</h2>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Tgl Upload</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Tagihan</th>
                            <th class="px-6 py-4">Bukti</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($pembayarans as $p)
                        <tr class="border-t border-slate-100 hover:bg-slate-50">
                            <td class="px-6 py-4">{{ $p->uploaded_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $p->periksa->daftarPoli->pasien->nama ?? '-' }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($p->periksa->biaya_periksa, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $p->bukti_bayar) }}" alt="Bukti bayar"
                                        class="w-20 h-20 object-cover rounded-lg border border-slate-200">
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->status === 'lunas')
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Lunas</span>
                                @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Menunggu Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($p->status === 'menunggu_verifikasi')
                                <form action="{{ route('pembayaran.verifikasi', $p->id) }}" method="POST"
                                    onsubmit="return confirm('Konfirmasi pembayaran ini sebagai lunas?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition">
                                        <i class="fas fa-check text-xs"></i> Konfirmasi Lunas
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-slate-400">
                                    Diverifikasi {{ $p->verified_at?->format('d/m/Y H:i') }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada pembayaran yang perlu diverifikasi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>