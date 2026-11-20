<x-layouts.instructor>
<x-slot name="title">Daftar Siswa — {{ $class->name }}</x-slot>
<x-slot name="pageTitle">Daftar Siswa Kelas {{ $class->name }}</x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('instructor.kelas.show', $class) }}" class="text-xs text-indigo-600 hover:underline">← Kembali ke Detail Kelas</a>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mt-1">Daftar Siswa: {{ $class->name }}</h1>
            <p class="text-sm text-gray-500">Total: {{ $class->students->count() }} Siswa Terdaftar</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('instructor.absensi.index', $class) }}" class="px-3 py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-100">
                📋 Absensi
            </a>
            <a href="{{ route('instructor.nilai.index', $class) }}" class="px-3 py-1.5 text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg hover:bg-indigo-100">
                ⭐ Nilai
            </a>
        </div>
    </div>

    {{-- Students Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Siswa</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">No. WhatsApp</th>
                        <th class="px-4 py-3 text-center">Target JLPT</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($class->students as $student)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                     style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div class="font-medium text-gray-900">{{ $student->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $student->email }}</td>
                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $student->studentProfile?->phone ?? '-' }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700">
                                {{ $student->studentProfile?->japanese_level_target ?? 'N4' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('instructor.progres.show', [$class, $student]) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                📊 Lihat Progres
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            Belum ada siswa terdaftar pada kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.instructor>

