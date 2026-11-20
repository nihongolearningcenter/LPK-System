<x-layouts.admin>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="pageTitle">Dashboard Admin</x-slot>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
        @php
        $statCards = [
            ['label' => 'Total Siswa',     'value' => $stats['total_students'],     'icon' => '👨‍🎓', 'color' => 'from-blue-500 to-blue-600'],
            ['label' => 'Total Pengajar',  'value' => $stats['total_instructors'],  'icon' => '👨‍🏫', 'color' => 'from-indigo-500 to-indigo-600'],
            ['label' => 'Program Aktif',   'value' => $stats['total_programs'],     'icon' => '📚',  'color' => 'from-green-500 to-green-600'],
            ['label' => 'Kelas Berjalan',  'value' => $stats['total_classes'],      'icon' => '🏫',  'color' => 'from-orange-500 to-orange-600'],
            ['label' => 'Pendaftaran Baru','value' => $stats['pending_enrollments'],'icon' => '📋',  'color' => 'from-yellow-500 to-yellow-600'],
            ['label' => 'Pemasukan Bulan Ini','value' => 'Rp ' . number_format($stats['monthly_revenue'], 0, ',', '.'), 'icon' => '💰', 'color' => 'from-red-500 to-red-600'],
        ];
        @endphp

        @foreach($statCards as $card)
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="text-2xl">{{ $card['icon'] }}</div>
                <div class="w-2 h-2 rounded-full bg-gradient-to-r {{ $card['color'] }}"></div>
            </div>
            <div class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Pendaftaran Terbaru --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">📋 Pendaftaran Terbaru</h2>
                <a href="{{ route('admin.pendaftaran.index') }}"
                   class="text-xs text-red-600 hover:text-red-700 font-medium">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Program</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentEnrollments as $enrollment)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $enrollment->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $enrollment->registration_number }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $enrollment->program->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @php
                                $statusClasses = [
                                    'pending'  => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'reviewing'=> 'bg-blue-50 text-blue-700 border-blue-200',
                                    'accepted' => 'bg-green-50 text-green-700 border-green-200',
                                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                    'enrolled' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                ];
                                $statusLabels = [
                                    'pending'  => 'Menunggu', 'reviewing' => 'Ditinjau',
                                    'accepted' => 'Diterima', 'rejected'  => 'Ditolak', 'enrolled' => 'Terdaftar'
                                ];
                                @endphp
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClasses[$enrollment->status] ?? '' }}">
                                    {{ $statusLabels[$enrollment->status] ?? $enrollment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs">
                                {{ $enrollment->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">Belum ada pendaftaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kelas Aktif --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">🏫 Kelas Aktif</h2>
                <a href="{{ route('admin.kelas.index') }}"
                   class="text-xs text-red-600 hover:text-red-700 font-medium">Kelola →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($activeClasses as $class)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $class->program->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                👨‍🏫 {{ $class->instructor->name ?? 'Belum ditugaskan' }}
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-sm font-bold text-gray-900">{{ $class->class_students_count }}</div>
                            <div class="text-xs text-gray-400">siswa</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center text-gray-400 text-sm">
                    Belum ada kelas aktif.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Akses Cepat --}}
    <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-900 mb-4">⚡ Akses Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            @foreach([
                ['icon' => '➕', 'label' => 'Tambah Siswa',    'route' => 'admin.siswa.create'],
                ['icon' => '📋', 'label' => 'Cek Pendaftaran', 'route' => 'admin.pendaftaran.index'],
                ['icon' => '🏫', 'label' => 'Buat Kelas',      'route' => 'admin.kelas.create'],
                ['icon' => '📚', 'label' => 'Tambah Program',  'route' => 'admin.program.create'],
                ['icon' => '❓', 'label' => 'Bank Soal',       'route' => 'admin.bank-soal.index'],
                ['icon' => '📣', 'label' => 'Pengumuman',      'route' => 'admin.pengumuman.index'],
                ['icon' => '💰', 'label' => 'Pembayaran',      'route' => 'admin.pembayaran.index'],
                ['icon' => '📊', 'label' => 'Laporan',         'route' => 'admin.laporan.index'],
            ] as $shortcut)
            <a href="{{ route($shortcut['route']) }}"
               class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-red-50 transition group text-center">
                <span class="text-2xl">{{ $shortcut['icon'] }}</span>
                <span class="text-xs text-gray-600 group-hover:text-red-600 font-medium leading-tight">{{ $shortcut['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</x-layouts.admin>
