<x-layouts.instructor>
<x-slot name="title">Tambah Materi Baru</x-slot>
<x-slot name="pageTitle">Tambah Materi Pembelajaran</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Tambah Materi Pembelajaran</h1>
                <p class="text-sm text-gray-500 mt-0.5">Unggah materi bacaan, video pengajaran, atau dokumen latihan</p>
            </div>
            <a href="{{ route('instructor.materi.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('instructor.materi.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Judul Materi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Bab 1 - Salam Sapaan & Perkenalan Diri (Aisatsu)"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mapel & Kelas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <select name="subject_id" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Kelas Khusus (Opsional)
                        </label>
                        <select name="class_id"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tipe & Durasi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Tipe Konten <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Artikel / Bacaan Teks</option>
                            <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video Pembelajaran (YouTube/Vimeo)</option>
                            <option value="mixed" {{ old('type') === 'mixed' ? 'selected' : '' }}>Campuran (Teks + Video + Berkas)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Estimasi Waktu Belajar (Menit)
                        </label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 30) }}" min="1"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Video URL --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Tautan Video YouTube (Opsional)
                    </label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}"
                           placeholder="https://www.youtube.com/watch?v=..."
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                </div>

                {{-- Deskripsi Singkat --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Deskripsi Singkat Ringkasan Materi
                    </label>
                    <textarea name="description" rows="2" placeholder="Ringkasan apa yang dipelajari siswa..."
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('description') }}</textarea>
                </div>

                {{-- Isi Materi Lengkap --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Konten Materi Lengkap
                    </label>
                    <textarea name="content" rows="8" placeholder="Tuliskan isi materi, rumus tata bahasa jepang, contoh kalimat, dsb..."
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none font-mono text-xs">{{ old('content') }}</textarea>
                </div>

                {{-- Berkas Lampiran --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Berkas Lampiran Modul / PDF / Audio (Opsional)
                    </label>
                    <input type="file" name="file_attachment"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    <p class="text-xs text-gray-400 mt-1">Maksimum ukuran berkas: 20MB</p>
                </div>

                {{-- Status Terbit --}}
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                               class="rounded text-red-600 focus:ring-red-400 w-4 h-4">
                        <span class="text-sm text-gray-700 font-medium">Langsung terbitkan ke portal siswa</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('instructor.materi.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        💾 Simpan Materi
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.instructor>

