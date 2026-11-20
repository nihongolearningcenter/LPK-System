<x-layouts.admin>
<x-slot name="title">Manajemen Tugas</x-slot>
<x-slot name="pageTitle">Manajemen Tugas</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Manajemen Tugas Siswa (課題管理)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola penugasan, batas waktu, berkas lampiran, dan pengumpulan tugas</p>
        </div>
        <a href="{{ route('admin.tugas.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Tugas Baru
        </a>
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
                           placeholder="Cari judul tugas..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                </div>

                <select name="class_id" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-5 py-2 text-sm font-medium text-white rounded-lg transition"
                        style="background: #e63946;">Filter</button>
                @if(request('search') || request('class_id'))
                    <a href="{{ route('admin.tugas.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 flex items-center">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Judul Tugas</th>
                        <th class="px-6 py-3">Kelas / Mapel</th>
                        <th class="px-4 py-3 text-center">Batas Waktu</th>
                        <th class="px-4 py-3 text-center">Pengumpulan</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assignments as $task)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ Str::limit(strip_tags($task->description), 60) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $task->classRoom?->name ?? '-' }}</div>
                            <div class="text-xs text-gray-400">{{ $task->subject?->name ?? 'Semua Mapel' }}</div>
                        </td>
                        <td class="px-4 py-4 text-center text-xs">
                            @if($task->due_date)
                                <div class="font-medium {{ $task->due_date->isPast() ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ $task->due_date->format('d M Y, H:i') }}
                                </div>
                                <div class="text-[10px] text-gray-400">{{ $task->due_date->diffForHumans() }}</div>
                            @else
                                <span class="text-gray-400">Tidak ada batas</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                📥 {{ $task->submissions_count }} Siswa
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($task->is_published)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Dipublikasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.tugas.show', $task) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                    📥 Jawaban
                                </a>
                                <a href="{{ route('admin.tugas.edit', $task) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 transition">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('admin.tugas.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">📌</div>
                            <p class="text-sm">Belum ada tugas yang dibuat.</p>
                            <a href="{{ route('admin.tugas.create') }}" class="inline-block mt-3 text-xs font-semibold text-red-600 hover:underline">
                                + Buat tugas pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assignments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $assignments->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
