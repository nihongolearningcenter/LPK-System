<x-layouts.admin>
<x-slot name="title">Terbitkan Tagihan Baru</x-slot>
<x-slot name="pageTitle">Tambah Tagihan</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.pembayaran.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Keuangan
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Terbitkan Tagihan</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 max-w-3xl">
        <form method="POST" action="{{ route('admin.pembayaran.store') }}">
            @csrf

            <div class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Pilih Siswa <span class="text-red-500">*</span></label>
                        <select name="student_id" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Pilih Siswa...</option>
                            @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Khusus Kelas (Opsional)</label>
                        <select name="class_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="">Tidak terikat kelas spesifik</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Tagihan <span class="text-red-500">*</span></label>
                    <input type="text" name="description" value="{{ old('description') }}" required placeholder="Contoh: Biaya Pelatihan Bahasa Jepang Program N5 Intensif"
                           class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nominal Biaya (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount', 2500000) }}" min="0" required
                               class="w-full text-sm font-bold border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Jatuh Tempo</label>
                        <input type="date" name="due_date" value="{{ old('due_date', now()->addWeeks(2)->format('Y-m-d')) }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status Pembayaran <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="unpaid">Belum Dibayar</option>
                            <option value="paid">Sudah Lunas</option>
                            <option value="partial">Menunggu Verifikasi</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.pembayaran.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Terbitkan Tagihan
                </button>
            </div>
        </form>
    </div>

</x-layouts.admin>

