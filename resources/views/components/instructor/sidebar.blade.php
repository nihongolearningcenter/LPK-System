@php
$menu = [
    ['icon' => '📊', 'label' => 'Dashboard',    'route' => 'instructor.dashboard', 'active' => 'instructor/dashboard'],
    ['icon' => '🗓️', 'label' => 'Jadwal',       'route' => 'instructor.jadwal',    'active' => 'instructor/jadwal*'],
    ['icon' => '🏫', 'label' => 'Kelas Saya',   'route' => 'instructor.kelas.index', 'active' => 'instructor/kelas*'],
    ['icon' => '📖', 'label' => 'Materi',        'route' => 'instructor.materi.index', 'active' => 'instructor/materi*'],
    ['icon' => '📌', 'label' => 'Tugas',         'route' => 'instructor.tugas.index', 'active' => 'instructor/tugas*'],
    ['icon' => '❓', 'label' => 'Bank Soal',     'route' => 'instructor.soal.index', 'active' => 'instructor/soal*'],
    ['icon' => '📝', 'label' => 'Ujian',         'route' => 'instructor.ujian.index', 'active' => 'instructor/ujian*'],
    ['icon' => '✅', 'label' => 'Nilai',         'route' => 'instructor.nilai',        'active' => 'instructor/nilai*'],
];
@endphp
<aside class="flex flex-col w-64 bg-indigo-900 min-h-screen flex-shrink-0">
    <div class="flex items-center gap-3 px-5 py-5 border-b border-indigo-800">
        <img src="{{ asset('images/icon.png') }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover shadow-sm flex-shrink-0">
        <div>
            <div class="text-white font-bold text-sm">LPK NIHONGO</div>
            <div class="text-indigo-300 text-xs">Panel Pengajar</div>
        </div>
    </div>
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
        @foreach($menu as $item)
            <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
                      {{ request()->is(ltrim($item['active'], '/'))
                         ? 'bg-indigo-600 text-white'
                         : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                <span>{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
    <div class="px-3 py-4 border-t border-indigo-800">
        <div class="flex items-center gap-2.5 px-3 py-2">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                 class="w-7 h-7 rounded-full object-cover ring-1 ring-white/30 flex-shrink-0">
            <div class="text-indigo-200 text-sm font-medium truncate">{{ auth()->user()->name }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left px-3 py-2 text-indigo-300 hover:text-white text-sm rounded-lg hover:bg-indigo-800 transition">
                ← Keluar
            </button>
        </form>
    </div>
</aside>
