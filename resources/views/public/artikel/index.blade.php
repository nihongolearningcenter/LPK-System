<x-layouts.public>
<x-slot name="title">Artikel & Tips Belajar Bahasa Jepang</x-slot>

    {{-- Hero --}}
    <section class="pt-28 pb-20 text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #0f0c29 0%, #302b63 60%, #24243e 100%);">
        <div class="absolute inset-0 opacity-5 select-none flex items-center justify-end pr-10">
            <div class="font-bold" style="font-size: 15vw; font-family: 'Noto Sans JP';">記</div>
        </div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold bg-white/10 border border-white/20 mb-4">
                Artikel & Tips
            </div>
            <h1 class="text-4xl lg:text-5xl font-black mb-4">Blog & Artikel</h1>
            <p class="text-lg text-white/75 max-w-2xl mx-auto">
                Tips belajar bahasa Jepang, panduan JLPT, kosakata sehari-hari, dan informasi seputar program kami.
            </p>
        </div>
    </section>

    {{-- Filter Kategori --}}
    <section class="py-8 bg-white border-b border-gray-100 sticky top-20 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 overflow-x-auto pb-1">
                @php
                $categories = [
                    ['slug' => 'all',    'label' => 'Semua'],
                    ['slug' => 'tips',   'label' => '💡 Tips Belajar'],
                    ['slug' => 'jlpt',   'label' => '📝 JLPT'],
                    ['slug' => 'grammar','label' => '📚 Grammar'],
                    ['slug' => 'kanji',  'label' => '🈲 Kanji'],
                    ['slug' => 'kosakata','label' => '🗣️ Kosakata'],
                    ['slug' => 'budaya', 'label' => '🎌 Budaya Jepang'],
                ];
                @endphp
                @foreach($categories as $cat)
                <button class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium border transition
                    {{ $loop->first ? 'bg-red-600 text-white border-red-600' : 'text-gray-600 border-gray-200 hover:border-red-300 hover:text-red-600' }}">
                    {{ $cat['label'] }}
                </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Artikel Grid --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Featured Article --}}
            <div class="mb-12">
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 grid md:grid-cols-2 group hover:shadow-xl transition-all duration-300">
                    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-900 to-purple-900 min-h-64 flex items-center justify-center">
                        <div class="text-center text-white p-8">
                            <div class="text-8xl mb-4" style="font-family: 'Noto Sans JP';">あいうえお</div>
                            <div class="text-white/50 text-sm">Hiragana Dasar</div>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-600 text-white">
                                🔥 Featured
                            </span>
                        </div>
                    </div>
                    <div class="p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Tips Belajar</span>
                            <span class="text-xs text-gray-400">5 Okt 2026</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition">
                            Panduan Lengkap Belajar Hiragana dalam 1 Minggu
                        </h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-6">
                            Hiragana adalah alfabet dasar bahasa Jepang yang wajib dikuasai sebelum melanjutkan ke level berikutnya.
                            Dengan teknik yang tepat, Anda bisa menguasainya dalam 7 hari!
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white text-sm font-bold">T</div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-800">Tim Nihongo LC</div>
                                    <div class="text-xs text-gray-400">8 menit baca</div>
                                </div>
                            </div>
                            <a href="{{ route('artikel.show', 'panduan-belajar-hiragana') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-xl text-sm font-semibold text-white transition hover:scale-105"
                               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                                Baca Artikel
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Article Grid --}}
            @php
            $articles = [
                [
                    'slug'     => 'strategi-lulus-jlpt-n5',
                    'emoji'    => '📝',
                    'color'    => 'from-blue-500 to-blue-700',
                    'cat'      => 'JLPT',
                    'catColor' => 'bg-blue-100 text-blue-700',
                    'title'    => 'Strategi Lulus JLPT N5 dalam 3 Bulan',
                    'excerpt'  => 'JLPT N5 adalah level terendah dalam ujian kemahiran bahasa Jepang. Berikut strategi belajar yang efektif.',
                    'author'   => 'Sensei Tanaka',
                    'date'     => '2 Okt 2026',
                    'readTime' => '6 menit',
                ],
                [
                    'slug'     => '80-kanji-jlpt-n5',
                    'emoji'    => '🈲',
                    'color'    => 'from-red-500 to-red-700',
                    'cat'      => 'Kanji',
                    'catColor' => 'bg-red-100 text-red-700',
                    'title'    => '80 Kanji JLPT N5 yang Wajib Dikuasai',
                    'excerpt'  => 'Daftar lengkap 80 kanji dasar untuk JLPT N5 beserta cara baca dan artinya dalam bahasa Indonesia.',
                    'author'   => 'Tim Nihongo LC',
                    'date'     => '28 Sep 2026',
                    'readTime' => '12 menit',
                ],
                [
                    'slug'     => 'panduan-belajar-hiragana',
                    'emoji'    => '🗣️',
                    'color'    => 'from-purple-500 to-purple-700',
                    'cat'      => 'Kosakata',
                    'catColor' => 'bg-purple-100 text-purple-700',
                    'title'    => '50 Kosakata Penting untuk Percakapan Sehari-hari',
                    'excerpt'  => 'Kuasai 50 kata ini dan mulailah percakapan sederhana dalam bahasa Jepang dengan rasa percaya diri.',
                    'author'   => 'Sensei Yamamoto',
                    'date'     => '20 Sep 2026',
                    'readTime' => '5 menit',
                ],
                [
                    'slug'     => 'memahami-partikel-wa-dan-ga',
                    'emoji'    => '📚',
                    'color'    => 'from-green-500 to-green-700',
                    'cat'      => 'Grammar',
                    'catColor' => 'bg-green-100 text-green-700',
                    'title'    => 'Memahami Partikel は (wa) dan が (ga)',
                    'excerpt'  => 'Partikel は dan が adalah salah satu aspek grammar yang paling membingungkan bagi pemula. Pelajari perbedaannya.',
                    'author'   => 'Tim Nihongo LC',
                    'date'     => '15 Sep 2026',
                    'readTime' => '7 menit',
                ],
                [
                    'slug'     => 'etika-budaya-kerja-jepang',
                    'emoji'    => '🎌',
                    'color'    => 'from-orange-500 to-orange-700',
                    'cat'      => 'Budaya',
                    'catColor' => 'bg-orange-100 text-orange-700',
                    'title'    => 'Etika dan Budaya Kerja di Jepang',
                    'excerpt'  => 'Sebelum bekerja di Jepang, penting untuk memahami budaya kerja mereka yang unik. Simak panduan lengkapnya.',
                    'author'   => 'Sensei Kobayashi',
                    'date'     => '10 Sep 2026',
                    'readTime' => '10 menit',
                ],
                [
                    'slug'     => 'panduan-belajar-hiragana',
                    'emoji'    => '💡',
                    'color'    => 'from-yellow-500 to-yellow-700',
                    'cat'      => 'Tips Belajar',
                    'catColor' => 'bg-yellow-100 text-yellow-700',
                    'title'    => 'Teknik Anki untuk Menghafal Kosakata Jepang',
                    'excerpt'  => 'Anki adalah aplikasi flashcard berbasis spaced repetition. Pelajari cara menggunakannya untuk belajar kosakata Jepang.',
                    'author'   => 'Tim Nihongo LC',
                    'date'     => '5 Sep 2026',
                    'readTime' => '5 menit',
                ],
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($articles as $article)
                <article class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300 group">
                    {{-- Image placeholder --}}
                    <div class="h-44 bg-gradient-to-br {{ $article['color'] }} flex items-center justify-center">
                        <span class="text-6xl">{{ $article['emoji'] }}</span>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $article['catColor'] }}">
                                {{ $article['cat'] }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $article['date'] }}</span>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 group-hover:text-red-600 transition line-clamp-2">
                            {{ $article['title'] }}
                        </h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">
                            {{ $article['excerpt'] }}
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <div class="text-xs text-gray-400">
                                ✍️ {{ $article['author'] }} &middot; {{ $article['readTime'] }} baca
                            </div>
                            <a href="#" class="text-xs font-semibold text-red-600 hover:text-red-700">
                                Baca →
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Load More --}}
            <div class="text-center mt-10">
                <button class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-red-200 text-red-600 font-semibold hover:bg-red-600 hover:text-white transition">
                    Muat Lebih Banyak
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    {{-- Newsletter CTA --}}
    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-4xl mb-4">📬</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Dapatkan Tips Terbaru</h2>
            <p class="text-gray-500 mb-8 text-sm">Daftarkan email Anda dan dapatkan artikel & tips belajar bahasa Jepang langsung di inbox Anda.</p>
            <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                <input type="email" placeholder="email@kamu.com"
                       class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100">
                <button class="px-6 py-3 rounded-xl text-sm font-semibold text-white transition hover:scale-105"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Subscribe
                </button>
            </div>
        </div>
    </section>

</x-layouts.public>
