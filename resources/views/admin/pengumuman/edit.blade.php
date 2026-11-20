<x-layouts.admin>
<x-slot name="title">Edit Pengumuman</x-slot>
<x-slot name="pageTitle">Edit Pengumuman</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.pengumuman.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Edit: {{ $pengumuman->title }}</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-3xl">
        <form method="POST" action="{{ route('admin.pengumuman.update', $pengumuman) }}">
            @csrf @method('PUT')

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $pengumuman->title) }}" required
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Konten <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="pengumuman" {{ old('type', $pengumuman->type) === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option value="berita" {{ old('type', $pengumuman->type) === 'berita' ? 'selected' : '' }}>Berita</option>
                            <option value="artikel" {{ old('type', $pengumuman->type) === 'artikel' ? 'selected' : '' }}>Artikel</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Audiens <span class="text-red-500">*</span></label>
                        <select name="target" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="all" {{ old('target', $pengumuman->target) === 'all' ? 'selected' : '' }}>Semua Pengguna</option>
                            <option value="students" {{ old('target', $pengumuman->target) === 'students' ? 'selected' : '' }}>Khusus Siswa</option>
                            <option value="instructors" {{ old('target', $pengumuman->target) === 'instructors' ? 'selected' : '' }}>Khusus Pengajar</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="6" required
                              class="w-full text-sm border border-gray-200 rounded-xl p-4 focus:outline-none focus:border-red-400">{{ old('content', $pengumuman->content) }}</textarea>
                    @error('content') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $pengumuman->is_published) ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                    <label for="is_published" class="text-sm font-medium text-gray-700">Publikasikan sekarang</label>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.pengumuman.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Perbarui Pengumuman
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>

