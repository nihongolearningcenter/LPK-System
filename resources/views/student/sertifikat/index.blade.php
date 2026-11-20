<x-layouts.student>
<x-slot name="title">Sertifikat Kelulusan</x-slot>
<x-slot name="pageTitle">Sertifikat Saya</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">🏆 Sertifikat & Kelulusan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftar sertifikat kompetensi dan kelulusan program yang telah Anda peroleh</p>
    </div>

    @if(isset($certificates) && $certificates->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($certificates as $cert)
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="font-mono text-xs text-gray-400">NO: {{ $cert->certificate_number }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        RESMI LPK
                    </span>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-3xl mb-4">
                    🎖️
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $cert->certificate_name }}</h3>
                <p class="text-xs text-gray-500 mb-4">Program: {{ $cert->program?->name ?? 'Pelatihan Bahasa Jepang' }}</p>

                <div class="space-y-1 text-xs text-gray-600 border-t border-gray-50 pt-3">
                    <div>Tanggal Terbit: <strong>{{ $cert->issue_date ? $cert->issue_date->format('d F Y') : '-' }}</strong></div>
                    @if($cert->grade_letter)
                    <div>Predikat Nilai: <strong class="text-red-600">{{ $cert->grade_letter }} ({{ $cert->final_grade }})</strong></div>
                    @endif
                    @if($cert->verification_code)
                    <div>Kode Verifikasi: <span class="font-mono text-gray-400">{{ $cert->verification_code }}</span></div>
                    @endif
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between">
                <span class="text-xs text-gray-400">Digital Certificate</span>
                <a href="{{ route('student.sertifikat.download', $cert) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-sm"
                   style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Unduh Sertifikat (PDF)
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-400">
        <div class="text-5xl mb-3">🎓</div>
        <h3 class="font-bold text-gray-800 text-base mb-1">Belum Ada Sertifikat yang Diterbitkan</h3>
        <p class="text-xs text-gray-400 max-w-md mx-auto">Sertifikat akan diterbitkan setelah Anda menyelesaikan program pelatihan, memenuhi batas kehadiran minimal, dan lulus ujian akhir.</p>
    </div>
    @endif

</x-layouts.student>
