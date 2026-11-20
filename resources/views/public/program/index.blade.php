<x-layouts.public>
    <x-slot name="title">Program Pelatihan Bahasa Jepang</x-slot>
    <x-slot name="description">Pilih program pelatihan Bahasa Jepang yang sesuai — dari level dasar hingga persiapan kerja ke Jepang.</x-slot>

    {{-- Page Header --}}
    <section class="pt-28 pb-16 text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);">
        <div class="absolute inset-0 opacity-5 select-none flex items-center justify-end pr-10">
            <div class="font-bold" style="font-size: 15vw; font-family: 'Noto Sans JP';">程</div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold bg-white/10 border border-white/20 mb-4">
                Program Kami
            </div>
            <h1 class="text-4xl lg:text-5xl font-black mb-4">Program Pelatihan</h1>
            <p class="text-lg text-white/70 max-w-2xl mx-auto">
                Kami menyediakan berbagai program pelatihan Bahasa Jepang yang terstruktur sesuai level dan tujuan Anda.
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z"/>
            </svg>
        </div>
    </section>

    {{-- Programs Grid --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
            $levelColors = [
                'dasar'        => ['badge' => 'bg-green-100 text-green-700', 'border' => 'hover:border-green-200', 'accent' => '#10b981'],
                'jlpt_n5'      => ['badge' => 'bg-blue-100 text-blue-700',   'border' => 'hover:border-blue-200',  'accent' => '#3b82f6'],
                'jlpt_n4'      => ['badge' => 'bg-indigo-100 text-indigo-700', 'border' => 'hover:border-indigo-200', 'accent' => '#6366f1'],
                'kaiwa'        => ['badge' => 'bg-purple-100 text-purple-700', 'border' => 'hover:border-purple-200', 'accent' => '#a855f7'],
                'kerja'        => ['badge' => 'bg-orange-100 text-orange-700', 'border' => 'hover:border-orange-200', 'accent' => '#f97316'],
                'tokutei_ginou'=> ['badge' => 'bg-red-100 text-red-700',      'border' => 'hover:border-red-200',   'accent' => '#e63946'],
            ];
            $typeLabels = [
                'dasar' => 'Level Dasar', 'jlpt_n5' => 'JLPT N5', 'jlpt_n4' => 'JLPT N4',
                'kaiwa' => 'Percakapan', 'kerja' => 'Persiapan Kerja', 'tokutei_ginou' => 'Tokutei Ginou',
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($programs as $program)
                @php $style = $levelColors[$program->type] ?? ['badge' => 'bg-gray-100 text-gray-700', 'border' => 'hover:border-gray-200', 'accent' => '#6b7280']; @endphp
                <div class="group bg-white rounded-3xl border border-gray-100 {{ $style['border'] }} hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col">
                    {{-- Color top bar --}}
                    <div class="h-1.5 w-full" style="background-color: {{ $style['accent'] }};"></div>

                    <div class="p-7 flex flex-col flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $style['badge'] }}">
                                {{ $typeLabels[$program->type] ?? $program->type }}
                            </span>
                            @if($program->price > 0)
                            <div class="text-right">
                                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($program->price, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-400">per program</div>
                            </div>
                            @endif
                        </div>

                        <h2 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-700 transition">
                            {{ $program->name }}
                        </h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-5 flex-1">
                            {{ $program->short_description }}
                        </p>

                        {{-- Detail Info --}}
                        <div class="space-y-2 mb-6 text-sm">
                            @if($program->duration_weeks)
                            <div class="flex items-center gap-2 text-gray-500">
                                <span>🕐</span>
                                <span><strong class="text-gray-700">{{ $program->duration_weeks }} minggu</strong> ({{ $program->duration_weeks / 4 }} bulan)</span>
                            </div>
                            @endif
                            @if($program->session_per_week)
                            <div class="flex items-center gap-2 text-gray-500">
                                <span>📅</span>
                                <span>{{ $program->session_per_week }}, <strong class="text-gray-700">{{ $program->session_duration_minutes ?? 90 }} menit</strong>/sesi</span>
                            </div>
                            @endif
                            @if($program->target_participant)
                            <div class="flex items-start gap-2 text-gray-500">
                                <span class="flex-shrink-0">🎯</span>
                                <span>{{ $program->target_participant }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="flex gap-3 mt-auto">
                            <a href="{{ route('program.show', $program->slug) }}"
                               class="flex-1 py-2.5 text-center text-sm font-semibold text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                                Detail
                            </a>
                            <a href="{{ route('enrollment.create') }}?program={{ $program->id }}"
                               class="flex-1 py-2.5 text-center text-sm font-semibold text-white rounded-xl transition hover:opacity-90"
                               style="background-color: {{ $style['accent'] }};">
                                Daftar
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-20">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Program Segera Hadir</h3>
                    <p class="text-gray-500">Program pelatihan sedang dalam persiapan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Belum yakin program mana yang tepat?</h2>
            <p class="text-gray-500 mb-6">Hubungi konsultan kami — gratis konsultasi untuk menentukan program yang paling sesuai kebutuhan Anda.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="https://wa.me/08123456789" class="btn-primary">💬 Chat via WhatsApp</a>
                <a href="{{ route('kontak') }}" class="btn-secondary">Hubungi Kami</a>
            </div>
        </div>
    </section>
</x-layouts.public>
