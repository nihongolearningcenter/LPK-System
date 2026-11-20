<x-layouts.student>
<x-slot name="title">Program Saya</x-slot>
<x-slot name="pageTitle">Program Pelatihan Saya</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">📚 Program Pelatihan Saya</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftar kelas dan program intensif Bahasa Jepang yang sedang Anda ikuti</p>
    </div>

    @if(isset($myClasses) && $myClasses->count() > 0)
    <div class="space-y-6">
        @foreach($myClasses as $class)
        @php $prog = $class->program; @endphp
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600">
                        {{ $prog?->type ? ucfirst($prog->type) : 'Reguler' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        Kelas: {{ $class->name }}
                    </span>
                    <span class="text-xs text-gray-400">Kode: {{ $class->code ?? '-' }}</span>
                </div>

                <h2 class="text-2xl font-black text-gray-900">{{ $prog?->name ?? $class->name }}</h2>
                <p class="text-sm text-gray-500 leading-relaxed max-w-2xl">
                    {{ $prog?->short_description ?? 'Program pelatihan intensif bahasa dan budaya Jepang di LPK Nihongo LC.' }}
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-3 text-xs text-gray-600 border-t border-gray-50">
                    <div>
                        <span class="text-gray-400 block">Pengajar (Sensei):</span>
                        <strong class="text-gray-800 text-sm">{{ $class->instructor?->name ?? 'Sensei LPK' }}</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Durasi Kursus:</span>
                        <strong class="text-gray-800 text-sm">{{ $prog?->duration_weeks ?? 12 }} Minggu</strong>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Jadwal Sesi:</span>
                        <strong class="text-gray-800 text-sm">{{ $prog?->session_per_week ?? 3 }}x Pertemuan / Minggu</strong>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 flex-shrink-0">
                <a href="{{ route('student.materi.index') }}"
                   class="px-6 py-3 rounded-2xl text-xs font-bold text-center text-white transition hover:opacity-95 shadow-sm"
                   style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Akses Materi Pelajaran →
                </a>
                <a href="{{ route('student.jadwal') }}"
                   class="px-6 py-3 rounded-2xl text-xs font-bold text-center text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                    Lihat Jadwal Kelas
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-400">
        <div class="text-5xl mb-3">📖</div>
        <h3 class="font-bold text-gray-800 text-base mb-1">Belum Terdaftar di Kelas</h3>
        <p class="text-xs text-gray-400 max-w-md mx-auto mb-5">Anda belum dialokasikan ke kelas aktif. Jika baru mendaftar, silakan tunggu verifikasi admin atau hubungi staf sekretariat.</p>
        <a href="{{ route('program.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition">
            Lihat Katalog Program
        </a>
    </div>
    @endif

</x-layouts.student>
