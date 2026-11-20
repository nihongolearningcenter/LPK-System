@php
$menu = [
    ['icon' => '📊', 'label' => 'Dashboard',       'route' => 'admin.dashboard',       'active' => 'admin/dashboard'],
    ['icon' => '👥', 'label' => 'Data Pengguna',    'route' => 'admin.users.index',     'active' => 'admin/users*'],
    ['icon' => '👨‍🏫', 'label' => 'Pengajar',        'route' => 'admin.pengajar.index',  'active' => 'admin/pengajar*'],
    ['icon' => '👨‍🎓', 'label' => 'Siswa',           'route' => 'admin.siswa.index',     'active' => 'admin/siswa*'],
    ['icon' => '📚', 'label' => 'Program',          'route' => 'admin.program.index',   'active' => 'admin/program*'],
    ['icon' => '🏫', 'label' => 'Kelas',            'route' => 'admin.kelas.index',     'active' => 'admin/kelas*'],
    ['icon' => '📋', 'label' => 'Pendaftaran',      'route' => 'admin.pendaftaran.index', 'active' => 'admin/pendaftaran*'],
    ['icon' => '📖', 'label' => 'Materi',           'route' => 'admin.materi.index',    'active' => 'admin/materi*'],
    ['icon' => '❓', 'label' => 'Bank Soal',        'route' => 'admin.bank-soal.index', 'active' => 'admin/bank-soal*'],
    ['icon' => '📝', 'label' => 'Ujian Online',     'route' => 'admin.ujian.index',     'active' => 'admin/ujian*'],
    ['icon' => '📌', 'label' => 'Tugas',            'route' => 'admin.tugas.index',     'active' => 'admin/tugas*'],
    ['icon' => '✅', 'label' => 'Absensi',          'route' => 'admin.absensi.index',   'active' => 'admin/absensi*'],
    ['icon' => '⭐', 'label' => 'Penilaian',        'route' => 'admin.nilai.index',     'active' => 'admin/nilai*'],
    ['icon' => '💰', 'label' => 'Pembayaran',       'route' => 'admin.pembayaran.index', 'active' => 'admin/pembayaran*'],
    ['icon' => '🏆', 'label' => 'Sertifikat',      'route' => 'admin.sertifikat.index', 'active' => 'admin/sertifikat*'],
    ['icon' => '📣', 'label' => 'Pengumuman',       'route' => 'admin.pengumuman.index', 'active' => 'admin/pengumuman*'],
    ['icon' => '📊', 'label' => 'Laporan',          'route' => 'admin.laporan.index',   'active' => 'admin/laporan*'],
    ['icon' => '⚙️', 'label' => 'Pengaturan',      'route' => 'admin.pengaturan.index', 'active' => 'admin/pengaturan*'],
];
@endphp

{{-- Sidebar --}}
<aside class="flex flex-col w-64 bg-gray-900 min-h-screen flex-shrink-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       style="transition: transform 0.3s ease;">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-800">
        <img src="{{ asset('images/icon.png') }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover shadow-sm flex-shrink-0">
        <div class="min-w-0">
            <div class="text-white font-bold text-sm leading-tight truncate">LPK NIHONGO</div>
            <div class="text-red-400 text-xs font-medium">Panel Admin</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
        @foreach($menu as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all group
                      {{ request()->routeIs(str_replace('*', '', $item['active']) . '*')
                         ? 'bg-red-600 text-white shadow-lg'
                         : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <span class="text-base w-5 text-center flex-shrink-0">{{ $item['icon'] }}</span>
                <span class="truncate">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- User Info --}}
    <div class="px-3 py-4 border-t border-gray-800">
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                <div class="text-gray-400 text-xs truncate">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-gray-800 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
