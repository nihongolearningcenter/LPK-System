<x-layouts.instructor>
<x-slot name="title">Dashboard Pengajar</x-slot>
<x-slot name="pageTitle">Dashboard Pengajar</x-slot>

    {{-- Welcome Banner --}}
    <div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden"
         style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 60%, #240046 100%);">
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-4 right-20 w-24 h-24 rounded-full bg-white/5"></div>
        <div class="relative">
            <div class="text-sm font-medium text-white/70 mb-1">Selamat mengajar!</div>
            <h1 class="text-2xl font-bold mb-1">{{ auth()->user()->name }} 👨‍🏫</h1>
            <p class="text-white/70 text-sm">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                — Semangat berbagi ilmu!
            </p>
        </div>
        {{-- Japanese decoration --}}
        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-white/10 font-bold select-none hidden md:block"
             style="font-size: 5rem; font-family: 'Noto Sans JP';">教</div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        @php
        $statItems = [
            ['icon' => '🏫', 'label' => 'Kelas Aktif',   'value' => $stats['active_classes'],  'color' => 'from-blue-500 to-blue-600'],
            ['icon' => '👨‍🎓','label' => 'Total Siswa',   'value' => $stats['total_students'],  'color' => 'from-indigo-500 to-indigo-600'],
        ];
        @endphp
        @foreach($statItems as $stat)
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-gradient-to-br {{ $stat['color'] }}">
                {{ $stat['icon'] }}
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</div>
                <div class="text-xs text-gray-500">{{ $stat['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- My Classes --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-900">🏫 Kelas Saya</h2>
            <a href="{{ route('instructor.kelas.index') }}"
               class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">Lihat Semua →</a>
        </div>
        @if($myClasses->isEmpty())
        <div class="py-12 text-center text-gray-400">
            <div class="text-4xl mb-3">🏫</div>
            <p class="text-sm">Belum ada kelas yang ditugaskan kepada Anda.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
            @foreach($myClasses as $class)
            <a href="{{ route('instructor.kelas.show', $class) }}"
               class="block p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-md transition group">
                <div class="flex items-start justify-between mb-3">
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                        {{ $class->status === 'ongoing' || $class->status === 'active' ? 'bg-green-100 text-green-700' : ($class->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ $class->status === 'ongoing' || $class->status === 'active' ? 'Aktif Berjalan' : ($class->status === 'upcoming' ? 'Akan Datang' : ucfirst($class->status)) }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $class->students_count ?? $class->class_students_count }} siswa</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition">{{ $class->name }}</h3>
                <p class="text-xs text-gray-500">{{ $class->program?->name ?? 'Program Reguler' }}</p>
                @if($class->schedules->isNotEmpty())
                <div class="mt-2 text-xs text-gray-400">
                    📅 {{ $class->schedules->first()->day_of_week }},
                    {{ substr($class->schedules->first()->start_time, 0, 5) }} – {{ substr($class->schedules->first()->end_time, 0, 5) }} WIB
                </div>
                @endif
            </a>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-900 mb-4">⚡ Akses Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
                ['icon' => '📝', 'label' => 'Materi',   'route' => 'instructor.materi.index',   'color' => 'hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600'],
                ['icon' => '📋', 'label' => 'Tugas',    'route' => 'instructor.tugas.index',    'color' => 'hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600'],
                ['icon' => '📝', 'label' => 'Bank Soal','route' => 'instructor.soal.index',     'color' => 'hover:bg-purple-50 hover:border-purple-200 hover:text-purple-600'],
                ['icon' => '📊', 'label' => 'Ujian',    'route' => 'instructor.ujian.index',    'color' => 'hover:bg-green-50 hover:border-green-200 hover:text-green-600'],
            ] as $action)
            <a href="{{ route($action['route']) }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-100 text-gray-600 transition {{ $action['color'] }}">
                <span class="text-2xl">{{ $action['icon'] }}</span>
                <span class="text-xs font-medium">{{ $action['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</x-layouts.instructor>
