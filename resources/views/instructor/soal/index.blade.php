<x-layouts.instructor>
<x-slot name="title">Bank Soal</x-slot>
<x-slot name="pageTitle">Bank Soal & Latihan</x-slot>

<div x-data="{ showImportModal: false, activeFormatTab: 'word' }">
    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Bank Soal (問題集)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola koleksi soal pilihan ganda, latihan JLPT, tata bahasa, dan kanji</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" @click="showImportModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 shadow-sm transition hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                📥 Import Dokumen (Word/Excel)
            </button>
            <a href="{{ route('instructor.soal.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Soal Baru
            </a>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="relative sm:col-span-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari teks soal..."
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300">
                </div>

                <select name="jlpt_level" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300">
                    <option value="">Semua Level JLPT</option>
                    @foreach(['N5', 'N4', 'N3', 'N2', 'N1'] as $lvl)
                        <option value="{{ $lvl }}" {{ request('jlpt_level') === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                    @endforeach
                </select>

                <select name="category" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300">
                    <option value="">Semua Kategori</option>
                    <option value="kosakata" {{ request('category') === 'kosakata' ? 'selected' : '' }}>Kosakata (文字・語彙)</option>
                    <option value="tata_bahasa" {{ request('category') === 'tata_bahasa' ? 'selected' : '' }}>Tata Bahasa (文法)</option>
                    <option value="membaca" {{ request('category') === 'membaca' ? 'selected' : '' }}>Membaca (読解)</option>
                    <option value="menyimak" {{ request('category') === 'menyimak' ? 'selected' : '' }}>Menyimak (聴解)</option>
                    <option value="kanji" {{ request('category') === 'kanji' ? 'selected' : '' }}>Kanji (漢字)</option>
                </select>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-lg transition"
                            style="background: #e63946;">Filter</button>
                    @if(request('search') || request('jlpt_level') || request('category'))
                        <a href="{{ route('instructor.soal.index') }}" class="px-2 py-2 text-xs text-gray-500 hover:text-gray-700">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Pertanyaan / Soal</th>
                        <th class="px-4 py-3 text-center">Level JLPT</th>
                        <th class="px-4 py-3 text-center">Kategori</th>
                        <th class="px-4 py-3 text-center">Pilihan Jawaban</th>
                        <th class="px-4 py-3 text-center">Poin</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($questions as $q)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 max-w-md">
                            <div class="font-medium text-gray-900 line-clamp-2 leading-relaxed">{{ $q->question_text }}</div>
                            @if($q->explanation)
                                <div class="text-[11px] text-gray-400 mt-1 line-clamp-1 italic">💡 {{ $q->explanation }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($q->jlpt_level)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">
                                    {{ $q->jlpt_level }}
                                </span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center text-xs text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $q->category ?? 'Umum')) }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $q->options->count() }} opsi
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center font-bold text-gray-700">
                            {{ $q->points }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('instructor.soal.edit', $q) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 transition">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('instructor.soal.destroy', $q) }}" onsubmit="return confirm('Hapus butir soal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">❓</div>
                            <p class="text-sm">Belum ada butir soal dalam bank soal.</p>
                            <a href="{{ route('instructor.soal.create') }}" class="inline-block mt-3 text-xs font-semibold text-red-600 hover:underline">
                                + Tambah butir soal pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($questions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $questions->links() }}</div>
        @endif
    </div>

    {{-- Modal Import Soal dari Dokumen --}}
    <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-xs">
        <div @click.away="showImportModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                        📥
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Import Butir Soal dari Dokumen</h3>
                        <p class="text-xs text-gray-500">Unggah berkas Microsoft Word (.docx), Excel (.csv), atau Catatan Teks (.txt)</p>
                    </div>
                </div>
                <button type="button" @click="showImportModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold p-1">✕</button>
            </div>

            {{-- Template Download Buttons --}}
            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 mb-5">
                <div class="text-xs font-semibold text-gray-700 mb-2 flex items-center gap-1.5">
                    <span>💡</span> Belum memiliki format dokumen? Unduh template resmi di bawah ini:
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('instructor.soal.template', 'docx') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold transition">
                        <span>📄</span> Template Word (.docx)
                    </a>
                    <a href="{{ route('instructor.soal.template', 'csv') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition">
                        <span>📊</span> Template Excel / CSV (.csv)
                    </a>
                    <a href="{{ route('instructor.soal.template', 'txt') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 rounded-xl text-xs font-bold transition">
                        <span>📝</span> Template Teks (.txt)
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('instructor.soal.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-4 mb-5">
                    {{-- Subject selector (optional) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Mata Pelajaran (Opsional)</label>
                        <select name="subject_id" class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Tanpa Mata Pelajaran Khusus --</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- File upload box --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Dokumen Soal (.docx, .csv, .txt) <span class="text-red-500">*</span></label>
                        <div class="border-2 border-dashed border-gray-200 hover:border-red-300 rounded-2xl p-5 text-center transition bg-gray-50/50">
                            <input type="file" name="document" accept=".docx,.csv,.txt" required
                                   class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                            <p class="text-[11px] text-gray-400 mt-2">Mendukung huruf Jepang (Kanji, Hiragana, Katakana) dan teks Bahasa Indonesia secara utuh.</p>
                        </div>
                    </div>

                    {{-- Panduan Format --}}
                    <div class="border border-gray-100 rounded-2xl p-4 bg-white">
                        <div class="flex items-center justify-between cursor-pointer mb-2">
                            <span class="text-xs font-bold text-gray-800">Contoh Format Penulisan Dokumen:</span>
                            <div class="flex gap-1 text-[11px]">
                                <button type="button" @click="activeFormatTab = 'word'"
                                        :class="activeFormatTab === 'word' ? 'font-bold text-red-600 border-b-2 border-red-500' : 'text-gray-400'">
                                    Format Word/Teks
                                </button>
                                <span class="text-gray-300">|</span>
                                <button type="button" @click="activeFormatTab = 'csv'"
                                        :class="activeFormatTab === 'csv' ? 'font-bold text-red-600 border-b-2 border-red-500' : 'text-gray-400'">
                                    Format Excel (CSV)
                                </button>
                            </div>
                        </div>

                        {{-- Tab Word/Teks --}}
                        <div x-show="activeFormatTab === 'word'" class="text-[11px] font-mono bg-gray-900 text-gray-100 p-3 rounded-xl overflow-x-auto leading-relaxed">
Soal: Apa lawan kata dari 「高い」(takai)?
A. やすい (yasui)
B. ひくい (hikui)
C. おもい (omoi)
D. ながい (nagai)
Kunci: A
Level: N5
Kategori: kosakata
Kesulitan: mudah
Poin: 2
Pembahasan: やすい (yasui) berarti murah.
                        </div>

                        {{-- Tab CSV --}}
                        <div x-show="activeFormatTab === 'csv'" class="text-[11px] font-mono bg-gray-900 text-gray-100 p-3 rounded-xl overflow-x-auto leading-relaxed" style="display: none;">
Pertanyaan,Pilihan A,Pilihan B,Pilihan C,Pilihan D,Kunci Jawaban,Level JLPT,Kategori,Poin
"Apa arti 「猫」?","Anjing","Kucing","Burung","Ikan","B","N5","kosakata","2"
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="showImportModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white rounded-xl shadow-sm transition hover:scale-105"
                            style="background: linear-gradient(135deg, #10b981, #059669);">
                        🚀 Mulai Import Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</x-layouts.instructor>
