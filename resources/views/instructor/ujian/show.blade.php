<x-layouts.instructor>
<x-slot name="title">{{ $ujian->title }}</x-slot>
<x-slot name="pageTitle">Detail Paket Ujian</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('instructor.ujian.index') }}" class="text-xs text-indigo-600 hover:underline">← Semua Ujian</a>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mt-1">{{ $ujian->title }}</h1>
            <p class="text-sm text-gray-500">
                Tipe: <strong class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $ujian->type)) }}</strong>
                @if($ujian->jlpt_level) | Level: <span class="text-red-600 font-bold">{{ $ujian->jlpt_level }}</span> @endif
                | Durasi: <strong>{{ $ujian->duration_minutes }} Menit</strong>
                | KKM: <strong>{{ $ujian->passing_score }}%</strong>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('instructor.ujian.edit', $ujian) }}"
               class="px-4 py-2 text-sm font-semibold text-white rounded-xl shadow-sm hover:opacity-95"
               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                ✏️ Edit Pengaturan
            </a>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6"
         x-data="{ tab: 'questions', showAddQuestionModal: false, showImportDocModal: false, activeFormatTab: 'word', modalSearch: '', modalCategory: '' }">
        <div class="px-6 py-3 border-b border-gray-100 flex gap-4 text-xs font-semibold">
            <button @click="tab = 'questions'" :class="tab === 'questions' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                ❓ Butir Soal Ujian ({{ $ujian->questions->count() }})
            </button>
            <button @click="tab = 'attempts'" :class="tab === 'attempts' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                👥 Riwayat Pengerjaan Siswa ({{ $ujian->attempts->count() }})
            </button>
        </div>

        {{-- Tab Questions --}}
        <div x-show="tab === 'questions'" class="p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100 flex-wrap gap-3">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Daftar Soal Terpasang</h2>
                    <p class="text-xs text-gray-400">Total: {{ $ujian->questions->count() }} butir soal terpasang</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button @click="showImportDocModal = true"
                            class="px-3.5 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition shadow-sm flex items-center gap-1.5">
                        <span>📥</span> Import Dokumen Soal
                    </button>
                    <button @click="showAddQuestionModal = true"
                            class="px-3.5 py-2 text-xs font-bold text-white rounded-xl shadow-sm transition hover:opacity-95 flex items-center gap-1.5"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        <span>+</span> Ambil dari Bank Soal
                    </button>
                    <a href="{{ route('instructor.soal.create') }}" target="_blank"
                       class="px-3 py-2 text-xs font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-xl hover:bg-indigo-100 transition">
                        + Buat Soal Baru ↗
                    </a>
                </div>
            </div>

            @if($ujian->questions->isEmpty())
                <div class="py-12 text-center text-gray-400">
                    <div class="text-4xl mb-2">❓</div>
                    <p class="text-sm font-semibold text-gray-700">Belum Ada Butir Soal</p>
                    <p class="text-xs text-gray-400 mt-1">Paket ujian ini belum memiliki butir soal untuk dikerjakan siswa.</p>
                    <div class="mt-4 flex items-center justify-center gap-2 flex-wrap">
                        <button @click="showImportDocModal = true"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-1.5">
                            <span>📥</span> Import Dokumen (Word/Excel)
                        </button>
                        <button @click="showAddQuestionModal = true"
                                class="px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition">
                            + Ambil dari Bank Soal
                        </button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($ujian->questions as $index => $q)
                    <div class="p-5 bg-gray-50/70 rounded-2xl border border-gray-100 hover:border-gray-200 transition">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="w-6 h-6 rounded-full bg-red-100 text-red-700 font-bold text-xs flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded uppercase bg-blue-50 text-blue-700">
                                    {{ str_replace('_', ' ', $q->category ?? 'Umum') }}
                                </span>
                                @if($q->jlpt_level)
                                <span class="text-xs font-bold px-2 py-0.5 rounded uppercase bg-red-100 text-red-700">
                                    {{ $q->jlpt_level }}
                                </span>
                                @endif
                                <span class="text-xs text-gray-400">({{ $q->points ?? 1 }} Poin)</span>
                            </div>

                            <form method="POST" action="{{ route('instructor.ujian.removeQuestion', [$ujian, $q]) }}"
                                  onsubmit="return confirm('Lepas butir soal ini dari paket ujian?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1 hover:underline">
                                    🗑️ Lepas Soal
                                </button>
                            </form>
                        </div>

                        <p class="text-sm text-gray-900 font-semibold mb-3" style="font-family:'Noto Sans JP', sans-serif;">
                            {{ $q->question_text }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            @foreach($q->options as $opt)
                            <div class="p-2.5 rounded-xl border {{ $opt->is_correct ? 'bg-green-50 border-green-200 text-green-900 font-bold' : 'bg-white border-gray-100 text-gray-600' }}">
                                <span class="font-bold mr-1">{{ $opt->option_label }}.</span>
                                <span style="font-family:'Noto Sans JP', sans-serif;">{{ $opt->option_text }}</span>
                                @if($opt->is_correct) <span class="text-green-600 font-semibold">(Benar ✅)</span> @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tab Attempts --}}
        <div x-show="tab === 'attempts'" class="p-6">
            @if($ujian->attempts->isEmpty())
                <p class="text-xs text-gray-400 text-center py-8">Belum ada siswa yang mengerjakan ujian ini.</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase">
                            <th class="p-3">Siswa</th>
                            <th class="p-3 text-center">Nilai Total</th>
                            <th class="p-3 text-center">Status Kelulusan</th>
                            <th class="p-3 text-right">Waktu Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($ujian->attempts as $att)
                        <tr>
                            <td class="p-3 font-semibold text-gray-900">{{ $att->student?->name ?? '-' }}</td>
                            <td class="p-3 text-center font-bold text-gray-900">{{ number_format($att->total_score, 1) }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $att->is_passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $att->is_passed ? 'LULUS' : 'TIDAK LULUS' }}
                                </span>
                            </td>
                            <td class="p-3 text-right text-gray-400">{{ $att->completed_at ? $att->completed_at->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Modal Add Question from Bank Soal --}}
        <div x-show="showAddQuestionModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div @click.away="showAddQuestionModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pilih Soal dari Bank Soal</h3>
                        <p class="text-xs text-gray-500">Pilih soal untuk ditambahkan ke dalam paket ujian ini.</p>
                    </div>
                    <button type="button" @click="showAddQuestionModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
                </div>

                @if($availableQuestions->count() > 0)
                <form method="POST" action="{{ route('instructor.ujian.addQuestion', $ujian) }}">
                    @csrf
                    
                    {{-- Search & Category Filter --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                        <input type="text" x-model="modalSearch" placeholder="Cari teks soal..."
                               class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 focus:border-red-400 focus:outline-none">
                        <select x-model="modalCategory" class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 focus:border-red-400 focus:outline-none">
                            <option value="">-- Semua Kategori --</option>
                            <option value="tata_bahasa">Tata Bahasa</option>
                            <option value="kosakata">Kosakata</option>
                            <option value="kanji">Kanji</option>
                            <option value="membaca">Membaca</option>
                            <option value="pendengaran">Mendengar</option>
                        </select>
                    </div>

                    <div class="max-h-72 overflow-y-auto space-y-2 border border-gray-100 p-2 rounded-2xl bg-gray-50/50 mb-5">
                        @foreach($availableQuestions as $aq)
                        <label class="flex items-start gap-3 p-3 bg-white rounded-xl border border-gray-100 hover:border-red-300 hover:bg-red-50/30 cursor-pointer text-xs transition"
                               x-show="(!modalSearch || '{{ addslashes(strtolower($aq->question_text)) }}'.includes(modalSearch.toLowerCase())) &&
                                       (!modalCategory || '{{ $aq->category }}' === modalCategory)">
                            <input type="radio" name="question_id" value="{{ $aq->id }}" required class="mt-0.5 text-red-600">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-gray-800" style="font-family:'Noto Sans JP', sans-serif;">{{ $aq->question_text }}</div>
                                <div class="text-gray-400 text-[11px] mt-1 flex items-center gap-2">
                                    <span class="font-semibold text-red-600">{{ $aq->jlpt_level ?? 'Umum' }}</span>
                                    <span>•</span>
                                    <span>{{ str_replace('_', ' ', $aq->category ?? 'Umum') }}</span>
                                    <span>•</span>
                                    <span>🪙 {{ $aq->points ?? 1 }} Poin</span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="showAddQuestionModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white rounded-xl shadow-sm hover:opacity-90"
                                style="background: linear-gradient(135deg, #e63946, #c1121f);">
                            + Masukkan ke Ujian
                        </button>
                    </div>
                </form>
                @else
                <div class="py-8 text-center text-gray-400 text-xs">
                    <p class="font-semibold text-gray-700 mb-1">Semua butir soal di Bank Soal sudah ditambahkan.</p>
                    <p>Anda dapat membuat butir soal baru melalui Bank Soal.</p>
                    <a href="{{ route('instructor.soal.create') }}" target="_blank"
                       class="inline-block mt-3 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700">
                        + Buat Soal Baru di Bank Soal ↗
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Modal Import Soal dari Dokumen Langsung ke Ujian --}}
        <div x-show="showImportDocModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-xs">
            <div @click.away="showImportDocModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                            📥
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Import Dokumen Soal ke Ujian Ini</h3>
                            <p class="text-xs text-gray-500">Soal akan otomatis dibuat di Bank Soal dan langsung dipasang ke paket ujian ini.</p>
                        </div>
                    </div>
                    <button type="button" @click="showImportDocModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold p-1">✕</button>
                </div>

                {{-- Template Download Buttons --}}
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 mb-5">
                    <div class="text-xs font-semibold text-gray-700 mb-2 flex items-center gap-1.5">
                        <span>💡</span> Unduh template dokumen resmi:
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

                <form method="POST" action="{{ route('instructor.ujian.importQuestions', $ujian) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-4 mb-5">
                        {{-- File upload box --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Dokumen Soal (.docx, .csv, .txt) <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-200 hover:border-emerald-300 rounded-2xl p-5 text-center transition bg-gray-50/50">
                                <input type="file" name="document" accept=".docx,.csv,.txt" required
                                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                <p class="text-[11px] text-gray-400 mt-2">Mendukung huruf Jepang (Kanji, Hiragana, Katakana) dan teks Bahasa Indonesia secara utuh.</p>
                            </div>
                        </div>

                        {{-- Panduan Format --}}
                        <div class="border border-gray-100 rounded-2xl p-4 bg-white">
                            <div class="flex items-center justify-between cursor-pointer mb-2">
                                <span class="text-xs font-bold text-gray-800">Contoh Format Penulisan:</span>
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
Soal: Apa arti dari kata 「猫」(ねこ)?
A. Anjing
B. Kucing
C. Burung
D. Ikan
Kunci: B
Level: N5
Kategori: kosakata
Kesulitan: mudah
Poin: 2
Pembahasan: 「猫」 dibaca neko yang berarti kucing.
                            </div>

                            {{-- Tab CSV --}}
                            <div x-show="activeFormatTab === 'csv'" class="text-[11px] font-mono bg-gray-900 text-gray-100 p-3 rounded-xl overflow-x-auto leading-relaxed" style="display: none;">
Pertanyaan,Pilihan A,Pilihan B,Pilihan C,Pilihan D,Kunci Jawaban,Level JLPT,Kategori,Poin
"Apa arti 「猫」?","Anjing","Kucing","Burung","Ikan","B","N5","kosakata","2"
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showImportDocModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white rounded-xl shadow-sm transition hover:scale-105"
                                style="background: linear-gradient(135deg, #10b981, #059669);">
                            🚀 Import & Masukkan ke Ujian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.instructor>
