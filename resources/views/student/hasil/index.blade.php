<x-layouts.student>
<x-slot name="title">Hasil Belajar</x-slot>
<x-slot name="pageTitle">Hasil Belajar & Nilai</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">📊 Transkrip & Hasil Belajar</h1>
        <p class="text-sm text-gray-500 mt-0.5">Rekapitulasi nilai ujian, tugas, dan evaluasi pengajar Anda</p>
    </div>

    {{-- Highlight Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-red-50 text-red-600 flex-shrink-0">
                ⭐
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900">{{ round($avgScore, 1) }}</div>
                <div class="text-xs text-gray-400">Rata-rata Nilai Ujian</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-blue-50 text-blue-600 flex-shrink-0">
                📝
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900">{{ $examAttempts->count() }}</div>
                <div class="text-xs text-gray-400">Ujian Diselesaikan</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-green-50 text-green-600 flex-shrink-0">
                📌
            </div>
            <div>
                <div class="text-2xl font-black text-gray-900">{{ $submissions->count() }}</div>
                <div class="text-xs text-gray-400">Tugas Dinilai</div>
            </div>
        </div>
    </div>

    {{-- Exam Results Table --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 text-base">Nilai Ujian & Kuis</h2>
            <a href="{{ route('student.ujian.index') }}" class="text-xs font-semibold text-red-600 hover:underline">Semua Ujian →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Ujian</th>
                        <th class="px-6 py-3">Skor</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($examAttempts as $att)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $att->exam?->title ?? 'Ujian' }}</td>
                        <td class="px-6 py-4 font-black {{ $att->is_passed ? 'text-green-600' : 'text-red-600' }}">{{ $att->score }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $att->is_passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $att->is_passed ? 'Lulus' : 'Tidak Lulus' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $att->submitted_at?->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('student.ujian.result', $att) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                Pembahasan
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-xs">Belum ada nilai ujian yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Assignment Results Table --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 text-base">Nilai Tugas & Latihan</h2>
            <a href="{{ route('student.tugas.index') }}" class="text-xs font-semibold text-red-600 hover:underline">Semua Tugas →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Tugas</th>
                        <th class="px-6 py-3">Nilai</th>
                        <th class="px-6 py-3">Umpan Balik Sensei</th>
                        <th class="px-6 py-3 text-right">Tanggal Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($submissions as $sub)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $sub->assignment?->title ?? 'Tugas' }}</td>
                        <td class="px-6 py-4 font-black text-green-600">{{ $sub->score }}</td>
                        <td class="px-6 py-4 text-xs text-gray-600">{{ $sub->feedback ?? 'Tidak ada catatan khusus.' }}</td>
                        <td class="px-6 py-4 text-xs text-gray-400 text-right">{{ $sub->graded_at?->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-xs">Belum ada tugas yang dinilai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.student>
