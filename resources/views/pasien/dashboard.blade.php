<x-layouts.app title="Pasien Dashboard">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Dashboard Pasien</h2>

    {{-- Banner antrian aktif --}}
    @if($antrianAktif)
    @php $jd = $antrianAktif->jadwalPeriksa; @endphp
    <div class="rounded-2xl bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-6 mb-8 shadow-md
                flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        <div class="space-y-3">
            <p class="text-xs font-bold uppercase tracking-widest opacity-80">Antrian Aktif Anda</p>

            <div>
                <p class="text-[11px] opacity-70">Poliklinik</p>
                <p class="text-lg font-bold">{{ $jd->dokter->poli->nama_poli ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] opacity-70">Dokter</p>
                <p class="font-semibold">{{ $jd->dokter->nama }}</p>
            </div>
            <div>
                <p class="text-[11px] opacity-70">Jadwal Periksa</p>
                <p class="font-semibold">
                    {{ $jd->hari }}
                    ({{ \Carbon\Carbon::parse($jd->jam_mulai)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($jd->jam_selesai)->format('H:i') }})
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="bg-white/20 rounded-2xl px-8 py-4 text-center">
                <p class="text-[11px] opacity-80">Nomor Anda</p>
                <p class="text-4xl font-extrabold">{{ $antrianAktif->no_antrian }}</p>
            </div>
            <div class="bg-white text-indigo-700 rounded-2xl px-8 py-4 text-center">
                <p class="text-[11px] font-bold uppercase">Sedang Dilayani</p>
                <p id="banner-nomor" class="text-4xl font-extrabold">{{ $nomorSekarang[$antrianAktif->id_jadwal] ?? '-' }}</p>
                <p class="text-[10px] text-emerald-600 font-semibold">
                    <span id="live-dot" class="inline-block w-2 h-2 rounded-full bg-slate-400 mr-1"></span>
                    <span id="live-text">Menghubungkan...</span>
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 mb-8 text-center text-slate-500">
        <i class="fas fa-ticket text-2xl mb-2 block"></i>
        Anda tidak memiliki antrian aktif.
        <a href="{{ route('pasien.daftar') }}" class="text-indigo-600 font-semibold hover:underline">Daftar ke poli</a>
    </div>
    @endif

    {{-- Tabel jadwal dan nomor yang sedang dilayani --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="font-bold text-slate-800">Jadwal Poliklinik</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Poli</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Hari</th>
                            <th class="px-6 py-4">Jam Periksa</th>
                            <th class="px-6 py-4">Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($jadwals as $jadwal)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $jadwal->dokter->poli->nama_poli ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $jadwal->dokter->nama }}</td>
                            <td class="px-6 py-4">{{ $jadwal->hari }}</td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span data-nomor-jadwal="{{ $jadwal->id }}"
                                    class="inline-block min-w-8 text-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 font-bold">
                                    {{ $nomorSekarang[$jadwal->id] ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">Belum ada jadwal poliklinik</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live update nomor antrian lewat Laravel Echo (Reverb), tanpa reload halaman.
        window.addEventListener('load', function () {
            const bannerJadwal = {{ $antrianAktif ? (int) $antrianAktif->id_jadwal : 'null' }};
            const dot = document.getElementById('live-dot');
            const text = document.getElementById('live-text');

            function setLive(on, label) {
                if (!dot) return;
                dot.className = 'inline-block w-2 h-2 rounded-full mr-1 ' + (on ? 'bg-emerald-500' : 'bg-slate-400');
                text.textContent = label;
            }

            if (!window.Echo) {
                setLive(false, 'Real-time nonaktif');
                return;
            }

            window.Echo.channel('antrian').listen('.antrian.diperbarui', function (e) {
                const nomor = e.nomor ?? '-';

                document.querySelectorAll('[data-nomor-jadwal="' + e.idJadwal + '"]').forEach(function (el) {
                    el.textContent = nomor;
                });

                if (bannerJadwal === e.idJadwal) {
                    const banner = document.getElementById('banner-nomor');
                    if (banner) banner.textContent = nomor;
                }
            });

            setLive(true, 'Live update');
        });
    </script>
    @endpush

</x-layouts.app>