<x-layouts.instructor>
<x-slot name="title">Progres Siswa — {{ $class->name }}</x-slot>
<x-slot name="pageTitle">Rekap Progres Belajar Siswa</x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('instructor.kelas.show', $class) }}" class="text-xs text-indigo-600 hover:underline">← Kembali ke Detail Kelas</a>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mt-1">Progres Siswa: {{ $class->name }}</h1>
            <p class="text-sm text-gray-500">Pantau tingkat kehadiran, keaktifan tugas, dan performa akademik seluruh siswa</p>
        </div>
    </div>

    {{-- Students Progress Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Siswa</th>
                        <th class="px-4 py-3 text-center">Tingkat Kehadiran</th>
                        <th class="px-4 py-3 text-center">Rata-rata Nilai</th>
                        <th class="px-4 py-3 text-center">Target JLPT</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                    @php
                        $stat = $studentStats[$student->id] ?? ['attendance_rate' => 100, 'average_grade' => null];
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                     style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="inline-flex items-center gap-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $stat['attendance_rate'] >= 80 ? 'bg-green-500' : 'bg-red-500' }}"
                                         style="width: {{ $stat['attendance_rate'] }}%"></div>
                                </div>
                                <span class="font-bold text-xs text-gray-700">{{ $stat['attendance_rate'] }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($stat['average_grade'] !== null)
                                <span class="font-bold text-gray-900">{{ $stat['average_grade'] }}</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700">
                                {{ $student->studentProfile?->japanese_level_target ?? 'N4' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('instructor.progres.show', [$class, $student]) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                📊 Detail Progres Siswa →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            Belum ada siswa di kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.instructor>

