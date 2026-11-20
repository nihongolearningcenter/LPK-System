<x-layouts.admin>
<x-slot name="title">Manajemen Ujian Online</x-slot>
<x-slot name="pageTitle">Ujian & Evaluasi</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">📝 Ujian Online & Kuis</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola paket tes ujian, simulasi JLPT, dan jadwal evaluasi siswa</p>
        </div>
        <a href="{{ route('admin.ujian.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Ujian Baru
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <select name="type" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Tipe Ujian</option>
                <option value="simulasi_jlpt" {{ request('type') === 'simulasi_jlpt' ? 'selected' : '' }}>Simulasi JLPT</option>
                <option value="ujian_harian" {{ request('type') === 'ujian_harian' ? 'selected' : '' }}>Ujian Harian</option>
                <option value="ujian_tengah" {{ request('type') === 'ujian_tengah' ? 'selected' : '' }}>Ujian Tengah Semester (UTS)</option>
                <option value="ujian_akhir" {{ request('type') === 'ujian_akhir' ? 'selected' : '' }}>Ujian Akhir (UAS)</option>
                <option value="latihan" {{ request('type') === 'latihan' ? 'selected' : '' }}>Latihan Bebas</option>
            </select>
            <select name="jlpt_level" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Level JLPT</option>
                @foreach(['N5', 'N4', 'N3', 'N2', 'N1'] as $lvl)
                <option value="{{ $lvl }}" {{ request('jlpt_level') === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg" style="background:#e63946;">Filter</button>
            <a href="{{ route('admin.ujian.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        </form>
    </div>

    {{-- Exams Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($exams as $exam)
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                        {{ $exam->type === 'simulasi_jlpt' ? 'bg-purple-100 text-purple-700' : 'bg-red-50 text-red-600' }}">
                        {{ $exam->jlpt_level ? 'JLPT ' . $exam->jlpt_level : ucfirst(str_replace('_', ' ', $exam->type)) }}
                    </span>
                    @if($exam->is_published)
                        <span class="text-xs font-bold text-green-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Publik
                        </span>
                    @else
                        <span class="text-xs font-bold text-gray-400">Draf</span>
                    @endif
                </div>

                <h3 class="font-bold text-gray-900 text-base mb-1">{{ $exam->title }}</h3>
                <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $exam->description ?? 'Tidak ada deskripsi khusus.' }}</p>

                <div class="grid grid-cols-3 gap-2 py-3 bg-gray-50 rounded-2xl text-center text-xs text-gray-600 mb-4">
                    <div>
                        <div class="text-[10px] text-gray-400 uppercase">Waktu</div>
                        <div class="font-bold text-gray-800">{{ $exam->duration_minutes }} Mnt</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-400 uppercase">Soal</div>
                        <div class="font-bold text-gray-800">{{ $exam->questions_count }} Butir</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-400 uppercase">KKM</div>
                        <div class="font-bold text-gray-800">{{ round($exam->passing_score) }}</div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                <span class="text-xs text-gray-400">{{ $exam->attempts_count }} pengerjaan</span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.ujian.show', $exam) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                        Kelola Soal
                    </a>
                    <a href="{{ route('admin.ujian.edit', $exam) }}"
                       class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        ✏️
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-400">
            <div class="text-4xl mb-3">📝</div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Belum Ada Paket Ujian</h3>
            <p class="text-xs text-gray-400 mb-4">Buat paket ujian untuk kelas tertentu atau simulasi JLPT umum.</p>
            <a href="{{ route('admin.ujian.create') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition">
                + Buat Ujian Pertama
            </a>
        </div>
        @endforelse
    </div>

    @if($exams->hasPages())
    <div class="mt-6">
        {{ $exams->links() }}
    </div>
    @endif

</x-layouts.admin>
