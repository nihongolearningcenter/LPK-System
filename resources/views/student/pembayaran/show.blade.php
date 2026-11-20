<x-layouts.student>
<x-slot name="title">Invoice #{{ $payment->invoice_number }}</x-slot>
<x-slot name="pageTitle">Detail Pembayaran</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('student.pembayaran.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Tagihan
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Invoice #{{ $payment->invoice_number }}</span>
        </div>

        {{-- Invoice Detail Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-100">
                <div>
                    <span class="font-mono text-xs text-gray-400">INVOICE NUMBER</span>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900">{{ $payment->invoice_number }}</h1>
                </div>

                <div>
                    @if($payment->status === 'paid')
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                            ✅ LUNAS
                        </span>
                    @elseif($payment->status === 'partial')
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                            ⏳ MENUNGGU VERIFIKASI
                        </span>
                    @else
                        <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                            ⚠️ BELUM DIBAYAR
                        </span>
                    @endif
                </div>
            </div>

            <div class="py-6 border-b border-gray-100 space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Keterangan Biaya</span>
                    <span class="font-semibold text-gray-900">{{ $payment->description }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Batas Waktu Pembayaran</span>
                    <span class="font-semibold text-gray-900">{{ $payment->due_date ? $payment->due_date->format('d F Y') : '-' }}</span>
                </div>
                @if($payment->paid_at)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tanggal Pembayaran</span>
                    <span class="font-semibold text-green-600">{{ $payment->paid_at->format('d F Y, H:i') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg pt-3 border-t border-gray-50 font-bold">
                    <span class="text-gray-900">Total Tagihan</span>
                    <span class="text-red-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Bank Account Instructions --}}
            <div class="my-6 p-5 rounded-2xl bg-gray-50 border border-gray-100">
                <h3 class="font-bold text-gray-800 text-sm mb-2 flex items-center gap-2">
                    <span>🏦</span> Rekening Pembayaran Resmi LPK Nihongo LC
                </h3>
                <div class="text-xs text-gray-600 space-y-1">
                    <div>Bank: <strong>Bank Central Asia (BCA)</strong></div>
                    <div>No. Rekening: <strong class="font-mono text-sm text-gray-900">123-456-7890</strong></div>
                    <div>Atas Nama: <strong>LPK NIHONGO LEARNING CENTER</strong></div>
                </div>
            </div>

            {{-- Bukti yang sudah diunggah --}}
            @if($payment->payment_proof)
            <div class="my-6 p-5 rounded-2xl bg-slate-50 border border-slate-200">
                <h3 class="font-bold text-gray-800 text-sm mb-3 flex items-center gap-2">
                    <span>🧾</span> Bukti Pembayaran yang Diunggah
                </h3>
                <div class="rounded-xl overflow-hidden max-w-xs border border-gray-200 bg-white">
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Transfer" class="w-full object-contain max-h-64">
                    <div class="p-2 text-center bg-gray-50 border-t border-gray-100">
                        <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="text-xs text-blue-600 font-semibold hover:underline">
                            Lihat Gambar Penuh ↗
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Proof Upload Form --}}
            @if($payment->status !== 'paid')
            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="font-bold text-gray-900 text-sm mb-2">Konfirmasi Bukti Transfer</h3>
                <p class="text-xs text-gray-500 mb-4">Unggah bukti transfer transfer ATM, m-Banking, atau struk pembayaran.</p>

                <form method="POST" action="{{ route('student.pembayaran.confirm', $payment) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                        <select name="payment_method" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            <option value="transfer">Transfer Bank (BCA / Mandiri / BRI / BNI)</option>
                            <option value="tunai">Tunai ke Kantor</option>
                            <option value="lainnya">Lainnya (E-Wallet / QRIS)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">File Bukti Transfer (JPG / PNG, Maks. 3MB)</label>
                        <input type="file" name="payment_proof" required accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl text-xs font-bold text-white transition hover:opacity-95 shadow-sm"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        Kirim Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
            @endif
        </div>

    </div>

</x-layouts.student>
