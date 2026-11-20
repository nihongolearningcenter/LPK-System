<x-layouts.public>
    <x-slot name="title">Belajar Bahasa Jepang Bersama Kami</x-slot>

    {{-- ═══════════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════════ --}}
    <section class="relative min-h-screen flex items-center overflow-hidden"
             style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);">

        {{-- Background decorative --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-20"
                 style="background: radial-gradient(circle, #e63946 0%, transparent 70%);"></div>
            <div class="absolute bottom-0 -left-20 w-80 h-80 rounded-full opacity-10"
                 style="background: radial-gradient(circle, #4361ee 0%, transparent 70%);"></div>
            {{-- Japanese characters background --}}
            <div class="absolute inset-0 flex items-center justify-end pr-16 opacity-5 pointer-events-none select-none">
                <div class="text-white font-bold leading-none" style="font-size: 20vw; font-family: 'Noto Sans JP';">語</div>
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            {{-- Left: Text --}}
            <div>
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mb-6">
                    <span class="text-yellow-400 text-sm">🎌</span>
                    <span class="text-white/90 text-sm font-medium">Lembaga Pelatihan Bahasa Jepang Terpercaya</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                    Kuasai
                    <span style="background: linear-gradient(90deg, #e63946, #f72585); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Bahasa Jepang
                    </span>
                    <br>Raih Impianmu
                </h1>
                <p class="text-lg text-white/70 leading-relaxed mb-10 max-w-lg">
                    Program pelatihan terstruktur dari nol hingga siap JLPT dan bekerja di Jepang.
                    Belajar bersama pengajar berpengalaman dengan metode yang terbukti efektif.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('enrollment.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-base font-bold text-white shadow-2xl transition-all hover:scale-105 hover:shadow-red-500/30"
                       style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        Daftar Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('program.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-base font-semibold text-white border border-white/30 hover:bg-white/10 transition-all">
                        Lihat Program
                    </a>
                </div>

                {{-- Stats --}}
                <div class="flex items-center gap-8 mt-12 pt-8 border-t border-white/10">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">6+</div>
                        <div class="text-xs text-white/50 mt-1">Program</div>
                    </div>
                    <div class="w-px h-10 bg-white/20"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">JLPT</div>
                        <div class="text-xs text-white/50 mt-1">N5 s/d N3</div>
                    </div>
                    <div class="w-px h-10 bg-white/20"></div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">Online</div>
                        <div class="text-xs text-white/50 mt-1">Learning</div>
                    </div>
                </div>
            </div>

            {{-- Right: Hiragana Card --}}
            <div class="hidden lg:flex flex-col gap-4">
                {{-- Card 1: Alphabet preview --}}
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-6">
                    <div class="text-xs font-semibold text-white/60 uppercase tracking-wider mb-4">Hiragana & Katakana</div>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach(['あ','い','う','え','お','か','き','く','け','こ','さ','し','す','せ','そ'] as $char)
                        <div class="aspect-square flex items-center justify-center rounded-xl bg-white/10 text-white text-xl font-bold hover:bg-white/20 transition cursor-default"
                             style="font-family: 'Noto Sans JP';">
                            {{ $char }}
                        </div>
                        @endforeach
                    </div>
                </div>
                {{-- Card 2: JLPT Levels --}}
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['level' => 'N5', 'desc' => 'Dasar', 'color' => 'from-green-400 to-emerald-600'],
                        ['level' => 'N4', 'desc' => 'Pemula', 'color' => 'from-blue-400 to-blue-600'],
                        ['level' => 'N3', 'desc' => 'Menengah', 'color' => 'from-purple-400 to-purple-600'],
                        ['level' => 'N2', 'desc' => 'Mahir', 'color' => 'from-orange-400 to-orange-600'],
                    ] as $jlpt)
                    <div class="bg-gradient-to-br {{ $jlpt['color'] }} rounded-2xl p-4 text-white">
                        <div class="text-2xl font-black">JLPT {{ $jlpt['level'] }}</div>
                        <div class="text-sm opacity-80">{{ $jlpt['desc'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Wave --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z"/>
            </svg>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         KEUNGGULAN
    ═══════════════════════════════════════════════════════════ --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold text-red-600 bg-red-50 mb-4">
                    Mengapa Nihongo LC?
                </div>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Keunggulan Kami</h2>
                <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
                    Kami dirancang untuk memberikan pengalaman belajar Bahasa Jepang yang efektif, terstruktur, dan menyenangkan.
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['icon' => '📚', 'title' => 'Kurikulum Terstruktur', 'desc' => 'Materi disusun bertahap dari dasar hingga mahir sesuai standar JLPT.'],
                    ['icon' => '👨‍🏫', 'title' => 'Pengajar Berpengalaman', 'desc' => 'Pengajar bersertifikat dengan kemampuan Bahasa Jepang yang teruji.'],
                    ['icon' => '💻', 'title' => 'Belajar Online', 'desc' => 'Portal belajar online lengkap: materi, latihan JLPT, dan progres belajar.'],
                    ['icon' => '🎯', 'title' => 'Latihan JLPT', 'desc' => 'Latihan soal JLPT dengan timer, pembahasan, dan analisis perkembangan.'],
                ] as $item)
                <div class="group p-6 rounded-2xl border border-gray-100 hover:border-red-100 hover:shadow-xl hover:shadow-red-50 transition-all duration-300">
                    <div class="text-4xl mb-4">{{ $item['icon'] }}</div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         PROGRAM PELATIHAN
    ═══════════════════════════════════════════════════════════ --}}
    <section class="py-20" style="background: linear-gradient(180deg, #f8f9fa 0%, #fff 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold text-red-600 bg-red-50 mb-4">
                    Program Kami
                </div>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Program Pelatihan</h2>
                <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
                    Pilih program yang sesuai dengan tujuan dan kemampuan awal Anda.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($programs as $program)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                @php
                                $levelColors = [
                                    'dasar'        => 'bg-green-100 text-green-700',
                                    'jlpt_n5'      => 'bg-blue-100 text-blue-700',
                                    'jlpt_n4'      => 'bg-indigo-100 text-indigo-700',
                                    'kaiwa'        => 'bg-purple-100 text-purple-700',
                                    'kerja'        => 'bg-orange-100 text-orange-700',
                                    'tokutei_ginou'=> 'bg-red-100 text-red-700',
                                ];
                                $color = $levelColors[$program->type] ?? 'bg-gray-100 text-gray-700';
                                $typeLabels = [
                                    'dasar' => 'Dasar', 'jlpt_n5' => 'JLPT N5', 'jlpt_n4' => 'JLPT N4',
                                    'kaiwa' => 'Kaiwa', 'kerja' => 'Persiapan Kerja', 'tokutei_ginou' => 'Tokutei Ginou',
                                ];
                                @endphp
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ $typeLabels[$program->type] ?? $program->type }}
                                </span>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-red-600 transition">
                            {{ $program->name }}
                        </h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4">
                            {{ $program->short_description }}
                        </p>
                        <div class="flex items-center gap-4 text-xs text-gray-400 mb-5">
                            @if($program->duration_weeks)
                            <span class="flex items-center gap-1">
                                🕐 {{ $program->duration_weeks }} minggu
                            </span>
                            @endif
                            @if($program->session_per_week)
                            <span class="flex items-center gap-1">
                                📅 {{ $program->session_per_week }}
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                @if($program->price > 0)
                                    <div class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($program->price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-400">per program</div>
                                @else
                                    <div class="text-sm text-gray-500">Hubungi kami</div>
                                @endif
                            </div>
                            <a href="{{ route('program.show', $program->slug) }}"
                               class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-sm font-semibold text-white transition hover:scale-105"
                               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                                Detail
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-16 text-gray-400">
                    <div class="text-5xl mb-4">📚</div>
                    <p>Program pelatihan segera hadir.</p>
                </div>
                @endforelse
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('program.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-red-200 text-red-600 font-semibold hover:bg-red-600 hover:text-white transition">
                    Lihat Semua Program
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         CTA DAFTAR
    ═══════════════════════════════════════════════════════════ --}}
    <section class="py-20" style="background: linear-gradient(135deg, #e63946, #c1121f);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-5xl mb-6">🎌</div>
            <h2 class="text-3xl lg:text-4xl font-black text-white mb-4">
                Mulai Perjalanan Belajarmu Hari Ini
            </h2>
            <p class="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
                Daftarkan diri Anda sekarang dan bergabung dalam program pelatihan Bahasa Jepang kami.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('enrollment.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-base font-bold bg-white text-red-600 shadow-2xl hover:scale-105 transition-all">
                    Daftar Sekarang — Gratis
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="https://wa.me/08123456789"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-base font-semibold border-2 border-white/50 text-white hover:bg-white/10 transition-all">
                    Tanya via WhatsApp
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════
         KONTAK
    ═══════════════════════════════════════════════════════════ --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Hubungi Kami</h2>
                <p class="mt-4 text-lg text-gray-500">Ada pertanyaan? Kami siap membantu Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                @foreach([
                    ['icon' => '📍', 'title' => 'Alamat', 'info' => 'Jl. Contoh No. 1, Kota Anda'],
                    ['icon' => '📱', 'title' => 'WhatsApp', 'info' => '0812-3456-7890'],
                    ['icon' => '📧', 'title' => 'Email', 'info' => 'info@nihongo-lc.id'],
                ] as $contact)
                <div class="text-center p-6 rounded-2xl bg-gray-50 border border-gray-100 hover:border-red-100 hover:shadow-lg transition">
                    <div class="text-4xl mb-3">{{ $contact['icon'] }}</div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $contact['title'] }}</h3>
                    <p class="text-gray-500 text-sm">{{ $contact['info'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</x-layouts.public>
