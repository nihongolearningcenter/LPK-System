<x-layouts.admin>
<x-slot name="title">Manajemen Kelas</x-slot>
<x-slot name="pageTitle">Daftar Kelas</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">🏫 Manajemen Kelas</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola kelas, alokasi siswa, dan penugasan pengajar (sensei)</p>
        </div>
        <a href="{{ route('admin.kelas.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buka Kelas Baru
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <select name="program_id" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Program</option>
                @foreach($programs as $p)
                <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg" style="background:#e63946;">Filter</button>
            <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        </form>
    </div>

    {{-- Classes Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Kelas</th>
                        <th class="px-6 py-3">Program</th>
                        <th class="px-6 py-3">Pengajar</th>
                        <th class="px-6 py-3">Jumlah Siswa</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($classes as $c)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.kelas.show', $c) }}" class="font-bold text-gray-900 hover:text-red-600">
                                {{ $c->name }}
                            </a>
                            <div class="text-xs text-gray-400 font-mono">{{ $c->code }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $c->program?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $c->instructor?->name ?? 'Belum ada pengajar' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">{{ $c->class_students_count }}</span>
                            <span class="text-xs text-gray-400">/ {{ $c->max_students ?? '∞' }} siswa</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $c->status === 'active' ? 'bg-green-100 text-green-700' : ($c->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.kelas.show', $c) }}"
                                   class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                    👁️ Detail & Siswa
                                </a>
                                <a href="{{ route('admin.kelas.edit', $c) }}"
                                   class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                                    ✏️ Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">🏫</div>
                            <p class="text-sm">Belum ada kelas yang terdaftar.</p>
                            <a href="{{ route('admin.kelas.create') }}" class="text-xs text-red-600 font-medium mt-1 inline-block hover:underline">
                                + Buat Kelas Baru Sekarang
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($classes->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $classes->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
