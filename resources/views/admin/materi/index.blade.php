<x-layouts.admin>
<x-slot name="title">Manajemen Materi Pelajaran</x-slot>
<x-slot name="pageTitle">Materi Pelajaran</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">📖 Modul & Materi Pelajaran</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola modul pembelajaran teks, video pembelajaran, dan dokumen PDF</p>
        </div>
        <a href="{{ route('admin.materi.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Materi Baru
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul materi..."
                       class="w-full pl-4 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
            </div>
            <select name="subject_id" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
            <select name="type" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Format</option>
                <option value="text" {{ request('type') === 'text' ? 'selected' : '' }}>Teks / Artikel</option>
                <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Video</option>
                <option value="audio" {{ request('type') === 'audio' ? 'selected' : '' }}>Audio</option>
                <option value="mixed" {{ request('type') === 'mixed' ? 'selected' : '' }}>Campuran</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg" style="background:#e63946;">Filter</button>
            <a href="{{ route('admin.materi.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        </form>
    </div>

    {{-- Lessons Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($lessons as $lesson)
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                        {{ $lesson->subject?->name ?? 'Mata Pelajaran' }}
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-gray-100 text-gray-600">
                        {{ ucfirst($lesson->type) }}
                    </span>
                </div>

                <h3 class="font-bold text-gray-900 text-base mb-1">{{ $lesson->title }}</h3>
                <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $lesson->description ?? 'Tidak ada deskripsi.' }}</p>

                <div class="text-xs text-gray-400 space-y-1 mb-4">
                    @if($lesson->duration_minutes)
                    <div>⏱️ Durasi: <strong class="text-gray-700">{{ $lesson->duration_minutes }} menit</strong></div>
                    @endif
                    @if($lesson->classRoom)
                    <div>🏫 Khusus Kelas: <strong class="text-gray-700">{{ $lesson->classRoom->name }}</strong></div>
                    @else
                    <div>🌐 Berlaku: <strong class="text-gray-700">Semua Kelas</strong></div>
                    @endif
                </div>
            </div>

            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                @if($lesson->is_published)
                    <span class="text-xs font-bold text-green-600 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Publik
                    </span>
                @else
                    <span class="text-xs font-bold text-gray-400">Draf</span>
                @endif

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.materi.edit', $lesson) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                        ✏️ Edit
                    </a>
                    <form method="POST" action="{{ route('admin.materi.destroy', $lesson) }}"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-400">
            <div class="text-4xl mb-3">📖</div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Belum Ada Materi Pembelajaran</h3>
            <p class="text-xs text-gray-400 mb-4">Tambahkan materi teks, video pembelajaran, atau modul PDF untuk siswa.</p>
            <a href="{{ route('admin.materi.create') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition">
                + Tambah Materi Pertama
            </a>
        </div>
        @endforelse
    </div>

    @if($lessons->hasPages())
    <div class="mt-6">
        {{ $lessons->links() }}
    </div>
    @endif

</x-layouts.admin>
