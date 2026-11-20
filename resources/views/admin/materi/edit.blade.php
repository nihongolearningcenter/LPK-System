<x-layouts.admin>
<x-slot name="title">Edit {{ $lesson->title }}</x-slot>
<x-slot name="pageTitle">Edit Materi Pelajaran</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.materi.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Materi
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit Materi</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 max-w-4xl">
        <form method="POST" action="{{ route('admin.materi.update', $lesson) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Materi <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select name="subject_id" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id', $lesson->subject_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Format Konten <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="text" {{ old('type', $lesson->type) === 'text' ? 'selected' : '' }}>Teks / Artikel</option>
                            <option value="video" {{ old('type', $lesson->type) === 'video' ? 'selected' : '' }}>Video (YouTube URL)</option>
                            <option value="audio" {{ old('type', $lesson->type) === 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="mixed" {{ old('type', $lesson->type) === 'mixed' ? 'selected' : '' }}>Campuran</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Khusus Kelas (Opsional)</label>
                        <select name="class_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Semua Kelas (Umum)</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id', $lesson->class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Perkiraan Durasi (Menit)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" min="1"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">URL Video Pembelajaran</label>
                        <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                    <input type="text" name="description" value="{{ old('description', $lesson->description) }}"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ganti File Lampiran / Dokumen PDF</label>
                    @if($lesson->file_attachment)
                    <div class="text-xs text-gray-500 mb-1">File saat ini: <span class="font-mono">{{ basename($lesson->file_attachment) }}</span></div>
                    @endif
                    <input type="file" name="file_attachment"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Konten Pelajaran</label>
                    <textarea name="content" rows="8"
                              class="w-full text-sm border border-gray-200 rounded-2xl p-4 focus:outline-none focus:border-red-400">{{ old('content', $lesson->content) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $lesson->is_published) ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <label for="is_published" class="text-sm font-medium text-gray-700">Publikasikan materi ini untuk siswa</label>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.materi.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>

