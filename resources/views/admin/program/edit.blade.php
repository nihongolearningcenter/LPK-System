<x-layouts.admin>
<x-slot name="title">Edit Program</x-slot>
<x-slot name="pageTitle">Edit Program</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.program.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Program
        </a>
    </div>

    <form method="POST" action="{{ route('admin.program.update', $program) }}" class="max-w-3xl">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <h2 class="font-semibold text-gray-900 pb-4 border-b border-gray-100">Edit: {{ $program->name }}</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Program <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $program->name) }}" required
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe / Level <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-red-300">
                    @foreach(['dasar'=>'Dasar','jlpt_n5'=>'JLPT N5','jlpt_n4'=>'JLPT N4','kaiwa'=>'Kaiwa (Percakapan)','kerja'=>'Persiapan Kerja','tokutei_ginou'=>'Tokutei Ginou'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ old('type', $program->type) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Singkat</label>
                <textarea name="short_description" rows="2"
                          class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-red-300 resize-none">{{ old('short_description', $program->short_description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Lengkap</label>
                <textarea name="description" rows="5"
                          class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-red-300 resize-y">{{ old('description', $program->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $program->price) }}" min="0"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-red-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi (minggu)</label>
                    <input type="number" name="duration_weeks" value="{{ old('duration_weeks', $program->duration_weeks) }}" min="1"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-red-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Sesi per Minggu</label>
                    <input type="text" name="session_per_week" value="{{ old('session_per_week', $program->session_per_week) }}"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-red-300">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order) }}" min="0"
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-red-300">
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $program->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Program Aktif</label>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:scale-105"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Perbarui Program
                </button>
                <a href="{{ route('admin.program.index') }}"
                   class="inline-flex items-center px-6 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </div>
    </form>

</x-layouts.admin>
