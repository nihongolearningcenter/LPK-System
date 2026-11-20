<x-layouts.admin>
<x-slot name="title">Laporan & Statistik</x-slot>
<x-slot name="pageTitle">Laporan & Statistik Lembaga</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Laporan & Rekapitulasi (レポート)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ringkasan performa pendaftaran, akademik, dan keuangan LPK Nihongo LC</p>
        </div>
        <button onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                style="background: linear-gradient(135deg, #e63946, #c1121f);">
            🖨️ Cetak / Simpan Laporan
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-emerald-50 text-emerald-600">💰</div>
            <div>
                <div class="text-xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                <div class="text-xs text-gray-500">Total Pendapatan Masuk</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-blue-50 text-blue-600">👨‍🎓</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] }}</div>
                <div class="text-xs text-gray-500">Total Siswa Terdaftar</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-purple-50 text-purple-600">📋</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_enrollments'] }}</div>
                <div class="text-xs text-gray-500">Total Pendaftaran Masuk</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-amber-50 text-amber-600">📜</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_certificates'] }}</div>
                <div class="text-xs text-gray-500">Sertifikat Kelulusan</div>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6"
         x-data="{ tab: '{{ $activeTab }}' }">
        <div class="px-6 py-3 border-b border-gray-100 flex gap-4 text-xs font-semibold overflow-x-auto">
            <button @click="tab = 'overview'" :class="tab === 'overview' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                📊 Ringkasan Umum
            </button>
            <button @click="tab = 'students'" :class="tab === 'students' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                👨‍🎓 Pendaftaran & Siswa
            </button>
            <button @click="tab = 'financial'" :class="tab === 'financial' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                💳 Keuangan & Pembayaran
            </button>
            <button @click="tab = 'academic'" :class="tab === 'academic' ? 'text-red-600 border-b-2 border-red-600 pb-2' : 'text-gray-500 hover:text-gray-800 pb-2'">
                🏆 Akademik & Kelulusan
            </button>
        </div>

        {{-- Tab 1: Overview --}}
        <div x-show="tab === 'overview'" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Program Enrollment Distribution --}}
                <div class="p-5 border border-gray-100 rounded-xl bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 text-sm mb-4">Peminat per Program Pelatihan</h3>
                    @if($enrollmentsByProgram->isEmpty())
                        <p class="text-xs text-gray-400">Belum ada data pendaftaran.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($enrollmentsByProgram as $ep)
                            @php
                                $pct = $stats['total_enrollments'] > 0 ? round(($ep->total / $stats['total_enrollments']) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-medium text-gray-700 mb-1">
                                    <span>{{ $ep->program?->title ?? 'Program Tidak Diketahui' }}</span>
                                    <span>{{ $ep->total }} pendaftar ({{ $pct }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full" style="width: {{ $pct }}%; background: #e63946;"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Status Pendaftaran --}}
                <div class="p-5 border border-gray-100 rounded-xl bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 text-sm mb-4">Status Seleksi Pendaftar</h3>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="p-4 bg-white rounded-xl border border-gray-100">
                            <div class="text-2xl font-bold text-amber-500">{{ $enrollmentStatuses['pending'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">Menunggu Review</div>
                        </div>
                        <div class="p-4 bg-white rounded-xl border border-gray-100">
                            <div class="text-2xl font-bold text-green-600">{{ $enrollmentStatuses['accepted'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">Diterima</div>
                        </div>
                        <div class="p-4 bg-white rounded-xl border border-gray-100">
                            <div class="text-2xl font-bold text-red-500">{{ $enrollmentStatuses['rejected'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">Ditolak</div>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-gray-500 text-center">
                        Rasio Penerimaan:
                        <strong class="text-gray-800">
                            {{ $stats['total_enrollments'] > 0 ? round(($enrollmentStatuses['accepted'] / $stats['total_enrollments']) * 100, 1) : 0 }}%
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 2: Students --}}
        <div x-show="tab === 'students'" class="p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Rekapitulasi Siswa & Pengajar</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-xs text-gray-500">Siswa Aktif</span>
                    <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_students'] }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-xs text-gray-500">Instruktur / Sensei</span>
                    <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_instructors'] }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-xs text-gray-500">Rasio Siswa per Pengajar</span>
                    <div class="text-2xl font-bold text-indigo-600 mt-1">
                        {{ $stats['total_instructors'] > 0 ? round($stats['total_students'] / $stats['total_instructors'], 1) : 0 }} : 1
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.siswa.index') }}" class="text-xs text-red-600 hover:underline font-semibold">
                Lihat Data Siswa Lengkap →
            </a>
        </div>

        {{-- Tab 3: Financial --}}
        <div x-show="tab === 'financial'" class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-sm">Riwayat Pembayaran Terbaru</h3>
                <a href="{{ route('admin.pembayaran.index') }}" class="text-xs text-red-600 hover:underline font-semibold">
                    Semua Pembayaran →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase">
                            <th class="p-3">No. Invoice</th>
                            <th class="p-3">Siswa</th>
                            <th class="p-3">Program</th>
                            <th class="p-3 text-right">Nominal</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-right">Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentPayments as $pay)
                        <tr>
                            <td class="p-3 font-mono font-medium text-gray-800">{{ $pay->invoice_number }}</td>
                            <td class="p-3 font-semibold text-gray-900">{{ $pay->student?->name ?? '-' }}</td>
                            <td class="p-3 text-gray-600">{{ $pay->enrollment?->program?->title ?? 'Pelatihan' }}</td>
                            <td class="p-3 text-right font-bold text-gray-900">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                    {{ $pay->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ strtoupper($pay->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-right text-gray-400">{{ $pay->paid_at ? $pay->paid_at->format('d M Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-400">Belum ada transaksi pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab 4: Academic --}}
        <div x-show="tab === 'academic'" class="p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Distribusi Capaian Nilai Akhir</h3>
            <div class="grid grid-cols-5 gap-3 text-center mb-6">
                <div class="p-4 bg-green-50 border border-green-100 rounded-xl">
                    <div class="text-2xl font-bold text-green-700">{{ $gradeDistribution['A'] }}</div>
                    <div class="text-xs text-green-600 font-semibold mt-1">Predikat A</div>
                </div>
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    <div class="text-2xl font-bold text-blue-700">{{ $gradeDistribution['B'] }}</div>
                    <div class="text-xs text-blue-600 font-semibold mt-1">Predikat B</div>
                </div>
                <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-xl">
                    <div class="text-2xl font-bold text-yellow-700">{{ $gradeDistribution['C'] }}</div>
                    <div class="text-xs text-yellow-600 font-semibold mt-1">Predikat C</div>
                </div>
                <div class="p-4 bg-orange-50 border border-orange-100 rounded-xl">
                    <div class="text-2xl font-bold text-orange-700">{{ $gradeDistribution['D'] }}</div>
                    <div class="text-xs text-orange-600 font-semibold mt-1">Predikat D</div>
                </div>
                <div class="p-4 bg-red-50 border border-red-100 rounded-xl">
                    <div class="text-2xl font-bold text-red-700">{{ $gradeDistribution['E'] }}</div>
                    <div class="text-xs text-red-600 font-semibold mt-1">Predikat E</div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-between text-xs">
                <div>
                    <span class="text-gray-500">Rata-rata Nilai Siswa: </span>
                    <strong class="text-gray-900 text-sm">{{ $stats['average_grade'] }} / 100</strong>
                </div>
                <div>
                    <span class="text-gray-500">Total Sertifikat Kelulusan: </span>
                    <strong class="text-gray-900 text-sm">{{ $stats['total_certificates'] }} berkas</strong>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
