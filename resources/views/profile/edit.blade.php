<x-layouts.app title="Profile">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Profile</h2>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 mb-6">
        <div class="card-body p-6 text-sm">
            <div class="flex gap-2 mb-2">
                <span class="w-32 text-slate-500 font-medium">Nama</span>
                <span class="font-semibold text-slate-800">{{ $user->nama }}</span>
            </div>
            <div class="flex gap-2 mb-2">
                <span class="w-32 text-slate-500 font-medium">Email</span>
                <span class="text-slate-800">{{ $user->email }}</span>
            </div>
            <div class="flex gap-2">
                <span class="w-32 text-slate-500 font-medium">Role</span>
                <span class="text-slate-800 capitalize">{{ $user->role }}</span>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Ganti Password</h3>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-5 max-w-xl">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Password Lama <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_lama" autocomplete="current-password"
                        class="w-full px-4 py-2 border-2 rounded-lg focus:border-primary focus:outline-none @error('password_lama') border-red-500 @enderror">
                    @error('password_lama')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" autocomplete="new-password"
                        class="w-full px-4 py-2 border-2 rounded-lg focus:border-primary focus:outline-none @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" autocomplete="new-password"
                        class="w-full px-4 py-2 border-2 rounded-lg focus:border-primary focus:outline-none">
                </div>

                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition">
                    <i class="fas fa-key mr-1"></i>
                    Ganti Password
                </button>
            </form>
        </div>
    </div>

</x-layouts.app>