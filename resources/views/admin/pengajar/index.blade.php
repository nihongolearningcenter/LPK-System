<x-layouts.admin>
<x-slot name="title">Data Pengajar</x-slot>
<x-slot name="pageTitle">Data Pengajar / Sensei</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Data Pengajar (講師一覧)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola instruktur dan pengajar bahasa Jepang & vokasi</p>
        </div>
        <a href="{{ route('admin.pengajar.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengajar
        </a>
    </div>

    {{-- Search & Table Container --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email pengajar..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                </div>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg transition"
                        style="background: #e63946;">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.pengajar.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Pengajar</th>
                        <th class="px-6 py-3">Keahlian (Speciality)</th>
                        <th class="px-6 py-3">Kontak</th>
                        <th class="px-4 py-3 text-center">Kelas Mengajar</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($instructors as $inst)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0"
                                     style="background: linear-gradient(135deg, #10b981, #059669);">
                                    {{ strtoupper(substr($inst->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $inst->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $inst->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $inst->instructorProfile?->speciality ?? 'Pengajar Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">
                            {{ $inst->instructorProfile?->phone ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                🏫 {{ $inst->taughtClasses->count() }} Kelas
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $inst->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $inst->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $inst->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.pengajar.edit', $inst) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 transition">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('admin.pengajar.destroy', $inst) }}" onsubmit="return confirm('Hapus data pengajar ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">👨‍🏫</div>
                            <p class="text-sm">Belum ada data pengajar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($instructors->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $instructors->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
