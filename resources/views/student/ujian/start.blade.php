<x-layouts.student>
<x-slot name="title">{{ $exam->title }} — Ujian Daring</x-slot>
<x-slot name="pageTitle">{{ $exam->title }}</x-slot>

    <div x-data="{
        questions: {{ json_encode($questions) }},
        answers: {{ json_encode($existingAnswers) }},
        currentIndex: 0,
        saving: false,
        durationMinutes: {{ $exam->duration_minutes }},
        timeLeft: {{ $exam->duration_minutes * 60 }},
        timerText: '',

        init() {
            this.updateTimer();
            setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                    this.updateTimer();
                } else {
                    document.getElementById('finish-form').submit();
                }
            }, 1000);
        },

        updateTimer() {
            const m = Math.floor(this.timeLeft / 60);
            const s = this.timeLeft % 60;
            this.timerText = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        },

        get currentQ() {
            return this.questions[this.currentIndex];
        },

        selectOption(qId, optId) {
            this.answers[qId] = optId;
            this.saving = true;

            fetch('{{ route('student.ujian.saveAnswer', $exam) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    attempt_id: {{ $attempt->id }},
                    question_id: qId,
                    option_id: optId
                })
            })
            .then(res => res.json())
            .then(data => {
                this.saving = false;
            })
            .catch(err => {
                this.saving = false;
                console.error(err);
            });
        }
    }">

        {{-- Top Exam Bar --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 shadow-sm flex items-center justify-between sticky top-4 z-20">
            <div>
                <h1 class="font-bold text-gray-900 text-base sm:text-lg">{{ $exam->title }}</h1>
                <div class="text-xs text-gray-400">
                    Soal <span x-text="currentIndex + 1"></span> dari <span x-text="questions.length"></span>
                    <span x-show="saving" class="ml-2 text-yellow-600 font-medium">Menyimpan...</span>
                </div>
            </div>

            {{-- Timer --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-red-50 text-red-600 font-mono font-bold text-sm border border-red-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="timerText"></span>
                </div>

                <form id="finish-form" method="POST" action="{{ route('student.ujian.finish', $attempt) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan ujian ini sekarang?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-sm"
                            style="background: linear-gradient(135deg, #10b981, #059669);">
                        Selesaikan Ujian
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Quiz Area --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- Left 3 Cols: Question & Options --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
                    <template x-if="currentQ">
                        <div>
                            {{-- Category / Subtopic --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-red-50 text-red-600"
                                      x-text="currentQ.category ? currentQ.category.toUpperCase() : 'SOAL'"></span>
                                <span class="text-xs text-gray-400 font-medium" x-text="'Poin: ' + (currentQ.points || 1)"></span>
                            </div>

                            {{-- Question Text --}}
                            <div class="text-lg sm:text-xl font-bold text-gray-900 mb-6 leading-relaxed"
                                 style="font-family:'Noto Sans JP', sans-serif;"
                                 x-text="currentQ.question_text"></div>

                            {{-- Options --}}
                            <div class="space-y-3">
                                <template x-for="opt in currentQ.options" :key="opt.id">
                                    <button type="button"
                                            @click="selectOption(currentQ.id, opt.id)"
                                            :class="answers[currentQ.id] == opt.id
                                                ? 'border-red-500 bg-red-50 text-red-900 shadow-sm'
                                                : 'border-gray-200 bg-white text-gray-700 hover:border-red-200 hover:bg-gray-50'"
                                            class="w-full text-left p-4 rounded-2xl border-2 transition flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold transition"
                                              :class="answers[currentQ.id] == opt.id ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600'"
                                              x-text="opt.option_label"></span>
                                        <span class="text-sm font-medium flex-1" style="font-family:'Noto Sans JP';" x-text="opt.option_text"></span>
                                    </button>
                                </template>
                            </div>

                            {{-- Navigation Buttons --}}
                            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-100">
                                <button type="button"
                                        @click="if(currentIndex > 0) currentIndex--"
                                        :disabled="currentIndex === 0"
                                        class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition disabled:opacity-30 disabled:cursor-not-allowed">
                                    ← Sebelumnya
                                </button>
                                <button type="button"
                                        @click="if(currentIndex < questions.length - 1) currentIndex++"
                                        :disabled="currentIndex === questions.length - 1"
                                        class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white transition hover:opacity-90 shadow-sm disabled:opacity-30 disabled:cursor-not-allowed"
                                        style="background: #e63946;">
                                    Berikutnya →
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Right 1 Col: Navigator Palette --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl border border-gray-100 p-5 shadow-sm sticky top-28">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Nomor Soal</h3>
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="(q, idx) in questions" :key="q.id">
                            <button type="button"
                                    @click="currentIndex = idx"
                                    :class="{
                                        'ring-2 ring-red-500 ring-offset-2': currentIndex === idx,
                                        'bg-red-600 text-white font-bold': answers[q.id],
                                        'bg-gray-100 text-gray-700 hover:bg-gray-200': !answers[q.id]
                                    }"
                                    class="h-10 rounded-xl text-xs font-medium flex items-center justify-center transition">
                                <span x-text="idx + 1"></span>
                            </button>
                        </template>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100 text-[11px] text-gray-500 space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-600 inline-block"></span> Sudah Dijawab
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gray-200 inline-block"></span> Belum Dijawab
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-layouts.student>

