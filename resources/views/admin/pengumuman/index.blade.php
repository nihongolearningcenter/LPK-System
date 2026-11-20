<x-layouts.admin>
<x-slot name="title">Manajemen Pengumuman</x-slot>
<x-slot name="pageTitle">Pengumuman & Informasi</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">📣 Pengumuman & Berita</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola informasi publik, pengumuman siswa, dan info pengajar</p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengumuman
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-48">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau isi pengumuman..."
                       class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
            </div>
            <select name="target" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Target</option>
                <option value="all" {{ request('target') === 'all' ? 'selected' : '' }}>Semua Pengguna</option>
                <option value="students" {{ request('target') === 'students' ? 'selected' : '' }}>Siswa</option>
                <option value="instructors" {{ request('target') === 'instructors' ? 'selected' : '' }}>Pengajar</option>
            </select>
            <select name="type" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Tipe</option>
                <option value="pengumuman" {{ request('type') === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                <option value="berita" {{ request('type') === 'berita' ? 'selected' : '' }}>Berita</option>
                <option value="artikel" {{ request('type') === 'artikel' ? 'selected' : '' }}>Artikel</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg" style="background:#e63946;">Filter</button>
            <a href="{{ route('admin.pengumuman.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        </form>
    </div>

    {{-- Announcements Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Judul Pengumuman</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Target</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Dibuat Pada</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($announcements as $ann)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $ann->title }}</div>
                            <div class="text-xs text-gray-400 line-clamp-1 mt-0.5">{{ Str::limit(strip_tags($ann->content), 80) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $ann->type === 'pengumuman' ? 'bg-red-100 text-red-700' : ($ann->type === 'berita' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                {{ ucfirst($ann->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-gray-600 font-medium">
                                {{ $ann->target === 'all' ? 'Semua' : ($ann->target === 'students' ? 'Siswa' : ($ann->target === 'instructors' ? 'Pengajar' : 'Kelas')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($ann->is_published)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Publik
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Draf
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">
                            {{ $ann->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.pengumuman.edit', $ann) }}"
                                   class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('admin.pengumuman.destroy', $ann) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">📢</div>
                            <p class="text-sm">Belum ada pengumuman.</p>
                            <a href="{{ route('admin.pengumuman.create') }}" class="text-xs text-red-600 font-medium mt-1 inline-block hover:underline">
                                + Buat Pengumuman Baru
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($announcements->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $announcements->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
