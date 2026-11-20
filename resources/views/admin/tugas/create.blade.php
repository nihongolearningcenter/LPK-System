<x-layouts.admin>
<x-slot name="title">Buat Tugas Baru</x-slot>
<x-slot name="pageTitle">Buat Tugas Baru</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Buat Tugas Baru</h1>
                <p class="text-sm text-gray-500 mt-0.5">Berikan tugas atau latihan kepada siswa di kelas tertentu</p>
            </div>
            <a href="{{ route('admin.tugas.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.tugas.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Judul Tugas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Latihan Menulis Hiragana Bab 1-3 & Terjemahan"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kelas & Mapel --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Pilih Kelas <span class="text-red-500">*</span>
                        </label>
                        <select name="class_id" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('class_id') border-red-400 @enderror">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->program?->title ?? 'Umum' }})
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Mata Pelajaran (Opsional)
                        </label>
                        <select name="subject_id"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Semua Mata Pelajaran --</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Deskripsi/Instruksi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Instruksi & Deskripsi Tugas
                    </label>
                    <textarea name="description" rows="5"
                              placeholder="Tuliskan petunjuk pengerjaan tugas secara rinci untuk siswa..."
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('description') }}</textarea>
                </div>

                {{-- Lampiran Berkas --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Berkas Lampiran Soal / Lembar Kerja (Opsional)
                    </label>
                    <input type="file" name="file_attachment"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    <p class="text-xs text-gray-400 mt-1">Format PDF, DOC, ZIP, atau Gambar (Maks 10MB)</p>
                </div>

                {{-- Deadline & Nilai Maksimum --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Batas Waktu Pengumpulan (Deadline)
                        </label>
                        <input type="datetime-local" name="due_date" value="{{ old('due_date') }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nilai Maksimum <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="1" min="1" max="100" name="max_score" value="{{ old('max_score', 100) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Pengaturan Tambahan --}}
                <div class="space-y-3 pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="allow_late_submission" value="1" {{ old('allow_late_submission', true) ? 'checked' : '' }}
                               class="rounded text-red-600 focus:ring-red-400 w-4 h-4">
                        <span class="text-sm text-gray-700">Izinkan pengumpulan tugas terlambat (melewati batas waktu)</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                               class="rounded text-red-600 focus:ring-red-400 w-4 h-4">
                        <span class="text-sm text-gray-700 font-medium">Langsung publikasikan tugas ke portal siswa</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.tugas.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        💾 Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>

