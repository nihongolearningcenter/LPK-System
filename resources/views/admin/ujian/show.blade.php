<x-layouts.admin>
<x-slot name="title">{{ $exam->title }} — Detail Ujian</x-slot>
<x-slot name="pageTitle">Detail & Butir Soal Ujian</x-slot>

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.ujian.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Ujian
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium truncate">{{ $exam->title }}</span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.ujian.edit', $exam) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 shadow-sm transition">
                ✏️ Edit Pengaturan
            </a>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ showAddQuestionModal: false }">

        {{-- Left 2 Cols: Attached Questions --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">📑 Butir Soal Ujian</h2>
                        <p class="text-xs text-gray-400">Total: {{ $exam->questions->count() }} soal terpasang</p>
                    </div>

                    <button @click="showAddQuestionModal = true"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-sm"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        + Ambil dari Bank Soal
                    </button>
                </div>

                <div class="space-y-4">
                    @forelse($exam->questions as $idx => $q)
                    <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50/50 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-lg">
                                Nomor {{ $idx + 1 }}
                            </span>
                            <form method="POST" action="{{ route('admin.ujian.removeQuestion', [$exam, $q]) }}"
                                  onsubmit="return confirm('Lepas soal ini dari paket ujian?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">
                                    🗑️ Lepas Soal
                                </button>
                            </form>
                        </div>

                        <div class="text-sm font-bold text-gray-900 mb-3" style="font-family:'Noto Sans JP';">
                            {{ $q->question_text }}
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            @foreach($q->options as $opt)
                            <div class="p-2 rounded-xl border {{ $opt->is_correct ? 'border-green-300 bg-green-50 text-green-900 font-semibold' : 'border-gray-200 bg-white text-gray-600' }}">
                                <span class="font-bold mr-1">{{ $opt->option_label }}.</span>
                                <span style="font-family:'Noto Sans JP';">{{ $opt->option_text }}</span>
                                @if($opt->is_correct) ✅ @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 text-gray-400 text-xs">
                        Belum ada butir soal yang ditautkan ke ujian ini.
                        <br>Klik tombol <strong>+ Ambil dari Bank Soal</strong> di atas untuk memasukkan pertanyaan.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Modal Add Question from Bank Soal --}}
            <div x-show="showAddQuestionModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                <div @click.away="showAddQuestionModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Pilih Soal dari Bank Soal</h3>
                    <p class="text-xs text-gray-500 mb-4">Pilih soal yang relevan untuk ditambahkan ke dalam paket ujian ini.</p>

                    @if($availableQuestions->count() > 0)
                    <form method="POST" action="{{ route('admin.ujian.addQuestion', $exam) }}">
                        @csrf
                        <div class="mb-5 max-h-60 overflow-y-auto space-y-2 border border-gray-100 p-2 rounded-2xl">
                            @foreach($availableQuestions as $aq)
                            <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-100 hover:bg-red-50 hover:border-red-200 cursor-pointer text-xs">
                                <input type="radio" name="question_id" value="{{ $aq->id }}" required class="mt-0.5 text-red-600">
                                <div>
                                    <div class="font-bold text-gray-800" style="font-family:'Noto Sans JP';">{{ $aq->question_text }}</div>
                                    <div class="text-gray-400 text-[11px] mt-0.5">{{ $aq->jlpt_level ?? 'Umum' }} • {{ $aq->category }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" @click="showAddQuestionModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 rounded-xl hover:bg-red-700">Tambahkan ke Ujian</button>
                        </div>
                    </form>
                    @else
                    <div class="py-6 text-center text-xs text-gray-400">
                        Tidak ada soal lain yang tersedia di Bank Soal. Silakan buat soal baru terlebih dahulu.
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="button" @click="showAddQuestionModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">Tutup</button>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right 1 Col: Exam Info & Attempts --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100">
                    Spesifikasi Ujian
                </h3>

                <div>
                    <div class="text-xs text-gray-400">Tipe Ujian</div>
                    <div class="font-bold text-gray-800 text-sm mt-0.5">{{ ucfirst(str_replace('_', ' ', $exam->type)) }}</div>
                </div>

                @if($exam->jlpt_level)
                <div>
                    <div class="text-xs text-gray-400">Level JLPT</div>
                    <div class="font-bold text-purple-700 text-sm mt-0.5">JLPT {{ $exam->jlpt_level }}</div>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <div class="text-gray-400">Durasi</div>
                        <div class="font-bold text-gray-800 text-sm mt-0.5">{{ $exam->duration_minutes }} Menit</div>
                    </div>
                    <div>
                        <div class="text-gray-400">KKM Nilai</div>
                        <div class="font-bold text-red-600 text-sm mt-0.5">{{ $exam->passing_score }}</div>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-400">Target Siswa</div>
                    <div class="font-medium text-gray-800 text-xs mt-0.5">
                        {{ $exam->classRoom ? 'Kelas: ' . $exam->classRoom->name : 'Terbuka untuk Semua Siswa' }}
                    </div>
                </div>

                @if($exam->description)
                <div>
                    <div class="text-xs text-gray-400">Petunjuk</div>
                    <div class="text-xs text-gray-600 bg-gray-50 p-3 rounded-xl mt-1">{{ $exam->description }}</div>
                </div>
                @endif
            </div>

            {{-- Recent Student Attempts Card --}}
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-3 border-b border-gray-100 mb-3">
                    Riwayat Siswa ({{ $exam->attempts->count() }})
                </h3>
                <div class="space-y-3 max-h-60 overflow-y-auto">
                    @forelse($exam->attempts->take(10) as $att)
                    <div class="flex items-center justify-between text-xs p-2 rounded-xl bg-gray-50">
                        <div>
                            <div class="font-bold text-gray-800">{{ $att->student?->name }}</div>
                            <div class="text-[11px] text-gray-400">{{ $att->submitted_at?->format('d M H:i') }}</div>
                        </div>
                        <span class="font-bold {{ $att->is_passed ? 'text-green-600' : 'text-red-600' }}">
                            {{ $att->score }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-xs text-gray-400">Belum ada siswa yang mengerjakan ujian ini.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</x-layouts.admin>

