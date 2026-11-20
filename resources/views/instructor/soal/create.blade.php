<x-layouts.instructor>
<x-slot name="title">Tambah Soal</x-slot>
<x-slot name="pageTitle">Tambah Soal Baru</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Tambah Butir Soal Baru</h1>
                <p class="text-sm text-gray-500 mt-0.5">Buat soal pilihan ganda baru untuk latihan atau bank soal ujian</p>
            </div>
            <a href="{{ route('instructor.soal.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('instructor.soal.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="type" value="pilihan_ganda">

                {{-- Mapel, Level JLPT, Kategori --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Mata Pelajaran (Opsional)
                        </label>
                        <select name="subject_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Semua Mapel --</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
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
                                <option value="{{ $lvl }}" {{ old('jlpt_level') === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Kategori Soal
                        </label>
                        <select name="category" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="kosakata" {{ old('category') === 'kosakata' ? 'selected' : '' }}>Kosakata (文字・語彙)</option>
                            <option value="tata_bahasa" {{ old('category') === 'tata_bahasa' ? 'selected' : '' }}>Tata Bahasa (文法)</option>
                            <option value="membaca" {{ old('category') === 'membaca' ? 'selected' : '' }}>Membaca (読解)</option>
                            <option value="menyimak" {{ old('category') === 'menyimak' ? 'selected' : '' }}>Menyimak (聴解)</option>
                            <option value="kanji" {{ old('category') === 'kanji' ? 'selected' : '' }}>Kanji (漢字)</option>
                        </select>
                    </div>
                </div>

                {{-- Teks Pertanyaan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Teks Pertanyaan / Soal <span class="text-red-500">*</span>
                    </label>
                    <textarea name="question_text" rows="4" required
                              placeholder="Tuliskan soal di sini (misal: 次の文の（　）に入れるのに最もよいものを、1・2・3・4から一つ選びなさい。)..."
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('question_text') border-red-400 @enderror">{{ old('question_text') }}</textarea>
                    @error('question_text')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilihan Jawaban (A-D) --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Pilihan Jawaban (Pilih radio untuk jawaban yang benar) <span class="text-red-500">*</span>
                        </label>
                    </div>

                    @for($i = 0; $i < 4; $i++)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-100 rounded-xl">
                        <input type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option', 0) == $i ? 'checked' : '' }}
                               required class="w-4 h-4 text-green-600 focus:ring-green-400 cursor-pointer">
                        <span class="font-bold text-gray-700 text-xs w-6">{{ chr(65 + $i) }}.</span>
                        <input type="text" name="options[{{ $i }}][text]" value="{{ old("options.$i.text") }}" required
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
                        <input type="number" name="points" value="{{ old('points', 2) }}" min="1" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Tingkat Kesulitan
                        </label>
                        <select name="difficulty" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="mudah" {{ old('difficulty') === 'mudah' ? 'selected' : '' }}>Mudah (Easy)</option>
                            <option value="sedang" {{ old('difficulty', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang (Medium)</option>
                            <option value="sulit" {{ old('difficulty') === 'sulit' ? 'selected' : '' }}>Sulit (Hard)</option>
                        </select>
                    </div>
                </div>

                {{-- Pembahasan / Penjelasan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Pembahasan Jawaban (Explanation)
                    </label>
                    <textarea name="explanation" rows="3"
                              placeholder="Tuliskan alasan mengapa jawaban tersebut benar dan penjelasan tata bahasa..."
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('explanation') }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('instructor.soal.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        💾 Simpan Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.instructor>

