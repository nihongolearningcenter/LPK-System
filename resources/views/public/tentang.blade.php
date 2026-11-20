<x-layouts.public>
    <x-slot name="title">Tentang Kami</x-slot>

    {{-- Hero --}}
    <section class="pt-28 pb-20 text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 60%, #24243e 100%);">
        <div class="absolute inset-0 opacity-5 select-none flex items-center justify-end pr-10">
            <div class="font-bold" style="font-size: 15vw; font-family: 'Noto Sans JP';">学</div>
        </div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold bg-white/10 border border-white/20 mb-4">
                Tentang Kami
            </div>
            <h1 class="text-4xl lg:text-5xl font-black mb-6">Nihongo Learning Center</h1>
            <p class="text-lg text-white/75 max-w-2xl mx-auto leading-relaxed">
                Lembaga Pelatihan Kerja (LPK) terpercaya yang berfokus pada pelatihan Bahasa Jepang
                untuk mempersiapkan generasi muda Indonesia menuju karir internasional.
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 50" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,25 C360,50 1080,0 1440,25 L1440,50 L0,50 Z"/>
            </svg>
        </div>
    </section>

    {{-- Visi & Misi --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-3xl p-8 border border-red-100">
                    <div class="text-4xl mb-4">🎯</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Visi Kami</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Menjadi lembaga pelatihan Bahasa Jepang terbaik dan terpercaya di Indonesia,
                        yang mampu mencetak lulusan berkualitas siap bersaing di pasar kerja Jepang dan internasional.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl p-8 border border-blue-100">
                    <div class="text-4xl mb-4">🚀</div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Misi Kami</h2>
                    <ul class="space-y-3 text-gray-600">
                        @foreach([
                            'Memberikan pendidikan Bahasa Jepang berkualitas tinggi dengan metode modern',
                            'Mempersiapkan siswa menghadapi ujian JLPT dengan strategi tepat',
                            'Membangun karakter dan budaya kerja Jepang pada setiap siswa',
                            'Menjembatani peluang kerja antara Indonesia dan Jepang',
                        ] as $misi)
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 mt-0.5">✓</span>
                            <span>{{ $misi }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                @foreach([
                    ['value' => '500+', 'label' => 'Alumni'],
                    ['value' => '95%',  'label' => 'Tingkat Kelulusan JLPT'],
                    ['value' => '6',    'label' => 'Program Pelatihan'],
                    ['value' => '10+',  'label' => 'Pengajar Berpengalaman'],
                ] as $stat)
                <div class="card">
                    <div class="text-3xl font-black text-gradient mb-1">{{ $stat['value'] }}</div>
                    <div class="text-sm text-gray-500 font-medium">{{ $stat['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Keunggulan --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="section-tag">Keunggulan Kami</div>
                <h2 class="text-3xl font-black text-gray-900">Mengapa Memilih Kami?</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['icon'=>'👨‍🏫','title'=>'Pengajar Native & Bersertifikat','desc'=>'Semua pengajar kami telah bersertifikat dan berpengalaman, beberapa di antaranya pernah tinggal di Jepang.'],
                    ['icon'=>'📚','title'=>'Kurikulum Terstruktur','desc'=>'Kurikulum dirancang mengikuti standar JLPT dengan progres belajar yang terukur dan bertahap.'],
                    ['icon'=>'💻','title'=>'Sistem Pembelajaran Digital','desc'=>'Didukung sistem LMS terintegrasi untuk materi, latihan soal, dan ujian online yang dapat diakses kapan saja.'],
                    ['icon'=>'🎌','title'=>'Simulasi Budaya Jepang','desc'=>'Program khusus pengenalan budaya kerja Jepang (keigo, eto) untuk mempersiapkan Anda bekerja di lingkungan nyata.'],
                    ['icon'=>'🏆','title'=>'Track Record Terbukti','desc'=>'Tingkat kelulusan JLPT siswa kami mencapai 95%, jauh di atas rata-rata nasional.'],
                    ['icon'=>'🤝','title'=>'Jaringan Perusahaan Jepang','desc'=>'Kami memiliki kemitraan dengan perusahaan Jepang dan agen tenaga kerja untuk membantu penempatan kerja alumni.'],
                ] as $item)
                <div class="card-hover">
                    <div class="text-3xl mb-3">{{ $item['icon'] }}</div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 text-white" style="background: linear-gradient(135deg, #e63946, #c1121f);">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-3">Siap Memulai Perjalanan Belajar Anda?</h2>
            <p class="text-white/80 mb-6">Daftar sekarang dan dapatkan konsultasi gratis dengan tim kami.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('enrollment.create') }}" class="btn-secondary">Daftar Sekarang</a>
                <a href="{{ route('program.index') }}" class="border-2 border-white/40 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-white/10 transition">
                    Lihat Program
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
