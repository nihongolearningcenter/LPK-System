<x-layouts.admin>
<x-slot name="title">Buka Kelas Baru</x-slot>
<x-slot name="pageTitle">Tambah Kelas</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.kelas.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Kelas
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Buka Kelas Baru</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('admin.kelas.store') }}">
            @csrf

            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Program Kursus <span class="text-red-500">*</span></label>
                        <select name="program_id" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Pilih Program...</option>
                            @foreach($programs as $p)
                            <option value="{{ $p->id }}" {{ old('program_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pengajar Utama (Sensei)</label>
                        <select name="instructor_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Pilih Pengajar...</option>
                            @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}" {{ old('instructor_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kelas JLPT N5 Batch 1"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Kelas</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="Otomatis jika kosong"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Kelas <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="active">Aktif Berjalan</option>
                            <option value="upcoming">Akan Datang</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Maks. Siswa</label>
                        <input type="number" name="max_students" value="{{ old('max_students', 20) }}" min="1"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tautan Kelas Virtual (Zoom / Google Meet)</label>
                    <input type="url" name="meeting_link" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/..."
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                    <textarea name="notes" rows="3" placeholder="Informasi tambahan mengenai kelas ini..."
                              class="w-full text-sm border border-gray-200 rounded-xl p-4 focus:outline-none focus:border-red-400">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.kelas.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Simpan & Buka Kelas
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>
