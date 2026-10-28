<x-layouts.app title="Notifikasi">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Notifikasi</h2>

        @if($notifications->whereNull('read_at')->count() > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit"
                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold transition">
                <i class="fas fa-check-double mr-1"></i>
                Tandai semua sudah dibaca
            </button>
        </form>
        @endif
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-0">

            @forelse($notifications as $notif)
            <div class="flex items-start justify-between gap-4 px-6 py-4 border-b border-slate-100
                        {{ $notif->read_at ? 'bg-white' : 'bg-indigo-50' }}">

                <div class="flex items-start gap-3">
                    <span class="mt-1 w-2.5 h-2.5 rounded-full {{ $notif->read_at ? 'bg-slate-300' : 'bg-indigo-500' }}"></span>
                    <div>
                        <p class="text-sm {{ $notif->read_at ? 'text-slate-600' : 'text-slate-900 font-semibold' }}">
                            {{ $notif->data['pesan'] ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $notif->created_at->format('d/m/Y H:i') }}
                            ({{ $notif->created_at->diffForHumans() }})
                        </p>
                    </div>
                </div>

                @if(is_null($notif->read_at))
                <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition whitespace-nowrap">
                        Tandai dibaca
                    </button>
                </form>
                @else
                <span class="text-xs text-slate-400 whitespace-nowrap">Sudah dibaca</span>
                @endif
            </div>
            @empty
            <div class="text-center py-12 text-slate-400">
                <i class="fas fa-bell-slash text-3xl mb-3 block"></i>
                Belum ada notifikasi
            </div>
            @endforelse

        </div>
    </div>

</x-layouts.app>