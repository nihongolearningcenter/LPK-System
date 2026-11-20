<x-layouts.admin>
<x-slot name="title">Manajemen Sertifikat</x-slot>
<x-slot name="pageTitle">Sertifikat Kelulusan</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Sertifikat Kelulusan (修了証書)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola dan terbitkan sertifikat resmi pelatihan dan bahasa Jepang siswa</p>
        </div>
        <a href="{{ route('admin.sertifikat.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Terbitkan Sertifikat
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-amber-50 text-amber-600">📜</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-500">Total Sertifikat Diterbitkan</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-blue-50 text-blue-600">🗓️</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['this_year'] }}</div>
                <div class="text-xs text-gray-500">Diterbitkan Tahun Ini ({{ date('Y') }})</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-green-50 text-green-600">🏆</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['grade_a'] }}</div>
                <div class="text-xs text-gray-500">Predikat Sangat Memuaskan (A)</div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nomor sertifikat, nama siswa, atau kode verifikasi..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                </div>

                <select name="program_id" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300">
                    <option value="">Semua Program</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->title }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-5 py-2 text-sm font-medium text-white rounded-lg transition"
                        style="background: #e63946;">Filter</button>
                @if(request('search') || request('program_id'))
                    <a href="{{ route('admin.sertifikat.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 flex items-center">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No. Sertifikat</th>
                        <th class="px-6 py-3">Siswa</th>
                        <th class="px-6 py-3">Program / Kelas</th>
                        <th class="px-4 py-3 text-center">Nilai</th>
                        <th class="px-4 py-3 text-center">Predikat</th>
                        <th class="px-6 py-3">Tgl Terbit</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($certificates as $cert)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-mono font-medium text-gray-900 text-xs">
                            {{ $cert->certificate_number }}
                            <div class="text-[10px] text-gray-400 font-sans mt-0.5">Kode: {{ $cert->verification_code }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $cert->student?->name ?? 'Siswa Dihapus' }}</div>
                            <div class="text-xs text-gray-400">{{ $cert->student?->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900 font-medium">{{ $cert->certificate_name }}</div>
                            <div class="text-xs text-gray-400">{{ $cert->program?->title ?? $cert->classRoom?->name ?? 'Umum' }}</div>
                        </td>
                        <td class="px-4 py-4 text-center font-bold text-gray-700">
                            {{ $cert->final_grade ? number_format($cert->final_grade, 1) : '-' }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($cert->grade_letter)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                    {{ match($cert->grade_letter) {
                                        'A' => 'bg-green-100 text-green-700',
                                        'B' => 'bg-blue-100 text-blue-700',
                                        'C' => 'bg-yellow-100 text-yellow-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    } }}">
                                    {{ $cert->grade_letter }}
                                </span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">
                            {{ $cert->issue_date?->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.sertifikat.show', $cert) }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                    👁️ Lihat / Cetak
                                </a>
                                <form method="POST" action="{{ route('admin.sertifikat.destroy', $cert) }}" onsubmit="return confirm('Hapus sertifikat ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">📜</div>
                            <p class="text-sm">Belum ada sertifikat yang diterbitkan.</p>
                            <a href="{{ route('admin.sertifikat.create') }}" class="inline-block mt-3 text-xs font-semibold text-red-600 hover:underline">
                                + Terbitkan sertifikat pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $certificates->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
