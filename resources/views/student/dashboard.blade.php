<x-layouts.student>
    <x-slot name="title">Dashboard Siswa</x-slot>
    <x-slot name="pageTitle">Selamat Datang 👋</x-slot>

    {{-- Welcome Banner --}}
    <div class="rounded-2xl p-6 mb-6 text-white relative overflow-hidden"
         style="background: linear-gradient(135deg, #e63946 0%, #c1121f 60%, #780000 100%);">
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-4 right-20 w-24 h-24 rounded-full bg-white/5"></div>
        <div class="relative">
            <div class="text-white/80 text-sm mb-1">こんにちは！</div>
            <h1 class="text-2xl font-bold mb-1">{{ auth()->user()->name }}</h1>
            <p class="text-white/80 text-sm">Terus semangat belajar Bahasa Jepang! 🎌</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm text-center">
            <div class="text-2xl font-bold text-gray-900">{{ $stats['classes'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Kelas Aktif</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm text-center">
            <div class="text-2xl font-bold text-gray-900">{{ $stats['exam_attempts'] }}</div>
            <div class="text-xs text-gray-400 mt-1">Ujian Selesai</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm text-center">
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_score'], 0) }}</div>
            <div class="text-xs text-gray-400 mt-1">Rata-rata Nilai</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Program Saya --}}
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-base font-semibold text-gray-900">📚 Program Saya</h2>
            @forelse($myClasses as $class)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 truncate">{{ $class->name }}</div>
                        <div class="text-sm text-gray-500 mt-0.5">{{ $class->program->name ?? '-' }}</div>
                        <div class="text-xs text-gray-400 mt-2">
                            👨‍🏫 {{ $class->instructor->name ?? 'Belum ditentukan' }}
                        </div>
                    </div>
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200 flex-shrink-0">
                        Aktif
                    </span>
                </div>
                @if($canAccessLearning)
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('student.materi.index') }}"
                       class="flex-1 py-2 text-center text-xs font-medium text-white rounded-xl transition hover:opacity-90"
                       style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        Buka Materi
                    </a>
                    <a href="{{ route('student.ujian.index') }}"
                       class="flex-1 py-2 text-center text-xs font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                        Ujian
                    </a>
                </div>
                @else
                <div class="mt-4">
                    <a href="{{ route('student.pembayaran.index') }}"
                       class="block w-full py-2 text-center text-xs font-medium text-amber-800 bg-amber-100/80 rounded-xl hover:bg-amber-200 transition">
                        🔒 Selesaikan Pembayaran untuk Membuka Materi & Ujian →
                    </a>
                </div>
                @endif
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-8 text-center">
                <div class="text-4xl mb-3">📭</div>
                <h3 class="font-bold text-gray-800 text-base mb-1">Belum Terdaftar di Kelas</h3>
                <p class="text-gray-500 text-xs max-w-md mx-auto">
                    Anda belum terdaftar dalam kelas pelatihan manapun. Silakan daftar program atau tunggu konfirmasi admin.
                </p>
                <div class="mt-4 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('enrollment.create') }}"
                       class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white shadow-sm transition hover:opacity-90"
                       style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        Daftar Program Baru
                    </a>
                    <a href="{{ route('student.pembayaran.index') }}"
                       class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                        Cek Tagihan / Pembayaran
                    </a>
                </div>
            </div>
            @endforelse

            {{-- Menu Belajar Cepat (Hanya tampil jika siswa sudah mengikuti program & menyelesaikan pembayaran) --}}
            @if($canAccessLearning)
            <h2 class="text-base font-semibold text-gray-900 pt-2">🎯 Belajar Sekarang</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach([
                    ['icon' => '🔤', 'label' => 'Hiragana & Katakana', 'route' => 'student.hiragana-katakana', 'color' => 'bg-blue-50 text-blue-700'],
                    ['icon' => '🈳', 'label' => 'Kanji',               'route' => 'student.kanji',             'color' => 'bg-purple-50 text-purple-700'],
                    ['icon' => '📝', 'label' => 'Kosakata',            'route' => 'student.kosakata.index',    'color' => 'bg-green-50 text-green-700'],
                    ['icon' => '🎯', 'label' => 'Latihan JLPT',        'route' => 'student.jlpt.index',        'color' => 'bg-red-50 text-red-700'],
                ] as $shortcut)
                <a href="{{ route($shortcut['route']) }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl {{ $shortcut['color'] }} border border-current/10 hover:scale-105 transition text-center">
                    <span class="text-2xl">{{ $shortcut['icon'] }}</span>
                    <span class="text-xs font-semibold leading-tight">{{ $shortcut['label'] }}</span>
                </a>
                @endforeach
            </div>
            @else
            {{-- Panduan Aktivasi & Status Pembayaran --}}
            <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 sm:p-6 mt-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                        🔒
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-bold text-amber-900">Akses Pembelajaran Belum Aktif</h3>
                        <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                            Fitur belajar (jadwal kelas, modul materi, latihan soal, ujian online, dan grafik progres) akan otomatis terbuka setelah Anda terdaftar dalam program aktif dan telah menyelesaikan pembayaran.
                        </p>

                        @if($pendingPayment)
                        <div class="mt-4 p-3.5 bg-white rounded-xl border border-amber-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Tagihan Menunggu Pembayaran:</span>
                                <div class="font-bold text-gray-900 text-sm">#{{ $pendingPayment->invoice_number }} — Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">{{ $pendingPayment->description }}</div>
                            </div>
                            <a href="{{ route('student.pembayaran.show', $pendingPayment) }}"
                               class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-xs font-bold text-white shadow-xs transition hover:opacity-90 flex-shrink-0"
                               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                               Bayar / Unggah Bukti →
                            </a>
                        </div>
                        @else
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('enrollment.create') }}"
                               class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white shadow-xs transition hover:opacity-90"
                               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                               Pilih & Daftar Program Sekarang
                            </a>
                            <a href="{{ route('student.pembayaran.index') }}"
                               class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition">
                               Lihat Riwayat Pembayaran
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Pengumuman --}}
        <div>
            <h2 class="text-base font-semibold text-gray-900 mb-4">📣 Pengumuman</h2>
            <div class="space-y-3">
                @forelse($announcements as $ann)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                    <div class="text-sm font-medium text-gray-900 leading-tight mb-1">{{ $ann->title }}</div>
                    <div class="text-xs text-gray-400">
                        {{ $ann->published_at ? $ann->published_at->diffForHumans() : '' }}
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-6 text-center text-gray-400 text-sm">
                    Belum ada pengumuman.
                </div>
                @endforelse
                <a href="{{ route('student.pengumuman.index') }}"
                   class="block text-center text-xs text-red-600 hover:text-red-700 font-medium py-2">
                    Lihat Semua Pengumuman →
                </a>
            </div>
        </div>
    </div>
</x-layouts.student>
