<x-layouts.student>
<x-slot name="title">Hiragana & Katakana</x-slot>
<x-slot name="pageTitle">Hiragana & Katakana</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">🔤 Hiragana & Katakana</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pelajari alfabet dasar bahasa Jepang</p>
    </div>

    {{-- Tab --}}
    <div x-data="{ tab: 'hiragana' }" class="space-y-6">
        <div class="flex gap-2 bg-white rounded-2xl border border-gray-100 p-1 w-fit shadow-sm">
            <button @click="tab = 'hiragana'"
                    :class="tab === 'hiragana' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                    class="px-5 py-2 rounded-xl text-sm font-semibold transition">
                ひらがな Hiragana
            </button>
            <button @click="tab = 'katakana'"
                    :class="tab === 'katakana' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50'"
                    class="px-5 py-2 rounded-xl text-sm font-semibold transition">
                カタカナ Katakana
            </button>
        </div>

        {{-- Hiragana Panel --}}
        <div x-show="tab === 'hiragana'" x-transition>
            @php
            $hiragana = [
                'Vokal'        => [['あ','a'],['い','i'],['う','u'],['え','e'],['お','o']],
                'Ka-gyō'       => [['か','ka'],['き','ki'],['く','ku'],['け','ke'],['こ','ko']],
                'Sa-gyō'       => [['さ','sa'],['し','shi'],['す','su'],['せ','se'],['そ','so']],
                'Ta-gyō'       => [['た','ta'],['ち','chi'],['つ','tsu'],['て','te'],['と','to']],
                'Na-gyō'       => [['な','na'],['に','ni'],['ぬ','nu'],['ね','ne'],['の','no']],
                'Ha-gyō'       => [['は','ha'],['ひ','hi'],['ふ','fu'],['へ','he'],['ほ','ho']],
                'Ma-gyō'       => [['ま','ma'],['み','mi'],['む','mu'],['め','me'],['も','mo']],
                'Ya-gyō'       => [['や','ya'],['',''],['ゆ','yu'],['',''],['よ','yo']],
                'Ra-gyō'       => [['ら','ra'],['り','ri'],['る','ru'],['れ','re'],['ろ','ro']],
                'Wa-gyō & N'   => [['わ','wa'],['',''],['',''],['を','wo'],['ん','n']],
            ];
            @endphp
            <div class="space-y-4">
                @foreach($hiragana as $row => $chars)
                <div class="bg-white rounded-2xl border border-gray-100 p-4">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ $row }}</div>
                    <div class="flex gap-2 flex-wrap">
                        @foreach($chars as [$char, $romaji])
                        @if($char)
                        <div class="flex flex-col items-center justify-center w-14 h-14 rounded-xl border border-gray-100 hover:border-red-200 hover:bg-red-50 transition cursor-default group">
                            <span class="text-2xl font-bold text-gray-800 group-hover:text-red-600 transition" style="font-family:'Noto Sans JP';">{{ $char }}</span>
                            <span class="text-xs text-gray-400 mt-0.5">{{ $romaji }}</span>
                        </div>
                        @else
                        <div class="w-14 h-14"></div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Katakana Panel --}}
        <div x-show="tab === 'katakana'" x-transition>
            @php
            $katakana = [
                'Vokal'        => [['ア','a'],['イ','i'],['ウ','u'],['エ','e'],['オ','o']],
                'Ka-gyō'       => [['カ','ka'],['キ','ki'],['ク','ku'],['ケ','ke'],['コ','ko']],
                'Sa-gyō'       => [['サ','sa'],['シ','shi'],['ス','su'],['セ','se'],['ソ','so']],
                'Ta-gyō'       => [['タ','ta'],['チ','chi'],['ツ','tsu'],['テ','te'],['ト','to']],
                'Na-gyō'       => [['ナ','na'],['ニ','ni'],['ヌ','nu'],['ネ','ne'],['ノ','no']],
                'Ha-gyō'       => [['ハ','ha'],['ヒ','hi'],['フ','fu'],['ヘ','he'],['ホ','ho']],
                'Ma-gyō'       => [['マ','ma'],['ミ','mi'],['ム','mu'],['メ','me'],['モ','mo']],
                'Ya-gyō'       => [['ヤ','ya'],['',''],['ユ','yu'],['',''],['ヨ','yo']],
                'Ra-gyō'       => [['ラ','ra'],['リ','ri'],['ル','ru'],['レ','re'],['ロ','ro']],
                'Wa-gyō & N'   => [['ワ','wa'],['',''],['',''],['ヲ','wo'],['ン','n']],
            ];
            @endphp
            <div class="space-y-4">
                @foreach($katakana as $row => $chars)
                <div class="bg-white rounded-2xl border border-gray-100 p-4">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ $row }}</div>
                    <div class="flex gap-2 flex-wrap">
                        @foreach($chars as [$char, $romaji])
                        @if($char)
                        <div class="flex flex-col items-center justify-center w-14 h-14 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50 transition cursor-default group">
                            <span class="text-2xl font-bold text-gray-800 group-hover:text-blue-600 transition" style="font-family:'Noto Sans JP';">{{ $char }}</span>
                            <span class="text-xs text-gray-400 mt-0.5">{{ $romaji }}</span>
                        </div>
                        @else
                        <div class="w-14 h-14"></div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Tips --}}
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-100 rounded-2xl p-5">
            <div class="flex items-start gap-3">
                <span class="text-2xl">💡</span>
                <div>
                    <div class="font-semibold text-gray-900 mb-1">Tips Belajar</div>
                    <p class="text-sm text-gray-600">Hafalkan 5 karakter per hari secara konsisten. Gunakan flashcard di halaman <a href="{{ route('student.kosakata.flashcard') }}" class="text-red-600 hover:underline">Flashcard</a> untuk latihan mengingat.</p>
                </div>
            </div>
        </div>
    </div>

</x-layouts.student>
