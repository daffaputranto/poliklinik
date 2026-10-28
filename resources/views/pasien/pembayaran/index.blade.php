<x-layouts.app title="Pembayaran">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Pembayaran</h2>

    @if ($errors->any())
    <div class="p-4 mb-6 rounded-lg bg-red-50 text-red-700">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Tanggal Periksa</th>
                            <th class="px-6 py-4">Poli / Dokter</th>
                            <th class="px-6 py-4">Tagihan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Bukti Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($tagihans as $periksa)
                        @php
                            $pay = $periksa->pembayaran;
                            $status = $pay->status ?? 'belum_bayar';
                        @endphp
                        <tr class="border-t border-slate-100 align-top">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold">{{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $periksa->daftarPoli->jadwalPeriksa->dokter->nama }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($status === 'lunas')
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Lunas</span>
                                @elseif($status === 'menunggu_verifikasi')
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Menunggu Verifikasi</span>
                                @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($status === 'belum_bayar')
                                <form action="{{ route('pasien.pembayaran.upload', $periksa->id) }}" method="POST"
                                    enctype="multipart/form-data" class="flex flex-col gap-2">
                                    @csrf
                                    <input type="file" name="bukti_bayar" accept="image/png,image/jpeg"
                                        class="file-input file-input-bordered file-input-sm w-full max-w-xs" required>
                                    <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition w-fit">
                                        <i class="fas fa-upload mr-1"></i> Upload Bukti
                                    </button>
                                </form>
                                @else
                                <a href="{{ asset('storage/' . $pay->bukti_bayar) }}" target="_blank"
                                    class="text-indigo-600 text-xs font-semibold hover:underline">
                                    <i class="fas fa-image mr-1"></i> Lihat bukti
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada tagihan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>