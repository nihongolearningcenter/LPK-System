<x-layouts.student>
<x-slot name="title">Pengumuman</x-slot>
<x-slot name="pageTitle">Pengumuman</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">📣 Pengumuman & Berita</h1>
        <p class="text-sm text-gray-500 mt-0.5">Informasi dan pengumuman terbaru dari LPK Nihongo LC</p>
    </div>

    @if(isset($announcements) && $announcements->count() > 0)
    <div class="space-y-4">
        @foreach($announcements as $ann)
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $ann->type === 'pengumuman' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $ann->type === 'pengumuman' ? '📢 Pengumuman' : '📰 ' . ucfirst($ann->type) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $ann->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-base mb-1.5">{{ $ann->title }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $ann->content }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($announcements->hasPages())
    <div class="mt-6">
        {{ $announcements->links() }}
    </div>
    @endif

    @else
    {{-- Demo announcements jika DB masih kosong --}}
    <div class="space-y-4">
        @php
        $demos = [
            ['title'=>'Jadwal Libur Nasional Hari Pahlawan', 'content'=>'Kelas tatap muka dan daring akan diliburkan pada 10 November 2026 sehubungan dengan Hari Pahlawan. Jadwal pengganti akan diinfokan oleh sensei masing-masing.', 'type'=>'pengumuman', 'time'=>'2 jam lalu'],
            ['title'=>'Pembukaan Pendaftaran JLPT Periode Desember', 'content'=>'Bagi siswa yang berencana mengikuti ujian JLPT periode Desember, batas akhir penyerahan berkas pendaftaran adalah tanggal 15 November 2026 di kantor tata usaha.', 'type'=>'pengumuman', 'time'=>'1 hari lalu'],
            ['title'=>'Tips Belajar: Menguasai Partikel Bahasa Jepang', 'content'=>'Partikel seperti は (wa), が (ga), を (wo), dan に (ni) seringkali membingungkan pemula. Kuncinya adalah rajin berlatih membuat kalimat sederhana setiap hari dan menggunakan kartu latihan.', 'type'=>'berita', 'time'=>'3 hari lalu'],
            ['title'=>'Fitur Baru Portal Siswa Nihongo LC', 'content'=>'Kini portal siswa telah dilengkapi modul interaktif: Flashcard Kosakata N5-N4, Latihan Soal JLPT, tabel Hiragana/Katakana, dan Kanji. Selamat belajar!', 'type'=>'berita', 'time'=>'1 minggu lalu'],
        ];
        @endphp
        @foreach($demos as $demo)
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $demo['type'] === 'pengumuman' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $demo['type'] === 'pengumuman' ? '📢 Pengumuman' : '📰 ' . ucfirst($demo['type']) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $demo['time'] }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-base mb-1.5">{{ $demo['title'] }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $demo['content'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</x-layouts.student>
