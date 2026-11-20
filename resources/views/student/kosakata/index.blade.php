<x-layouts.student>
<x-slot name="title">Kosakata Bahasa Jepang</x-slot>
<x-slot name="pageTitle">Kosakata</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">📖 Kosakata Bahasa Jepang</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pelajari kosakata esensial JLPT N5 & N4 beserta cara baca dan artinya</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('student.kosakata.flashcard') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                🃏 Mode Flashcard
            </a>
        </div>
    </div>

    {{-- Filter Kategori --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2 scrollbar-none">
        @foreach($categories as $cat)
        <a href="{{ route('student.kosakata.index', ['category' => $cat, 'level' => request('level', 'all')]) }}"
           class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-medium border transition
            {{ $selectedCategory === $cat
                ? 'bg-red-600 text-white border-red-600 shadow-sm'
                : 'text-gray-600 border-gray-200 bg-white hover:border-red-300 hover:text-red-600' }}">
            {{ $cat }}
        </a>
        @endforeach
    </div>

    {{-- Vocabulary Grid --}}
    @if(isset($vocabularies) && $vocabularies->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($vocabularies as $vocab)
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md hover:border-red-200 transition group flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between mb-2">
                    <div class="text-2xl font-bold text-gray-900 group-hover:text-red-600 transition" style="font-family:'Noto Sans JP';">
                        {{ $vocab->japanese }}
                    </div>
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-red-50 text-red-600">
                        {{ $vocab->jlpt_level ?? 'N5' }}
                    </span>
                </div>
                @if($vocab->hiragana)
                    <div class="text-xs text-gray-400 font-medium mb-1">{{ $vocab->hiragana }} ({{ $vocab->romaji }})</div>
                @elseif($vocab->romaji)
                    <div class="text-xs text-gray-400 font-medium mb-1">{{ $vocab->romaji }}</div>
                @endif
                <div class="font-medium text-gray-800 text-sm mt-2">{{ $vocab->meaning_id }}</div>
            </div>

            @if($vocab->example_sentence_jp)
            <div class="mt-4 pt-3 border-t border-gray-50 text-xs text-gray-500">
                <div class="font-medium text-gray-700 mb-0.5" style="font-family:'Noto Sans JP';">{{ $vocab->example_sentence_jp }}</div>
                <div class="text-gray-400 italic">{{ $vocab->example_sentence_id }}</div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @if($vocabularies->hasPages())
    <div class="mt-6">
        {{ $vocabularies->links() }}
    </div>
    @endif

    @else
    {{-- Demo Fallback --}}
    @php
    $vocabs = [
        ['jp'=>'ありがとう','kana'=>'arigatou','id'=>'Terima kasih','cat'=>'Salam','jlpt'=>'N5'],
        ['jp'=>'おはよう','kana'=>'ohayou','id'=>'Selamat pagi','cat'=>'Salam','jlpt'=>'N5'],
        ['jp'=>'こんにちは','kana'=>'konnichiwa','id'=>'Selamat siang/halo','cat'=>'Salam','jlpt'=>'N5'],
        ['jp'=>'こんばんは','kana'=>'konbanwa','id'=>'Selamat malam','cat'=>'Salam','jlpt'=>'N5'],
        ['jp'=>'すみません','kana'=>'sumimasen','id'=>'Permisi/Maaf','cat'=>'Salam','jlpt'=>'N5'],
        ['jp'=>'いち','kana'=>'ichi','id'=>'Satu (1)','cat'=>'Angka','jlpt'=>'N5'],
        ['jp'=>'に','kana'=>'ni','id'=>'Dua (2)','cat'=>'Angka','jlpt'=>'N5'],
        ['jp'=>'さん','kana'=>'san','id'=>'Tiga (3)','cat'=>'Angka','jlpt'=>'N5'],
        ['jp'=>'し/よん','kana'=>'shi/yon','id'=>'Empat (4)','cat'=>'Angka','jlpt'=>'N5'],
        ['jp'=>'ご','kana'=>'go','id'=>'Lima (5)','cat'=>'Angka','jlpt'=>'N5'],
        ['jp'=>'がっこう','kana'=>'gakkou','id'=>'Sekolah','cat'=>'Tempat','jlpt'=>'N5'],
        ['jp'=>'えき','kana'=>'eki','id'=>'Stasiun','cat'=>'Tempat','jlpt'=>'N5'],
    ];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($vocabs as $vocab)
        <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md hover:border-red-200 transition group flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between mb-2">
                    <div class="text-2xl font-bold text-gray-900 group-hover:text-red-600 transition" style="font-family:'Noto Sans JP';">
                        {{ $vocab['jp'] }}
                    </div>
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-red-50 text-red-600">{{ $vocab['jlpt'] }}</span>
                </div>
                <div class="text-xs text-gray-400 font-medium mb-1">{{ $vocab['kana'] }}</div>
                <div class="font-medium text-gray-800 text-sm mt-2">{{ $vocab['id'] }}</div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400">
                <span>{{ $vocab['cat'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Flashcard CTA --}}
    <div class="mt-8 bg-gradient-to-r from-red-600 to-red-700 rounded-3xl p-6 sm:p-8 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-lg">
        <div>
            <div class="text-3xl mb-2">🃏</div>
            <h3 class="font-bold text-lg">Hafalkan dengan Mode Flashcard!</h3>
            <p class="text-white/80 text-sm mt-1 max-w-xl">Uji ingatan Anda dengan kartu bolak-balik interaktif untuk mengingat arti dan cara baca kosakata secara cepat.</p>
        </div>
        <a href="{{ route('student.kosakata.flashcard') }}"
           class="inline-flex items-center justify-center px-6 py-3 rounded-2xl text-sm font-bold bg-white text-red-600 hover:bg-gray-50 transition shadow-sm flex-shrink-0">
            Mulai Sekarang →
        </a>
    </div>

</x-layouts.student>
