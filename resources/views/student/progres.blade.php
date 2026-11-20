<x-layouts.student>
<x-slot name="title">Progres Belajar</x-slot>
<x-slot name="pageTitle">Progres & Kehadiran</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">📈 Progres Belajar & Kehadiran</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pantau persentase kehadiran dan target pembelajaran Anda di LPK Nihongo LC</p>
    </div>

    {{-- Progress KPI Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        {{-- Attendance Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Tingkat Kehadiran</span>
                <span class="text-2xl">🗓️</span>
            </div>
            <div>
                <div class="text-4xl font-black text-gray-900 mb-2">{{ $attendanceRate }}%</div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden mb-3">
                    <div class="h-2.5 rounded-full transition-all duration-500 {{ $attendanceRate >= 80 ? 'bg-green-500' : 'bg-yellow-500' }}"
                         style="width: {{ $attendanceRate }}%;"></div>
                </div>
                <p class="text-xs text-gray-400">
                    Hadir <strong class="text-gray-700">{{ $presentCount }}</strong> dari <strong class="text-gray-700">{{ $totalAttendance }}</strong> total sesi absensi kelas.
                </p>
            </div>
        </div>

        {{-- Exam Completion Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Evaluasi Ujian</span>
                <span class="text-2xl">🏆</span>
            </div>
            <div>
                <div class="text-4xl font-black text-gray-900 mb-2">{{ $passedExams }}</div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden mb-3">
                    <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $totalExams > 0 ? round(($passedExams / $totalExams) * 100) : 100 }}%;"></div>
                </div>
                <p class="text-xs text-gray-400">
                    Ujian lulus dengan nilai di atas KKM kelulusan.
                </p>
            </div>
        </div>

        {{-- Tasks Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Tugas Terkumpul</span>
                <span class="text-2xl">📚</span>
            </div>
            <div>
                <div class="text-4xl font-black text-gray-900 mb-2">{{ $submittedTasks }}</div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden mb-3">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 85%;"></div>
                </div>
                <p class="text-xs text-gray-400">
                    Total tugas latihan yang telah diserahkan ke sensei.
                </p>
            </div>
        </div>
    </div>

    {{-- Classes Progress Card --}}
    <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
        <h2 class="text-base font-bold text-gray-900 mb-6">Status Kelas Aktif</h2>

        @if($myClasses->count() > 0)
        <div class="space-y-6">
            @foreach($myClasses as $cls)
            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">{{ $cls->name }}</h3>
                        <p class="text-xs text-gray-500">Program: {{ $cls->program?->name }} • Sensei: {{ $cls->instructor?->name ?? 'Belum ditentukan' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 self-start sm:self-auto">
                        Status: Aktif
                    </span>
                </div>

                <div class="text-xs text-gray-500 space-y-1">
                    <div>Periode: <strong>{{ $cls->start_date ? $cls->start_date->format('d M Y') : '-' }} s/d {{ $cls->end_date ? $cls->end_date->format('d M Y') : 'Selesai' }}</strong></div>
                    @if($cls->meeting_link)
                    <div class="pt-2">
                        <a href="{{ $cls->meeting_link }}" target="_blank" class="inline-flex items-center gap-1.5 text-red-600 font-bold hover:underline">
                            🎥 Buka Ruang Belajar Virtual (Meeting) →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-400 text-sm">
            <div class="text-3xl mb-2">🏫</div>
            Anda belum terdaftar dalam kelas aktif saat ini.
        </div>
        @endif
    </div>

</x-layouts.student>
