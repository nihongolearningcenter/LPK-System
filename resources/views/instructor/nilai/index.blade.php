<x-layouts.instructor>
<x-slot name="title">Penilaian Siswa — {{ $class->name }}</x-slot>
<x-slot name="pageTitle">Lembar Penilaian Nilai Siswa</x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('instructor.kelas.show', $class) }}" class="text-xs text-indigo-600 hover:underline">← Kembali ke Detail Kelas</a>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mt-1">Penilaian Siswa: {{ $class->name }}</h1>
            <p class="text-sm text-gray-500">Bobot: Absensi 15% | Tugas 25% | UTS 25% | UAS 35%</p>
        </div>
    </div>

    {{-- Subject Selector --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('instructor.nilai.index', $class) }}" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    Pilih Mata Pelajaran
                </label>
                <select name="subject_id" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ $s->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95"
                    style="background: linear-gradient(135deg, #e63946, #c1121f);">
                🔍 Tampilkan Nilai
            </button>
        </form>
    </div>

    {{-- Grades Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 text-sm">Lembar Input Nilai</h2>
            <span class="text-xs text-gray-500">{{ $students->count() }} Siswa</span>
        </div>

        @if($students->isEmpty())
        <div class="p-12 text-center text-gray-400">
            <p class="text-sm">Belum ada siswa di kelas ini.</p>
        </div>
        @else
        <form method="POST" action="{{ route('instructor.nilai.store', $class) }}">
            @csrf
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
                            <th class="px-6 py-3">Catatan Sensei</th>
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
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                         style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $student->name }}</div>
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
                                       placeholder="Catatan perkembangan siswa..."
                                       class="w-full text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:border-red-400 focus:outline-none">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">Nilai akan otomatis diperbarui dan dapat dilihat pada rapor belajar siswa.</span>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    💾 Simpan Rekap Nilai
                </button>
            </div>
        </form>
        @endif
    </div>

</x-layouts.instructor>

