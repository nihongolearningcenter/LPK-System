<x-layouts.admin>
<x-slot name="title">Tambah Kosakata Baru</x-slot>
<x-slot name="pageTitle">Tambah Kosakata</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.kosakata.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Master Kosakata
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Tambah Kosakata Baru</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('admin.kosakata.store') }}">
            @csrf

            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kata Jepang (Kanji / Kana) <span class="text-red-500">*</span></label>
                        <input type="text" name="japanese" value="{{ old('japanese') }}" required placeholder="Contoh: 学校 atau がっこう"
                               class="w-full text-base font-bold border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400"
                               style="font-family:'Noto Sans JP';">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Arti Bahasa Indonesia <span class="text-red-500">*</span></label>
                        <input type="text" name="meaning_id" value="{{ old('meaning_id') }}" required placeholder="Contoh: Sekolah"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cara Baca Hiragana</label>
                        <input type="text" name="hiragana" value="{{ old('hiragana') }}" placeholder="Contoh: がっこう"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400"
                               style="font-family:'Noto Sans JP';">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cara Baca Romaji</label>
                        <input type="text" name="romaji" value="{{ old('romaji') }}" placeholder="Contoh: gakkou"
                               class="w-full text-sm font-mono border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tema / Kategori</label>
                        <select name="category" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Jenis Kata</label>
                        <input type="text" name="part_of_speech" value="{{ old('part_of_speech') }}" placeholder="Kata Benda / Kata Kerja"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Contoh Kalimat (Bahasa Jepang)</label>
                    <input type="text" name="example_sentence_jp" value="{{ old('example_sentence_jp') }}" placeholder="Contoh: まいにち がっこうへ いきます。"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400"
                           style="font-family:'Noto Sans JP';">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Terjemahan Contoh Kalimat (Bahasa Indonesia)</label>
                    <input type="text" name="example_sentence_id" value="{{ old('example_sentence_id') }}" placeholder="Contoh: Setiap hari saya pergi ke sekolah."
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.kosakata.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Simpan Kosakata
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>

