<x-layouts.admin>
<x-slot name="title">Data Penilaian Akademik</x-slot>
<x-slot name="pageTitle">Penilaian Akademik</x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Penilaian Siswa (成績管理)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola rekap nilai tugas, UTS, UAS, dan kehadiran per kelas & mata pelajaran</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-blue-50 text-blue-600">📝</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_graded'] }}</div>
                <div class="text-xs text-gray-500">Total Nilai Tercatat</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-green-50 text-green-600">🌟</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['grade_a'] }}</div>
                <div class="text-xs text-gray-500">Predikat A (Sangat Baik)</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-indigo-50 text-indigo-600">👍</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['grade_b'] }}</div>
                <div class="text-xs text-gray-500">Predikat B (Baik)</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-amber-50 text-amber-600">📊</div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['average'], 1) }}</div>
                <div class="text-xs text-gray-500">Rata-rata Nilai Keseluruhan</div>
            </div>
        </div>
    </div>

    {{-- Filter Selector --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('admin.nilai.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Pilih Kelas</label>
                <select name="class_id" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-300">
                    @forelse($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->program?->title ?? 'Umum' }})
                        </option>
                    @empty
                        <option value="">Belum ada kelas</option>
                    @endforelse
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Pilih Mata Pelajaran</label>
                <select name="subject_id" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-300">
                    @forelse($subjects as $s)
                        <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->code }})
                        </option>
                    @empty
                        <option value="">Belum ada mata pelajaran</option>
                    @endforelse
                </select>
            </div>

            <div>
                <button type="submit" class="w-full px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    🔍 Tampilkan Form Nilai
                </button>
            </div>
        </form>
    </div>

    {{-- Grades Form Table --}}
    @if($selectedClass && $selectedSubjectId)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="font-bold text-gray-900">{{ $selectedClass->name }} — Lembar Penilaian</h2>
                <p class="text-xs text-gray-500">Bobot: Absensi 15% | Tugas 25% | UTS 25% | UAS 35%</p>
            </div>
            <div class="text-xs text-gray-400">
                Total Siswa: <span class="font-semibold text-gray-700">{{ $students->count() }} orang</span>
            </div>
        </div>

        @if($students->isEmpty())
        <div class="p-12 text-center text-gray-400">
            <div class="text-4xl mb-3">👨‍🎓</div>
            <p class="text-sm font-medium text-gray-600">Belum ada siswa di kelas ini.</p>
            <p class="text-xs text-gray-400 mt-1">Tambahkan siswa ke kelas melalui menu Manajemen Kelas.</p>
            <a href="{{ route('admin.kelas.show', $selectedClass->id) }}" class="inline-block mt-4 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                Kelola Anggota Kelas
            </a>
        </div>
        @else
        <form method="POST" action="{{ route('admin.nilai.store') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3 w-64">Siswa</th>
                            <th class="px-4 py-3 text-center w-28">Absensi (15%)</th>
                            <th class="px-4 py-3 text-center w-28">Tugas (25%)</th>
                            <th class="px-4 py-3 text-center w-28">UTS (25%)</th>
                            <th class="px-4 py-3 text-center w-28">UAS (35%)</th>
                            <th class="px-4 py-3 text-center w-24">Nilai Akhir</th>
                            <th class="px-4 py-3 text-center w-20">Huruf</th>
                            <th class="px-6 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($students as $index => $student)
                        @php
                            $grade = $grades->get($student->id);
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                         style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 leading-tight">{{ $student->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <input type="number" step="0.01" min="0" max="100" name="grades[{{ $index }}][score_attendance]"
                                       value="{{ old('grades.'.$index.'.score_attendance', $grade?->score_attendance) }}"
                                       placeholder="0-100"
                                       class="w-20 text-center text-xs font-semibold border border-gray-200 rounded-lg px-2 py-1.5 focus:border-red-400 focus:outline-none">
                            </td>
                            <td class="px-4 py-4 text-center">
                                <input type="number" step="0.01" min="0" max="100" name="grades[{{ $index }}][score_assignments]"
                                       value="{{ old('grades.'.$index.'.score_assignments', $grade?->score_assignments) }}"
                                       placeholder="0-100"
                                       class="w-20 text-center text-xs font-semibold border border-gray-200 rounded-lg px-2 py-1.5 focus:border-red-400 focus:outline-none">
                            </td>
                            <td class="px-4 py-4 text-center">
                                <input type="number" step="0.01" min="0" max="100" name="grades[{{ $index }}][score_midterm]"
                                       value="{{ old('grades.'.$index.'.score_midterm', $grade?->score_midterm) }}"
                                       placeholder="0-100"
                                       class="w-20 text-center text-xs font-semibold border border-gray-200 rounded-lg px-2 py-1.5 focus:border-red-400 focus:outline-none">
                            </td>
                            <td class="px-4 py-4 text-center">
                                <input type="number" step="0.01" min="0" max="100" name="grades[{{ $index }}][score_final]"
                                       value="{{ old('grades.'.$index.'.score_final', $grade?->score_final) }}"
                                       placeholder="0-100"
                                       class="w-20 text-center text-xs font-semibold border border-gray-200 rounded-lg px-2 py-1.5 focus:border-red-400 focus:outline-none">
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($grade?->final_grade !== null)
                                    <span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold text-gray-800 bg-gray-100">
                                        {{ number_format($grade->final_grade, 1) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($grade?->grade_letter)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                                        {{ match($grade->grade_letter) {
                                            'A' => 'bg-green-100 text-green-700',
                                            'B' => 'bg-blue-100 text-blue-700',
                                            'C' => 'bg-yellow-100 text-yellow-700',
                                            'D' => 'bg-orange-100 text-orange-700',
                                            default => 'bg-red-100 text-red-700',
                                        } }}">
                                        {{ $grade->grade_letter }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <input type="text" name="grades[{{ $index }}][notes]"
                                       value="{{ old('grades.'.$index.'.notes', $grade?->notes) }}"
                                       placeholder="Catatan kemajuan siswa..."
                                       class="w-full text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:border-red-400 focus:outline-none">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <span class="text-xs text-gray-500">Pastikan semua nilai antara 0-100 sebelum menyimpan.</span>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    💾 Simpan Semua Nilai
                </button>
            </div>
        </form>
        @endif
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <div class="text-4xl mb-3">🏫</div>
        <p class="text-sm">Silakan pilih kelas dan mata pelajaran di atas untuk melihat lembar nilai.</p>
    </div>
    @endif

</x-layouts.admin>
