<x-layouts.instructor>
<x-slot name="title">Edit Soal</x-slot>
<x-slot name="pageTitle">Edit Butir Soal</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Edit Butir Soal</h1>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui pertanyaan, opsi jawaban, atau pembahasan soal</p>
            </div>
            <a href="{{ route('instructor.soal.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('instructor.soal.update', $question) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="pilihan_ganda">

                {{-- Mapel, Level JLPT, Kategori --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Mata Pelajaran
                        </label>
                        <select name="subject_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Semua Mapel --</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id', $question->subject_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Level JLPT
                        </label>
                        <select name="jlpt_level" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Umum / Non-JLPT --</option>
                            @foreach(['N5', 'N4', 'N3', 'N2', 'N1'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('jlpt_level', $question->jlpt_level) === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Kategori Soal
                        </label>
                        <select name="category" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="kosakata" {{ old('category', $question->category) === 'kosakata' ? 'selected' : '' }}>Kosakata (文字・語彙)</option>
                            <option value="tata_bahasa" {{ old('category', $question->category) === 'tata_bahasa' ? 'selected' : '' }}>Tata Bahasa (文法)</option>
                            <option value="membaca" {{ old('category', $question->category) === 'membaca' ? 'selected' : '' }}>Membaca (読解)</option>
                            <option value="menyimak" {{ old('category', $question->category) === 'menyimak' ? 'selected' : '' }}>Menyimak (聴解)</option>
                            <option value="kanji" {{ old('category', $question->category) === 'kanji' ? 'selected' : '' }}>Kanji (漢字)</option>
                        </select>
                    </div>
                </div>

                {{-- Teks Pertanyaan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Teks Pertanyaan / Soal <span class="text-red-500">*</span>
                    </label>
                    <textarea name="question_text" rows="4" required
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                {{-- Pilihan Jawaban (A-D) --}}
                <div class="space-y-4">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Pilihan Jawaban (Pilih radio untuk jawaban yang benar) <span class="text-red-500">*</span>
                    </label>

                    @php
                        $options = $question->options->values();
                    @endphp

                    @for($i = 0; $i < 4; $i++)
                    @php
                        $opt = $options->get($i);
                        $isCorrect = $opt ? $opt->is_correct : ($i === 0);
                    @endphp
                    <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl">
                        <input type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option', $isCorrect ? $i : -1) == $i ? 'checked' : '' }}
                               required class="w-4 h-4 text-green-600 focus:ring-green-400 cursor-pointer">
                        <span class="font-bold text-gray-700 text-xs w-6">{{ chr(65 + $i) }}.</span>
                        <input type="text" name="options[{{ $i }}][text]" value="{{ old("options.$i.text", $opt?->option_text) }}" required
                               placeholder="Teks opsi {{ chr(65 + $i) }}"
                               class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:border-red-400 focus:outline-none">
                    </div>
                    @endfor
                </div>

                {{-- Bobot & Kesulitan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Poin / Skor Soal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="points" value="{{ old('points', $question->points) }}" min="1" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Tingkat Kesulitan
                        </label>
                        <select name="difficulty" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="mudah" {{ old('difficulty', $question->difficulty) === 'mudah' ? 'selected' : '' }}>Mudah</option>
                            <option value="sedang" {{ old('difficulty', $question->difficulty) === 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="sulit" {{ old('difficulty', $question->difficulty) === 'sulit' ? 'selected' : '' }}>Sulit</option>
                        </select>
                    </div>
                </div>

                {{-- Pembahasan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Pembahasan Jawaban
                    </label>
                    <textarea name="explanation" rows="3"
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('explanation', $question->explanation) }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('instructor.soal.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.instructor>

