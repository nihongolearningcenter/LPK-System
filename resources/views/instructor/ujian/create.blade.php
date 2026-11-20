<x-layouts.instructor>
<x-slot name="title">Buat Paket Ujian</x-slot>
<x-slot name="pageTitle">Buat Paket Ujian Baru</x-slot>

    <div class="max-w-4xl mx-auto" x-data="{
        selectedQuestions: {{ json_encode(old('question_ids', [])) }},
        search: '',
        filterLevel: '',
        filterCategory: '',
        get totalPoints() {
            let pts = 0;
            const items = document.querySelectorAll('.question-checkbox:checked');
            items.forEach(el => pts += parseInt(el.dataset.points || 1));
            return pts;
        }
    }">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Buat Paket Ujian Baru</h1>
                <p class="text-sm text-gray-500 mt-0.5">Atur durasi, ambang kelulusan, dan pilih butir soal ujian</p>
            </div>
            <a href="{{ route('instructor.ujian.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('instructor.ujian.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- 1. Pengaturan Dasar Ujian --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
                <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <span>⚙️</span> Informasi & Pengaturan Ujian
                </h2>

                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Judul Paket Ujian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Simulasi JLPT N5 — Sesi 1 (Tata Bahasa & Kosakata)"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Ujian & Level JLPT --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Tipe Ujian <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="simulasi_jlpt" {{ old('type') === 'simulasi_jlpt' ? 'selected' : '' }}>Simulasi JLPT Resmi</option>
                            <option value="latihan" {{ old('type') === 'latihan' ? 'selected' : '' }}>Latihan / Quiz Interaktif</option>
                            <option value="ujian_harian" {{ old('type') === 'ujian_harian' ? 'selected' : '' }}>Ujian Harian</option>
                            <option value="ujian_tengah" {{ old('type') === 'ujian_tengah' ? 'selected' : '' }}>Ujian Tengah Semester (UTS)</option>
                            <option value="ujian_akhir" {{ old('type') === 'ujian_akhir' ? 'selected' : '' }}>Ujian Akhir Semester (UAS)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Target Level JLPT
                        </label>
                        <select name="jlpt_level" x-model="filterLevel" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Non-JLPT / Semua Level --</option>
                            @foreach(['N5', 'N4', 'N3', 'N2', 'N1'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('jlpt_level') === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Kelas & Mapel --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Kelas Terkait
                        </label>
                        <select name="class_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Terbuka Untuk Semua Kelas --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Mata Pelajaran
                        </label>
                        <select name="subject_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Semua Mata Pelajaran --</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Durasi & Skor Kelulusan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Durasi Pengerjaan (Menit) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="5" max="300" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Passing Score / Skor Minimal Lulus (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="1" name="passing_score" value="{{ old('passing_score', 65) }}" min="0" max="100" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Deskripsi/Petunjuk Ujian --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Petunjuk Pengerjaan Ujian
                    </label>
                    <textarea name="description" rows="3"
                              placeholder="Petunjuk dan peraturan ujian untuk peserta..."
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('description') }}</textarea>
                </div>

                {{-- Status Publikasi --}}
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                               class="rounded text-red-600 focus:ring-red-400 w-4 h-4">
                        <span class="text-sm text-gray-700 font-medium">Publikasikan ujian ke portal siswa</span>
                    </label>
                </div>
            </div>

            {{-- 2. Pemilihan Butir Soal dari Bank Soal --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <span>📝</span> Pilih Butir Soal Ujian (Bank Soal)
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Pilih soal dari Bank Soal atau upload dokumen soal sekaligus.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-red-50 text-red-700 text-xs font-bold rounded-lg border border-red-100">
                            Terpilih: <span x-text="selectedQuestions.length"></span> Soal
                        </span>
                        <a href="{{ route('instructor.soal.create') }}" target="_blank"
                           class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition">
                            + Buat Soal Baru ↗
                        </a>
                    </div>
                </div>

                {{-- Upload Berkas Dokumen Soal Langsung (Opsional) --}}
                <div class="bg-gradient-to-r from-emerald-50/70 to-teal-50/70 rounded-2xl p-4 border border-emerald-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">📥</span>
                            <div>
                                <span class="text-xs font-bold text-gray-800">Punya berkas soal Word (.docx) atau Excel (.csv)?</span>
                                <p class="text-[11px] text-gray-500">Unggah berkas dokumen di sini, soal akan otomatis dibuat dan digabungkan ke paket ujian ini.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 text-[11px]">
                            <a href="{{ route('instructor.soal.template', 'docx') }}" class="font-semibold text-blue-600 hover:underline">📄 Template Word</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('instructor.soal.template', 'csv') }}" class="font-semibold text-emerald-600 hover:underline">📊 Template CSV</a>
                        </div>
                    </div>
                    <input type="file" name="document" accept=".docx,.csv,.txt"
                           class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer bg-white rounded-xl p-1.5 border border-emerald-200">
                </div>

                {{-- Filter & Search Butir Soal --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <input type="text" x-model="search" placeholder="Cari isi teks soal..."
                               class="w-full text-xs border border-gray-200 rounded-xl px-3.5 py-2 focus:border-red-400 focus:outline-none">
                    </div>
                    <div>
                        <select x-model="filterCategory" class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 focus:border-red-400 focus:outline-none">
                            <option value="">-- Semua Kategori --</option>
                            <option value="tata_bahasa">Tata Bahasa (文法)</option>
                            <option value="kosakata">Kosakata (語彙)</option>
                            <option value="kanji">Kanji (漢字)</option>
                            <option value="membaca">Membaca (読解)</option>
                            <option value="pendengaran">Mendengar (聴解)</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button"
                                @click="document.querySelectorAll('.question-item:not([style*=\'none\']) input[type=checkbox]').forEach(el => {
                                    if (!selectedQuestions.includes(el.value)) selectedQuestions.push(el.value);
                                    el.checked = true;
                                })"
                                class="flex-1 px-2.5 py-2 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                            Pilih Semua
                        </button>
                        <button type="button"
                                @click="selectedQuestions = []; document.querySelectorAll('.question-checkbox').forEach(el => el.checked = false)"
                                class="px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 rounded-xl transition">
                            Reset
                        </button>
                    </div>
                </div>

                {{-- Daftar Butir Soal --}}
                @if($questions->isEmpty())
                    <div class="py-10 text-center text-gray-400 border border-dashed border-gray-200 rounded-2xl">
                        <div class="text-3xl mb-2">📭</div>
                        <p class="text-sm font-semibold text-gray-700">Bank Soal Masih Kosong</p>
                        <p class="text-xs text-gray-400 mt-1">Belum ada butir soal yang dibuat di Bank Soal.</p>
                        <a href="{{ route('instructor.soal.create') }}" target="_blank"
                           class="inline-block mt-3 px-4 py-2 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition">
                            + Buat Soal Pertama Sekarang ↗
                        </a>
                    </div>
                @else
                    <div class="max-h-96 overflow-y-auto space-y-3 pr-1 border border-gray-100 rounded-2xl p-3 bg-gray-50/50">
                        @foreach($questions as $q)
                        <div class="question-item p-4 bg-white rounded-xl border border-gray-100 hover:border-red-200 transition"
                             x-show="(!search || '{{ addslashes(strtolower($q->question_text)) }}'.includes(search.toLowerCase())) &&
                                     (!filterLevel || '{{ $q->jlpt_level }}' === filterLevel) &&
                                     (!filterCategory || '{{ $q->category }}' === filterCategory)">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="question_ids[]" value="{{ $q->id }}"
                                       data-points="{{ $q->points ?? 1 }}"
                                       x-model="selectedQuestions"
                                       class="question-checkbox mt-1 rounded text-red-600 focus:ring-red-400 w-4 h-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase
                                            {{ $q->jlpt_level ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $q->jlpt_level ?? 'Umum' }}
                                        </span>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-700 uppercase">
                                            {{ str_replace('_', ' ', $q->category ?? 'Umum') }}
                                        </span>
                                        <span class="text-[10px] font-medium text-gray-400">
                                            🪙 {{ $q->points ?? 1 }} Poin
                                        </span>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900 leading-snug" style="font-family:'Noto Sans JP', sans-serif;">
                                        {{ $q->question_text }}
                                    </p>

                                    @if($q->options->isNotEmpty())
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 mt-2 text-xs">
                                        @foreach($q->options as $opt)
                                        <div class="p-1.5 rounded-lg border {{ $opt->is_correct ? 'bg-green-50 border-green-200 text-green-900 font-semibold' : 'bg-gray-50 border-gray-100 text-gray-600' }}">
                                            <span class="font-bold mr-1">{{ $opt->option_label }}.</span>
                                            <span style="font-family:'Noto Sans JP', sans-serif;">{{ $opt->option_text }}</span>
                                            @if($opt->is_correct) ✅ @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('instructor.ujian.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    💾 Simpan & Publikasikan Ujian
                </button>
            </div>
        </form>
    </div>

</x-layouts.instructor>
