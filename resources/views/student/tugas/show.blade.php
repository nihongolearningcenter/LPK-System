<x-layouts.student>
<x-slot name="title">{{ $assignment->title }}</x-slot>
<x-slot name="pageTitle">Detail & Pengumpulan Tugas</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('student.tugas.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Tugas
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium truncate">{{ $assignment->title }}</span>
        </div>

        {{-- Assignment Info Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                    {{ $assignment->classRoom?->name ?? 'Kelas' }}
                </span>
                @if($assignment->subject)
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                    {{ $assignment->subject->name }}
                </span>
                @endif
                <span class="text-xs text-gray-400">
                    Tenggat: <strong class="text-gray-700">{{ $assignment->due_date ? $assignment->due_date->format('d M Y, H:i') : 'Tanpa batas waktu' }}</strong>
                </span>
            </div>

            <h1 class="text-2xl font-extrabold text-gray-900 mb-4">{{ $assignment->title }}</h1>

            @if($assignment->description)
            <div class="text-sm text-gray-700 leading-relaxed bg-gray-50 rounded-2xl p-5 mb-6 border border-gray-100">
                {!! nl2br(e($assignment->description)) !!}
            </div>
            @endif

            @if($assignment->file_attachment)
            <div class="p-4 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📎</span>
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Lampiran Instruksi / Lembar Soal</div>
                        <div class="text-xs text-gray-500">Unduh dokumen ini untuk panduan pengerjaan</div>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $assignment->file_attachment) }}" target="_blank"
                   class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition">
                    Unduh Soal
                </a>
            </div>
            @endif

            {{-- Submission Status & Form --}}
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span>📤</span> Pengumpulan Tugas
                </h2>

                @if($submission)
                    <div class="rounded-2xl p-5 mb-6 {{ $submission->status === 'graded' ? 'bg-green-50 border border-green-200' : 'bg-blue-50 border border-blue-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider {{ $submission->status === 'graded' ? 'text-green-800' : 'text-blue-800' }}">
                                {{ $submission->status === 'graded' ? '✅ Tugas Telah Dinilai' : '📥 Sudah Dikumpulkan' }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $submission->submitted_at?->format('d M Y, H:i') }}</span>
                        </div>

                        @if($submission->status === 'graded')
                            <div class="text-2xl font-black text-green-700 mb-2">
                                Nilai: {{ $submission->score }} / {{ $assignment->max_score }}
                            </div>
                            @if($submission->feedback)
                            <div class="text-xs text-gray-600 bg-white/70 p-3 rounded-xl mt-2">
                                <strong>Komentar Pengajar:</strong> {{ $submission->feedback }}
                            </div>
                            @endif
                        @else
                            <p class="text-xs text-blue-900">Tugas Anda telah berhasil dikirim dan sedang menunggu penilaian dari sensei.</p>
                        @endif

                        @if($submission->answer_text)
                        <div class="mt-4 pt-3 border-t border-blue-200/50 text-xs text-gray-700">
                            <strong>Jawaban Anda:</strong>
                            <div class="mt-1 bg-white p-3 rounded-xl whitespace-pre-line">{{ $submission->answer_text }}</div>
                        </div>
                        @endif
                    </div>
                @endif

                {{-- Submission Form --}}
                <form method="POST" action="{{ route('student.tugas.submit', $assignment) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Jawaban Teks / Catatan</label>
                        <textarea name="answer_text" rows="5" placeholder="Tuliskan jawaban atau link dokumen tugas Anda di sini..."
                                  class="w-full text-sm border border-gray-200 rounded-2xl p-4 focus:outline-none focus:border-red-400">{{ old('answer_text', $submission?->answer_text) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Unggah File Tugas (PDF / Gambar / ZIP, Maks. 10MB)</label>
                        <input type="file" name="file_submission"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-sm"
                                style="background: linear-gradient(135deg, #e63946, #c1121f);">
                            {{ $submission ? 'Perbarui Pengumpulan Tugas' : 'Kirim Tugas Sekarang' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.student>
