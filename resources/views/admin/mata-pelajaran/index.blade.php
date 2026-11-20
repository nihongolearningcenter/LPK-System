<x-layouts.admin>
<x-slot name="title">Mata Pelajaran</x-slot>
<x-slot name="pageTitle">Mata Pelajaran & Kurikulum</x-slot>

    <div class="flex items-center justify-between mb-6" x-data="{ showModal: false, editMode: false, currentSubject: {} }">
        <div>
            <h1 class="text-xl font-bold text-gray-900">📚 Mata Pelajaran</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola silabus, mata kuliah, dan topik kurikulum Bahasa Jepang</p>
        </div>
        <button @click="showModal = true; editMode = false; currentSubject = {}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
                style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Mata Pelajaran
        </button>

        {{-- Add / Edit Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div @click.away="showModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl">
                <h3 class="text-lg font-bold text-gray-900 mb-1" x-text="editMode ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran Baru'"></h3>
                <p class="text-xs text-gray-500 mb-4">Mata pelajaran mengelompokkan materi, kosakata, dan soal ujian.</p>

                <form :action="editMode ? '{{ url('admin/mata-pelajaran') }}/' + currentSubject.id : '{{ route('admin.mata-pelajaran.store') }}'" method="POST">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Program Kursus <span class="text-red-500">*</span></label>
                            <select name="program_id" x-model="currentSubject.program_id" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                                <option value="">Pilih Program...</option>
                                @foreach($programs as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="currentSubject.name" required placeholder="Contoh: Tata Bahasa Dasar N5 (Bunpou)"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Deskripsi Singkat</label>
                            <textarea name="description" x-model="currentSubject.description" rows="3" placeholder="Uraian silabus mata pelajaran..."
                                      class="w-full text-sm border border-gray-200 rounded-xl p-3 focus:outline-none focus:border-red-400"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Urutan Tampil (Sort Order)</label>
                            <input type="number" name="sort_order" x-model="currentSubject.sort_order" value="1" min="1"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">Batal</button>
                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 rounded-xl hover:bg-red-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Subjects Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Mata Pelajaran</th>
                        <th class="px-6 py-3">Program</th>
                        <th class="px-6 py-3">Materi</th>
                        <th class="px-6 py-3">Bank Soal</th>
                        <th class="px-6 py-3">Kosakata</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($subjects as $sub)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $sub->name }}</div>
                            <div class="text-xs text-gray-400 line-clamp-1 mt-0.5">{{ $sub->description ?? 'Tidak ada deskripsi.' }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-red-600 bg-red-50/50">
                            {{ $sub->program?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $sub->lessons_count }} modul</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $sub->questions_count }} butir</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $sub->vocabularies_count }} kata</td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.mata-pelajaran.destroy', $sub) }}"
                                  onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 font-semibold hover:underline">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Belum ada mata pelajaran yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subjects->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $subjects->links() }}</div>
        @endif
    </div>

</x-layouts.admin>

