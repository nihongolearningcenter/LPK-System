<x-layouts.admin>
<x-slot name="title">Tambah Soal Baru</x-slot>
<x-slot name="pageTitle">Tambah Soal</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.bank-soal.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Bank Soal
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Tambah Soal Baru</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 max-w-4xl">
        <form method="POST" action="{{ route('admin.bank-soal.store') }}">
            @csrf

            <div class="space-y-6">

                {{-- Classification --}}
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Level JLPT</label>
                        <select name="jlpt_level" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Pilih Level...</option>
                            @foreach(['N5', 'N4', 'N3', 'N2', 'N1', 'dasar'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('jlpt_level') === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Kategori</label>
                        <select name="category" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="kosakata" {{ old('category') === 'kosakata' ? 'selected' : '' }}>Kosakata (Goi)</option>
                            <option value="tata_bahasa" {{ old('category') === 'tata_bahasa' ? 'selected' : '' }}>Tata Bahasa (Bunpou)</option>
                            <option value="kanji" {{ old('category') === 'kanji' ? 'selected' : '' }}>Kanji (Moji)</option>
                            <option value="membaca" {{ old('category') === 'membaca' ? 'selected' : '' }}>Membaca (Dokkai)</option>
                            <option value="menyimak" {{ old('category') === 'menyimak' ? 'selected' : '' }}>Menyimak (Choukai)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tingkat Kesulitan</label>
                        <select name="difficulty" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="mudah" {{ old('difficulty') === 'mudah' ? 'selected' : '' }}>Mudah</option>
                            <option value="sedang" {{ old('difficulty', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="sulit" {{ old('difficulty') === 'sulit' ? 'selected' : '' }}>Sulit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Bobot Poin</label>
                        <input type="number" name="points" value="{{ old('points', 20) }}" min="1" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>
                </div>

                {{-- Hidden Type default to multiple choice --}}
                <input type="hidden" name="type" value="pilihan_ganda">

                {{-- Question Text --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Teks Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="4" required placeholder="Tuliskan teks soal (dapat menggunakan huruf Kanji, Hiragana, Katakana, dan Romaji)..."
                              class="w-full text-sm border border-gray-200 rounded-2xl p-4 focus:outline-none focus:border-red-400"
                              style="font-family:'Noto Sans JP', sans-serif;">{{ old('question_text') }}</textarea>
                    @error('question_text') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Multiple Choice Options --}}
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Pilihan Jawaban & Kunci Benar <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-400 mb-2">Pilih radio button di sebelah kiri untuk menandai jawaban yang benar.</p>

                    @foreach(['A', 'B', 'C', 'D'] as $i => $label)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                        <input type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option', '0') == $i ? 'checked' : '' }}
                               class="w-5 h-5 text-red-600 focus:ring-red-500 cursor-pointer">
                        <span class="w-8 h-8 rounded-xl bg-white border border-gray-200 text-xs font-bold flex items-center justify-center text-gray-700 flex-shrink-0">
                            {{ $label }}
                        </span>
                        <input type="text" name="options[{{ $i }}][text]" value="{{ old("options.{$i}.text") }}" required
                               placeholder="Isi teks pilihan {{ $label }}..."
                               class="flex-1 text-sm bg-white border border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-red-400"
                               style="font-family:'Noto Sans JP', sans-serif;">
                    </div>
                    @endforeach
                </div>

                {{-- Explanation --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Pembahasan / Penjelasan Jawaban</label>
                    <textarea name="explanation" rows="3" placeholder="Tuliskan pembahasan mengapa jawaban tersebut benar untuk ditampilkan kepada siswa setelah ujian..."
                              class="w-full text-sm border border-gray-200 rounded-2xl p-4 focus:outline-none focus:border-red-400">{{ old('explanation') }}</textarea>
                </div>

            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.bank-soal.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Simpan Soal ke Bank Soal
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>

