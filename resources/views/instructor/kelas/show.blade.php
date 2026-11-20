<x-layouts.instructor>
<x-slot name="title">Kelas {{ $class->name }}</x-slot>
<x-slot name="pageTitle">Detail Kelas Mengajar</x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('instructor.kelas.index') }}" class="text-xs text-indigo-600 hover:underline">← Semua Kelas</a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $class->name }}</h1>
            <p class="text-sm text-gray-500">{{ $class->program?->title ?? 'Program Reguler' }} | Ruang: {{ $class->room ?? 'Online / Lab' }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('instructor.absensi.index', $class) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100">
                📋 Isi Absensi
            </a>
            <a href="{{ route('instructor.nilai.index', $class) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100">
                ⭐ Input Nilai
            </a>
            <a href="{{ route('instructor.materi.create') }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold text-white shadow-sm hover:opacity-95"
               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                + Upload Materi
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">👨‍🎓</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $class->students->count() }}</div>
                <div class="text-xs text-gray-500">Siswa Terdaftar</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">📖</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $class->lessons->count() }}</div>
                <div class="text-xs text-gray-500">Materi Terbit</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl">📌</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $class->assignments->count() }}</div>
                <div class="text-xs text-gray-500">Penugasan Aktif</div>
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Left 2 cols: Recent Lessons & Assignments --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Lessons --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-900 text-sm">📖 Materi Terbaru</h2>
                    <a href="{{ route('instructor.materi.index') }}" class="text-xs text-indigo-600 hover:underline">Semua Materi →</a>
                </div>
                @if($class->lessons->isEmpty())
                    <p class="text-xs text-gray-400 italic py-4 text-center">Belum ada materi pembelajaran yang diunggah untuk kelas ini.</p>
                @else
                    <div class="space-y-3">
                        @foreach($class->lessons as $l)
                        <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between text-xs">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $l->title }}</div>
                                <div class="text-gray-400 text-[10px]">{{ $l->created_at->format('d M Y') }}</div>
                            </div>
                            <a href="{{ route('instructor.materi.show', $l) }}" class="text-indigo-600 font-semibold hover:underline">Lihat</a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Assignments --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-900 text-sm">📌 Tugas Siswa</h2>
                    <a href="{{ route('instructor.tugas.index') }}" class="text-xs text-indigo-600 hover:underline">Semua Tugas →</a>
                </div>
                @if($class->assignments->isEmpty())
                    <p class="text-xs text-gray-400 italic py-4 text-center">Belum ada tugas untuk kelas ini.</p>
                @else
                    <div class="space-y-3">
                        @foreach($class->assignments as $a)
                        <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between text-xs">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $a->title }}</div>
                                <div class="text-gray-400 text-[10px]">Deadline: {{ $a->due_date ? $a->due_date->format('d M Y, H:i') : 'Tanpa batas' }}</div>
                            </div>
                            <a href="{{ route('instructor.tugas.submissions', $a) }}" class="text-indigo-600 font-semibold hover:underline">Cek Pengumpulan</a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right 1 col: Students roster --}}
        <div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-900 text-sm">👨‍🎓 Siswa di Kelas Ini</h2>
                    <a href="{{ route('instructor.kelas.students', $class) }}" class="text-xs text-indigo-600 hover:underline">Daftar Lengkap</a>
                </div>
                @if($class->students->isEmpty())
                    <p class="text-xs text-gray-400 italic py-4 text-center">Belum ada siswa terdaftar.</p>
                @else
                    <div class="space-y-3">
                        @foreach($class->students->take(8) as $s)
                        <div class="flex items-center gap-3 text-xs">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[11px] font-bold"
                                 style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                                {{ strtoupper(substr($s->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 truncate">
                                <div class="font-medium text-gray-900 truncate">{{ $s->name }}</div>
                                <div class="text-gray-400 text-[10px]">{{ $s->studentProfile?->phone ?? $s->email }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.instructor>

