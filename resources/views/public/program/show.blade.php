<x-layouts.public>
    <x-slot name="title">{{ $program->name }}</x-slot>
    <x-slot name="description">{{ $program->short_description }}</x-slot>

    {{-- Hero --}}
    <section class="pt-28 pb-16 text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 60%, #24243e 100%);">
        <div class="absolute inset-0 opacity-5 select-none flex items-center justify-end pr-10">
            <div class="font-bold" style="font-size: 15vw; font-family: 'Noto Sans JP';">程</div>
        </div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('program.index') }}" class="inline-flex items-center gap-2 text-white/60 hover:text-white text-sm transition">
                    ← Semua Program
                </a>
            </div>
            @php
            $typeLabels = ['dasar'=>'Level Dasar','jlpt_n5'=>'JLPT N5','jlpt_n4'=>'JLPT N4','kaiwa'=>'Percakapan','kerja'=>'Persiapan Kerja','tokutei_ginou'=>'Tokutei Ginou'];
            @endphp
            <div class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold bg-white/10 border border-white/20 mb-4">
                {{ $typeLabels[$program->type] ?? $program->type }}
            </div>
            <h1 class="text-4xl lg:text-5xl font-black mb-4">{{ $program->name }}</h1>
            <p class="text-lg text-white/75 max-w-2xl">{{ $program->short_description }}</p>

            <div class="flex flex-wrap gap-6 mt-8">
                @if($program->duration_weeks)
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $program->duration_weeks }}</div>
                    <div class="text-white/60 text-sm">Minggu</div>
                </div>
                @endif
                @if($program->session_per_week)
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $program->session_per_week }}</div>
                    <div class="text-white/60 text-sm">Sesi/Minggu</div>
                </div>
                @endif
                @if($program->price > 0)
                <div class="text-center">
                    <div class="text-2xl font-bold">Rp {{ number_format($program->price, 0, ',', '.') }}</div>
                    <div class="text-white/60 text-sm">Biaya Kursus</div>
                </div>
                @endif
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 50" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,25 C360,50 1080,0 1440,25 L1440,50 L0,50 Z"/>
            </svg>
        </div>
    </section>

    {{-- Konten Detail --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-10">

                    {{-- Deskripsi Lengkap --}}
                    @if($program->description)
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Tentang Program</h2>
                        <div class="prose prose-gray max-w-none text-gray-600 leading-relaxed">
                            {!! nl2br(e($program->description)) !!}
                        </div>
                    </div>
                    @endif

                    {{-- Target Peserta --}}
                    @if($program->target_participant)
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Untuk Siapa?</h2>
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
                            <p class="text-blue-800">{{ $program->target_participant }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Materi Pembelajaran --}}
                    @if($program->subjects->count() > 0)
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Materi yang Dipelajari</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($program->subjects as $subject)
                            <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0 text-sm font-bold">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 text-sm">{{ $subject->name }}</div>
                                    @if($subject->description)
                                    <div class="text-gray-500 text-xs mt-0.5">{{ $subject->description }}</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Kelas Tersedia --}}
                    @if($classes->count() > 0)
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Kelas Tersedia</h2>
                        <div class="space-y-3">
                            @foreach($classes as $class)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $class->name }}</div>
                                    <div class="text-sm text-gray-500 mt-0.5">
                                        Mulai: {{ $class->start_date ? \Carbon\Carbon::parse($class->start_date)->format('d M Y') : 'Belum ditentukan' }}
                                        · Maks {{ $class->max_students }} siswa
                                    </div>
                                </div>
                                <span class="badge-{{ $class->status === 'upcoming' ? 'info' : ($class->status === 'ongoing' ? 'success' : 'warning') }}">
                                    {{ ucfirst($class->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Card Daftar --}}
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-xl p-6 sticky top-24">
                        <div class="text-center mb-5">
                            @if($program->price > 0)
                            <div class="text-3xl font-black text-gray-900">
                                Rp {{ number_format($program->price, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-gray-400">per program</div>
                            @else
                            <div class="text-xl font-bold text-green-600">Hubungi Kami untuk Harga</div>
                            @endif
                        </div>

                        <div class="space-y-3 mb-6 text-sm">
                            @if($program->duration_weeks)
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Durasi</span>
                                <span class="font-semibold text-gray-900">{{ $program->duration_weeks }} minggu</span>
                            </div>
                            @endif
                            @if($program->session_per_week)
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Jadwal</span>
                                <span class="font-semibold text-gray-900">{{ $program->session_per_week }}</span>
                            </div>
                            @endif
                            @if($program->session_duration_minutes)
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Durasi sesi</span>
                                <span class="font-semibold text-gray-900">{{ $program->session_duration_minutes }} menit</span>
                            </div>
                            @endif
                            <div class="flex items-center justify-between text-gray-600">
                                <span>Metode</span>
                                <span class="font-semibold text-gray-900">Offline / Hybrid</span>
                            </div>
                        </div>

                        <a href="{{ route('enrollment.create') }}?program={{ $program->id }}"
                           class="btn-primary w-full justify-center text-base py-3 mb-3">
                            Daftar Sekarang
                        </a>
                        <a href="https://wa.me/08123456789?text=Halo, saya ingin tanya tentang program {{ urlencode($program->name) }}"
                           class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold text-green-700 border-2 border-green-200 hover:bg-green-50 transition">
                            💬 Tanya via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Program Lainnya --}}
    @if($otherPrograms->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Program Lainnya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($otherPrograms as $other)
                <a href="{{ route('program.show', $other->slug) }}"
                   class="block bg-white rounded-2xl border border-gray-100 hover:shadow-lg hover:border-gray-200 transition-all p-5 group">
                    <div class="font-bold text-gray-900 group-hover:text-primary-700 transition mb-1">{{ $other->name }}</div>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $other->short_description }}</p>
                    @if($other->price > 0)
                    <div class="mt-3 text-sm font-semibold text-gray-700">Rp {{ number_format($other->price, 0, ',', '.') }}</div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-layouts.public>
