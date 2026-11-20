<x-layouts.public>
    <x-slot name="title">Tim Pengajar</x-slot>

    <section class="pt-28 pb-16 text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 60%, #24243e 100%);">
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold bg-white/10 border border-white/20 mb-4">Tim Kami</div>
            <h1 class="text-4xl lg:text-5xl font-black mb-4">Tim Pengajar</h1>
            <p class="text-lg text-white/75 max-w-2xl mx-auto">
                Pengajar kami adalah praktisi bahasa Jepang berpengalaman dengan passion mengajar yang tinggi.
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 50" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,25 C360,50 1080,0 1440,25 L1440,50 L0,50 Z"/>
            </svg>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($instructors->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($instructors as $instructor)
                <div class="card-hover text-center">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-3xl font-bold shadow-lg"
                         style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        {{ strtoupper(substr($instructor->name, 0, 1)) }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $instructor->name }}</h3>
                    @if($instructor->instructorProfile)
                    <p class="text-sm text-primary-600 font-medium mt-1">
                        {{ $instructor->instructorProfile->specialization ?? 'Pengajar Bahasa Jepang' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                        {{ $instructor->instructorProfile->bio ?? '' }}
                    </p>
                    @if($instructor->instructorProfile->jlpt_certificate)
                    <div class="mt-3 inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                        Sertifikat {{ $instructor->instructorProfile->jlpt_certificate }}
                    </div>
                    @endif
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <div class="text-6xl mb-4">👨‍🏫</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Informasi Pengajar Segera Hadir</h3>
                <p class="text-gray-500">Tim pengajar kami sedang dalam proses pendataan.</p>
            </div>
            @endif
        </div>
    </section>

    {{-- CTA Join --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Ingin Bergabung Sebagai Pengajar?</h2>
            <p class="text-gray-500 mb-6">Kami selalu mencari pengajar Bahasa Jepang yang passionate. Kirimkan CV Anda kepada kami.</p>
            <a href="mailto:rekrutmen@nihongo-lc.id" class="btn-primary">📧 Kirim Lamaran</a>
        </div>
    </section>
</x-layouts.public>
