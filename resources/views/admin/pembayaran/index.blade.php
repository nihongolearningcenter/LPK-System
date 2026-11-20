<x-layouts.admin>
<x-slot name="title">Keuangan & Pembayaran</x-slot>
<x-slot name="pageTitle">Tagihan & Kas Pembayaran</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">💰 Manajemen Keuangan & Pembayaran</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola invoice tagihan siswa, verifikasi bukti transfer, dan status pelunasan</p>
        </div>
        <a href="{{ route('admin.pembayaran.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Terbitkan Tagihan Baru
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="text-xs text-gray-400 font-medium">Total Nominal Tagihan</div>
            <div class="text-xl font-black text-gray-900 mt-1">Rp {{ number_format($stats['total'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="text-xs text-green-600 font-medium">Dana Masuk (Lunas)</div>
            <div class="text-xl font-black text-green-600 mt-1">Rp {{ number_format($stats['paid'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="text-xs text-red-500 font-medium">Piutang / Belum Bayar</div>
            <div class="text-xl font-black text-red-600 mt-1">Rp {{ number_format($stats['unpaid'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="text-xs text-yellow-600 font-medium">Menunggu Verifikasi</div>
            <div class="text-xl font-black text-yellow-600 mt-1">{{ $stats['pending'] }} Invoice</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice, nama siswa, atau deskripsi..."
                       class="w-full pl-4 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
            </div>
            <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Status</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Menunggu Verifikasi</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg" style="background:#e63946;">Filter</button>
            <a href="{{ route('admin.pembayaran.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        </form>
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No. Invoice</th>
                        <th class="px-6 py-3">Siswa</th>
                        <th class="px-6 py-3">Deskripsi Tagihan</th>
                        <th class="px-6 py-3">Jumlah</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments as $pay)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-xs text-gray-800">
                            <a href="{{ route('admin.pembayaran.show', $pay) }}" class="hover:text-red-600">
                                {{ $pay->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $pay->student?->name }}</div>
                            <div class="text-xs text-gray-400">{{ $pay->student?->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">{{ $pay->description }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($pay->status === 'paid')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Lunas</span>
                            @elseif($pay->status === 'partial')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 animate-pulse">Verifikasi</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $pay->due_date ? $pay->due_date->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.pembayaran.show', $pay) }}"
                                   class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                    Detail / Verifikasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">🧾</div>
                            Belum ada riwayat transaksi pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
