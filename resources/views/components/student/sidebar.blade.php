@php
$user = auth()->user();
$canAccessLearning = $user ? $user->canAccessLearningFeatures() : false;

$menu = [
    ['icon' => '🏠', 'label' => 'Beranda',          'route' => 'student.dashboard'],
    ['icon' => '👤', 'label' => 'Profil Saya',       'route' => 'student.profil.edit'],
    ['icon' => '📚', 'label' => 'Program Saya',      'route' => 'student.program'],
];

if ($canAccessLearning) {
    $menu = array_merge($menu, [
        ['icon' => '🗓️', 'label' => 'Jadwal Belajar',   'route' => 'student.jadwal'],
        ['icon' => '📖', 'label' => 'Materi',            'route' => 'student.materi.index'],
        ['icon' => '🔤', 'label' => 'Hiragana & Katakana','route' => 'student.hiragana-katakana'],
        ['icon' => '🈳', 'label' => 'Kanji',             'route' => 'student.kanji'],
        ['icon' => '📝', 'label' => 'Kosakata',          'route' => 'student.kosakata.index'],
        ['icon' => '🎯', 'label' => 'Latihan JLPT',      'route' => 'student.jlpt.index'],
        ['icon' => '📋', 'label' => 'Ujian Online',      'route' => 'student.ujian.index'],
        ['icon' => '📌', 'label' => 'Tugas Saya',        'route' => 'student.tugas.index'],
        ['icon' => '📊', 'label' => 'Hasil Belajar',     'route' => 'student.hasil.index'],
        ['icon' => '📈', 'label' => 'Progres',           'route' => 'student.progres'],
    ]);
}

$menu = array_merge($menu, [
    ['icon' => '💳', 'label' => 'Pembayaran',        'route' => 'student.pembayaran.index'],
    ['icon' => '📣', 'label' => 'Pengumuman',        'route' => 'student.pengumuman.index'],
    ['icon' => '🏆', 'label' => 'Sertifikat',        'route' => 'student.sertifikat.index'],
]);
@endphp

{{-- Mobile Overlay --}}
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
     class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

{{-- Sidebar --}}
<aside class="fixed lg:static inset-y-0 left-0 z-30 flex flex-col w-64 bg-white border-r border-gray-200 transition-transform duration-300 lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-100">
        <img src="{{ asset('images/icon.png') }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover shadow-sm flex-shrink-0">
        <div>
            <div class="font-bold text-gray-900 text-sm">LPK NIHONGO</div>
            <div class="text-xs text-red-600 font-medium">Portal Siswa</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
        @foreach($menu as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                      {{ request()->routeIs($item['route'])
                         ? 'bg-red-50 text-red-600 font-semibold'
                         : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="text-base">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- User & Logout --}}
    <div class="px-3 py-4 border-t border-gray-100">
        <div class="flex items-center gap-3 px-3 py-2 mb-1">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                 class="w-8 h-8 rounded-full object-cover ring-2 ring-red-500/20 shadow-xs flex-shrink-0">
            <div class="min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-400">Siswa</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-500 hover:bg-red-50 hover:text-red-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
