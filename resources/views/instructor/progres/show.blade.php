<x-layouts.instructor>
<x-slot name="title">Progres Siswa: {{ $student->name }}</x-slot>
<x-slot name="pageTitle">Detail Progres Siswa</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('instructor.progres.index', $class) }}" class="text-xs text-indigo-600 hover:underline">← Kembali ke Rekap Kelas</a>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mt-1">{{ $student->name }}</h1>
            <p class="text-sm text-gray-500">Kelas: <strong class="text-gray-800">{{ $class->name }}</strong> | Target JLPT: <strong class="text-red-600">{{ $student->studentProfile?->japanese_level_target ?? 'N4' }}</strong></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('instructor.nilai.index', $class) }}"
               class="px-4 py-2 text-sm font-semibold text-white rounded-xl shadow-sm hover:opacity-95"
               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                ⭐ Kelola Nilai
            </a>
        </div>
    </div>

    {{-- Summary Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <span class="text-xs text-gray-400 block mb-1">Total Hadir</span>
            <div class="text-2xl font-bold text-green-600">{{ $attendanceSummary['present'] }} <span class="text-xs text-gray-400 font-normal">/ {{ $attendanceSummary['total'] }} sesi</span></div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <span class="text-xs text-gray-400 block mb-1">Izin / Sakit</span>
            <div class="text-2xl font-bold text-amber-500">{{ $attendanceSummary['permission'] + $attendanceSummary['sick'] }} <span class="text-xs text-gray-400 font-normal">sesi</span></div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <span class="text-xs text-gray-400 block mb-1">Alpa / Tanpa Keterangan</span>
            <div class="text-2xl font-bold text-red-500">{{ $attendanceSummary['absent'] }} <span class="text-xs text-gray-400 font-normal">sesi</span></div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <span class="text-xs text-gray-400 block mb-1">Tugas Dikumpulkan</span>
            <div class="text-2xl font-bold text-indigo-600">{{ $submissions->count() }} <span class="text-xs text-gray-400 font-normal">tugas</span></div>
        </div>
    </div>

    {{-- Detailed Tabs --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6"
         x-data="{ tab: 'grades' }">
        <div class="px-6 py-3 border-b border-gray-100 flex gap-4 text-xs font-semibold">
            <button @click="tab = 'grades'" :class="tab === 'grades' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                📊 Nilai Akademik
            </button>
            <button @click="tab = 'attendance'" :class="tab === 'attendance' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                📋 Riwayat Absensi
            </button>
            <button @click="tab = 'assignments'" :class="tab === 'assignments' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                📌 Pengumpulan Tugas
            </button>
            <button @click="tab = 'exams'" :class="tab === 'exams' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                📝 Ujian Online & JLPT
            </button>
        </div>

        {{-- Grades --}}
        <div x-show="tab === 'grades'" class="p-6">
            @if($grades->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">Belum ada rekap nilai untuk siswa ini.</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase">
                            <th class="p-3">Mata Pelajaran</th>
                            <th class="p-3 text-center">Absensi</th>
                            <th class="p-3 text-center">Tugas</th>
                            <th class="p-3 text-center">UTS</th>
                            <th class="p-3 text-center">UAS</th>
                            <th class="p-3 text-center">Nilai Akhir</th>
                            <th class="p-3 text-center">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($grades as $g)
                        <tr>
                            <td class="p-3 font-semibold text-gray-800">{{ $g->subject?->name ?? 'Umum' }}</td>
                            <td class="p-3 text-center">{{ $g->score_attendance ?? '-' }}</td>
                            <td class="p-3 text-center">{{ $g->score_assignments ?? '-' }}</td>
                            <td class="p-3 text-center">{{ $g->score_midterm ?? '-' }}</td>
                            <td class="p-3 text-center">{{ $g->score_final ?? '-' }}</td>
                            <td class="p-3 text-center font-bold text-gray-900">{{ $g->final_grade ?? '-' }}</td>
                            <td class="p-3 text-center font-bold text-red-600">{{ $g->grade_letter ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Attendance --}}
        <div x-show="tab === 'attendance'" class="p-6">
            @if($attendances->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">Belum ada data absensi untuk siswa ini.</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase">
                            <th class="p-3">Tanggal Sesi</th>
                            <th class="p-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($attendances as $att)
                        <tr>
                            <td class="p-3 font-medium text-gray-900">{{ \Carbon\Carbon::parse($att->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                    {{ match($att->status) {
                                        'present' => 'bg-green-100 text-green-700',
                                        'permission' => 'bg-blue-100 text-blue-700',
                                        'sick' => 'bg-amber-100 text-amber-700',
                                        default => 'bg-red-100 text-red-700',
                                    } }}">
                                    {{ match($att->status) {
                                        'present' => 'HADIR (出席)',
                                        'permission' => 'IZIN',
                                        'sick' => 'SAKIT',
                                        default => 'ALPA (欠席)',
                                    } }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Assignments --}}
        <div x-show="tab === 'assignments'" class="p-6">
            @if($submissions->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">Belum ada tugas yang dikumpulkan siswa ini.</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase">
                            <th class="p-3">Judul Tugas</th>
                            <th class="p-3 text-center">Waktu Kirim</th>
                            <th class="p-3 text-center">Nilai</th>
                            <th class="p-3">Umpan Balik / Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($submissions as $sub)
                        <tr>
                            <td class="p-3 font-semibold text-gray-800">{{ $sub->assignment?->title ?? '-' }}</td>
                            <td class="p-3 text-center text-gray-500">{{ $sub->submitted_at ? $sub->submitted_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="p-3 text-center font-bold text-gray-900">
                                {{ $sub->score !== null ? number_format($sub->score, 1) : '-' }}
                            </td>
                            <td class="p-3 text-gray-600 italic">{{ $sub->feedback ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Exams --}}
        <div x-show="tab === 'exams'" class="p-6">
            @if($examAttempts->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">Siswa belum mengikuti ujian apapun.</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase">
                            <th class="p-3">Paket Ujian</th>
                            <th class="p-3 text-center">Skor Akhir</th>
                            <th class="p-3 text-center">Status Kelulusan</th>
                            <th class="p-3 text-right">Tanggal Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($examAttempts as $ea)
                        <tr>
                            <td class="p-3 font-semibold text-gray-800">{{ $ea->exam?->title ?? '-' }}</td>
                            <td class="p-3 text-center font-bold text-gray-900">{{ number_format($ea->total_score, 1) }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $ea->is_passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $ea->is_passed ? 'LULUS' : 'TIDAK LULUS' }}
                                </span>
                            </td>
                            <td class="p-3 text-right text-gray-400">{{ $ea->completed_at ? $ea->completed_at->format('d M Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</x-layouts.instructor>

