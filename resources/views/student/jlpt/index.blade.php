<x-layouts.student>
<x-slot name="title">Latihan JLPT</x-slot>
<x-slot name="pageTitle">Latihan JLPT</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">📝 Latihan Soal JLPT</h1>
        <p class="text-sm text-gray-500 mt-0.5">Persiapkan diri untuk ujian JLPT resmi dengan latihan soal interaktif per level</p>
    </div>

    {{-- JLPT Level Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['level'=>'N5','desc'=>'Dasar — Kosakata, Hiragana, Katakana & Pola Kalimat dasar','color'=>'from-emerald-500 to-green-600','questions'=>'100+ Soal','topics'=>'Kosakata, Kanji N5, Partikel, Dokkai'],
            ['level'=>'N4','desc'=>'Pemula Lanjut — Percakapan sehari-hari & Pola Kalimat menengah','color'=>'from-blue-500 to-indigo-600','questions'=>'150+ Soal','topics'=>'Kanji N4, Bentuk Te/Ta, Dokkai dasar'],
            ['level'=>'N3','desc'=>'Menengah — Pemahaman artikel & percakapan kecepatan normal','color'=>'from-purple-500 to-indigo-700','questions'=>'200+ Soal','topics'=>'Kanji N3, Dokkai esai, Keigo'],
            ['level'=>'N2','desc'=>'Mahir — Membaca artikel koran, berita, & bisnis profesional','color'=>'from-orange-500 to-red-600','questions'=>'250+ Soal','topics'=>'Kanji N2, Berita, Ungkapan idiomatis'],
        ] as $jlpt)
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-lg transition group flex flex-col justify-between">
            <div class="p-6 bg-gradient-to-br {{ $jlpt['color'] }} text-white relative overflow-hidden">
                <div class="absolute -right-2 -bottom-4 text-white/10 text-8xl font-black select-none">{{ $jlpt['level'] }}</div>
                <div class="text-3xl font-black mb-1 relative z-10">JLPT {{ $jlpt['level'] }}</div>
                <div class="text-xs text-white/80 font-medium relative z-10">{{ $jlpt['questions'] }}</div>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-3 leading-relaxed">{{ $jlpt['desc'] }}</p>
                    <div class="text-xs text-gray-400 mb-4">
                        <span class="font-medium text-gray-700">Fokus:</span> {{ $jlpt['topics'] }}
                    </div>
                </div>
                <a href="{{ route('student.jlpt.index', ['level' => $jlpt['level']]) }}"
                   class="w-full py-2.5 rounded-xl text-xs font-bold text-center text-white transition group-hover:opacity-95 shadow-sm block"
                   style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Pilih Level {{ $jlpt['level'] }}
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Interactive JLPT Sample Questions Box --}}
    <div x-data="{
        questions: {{ json_encode($sampleQuestions) }},
        activeIdx: 0,
        selected: {},
        answered: {},

        selectOption(idx, optIdx) {
            this.selected[idx] = optIdx;
            this.answered[idx] = true;
        }
    }" class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <h2 class="font-bold text-gray-900 text-lg">💡 Simulasi Soal Cepat JLPT {{ $level ?? 'N5' }}</h2>
                <p class="text-xs text-gray-500">Coba jawab pertanyaan berikut dan lihat penjelasan langsung</p>
            </div>
            <div class="flex items-center gap-1">
                <template x-for="(q, idx) in questions" :key="idx">
                    <button @click="activeIdx = idx"
                            :class="activeIdx === idx ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="w-7 h-7 rounded-lg text-xs font-bold transition flex items-center justify-center">
                        <span x-text="idx + 1"></span>
                    </button>
                </template>
            </div>
        </div>

        <template x-for="(q, idx) in questions" :key="idx">
            <div x-show="activeIdx === idx" x-transition>
                <div class="mb-6">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                        Soal <span x-text="idx + 1"></span> dari <span x-text="questions.length"></span>
                    </div>
                    <div class="text-xl font-bold text-gray-900 mb-2 leading-relaxed"
                         style="font-family:'Noto Sans JP';"
                         x-text="q.q"></div>
                    <div class="text-xs text-gray-500" x-text="q.q_sub"></div>
                </div>

                {{-- Option Buttons --}}
                <div class="space-y-3 mb-6">
                    <template x-for="(opt, optIdx) in q.options" :key="optIdx">
                        <button type="button"
                                @click="selectOption(idx, optIdx)"
                                :disabled="answered[idx]"
                                :class="{
                                    'border-green-400 bg-green-50 text-green-900 font-bold': answered[idx] && optIdx === q.correct,
                                    'border-red-400 bg-red-50 text-red-900 font-bold': answered[idx] && selected[idx] === optIdx && optIdx !== q.correct,
                                    'border-gray-200 hover:border-red-300 bg-white': !answered[idx],
                                    'border-gray-100 opacity-60': answered[idx] && optIdx !== q.correct && selected[idx] !== optIdx
                                }"
                                class="w-full text-left px-5 py-3.5 rounded-2xl border-2 text-sm transition flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg text-xs font-bold flex items-center justify-center transition"
                                  :class="{
                                      'bg-green-600 text-white': answered[idx] && optIdx === q.correct,
                                      'bg-red-600 text-white': answered[idx] && selected[idx] === optIdx && optIdx !== q.correct,
                                      'bg-gray-100 text-gray-600': !answered[idx] || (optIdx !== q.correct && selected[idx] !== optIdx)
                                  }"
                                  x-text="['A','B','C','D'][optIdx]"></span>
                            <span class="flex-1 font-medium" style="font-family:'Noto Sans JP';" x-text="opt"></span>
                        </button>
                    </template>
                </div>

                {{-- Feedback / Explanation --}}
                <div x-show="answered[idx]" x-transition class="rounded-2xl p-5 text-xs border"
                     :class="selected[idx] === q.correct ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'">
                    <div class="font-bold text-sm mb-1">
                        <span x-show="selected[idx] === q.correct">✅ Jawaban Anda Tepat!</span>
                        <span x-show="selected[idx] !== q.correct">❌ Kurang Tepat! Jawaban yang benar adalah pilihan <strong x-text="['A','B','C','D'][q.correct]"></strong>.</span>
                    </div>
                    <div class="text-xs opacity-90 leading-relaxed mt-1" x-text="q.exp"></div>
                </div>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                    <button type="button" @click="if(activeIdx > 0) activeIdx--" :disabled="activeIdx === 0"
                            class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition disabled:opacity-30">
                        ← Sebelumnya
                    </button>
                    <button type="button" @click="if(activeIdx < questions.length - 1) activeIdx++" :disabled="activeIdx === questions.length - 1"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-white transition hover:opacity-90 disabled:opacity-30"
                            style="background:#e63946;">
                        Berikutnya →
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Ujian Online Banner --}}
    <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-3xl p-6 sm:p-8 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 shadow-xl">
        <div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/20 uppercase tracking-wider">Simulasi Ujian Resmi</span>
            <h3 class="text-xl font-bold mt-2 mb-1">Siap Menghadapi Ujian Lengkap Berwaktu?</h3>
            <p class="text-white/80 text-xs max-w-xl">Ikuti simulasi ujian online dengan batasan waktu nyata, sistem skor otomatis, dan evaluasi kelulusan langsung.</p>
        </div>
        <a href="{{ route('student.ujian.index') }}"
           class="px-6 py-3 rounded-2xl text-xs font-bold bg-white text-red-600 hover:bg-gray-50 transition shadow-sm flex-shrink-0 text-center">
            Buka Ujian Online →
        </a>
    </div>

</x-layouts.student>
