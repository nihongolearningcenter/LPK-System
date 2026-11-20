<x-layouts.public>
<x-slot name="title">{{ $article['title'] }}</x-slot>

    {{-- Breadcrumbs & Header --}}
    <section class="pt-28 pb-12 bg-gray-50 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-red-600">Beranda</a>
                <span>/</span>
                <a href="{{ route('artikel.index') }}" class="hover:text-red-600">Artikel</a>
                <span>/</span>
                <span class="text-gray-900 font-medium truncate">{{ $article['title'] }}</span>
            </div>

            <div class="flex items-center gap-3 mb-4">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                    {{ $article['category'] }}
                </span>
                <span class="text-xs text-gray-400">⏱️ {{ $article['readTime'] }} baca</span>
                <span class="text-xs text-gray-400">📅 {{ $article['date'] }}</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-4">
                {{ $article['title'] }}
            </h1>

            <div class="flex items-center gap-3 pt-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                     style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    {{ strtoupper(substr($article['author'], 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-bold text-gray-900">{{ $article['author'] }}</div>
                    <div class="text-xs text-gray-500">Instruktur & Edukator LPK Nihongo LC</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Article Content --}}
    <section class="py-12 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- Main Article Text --}}
                <div class="lg:col-span-2">
                    <div class="prose prose-red max-w-none text-gray-800 leading-relaxed text-sm sm:text-base space-y-4">
                        @php
                            // Parse simple markdown headings and lists for clean presentation
                            $paragraphs = explode("\n\n", $article['content']);
                        @endphp

                        @foreach($paragraphs as $p)
                            @if(str_starts_with(trim($p), '### '))
                                <h3 class="text-lg font-bold text-gray-900 mt-6 mb-2 border-l-4 border-red-600 pl-3">
                                    {{ substr(trim($p), 4) }}
                                </h3>
                            @elseif(str_starts_with(trim($p), '- '))
                                <ul class="list-disc list-inside space-y-1 my-2 text-gray-700">
                                    @foreach(explode("\n", trim($p)) as $line)
                                        @if(str_starts_with(trim($line), '- '))
                                            <li>{!! nl2br(e(substr(trim($line), 2))) !!}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @elseif(preg_match('/^[0-9]+\. /', trim($p)))
                                <ol class="list-decimal list-inside space-y-1 my-2 text-gray-700">
                                    @foreach(explode("\n", trim($p)) as $line)
                                        @if(preg_match('/^[0-9]+\. (.*)/', trim($line), $matches))
                                            <li>{!! nl2br(e($matches[1])) !!}</li>
                                        @endif
                                    @endforeach
                                </ol>
                            @else
                                <p class="text-gray-700 leading-relaxed">
                                    {!! nl2br(e($p)) !!}
                                </p>
                            @endif
                        @endforeach
                    </div>

                    {{-- Japanese Quote Box --}}
                    <div class="mt-10 p-6 rounded-2xl bg-gradient-to-r from-red-50 to-orange-50 border border-red-100 flex items-start gap-4">
                        <div class="text-3xl text-red-600">🎌</div>
                        <div>
                            <div class="font-bold text-gray-900 text-sm mb-1" style="font-family: 'Noto Sans JP';">
                                継続は力なり (Keizoku wa chikara nari)
                            </div>
                            <p class="text-xs text-gray-600">
                                "Ketekunan adalah kunci kekuatan." Kunci utama keberhasilan menguasai bahasa Jepang adalah konsistensi belajar setiap hari meskipun hanya 15-30 menit.
                            </p>
                        </div>
                    </div>

                    {{-- Back & Share --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                        <a href="{{ route('artikel.index') }}" class="text-sm font-semibold text-gray-600 hover:text-red-600">
                            ← Kembali ke Daftar Artikel
                        </a>
                        <a href="{{ route('enrollment.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-sm hover:opacity-95"
                           style="background: linear-gradient(135deg, #e63946, #c1121f);">
                            Daftar Pelatihan Sekarang 🚀
                        </a>
                    </div>
                </div>

                {{-- Sidebar: Related Articles & CTA --}}
                <div class="space-y-6">
                    {{-- CTA Card --}}
                    <div class="rounded-2xl p-6 text-white text-center shadow-lg relative overflow-hidden"
                         style="background: linear-gradient(135deg, #e63946 0%, #c1121f 60%, #780000 100%);">
                        <div class="text-4xl mb-3">🌸</div>
                        <h3 class="font-bold text-lg mb-2">Ingin Belajar Lebih Intensif?</h3>
                        <p class="text-xs text-white/80 mb-5 leading-relaxed">
                            Bergabunglah dengan program pelatihan bahasa Jepang resmi di LPK Nihongo LC dengan pengajar berpengalaman dan native speaker.
                        </p>
                        <a href="{{ route('enrollment.create') }}"
                           class="block w-full py-2.5 px-4 rounded-xl bg-white text-red-700 font-bold text-xs shadow-md hover:bg-gray-50 transition">
                            Daftar Kelas Online / Offline
                        </a>
                    </div>

                    {{-- Other Articles --}}
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                        <h4 class="font-bold text-gray-900 text-sm mb-4">Artikel Lainnya</h4>
                        <div class="space-y-4">
                            @foreach($otherArticles as $oa)
                            <a href="{{ route('artikel.show', $oa['slug']) }}" class="block group">
                                <div class="text-[10px] text-red-600 font-semibold mb-0.5">{{ $oa['category'] }}</div>
                                <div class="text-xs font-bold text-gray-800 group-hover:text-red-600 transition leading-snug line-clamp-2">
                                    {{ $oa['title'] }}
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1">{{ $oa['readTime'] }} baca</div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</x-layouts.public>
