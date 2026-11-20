<x-layouts.student>
<x-slot name="title">Daftar Kanji {{ $level }}</x-slot>
<x-slot name="pageTitle">Modul Kanji</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">🈳 Kanji {{ $level }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pelajari cara baca Kun'yomi, On'yomi, arti kata, dan contoh penggunaannya</p>
        </div>

        {{-- Level switch pills --}}
        <div class="flex items-center gap-2 bg-white rounded-2xl border border-gray-100 p-1 w-fit shadow-sm">
            <a href="{{ route('student.kanji', ['level' => 'N5']) }}"
               class="px-4 py-1.5 rounded-xl text-sm font-semibold transition {{ $level === 'N5' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                JLPT N5
            </a>
            <a href="{{ route('student.kanji', ['level' => 'N4']) }}"
               class="px-4 py-1.5 rounded-xl text-sm font-semibold transition {{ $level === 'N4' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                JLPT N4
            </a>
        </div>
    </div>

    {{-- Grid Kanji --}}
    <div x-data="{
        activeKanji: null,
        openModal(item) { this.activeKanji = item; },
        closeModal() { this.activeKanji = null; }
    }">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($kanjis as $item)
            <div @click="openModal({{ json_encode($item) }})"
                 class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-lg hover:border-red-200 transition cursor-pointer flex flex-col items-center text-center group">
                <div class="text-4xl font-bold text-gray-800 mb-2 group-hover:text-red-600 group-hover:scale-110 transition duration-300"
                     style="font-family: 'Noto Sans JP';">
                    {{ $item['kanji'] }}
                </div>
                <div class="text-xs font-semibold text-gray-900 mb-0.5">{{ $item['meaning'] }}</div>
                <div class="text-[11px] text-gray-400 line-clamp-1">{{ $item['onyomi'] }}</div>
                <span class="mt-3 text-[10px] font-medium bg-red-50 text-red-600 px-2 py-0.5 rounded-full">
                    {{ $item['strokes'] }} Goresan
                </span>
            </div>
            @endforeach
        </div>

        {{-- Detail Modal --}}
        <div x-show="activeKanji" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div @click.away="closeModal()"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative animate-in fade-in zoom-in duration-200">
                <button @click="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="flex items-center gap-5 mb-6">
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-5xl font-bold text-white shadow-md flex-shrink-0"
                         style="background: linear-gradient(135deg, #e63946, #c1121f); font-family: 'Noto Sans JP';">
                        <span x-text="activeKanji?.kanji"></span>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-red-600 uppercase tracking-wider mb-1">
                            Kanji {{ $level }} • <span x-text="activeKanji?.strokes"></span> Goresan
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900" x-text="activeKanji?.meaning"></h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm bg-gray-50 rounded-2xl p-5 mb-6">
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">音読み (On'yomi - Cara baca Cina)</div>
                        <div class="font-bold text-gray-800" x-text="activeKanji?.onyomi"></div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">訓読み (Kun'yomi - Cara baca Jepang)</div>
                        <div class="font-bold text-gray-800" x-text="activeKanji?.kunyomi"></div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Contoh Kosakata Terkait</h3>
                    <div class="space-y-2">
                        <template x-for="(ex, idx) in activeKanji?.examples" :key="idx">
                            <div class="p-3 bg-white border border-gray-100 rounded-xl text-sm font-medium text-gray-700 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                <span x-text="ex" style="font-family: 'Noto Sans JP';"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button @click="closeModal()" class="px-5 py-2 rounded-xl text-sm font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-layouts.student>
