<x-layouts.student>
<x-slot name="title">Hasil Ujian — {{ $attempt->exam?->title }}</x-slot>
<x-slot name="pageTitle">Hasil & Pembahasan Ujian</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('student.ujian.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Ujian
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Hasil Ujian</span>
        </div>

        {{-- Score Banner Card --}}
        <div class="rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden"
             style="background: {{ $attempt->is_passed ? 'linear-gradient(135deg, #10b981, #047857)' : 'linear-gradient(135deg, #ef4444, #b91c1c)' }};">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20">
                        {{ $attempt->is_passed ? '🎉 LULUS MEMUASKAN' : '⚠️ PERLU BELAJAR LAGI' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold mt-2 mb-1">{{ $attempt->exam?->title }}</h1>
                    <p class="text-white/80 text-sm">
                        KKM Kelulusan: <span class="font-bold">{{ $attempt->exam?->passing_score }}</span> • Diselesaikan pada {{ $attempt->submitted_at?->format('d M Y, H:i') }}
                    </p>
                </div>

                {{-- Big Score Circle --}}
                <div class="w-28 h-28 rounded-2xl bg-white/10 backdrop-blur-sm border-2 border-white/20 flex flex-col items-center justify-center flex-shrink-0">
                    <span class="text-4xl font-black">{{ round($attempt->score) }}</span>
                    <span class="text-xs text-white/70">dari 100</span>
                </div>
            </div>

            {{-- Stat Pills --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-white/20 text-xs">
                <div>
                    <div class="text-white/70">Jawaban Benar</div>
                    <div class="text-base font-bold">{{ $attempt->correct_count }} Soal</div>
                </div>
                <div>
                    <div class="text-white/70">Jawaban Salah</div>
                    <div class="text-base font-bold">{{ $attempt->wrong_count }} Soal</div>
                </div>
                <div>
                    <div class="text-white/70">Tidak Dijawab</div>
                    <div class="text-base font-bold">{{ $attempt->unanswered_count }} Soal</div>
                </div>
                <div>
                    <div class="text-white/70">Waktu Pengerjaan</div>
                    <div class="text-base font-bold">{{ round(($attempt->time_spent_seconds ?? 0) / 60) }} Menit</div>
                </div>
            </div>
        </div>

        {{-- Question Discussion / Review --}}
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span>📖</span> Pembahasan & Kunci Jawaban
            </h2>

            <div class="space-y-4">
                @php
                $answersByQuestion = $attempt->answers->keyBy('question_id');
                @endphp

                @foreach($attempt->exam->questions as $i => $q)
                @php
                $ans = $answersByQuestion[$q->id] ?? null;
                $isCorrect = $ans?->is_correct ?? false;
                $userOptId = $ans?->selected_option_id;
                @endphp
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-400">Soal {{ $i + 1 }}</span>
                        @if($isCorrect)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                ✅ Jawaban Benar
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                ❌ {{ $userOptId ? 'Jawaban Salah' : 'Tidak Dijawab' }}
                            </span>
                        @endif
                    </div>

                    <div class="text-base font-bold text-gray-900 mb-4" style="font-family:'Noto Sans JP';">
                        {{ $q->question_text }}
                    </div>

                    {{-- Options display --}}
                    <div class="space-y-2 mb-4">
                        @foreach($q->options as $opt)
                        @php
                        $isUserSelected = ($userOptId == $opt->id);
                        $isOptCorrect = $opt->is_correct;
                        @endphp
                        <div class="p-3.5 rounded-2xl border text-sm flex items-center gap-3
                            {{ $isOptCorrect ? 'border-green-300 bg-green-50 text-green-900 font-semibold' : ($isUserSelected ? 'border-red-300 bg-red-50 text-red-900 font-semibold' : 'border-gray-100 text-gray-600') }}">
                            <span class="w-6 h-6 rounded-lg text-xs font-bold flex items-center justify-center
                                {{ $isOptCorrect ? 'bg-green-600 text-white' : ($isUserSelected ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600') }}">
                                {{ $opt->option_label }}
                            </span>
                            <span class="flex-1" style="font-family:'Noto Sans JP';">{{ $opt->option_text }}</span>
                            @if($isOptCorrect)
                                <span class="text-xs text-green-600 font-bold">Kunci Benar</span>
                            @elseif($isUserSelected)
                                <span class="text-xs text-red-600 font-bold">Pilihan Anda</span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Explanation --}}
                    @if($q->explanation)
                    <div class="bg-gray-50 rounded-2xl p-4 text-xs text-gray-600 border border-gray-100">
                        <span class="font-bold text-gray-800">💡 Pembahasan:</span> {{ $q->explanation }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('student.ujian.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                ← Kembali ke Daftar Ujian
            </a>
            <a href="{{ route('student.jlpt.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white transition hover:opacity-90 shadow-sm" style="background:#e63946;">
                Latihan JLPT Lainnya →
            </a>
        </div>

    </div>

</x-layouts.student>

