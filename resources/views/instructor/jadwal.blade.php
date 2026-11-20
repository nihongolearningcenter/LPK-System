<x-layouts.instructor>
<x-slot name="title">Jadwal Mengajar</x-slot>
<x-slot name="pageTitle">Jadwal Mengajar Sensei</x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Jadwal Mengajar (授業日程)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar agenda sesi tatap muka dan kelas online Anda setiap minggu</p>
        </div>
    </div>

    @if($classes->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <div class="text-5xl mb-3">🗓️</div>
        <h3 class="font-bold text-gray-800 text-base mb-1">Belum Ada Jadwal Mengajar</h3>
        <p class="text-sm">Anda belum ditugaskan ke kelas manapun saat ini.</p>
    </div>
    @else

    @php
        $days = [
            'Senin'   => 'Senin (月曜日)',
            'Selasa'  => 'Selasa (火曜日)',
            'Rabu'    => 'Rabu (水曜日)',
            'Kamis'   => 'Kamis (木曜日)',
            'Jumat'   => 'Jumat (金曜日)',
            'Sabtu'   => 'Sabtu (土曜日)',
            'Minggu'  => 'Minggu (日曜日)',
        ];

        $dayMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        // Gather all schedules from all classes taught by this instructor
        $schedulesByDay = [];
        foreach ($days as $dayKey => $dayLabel) {
            $schedulesByDay[$dayKey] = [];
        }

        foreach ($classes as $class) {
            foreach ($class->schedules as $sched) {
                $rawDay = $sched->day_of_week ?? $sched->day;
                $dayName = $dayMap[$rawDay] ?? $rawDay;
                if (isset($schedulesByDay[$dayName])) {
                    $schedulesByDay[$dayName][] = [
                        'class'      => $class,
                        'start_time' => $sched->start_time,
                        'end_time'   => $sched->end_time,
                        'room'       => $sched->room ?? $class->room,
                    ];
                }
            }
        }
    @endphp

    {{-- Day-by-Day Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        @foreach($days as $dayKey => $dayLabel)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-5 py-3.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <span class="font-bold text-gray-800 text-sm">{{ $dayLabel }}</span>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ count($schedulesByDay[$dayKey]) > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-400' }}">
                    {{ count($schedulesByDay[$dayKey]) }} Sesi
                </span>
            </div>

            <div class="p-5 flex-1 space-y-3">
                @forelse($schedulesByDay[$dayKey] as $item)
                <div class="p-3.5 rounded-xl border border-indigo-100 bg-indigo-50/40 hover:bg-indigo-50 transition">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-indigo-700 font-mono">
                            ⏰ {{ is_string($item['start_time']) ? substr($item['start_time'], 0, 5) : $item['start_time']->format('H:i') }} -
                               {{ is_string($item['end_time']) ? substr($item['end_time'], 0, 5) : $item['end_time']->format('H:i') }}
                        </span>
                        <span class="text-[10px] font-semibold text-gray-500 bg-white px-2 py-0.5 rounded border border-gray-100">
                            🚪 {{ $item['room'] ?? 'Ruang Kelas' }}
                        </span>
                    </div>
                    <div class="font-bold text-gray-900 text-sm">{{ $item['class']->name }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $item['class']->program?->title ?? 'Program Reguler' }}</div>
                    <div class="mt-2 pt-2 border-t border-indigo-100/60 flex items-center justify-between">
                        <a href="{{ route('instructor.kelas.show', $item['class']) }}" class="text-xs text-indigo-600 font-semibold hover:underline">
                            Detail Kelas →
                        </a>
                        <a href="{{ route('instructor.absensi.index', $item['class']) }}" class="text-xs text-green-700 font-semibold hover:underline">
                            📋 Isi Absensi
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-6 text-center text-gray-400 text-xs italic">
                    Tidak ada jadwal mengajar pada hari ini.
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>

    @endif

</x-layouts.instructor>
