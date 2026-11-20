<x-layouts.admin>
<x-slot name="title">Data Pengguna</x-slot>
<x-slot name="pageTitle">Manajemen Pengguna</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Manajemen Pengguna (ユーザー管理)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola seluruh akun administrator, instruktur, dan siswa dalam sistem</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengguna
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-xl">👥</div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-500">Total Akun</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl">🛡️</div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $stats['admins'] }}</div>
                <div class="text-xs text-gray-500">Administrator</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl">👨‍🏫</div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $stats['instructors'] }}</div>
                <div class="text-xs text-gray-500">Pengajar / Sensei</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">👨‍🎓</div>
            <div>
                <div class="text-xl font-bold text-gray-900">{{ $stats['students'] }}</div>
                <div class="text-xs text-gray-500">Siswa / Peserta</div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau email pengguna..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                </div>

                <select name="role" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>Pengajar</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Siswa</option>
                </select>

                <button type="submit" class="px-5 py-2 text-sm font-medium text-white rounded-lg transition"
                        style="background: #e63946;">Filter</button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 flex items-center">Reset</a>
                @endif
            </form>
        </div>

        {{-- Users Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Pengguna</th>
                        <th class="px-6 py-3">Peran (Role)</th>
                        <th class="px-6 py-3">Kontak</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-6 py-3">Tgl Daftar</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0"
                                     style="background: {{ match($user->role) {
                                         'admin' => 'linear-gradient(135deg, #e63946, #c1121f)',
                                         'instructor' => 'linear-gradient(135deg, #10b981, #059669)',
                                         default => 'linear-gradient(135deg, #4361ee, #3a0ca3)',
                                     } }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ match($user->role) {
                                    'admin' => 'bg-red-100 text-red-800',
                                    'instructor' => 'bg-green-100 text-green-800',
                                    default => 'bg-blue-100 text-blue-800',
                                } }}">
                                {{ match($user->role) {
                                    'admin' => '🛡️ Admin',
                                    'instructor' => '👨‍🏫 Pengajar',
                                    default => '👨‍🎓 Siswa',
                                } }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">{{ $user->phone ?? '-' }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 transition">
                                    ✏️ Edit
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus akun pengguna ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-sm">Tidak ada pengguna ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
