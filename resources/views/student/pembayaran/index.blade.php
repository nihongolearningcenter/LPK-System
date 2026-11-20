<x-layouts.student>
<x-slot name="title">Riwayat Pembayaran</x-slot>
<x-slot name="pageTitle">Tagihan & Pembayaran</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">💳 Tagihan & Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-0.5">Informasi status pembayaran program dan biaya pelatihan Anda</p>
    </div>

    @if(isset($payments) && $payments->count() > 0)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No. Invoice</th>
                        <th class="px-6 py-3">Deskripsi Tagihan</th>
                        <th class="px-6 py-3">Total Tagihan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($payments as $pay)
                    @php
                    $statusStyles = [
                        'paid'    => ['bg'=>'bg-green-100 text-green-700', 'label'=>'Lunas'],
                        'unpaid'  => ['bg'=>'bg-red-100 text-red-700', 'label'=>'Belum Bayar'],
                        'partial' => ['bg'=>'bg-yellow-100 text-yellow-800', 'label'=>'Menunggu Verifikasi'],
                    ];
                    $st = $statusStyles[$pay->status] ?? ['bg'=>'bg-gray-100 text-gray-700', 'label'=>$pay->status];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono font-medium text-xs text-gray-700">{{ $pay->invoice_number }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $pay->description }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $st['bg'] }}">
                                {{ $st['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $pay->due_date ? $pay->due_date->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('student.pembayaran.show', $pay) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                Detail / Konfirmasi
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-400">
        <div class="text-4xl mb-3">🧾</div>
        <h3 class="font-bold text-gray-800 text-base mb-1">Tidak Ada Tagihan Aktif</h3>
        <p class="text-xs text-gray-400">Anda tidak memiliki tagihan pembayaran yang belum terselesaikan.</p>
    </div>
    @endif

</x-layouts.student>
