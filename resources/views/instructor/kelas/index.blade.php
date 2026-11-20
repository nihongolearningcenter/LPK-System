<x-layouts.instructor>
<x-slot name="title">Kelas Saya</x-slot>
<x-slot name="pageTitle">Daftar Kelas Mengajar</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Kelas Saya (担当クラス)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola materi, absensi, tugas, dan nilai siswa di kelas Anda</p>
        </div>
    </div>

    @if($classes->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <div class="text-5xl mb-3">🏫</div>
        <h3 class="font-bold text-gray-800 text-base mb-1">Belum Ada Kelas</h3>
        <p class="text-sm">Anda belum ditugaskan mengajar di kelas manapun.</p>
    </div>
    @else

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
        @foreach($classes as $c)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
            <div class="p-5 flex-1">
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                        {{ $c->status === 'ongoing' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ strtoupper($c->status) }}
                    </span>
                    <span class="text-xs text-gray-400 font-mono">{{ $c->code }}</span>
                </div>

                <h3 class="font-bold text-gray-900 text-lg mb-1 leading-snug">{{ $c->name }}</h3>
                <p class="text-xs text-gray-500 mb-4">{{ $c->program?->title ?? 'Program Reguler' }}</p>

                {{-- Stats Chips --}}
                <div class="grid grid-cols-3 gap-2 py-3 border-y border-gray-100 text-center text-xs">
                    <div>
                        <div class="font-bold text-gray-800 text-sm">{{ $c->students_count }}</div>
                        <div class="text-[10px] text-gray-400">Siswa</div>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">{{ $c->lessons_count }}</div>
                        <div class="text-[10px] text-gray-400">Materi</div>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">{{ $c->assignments_count }}</div>
                        <div class="text-[10px] text-gray-400">Tugas</div>
                    </div>
                </div>

                @if($c->schedules->isNotEmpty())
                <div class="mt-3 text-xs text-gray-600 space-y-1">
                    @foreach($c->schedules as $sch)
                    <div class="flex items-center gap-1.5 text-gray-500">
                        <span>🗓️</span>
                        <span>{{ $sch->day_of_week }}: {{ substr($sch->start_time, 0, 5) }} - {{ substr($sch->end_time, 0, 5) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-xs">
                <a href="{{ route('instructor.kelas.show', $c) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">
                    Buka Kelas →
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('instructor.absensi.index', $c) }}" class="px-2.5 py-1 bg-white border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                        Absensi
                    </a>
                    <a href="{{ route('instructor.nilai.index', $c) }}" class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg font-semibold hover:bg-indigo-100">
                        Nilai
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($classes->hasPages())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        {{ $classes->links() }}
    </div>
    @endif

    @endif

</x-layouts.instructor>
