<x-layouts.admin>
<x-slot name="title">Data Siswa</x-slot>
<x-slot name="pageTitle">Data Siswa</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Data Siswa</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua akun siswa terdaftar</p>
        </div>
        <a href="{{ route('admin.siswa.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Siswa
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                </div>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg transition"
                        style="background: #e63946;">Cari</button>
                @if(request('search'))
                <a href="{{ route('admin.siswa.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Siswa</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Telepon</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Terdaftar</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0"
                                     style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $student->name }}</div>
                                    @if($student->studentProfile?->nik)
                                    <div class="text-xs text-gray-400">NIK: {{ $student->studentProfile->nik }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $student->email }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $student->studentProfile?->phone ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $student->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $student->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $student->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.siswa.show', $student) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                    👁️ Lihat
                                </a>
                                <form method="POST" action="{{ route('admin.siswa.destroy', $student) }}" onsubmit="return confirm('Hapus siswa ini?')">
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
                            <div class="text-4xl mb-3">👨‍🎓</div>
                            <p class="text-sm">Belum ada data siswa.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $students->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
