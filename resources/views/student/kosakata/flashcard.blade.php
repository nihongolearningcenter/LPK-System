<x-layouts.student>
<x-slot name="title">Flashcard Kosakata</x-slot>
<x-slot name="pageTitle">Flashcard Kosakata</x-slot>

    @php
    $cards = $cards ?? [
        ['jp'=>'ありがとう','kana'=>'arigatou','id'=>'Terima kasih','ex'=>'ありがとうございます。','ex_id'=>'Terima kasih banyak.'],
        ['jp'=>'おはようございます','kana'=>'ohayou gozaimasu','id'=>'Selamat pagi (sopan)','ex'=>'おはようございます！','ex_id'=>'Selamat pagi!'],
        ['jp'=>'すみません','kana'=>'sumimasen','id'=>'Permisi / Maaf','ex'=>'すみません、トイレはどこですか？','ex_id'=>'Permisi, di mana toiletnya?'],
        ['jp'=>'がっこう','kana'=>'gakkou','id'=>'Sekolah','ex'=>'がっこうへいきます。','ex_id'=>'Saya pergi ke sekolah.'],
        ['jp'=>'たべます','kana'=>'tabemasu','id'=>'Makan','ex'=>'ごはんをたべます。','ex_id'=>'Saya makan nasi.'],
    ];
    @endphp

    <div x-data="{
        cards: {{ json_encode($cards) }},
        current: 0,
        flipped: false,
        done: [],
        get card() { return this.cards[this.current]; },
        get progress() { return Math.round(((this.current + 1) / this.cards.length) * 100); },
        next() { this.flipped = false; if(this.current < this.cards.length - 1) { setTimeout(() => this.current++, 200); } },
        prev() { this.flipped = false; if(this.current > 0) { setTimeout(() => this.current--, 200); } },
        know() { if(!this.done.includes(this.current)) { this.done.push(this.current); } this.next(); },
        restart() { this.current = 0; this.flipped = false; this.done = []; }
    }" class="max-w-xl mx-auto py-2">

        {{-- Top Bar & Level Select --}}
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('student.kosakata.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('student.kosakata.flashcard', ['level' => 'N5']) }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold {{ ($level ?? 'N5') === 'N5' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    N5
                </a>
                <a href="{{ route('student.kosakata.flashcard', ['level' => 'N4']) }}"
                   class="px-3 py-1 rounded-full text-xs font-semibold {{ ($level ?? '') === 'N4' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    N4
                </a>
                <span class="text-xs font-mono text-gray-400 ml-2">
                    <span x-text="current + 1"></span>/<span x-text="cards.length"></span>
                </span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="w-full bg-gray-200 rounded-full h-2 mb-6 overflow-hidden">
            <div class="bg-red-600 h-2 rounded-full transition-all duration-300" :style="'width:' + progress + '%'"></div>
        </div>

        {{-- Flashcard --}}
        <div class="relative" style="perspective: 1000px; height: 320px;">
            <div @click="flipped = !flipped"
                 class="absolute inset-0 cursor-pointer transition-transform duration-500"
                 :style="flipped ? 'transform: rotateY(180deg); transform-style: preserve-3d;' : 'transform-style: preserve-3d;'">

                {{-- Front --}}
                <div class="absolute inset-0 rounded-3xl shadow-xl flex flex-col items-center justify-center text-white p-8 select-none"
                     style="background: linear-gradient(135deg, #e63946, #9b111e); backface-visibility: hidden;">
                    <div class="text-xs font-semibold uppercase tracking-widest text-white/60 mb-3">日本語 (Bahasa Jepang)</div>
                    <div class="text-5xl font-black mb-3 text-center" style="font-family:'Noto Sans JP';" x-text="card.jp"></div>
                    <div class="text-white/80 text-base font-medium tracking-wide" x-text="card.kana"></div>
                    <div class="mt-8 text-white/50 text-xs flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Klik kartu untuk melihat arti
                    </div>
                </div>

                {{-- Back --}}
                <div class="absolute inset-0 rounded-3xl bg-white border-2 border-red-100 shadow-xl flex flex-col items-center justify-center p-8 select-none"
                     style="backface-visibility: hidden; transform: rotateY(180deg);">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">Arti Bahasa Indonesia</div>
                    <div class="text-3xl font-extrabold text-gray-900 mb-5 text-center" x-text="card.id"></div>
                    <div class="w-full bg-gray-50 rounded-2xl p-4 text-center border border-gray-100">
                        <div class="text-sm font-semibold text-gray-800 mb-1" x-text="card.ex" style="font-family:'Noto Sans JP';"></div>
                        <div class="text-xs text-gray-500 italic" x-text="card.ex_id"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Controls --}}
        <div class="flex items-center justify-center gap-4 mt-8">
            <button @click="prev()" :disabled="current === 0"
                    class="p-3.5 rounded-2xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="know()"
                    class="px-8 py-3.5 rounded-2xl font-bold text-white text-sm transition hover:scale-105 shadow-md flex items-center gap-2"
                    style="background: linear-gradient(135deg, #10b981, #059669);">
                <span>✅</span> Sudah Paham
            </button>
            <button @click="next()" :disabled="current === cards.length - 1"
                    class="p-3.5 rounded-2xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        {{-- Finished Celebration --}}
        <div x-show="current === cards.length - 1 && flipped" class="mt-6 text-center animate-in fade-in zoom-in duration-300">
            <div class="bg-green-50 border border-green-200 rounded-3xl p-6">
                <div class="text-4xl mb-2">🎉 お疲れ様でした！</div>
                <div class="font-bold text-gray-900 text-lg mb-1">Hebat! Semua kartu telah selesai!</div>
                <p class="text-sm text-gray-600 mb-4">Anda telah mengulas seluruh daftar kosakata pada sesi ini.</p>
                <div class="flex items-center justify-center gap-3">
                    <button @click="restart()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90" style="background:#e63946;">
                        🔄 Ulangi Lagi
                    </button>
                    <a href="{{ route('student.kosakata.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition">
                        Daftar Kosakata
                    </a>
                </div>
            </div>
        </div>

    </div>

</x-layouts.student>
