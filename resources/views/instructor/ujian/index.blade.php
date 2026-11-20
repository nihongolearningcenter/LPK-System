<x-layouts.instructor>
<x-slot name="title">Daftar Ujian</x-slot>
<x-slot name="pageTitle">Ujian & Evaluasi Online</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Ujian & Evaluasi (試験管理)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola paket tes harian, UTS, UAS, dan simulasi JLPT online</p>
        </div>
        <a href="{{ route('instructor.ujian.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Paket Ujian
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <select name="type" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300">
                    <option value="">Semua Jenis Evaluasi</option>
                    <option value="latihan" {{ request('type') === 'latihan' ? 'selected' : '' }}>Latihan / Kuis</option>
                    <option value="ujian_harian" {{ request('type') === 'ujian_harian' ? 'selected' : '' }}>Ujian Harian</option>
                    <option value="ujian_tengah" {{ request('type') === 'ujian_tengah' ? 'selected' : '' }}>UTS</option>
                    <option value="ujian_akhir" {{ request('type') === 'ujian_akhir' ? 'selected' : '' }}>UAS</option>
                    <option value="simulasi_jlpt" {{ request('type') === 'simulasi_jlpt' ? 'selected' : '' }}>Simulasi JLPT</option>
                </select>

                <select name="jlpt_level" class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-red-300">
                    <option value="">Semua Level JLPT</option>
                    @foreach(['N5', 'N4', 'N3', 'N2', 'N1'] as $lvl)
                        <option value="{{ $lvl }}" {{ request('jlpt_level') === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-5 py-2 text-sm font-medium text-white rounded-lg transition"
                        style="background: #e63946;">Filter</button>
                @if(request('type') || request('jlpt_level'))
                    <a href="{{ route('instructor.ujian.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 flex items-center">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Paket Ujian</th>
                        <th class="px-6 py-3">Jenis & Level</th>
                        <th class="px-4 py-3 text-center">Durasi</th>
                        <th class="px-4 py-3 text-center">Jumlah Soal</th>
                        <th class="px-4 py-3 text-center">Peserta Selesai</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($exams as $exam)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $exam->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $exam->classRoom?->name ?? 'Semua Kelas' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800 text-xs">{{ ucfirst(str_replace('_', ' ', $exam->type)) }}</div>
                            @if($exam->jlpt_level)
                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 mt-0.5">
                                    {{ $exam->jlpt_level }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center font-mono text-xs text-gray-700">
                            ⏱️ {{ $exam->duration_minutes }} Menit
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700">
                                ❓ {{ $exam->questions_count }} Butir
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700">
                                👥 {{ $exam->attempts_count }} Siswa
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($exam->is_published)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('instructor.ujian.show', $exam) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                    👁️ Butir Soal
                                </a>
                                <a href="{{ route('instructor.ujian.edit', $exam) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 transition">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('instructor.ujian.destroy', $exam) }}" onsubmit="return confirm('Hapus paket ujian ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">📝</div>
                            <p class="text-sm">Belum ada paket ujian online.</p>
                            <a href="{{ route('instructor.ujian.create') }}" class="inline-block mt-3 text-xs font-semibold text-red-600 hover:underline">
                                + Buat paket ujian pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($exams->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $exams->links() }}</div>
        @endif
    </div>

</x-layouts.instructor>
