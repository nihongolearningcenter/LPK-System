<x-layouts.admin>
<x-slot name="title">Buat Ujian Baru</x-slot>
<x-slot name="pageTitle">Tambah Ujian</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.ujian.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Buat Ujian Baru</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('admin.ujian.store') }}">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Ujian / Tes <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Simulasi JLPT N5 Batch 1"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tipe Ujian <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="simulasi_jlpt">Simulasi JLPT</option>
                            <option value="ujian_harian">Ujian Harian</option>
                            <option value="ujian_tengah">Ujian Tengah (UTS)</option>
                            <option value="ujian_akhir">Ujian Akhir (UAS)</option>
                            <option value="latihan">Latihan Bebas</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Level JLPT</label>
                        <select name="jlpt_level" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Tidak terikat level spesifik</option>
                            @foreach(['N5', 'N4', 'N3', 'N2', 'N1', 'dasar'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('jlpt_level') === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Khusus Kelas (Opsional)</label>
                        <select name="class_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Semua Siswa (Ujian Umum)</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->program?->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Mata Pelajaran (Opsional)</label>
                        <select name="subject_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Pilih Mata Pelajaran...</option>
                            @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Durasi Pengerjaan (Menit) <span class="text-red-500">*</span></label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="5" max="300" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Passing Score (KKM Kelulusan) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.5" name="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi & Petunjuk Ujian</label>
                    <textarea name="description" rows="3" placeholder="Petunjuk tata tertib atau cakupan materi yang diujikan..."
                              class="w-full text-sm border border-gray-200 rounded-xl p-4 focus:outline-none focus:border-red-400">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <label for="is_published" class="text-sm font-medium text-gray-700">Langsung publikasikan ujian untuk siswa</label>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.ujian.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Simpan & Lanjut Kelola Soal →
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>

