<x-layouts.public>
    <x-slot name="title">FAQ — Pertanyaan Umum</x-slot>

    <div class="pt-28 pb-16 min-h-screen bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12">
                <div class="section-tag">FAQ</div>
                <h1 class="text-3xl font-black text-gray-900">Pertanyaan yang Sering Ditanyakan</h1>
                <p class="mt-2 text-gray-500">Temukan jawaban dari pertanyaan yang paling sering kami terima.</p>
            </div>

            <div class="space-y-3" x-data="{ open: null }">
                @php
                $faqs = [
                    ['q' => 'Apakah saya perlu memiliki dasar Bahasa Jepang untuk mendaftar?',
                     'a' => 'Tidak perlu! Kami memiliki program Bahasa Jepang Dasar yang dirancang khusus untuk pemula yang belum pernah belajar sama sekali. Pengajar kami akan membimbing dari awal.'],
                    ['q' => 'Berapa biaya pendaftaran?',
                     'a' => 'Biaya bervariasi tergantung program yang dipilih. Anda dapat melihat detail harga di halaman Program kami. Tersedia juga opsi cicilan yang dapat dibicarakan dengan admin.'],
                    ['q' => 'Apakah kelas tersedia secara online?',
                     'a' => 'Ya, kami menyediakan kelas offline, online via Zoom/Google Meet, dan hybrid. Anda dapat memilih metode yang paling nyaman sesuai jadwal Anda.'],
                    ['q' => 'Berapa lama durasi setiap program?',
                     'a' => 'Durasi bervariasi: program Dasar 8 minggu, JLPT N5 16 minggu, JLPT N4 20 minggu. Detail durasi dapat dilihat di halaman masing-masing program.'],
                    ['q' => 'Apakah ada sertifikat setelah menyelesaikan program?',
                     'a' => 'Ya, setiap siswa yang menyelesaikan program akan mendapatkan Sertifikat Kelulusan dari Nihongo Learning Center. Untuk JLPT, kami juga membantu pendaftaran ujian resmi JLPT.'],
                    ['q' => 'Bagaimana cara mendaftar?',
                     'a' => 'Pendaftaran sangat mudah! Klik tombol "Daftar Sekarang" dan isi formulir online. Tim kami akan menghubungi Anda dalam 1-3 hari kerja untuk informasi lebih lanjut.'],
                    ['q' => 'Apakah ada program beasiswa atau diskon?',
                     'a' => 'Kami sesekali mengadakan program early bird dan referral discount. Ikuti media sosial kami atau hubungi admin untuk informasi promo terkini.'],
                    ['q' => 'Bagaimana program persiapan kerja ke Jepang?',
                     'a' => 'Program Kerja ke Jepang mencakup pelatihan bahasa level N4, budaya kerja Jepang, wawancara, dan pendampingan pengurusan dokumen. Kami bekerja sama dengan agen penempatan terpercaya.'],
                ];
                @endphp

                @foreach($faqs as $i => $faq)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                            class="w-full text-left px-6 py-5 flex items-center justify-between gap-4 hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 flex-shrink-0 text-gray-400 transition-transform duration-200"
                             :class="open === {{ $i }} ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-5">
                        <p class="text-gray-600 leading-relaxed border-t border-gray-100 pt-4">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 text-center bg-white rounded-3xl border border-gray-100 p-8">
                <div class="text-3xl mb-3">🤔</div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Masih Ada Pertanyaan?</h2>
                <p class="text-gray-500 mb-5">Tim kami siap membantu Anda. Hubungi kami kapan saja.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="https://wa.me/08123456789" class="btn-primary" style="background: linear-gradient(135deg, #25d366, #128c7e);">
                        💬 Chat WhatsApp
                    </a>
                    <a href="{{ route('kontak') }}" class="btn-secondary">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
