<x-layouts.instructor>
<x-slot name="title">Edit Paket Ujian</x-slot>
<x-slot name="pageTitle">Edit Paket Ujian</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Edit Paket Ujian: {{ $ujian->title }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi dan durasi ujian</p>
            </div>
            <a href="{{ route('instructor.ujian.show', $ujian) }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('instructor.ujian.update', $ujian) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Judul Paket Ujian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $ujian->title) }}" required
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('title') border-red-400 @enderror">
                </div>

                {{-- Jenis Ujian & Level JLPT --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Tipe Ujian <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="simulasi_jlpt" {{ old('type', $ujian->type) === 'simulasi_jlpt' ? 'selected' : '' }}>Simulasi JLPT Resmi</option>
                            <option value="latihan" {{ old('type', $ujian->type) === 'latihan' ? 'selected' : '' }}>Latihan / Quiz Interaktif</option>
                            <option value="ujian_harian" {{ old('type', $ujian->type) === 'ujian_harian' ? 'selected' : '' }}>Ujian Harian</option>
                            <option value="ujian_tengah" {{ old('type', $ujian->type) === 'ujian_tengah' ? 'selected' : '' }}>Ujian Tengah Semester (UTS)</option>
                            <option value="ujian_akhir" {{ old('type', $ujian->type) === 'ujian_akhir' ? 'selected' : '' }}>Ujian Akhir Semester (UAS)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Target Level JLPT
                        </label>
                        <select name="jlpt_level" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Non-JLPT / Umum --</option>
                            @foreach(['N5', 'N4', 'N3', 'N2', 'N1'] as $lvl)
                                <option value="{{ $lvl }}" {{ old('jlpt_level', $ujian->jlpt_level) === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
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
                                <option value="{{ $c->id }}" {{ old('class_id', $ujian->class_id) == $c->id ? 'selected' : '' }}>
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
                                <option value="{{ $s->id }}" {{ old('subject_id', $ujian->subject_id) == $s->id ? 'selected' : '' }}>
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
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $ujian->duration_minutes) }}" min="5" max="300" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Passing Score / Skor Minimal Lulus (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="1" name="passing_score" value="{{ old('passing_score', $ujian->passing_score) }}" min="0" max="100" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Petunjuk Pengerjaan Ujian
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('description', $ujian->description) }}</textarea>
                </div>

                {{-- Status Publikasi --}}
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $ujian->is_published) ? 'checked' : '' }}
                               class="rounded text-red-600 focus:ring-red-400 w-4 h-4">
                        <span class="text-sm text-gray-700 font-medium">Publikasikan ujian ke portal siswa</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('instructor.ujian.show', $ujian) }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
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

