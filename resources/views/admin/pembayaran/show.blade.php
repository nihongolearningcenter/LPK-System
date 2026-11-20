<x-layouts.admin>
<x-slot name="title">Invoice #{{ $pembayaran->invoice_number }}</x-slot>
<x-slot name="pageTitle">Detail & Verifikasi Pembayaran</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.pembayaran.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Tagihan
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $pembayaran->invoice_number }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Cols: Invoice Details & Proof --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-100">
                    <div>
                        <span class="font-mono text-xs text-gray-400">INVOICE</span>
                        <h1 class="text-2xl font-black text-gray-900">{{ $pembayaran->invoice_number }}</h1>
                        <p class="text-xs text-gray-500 mt-1">Diterbitkan pada: {{ $pembayaran->created_at->format('d F Y, H:i') }}</p>
                    </div>

                    <div>
                        @if($pembayaran->status === 'paid')
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                ✅ LUNAS
                            </span>
                        @elseif($pembayaran->status === 'partial')
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                ⏳ MENUNGGU VERIFIKASI
                            </span>
                        @else
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                ⚠️ BELUM LUNAS
                            </span>
                        @endif
                    </div>
                </div>

                <div class="py-6 border-b border-gray-100 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama Siswa:</span>
                        <strong class="text-gray-900">{{ $pembayaran->student?->name }} ({{ $pembayaran->student?->email }})</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Keterangan:</span>
                        <span class="text-gray-800 font-medium">{{ $pembayaran->description }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jatuh Tempo:</span>
                        <span class="text-gray-800">{{ $pembayaran->due_date ? $pembayaran->due_date->format('d M Y') : '-' }}</span>
                    </div>
                    @if($pembayaran->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Pelunasan:</span>
                        <strong class="text-green-600">{{ $pembayaran->paid_at->format('d M Y, H:i') }}</strong>
                    </div>
                    @endif
                    <div class="flex justify-between text-lg pt-3 border-t border-gray-50 font-bold">
                        <span class="text-gray-900">Total Nominal:</span>
                        <span class="text-red-600">Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Bukti Transfer --}}
                <div class="mt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 mb-3">Bukti Pembayaran / Transfer</h3>
                    @if($pembayaran->payment_proof)
                        <div class="rounded-2xl border border-gray-100 overflow-hidden max-w-md bg-gray-50 p-2">
                            <img src="{{ asset('storage/' . $pembayaran->payment_proof) }}" alt="Bukti Transfer" class="w-full rounded-xl object-contain max-h-96">
                            <div class="p-2 text-center">
                                <a href="{{ asset('storage/' . $pembayaran->payment_proof) }}" target="_blank" class="text-xs text-blue-600 font-semibold hover:underline">
                                    Buka Gambar Ukuran Penuh ↗
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="p-6 rounded-2xl bg-gray-50 border border-gray-100 text-center text-xs text-gray-400">
                            Siswa belum mengunggah foto / bukti transfer untuk tagihan ini.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right 1 Col: Actions --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100">
                    Aksi Verifikasi Tagihan
                </h3>

                <form method="POST" action="{{ route('admin.pembayaran.update', $pembayaran) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Ubah Status</label>
                        <select name="status" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="paid" {{ $pembayaran->status === 'paid' ? 'selected' : '' }}>✅ Setujui & Tandai Lunas</option>
                            <option value="partial" {{ $pembayaran->status === 'partial' ? 'selected' : '' }}>⏳ Menunggu Verifikasi</option>
                            <option value="unpaid" {{ $pembayaran->status === 'unpaid' ? 'selected' : '' }}>⚠️ Belum Dibayar</option>
                            <option value="cancelled" {{ $pembayaran->status === 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Catatan Admin</label>
                        <textarea name="notes" rows="3" placeholder="Catatan konfirmasi atau nomor referensi bank..."
                                  class="w-full text-sm border border-gray-200 rounded-xl p-3 focus:outline-none focus:border-red-400">{{ $pembayaran->notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-sm"
                            style="background: linear-gradient(135deg, #10b981, #059669);">
                        Simpan Status Pembayaran
                    </button>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>

