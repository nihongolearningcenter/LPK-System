<x-layouts.admin>
<x-slot name="title">Pengumpulan Tugas: {{ $assignment->title }}</x-slot>
<x-slot name="pageTitle">Detail Tugas & Pengumpulan</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $assignment->title }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Kelas: <span class="font-medium text-gray-800">{{ $assignment->classRoom?->name ?? '-' }}</span> |
                Mapel: <span class="font-medium text-gray-800">{{ $assignment->subject?->name ?? 'Semua Mapel' }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.tugas.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50">
                ← Kembali
            </a>
            <a href="{{ route('admin.tugas.edit', $assignment) }}"
               class="px-4 py-2 text-sm font-semibold text-white rounded-xl shadow-sm transition hover:opacity-95"
               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                ✏️ Edit Tugas
            </a>
        </div>
    </div>

    {{-- Assignment Details Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 pb-4 border-b border-gray-100 text-xs">
            <div>
                <span class="text-gray-400 block mb-0.5">Batas Pengumpulan:</span>
                <span class="font-semibold text-gray-800">
                    {{ $assignment->due_date ? $assignment->due_date->format('d M Y, H:i') : 'Tidak ditentukan' }}
                </span>
            </div>
            <div>
                <span class="text-gray-400 block mb-0.5">Nilai Maksimum:</span>
                <span class="font-semibold text-gray-800">{{ $assignment->max_score }} Poin</span>
            </div>
            <div>
                <span class="text-gray-400 block mb-0.5">Pengumpulan Terlambat:</span>
                <span class="font-semibold {{ $assignment->allow_late_submission ? 'text-green-600' : 'text-red-500' }}">
                    {{ $assignment->allow_late_submission ? 'Diizinkan' : 'Tidak Diizinkan' }}
                </span>
            </div>
            <div>
                <span class="text-gray-400 block mb-0.5">Status Publikasi:</span>
                <span class="font-semibold {{ $assignment->is_published ? 'text-green-600' : 'text-amber-600' }}">
                    {{ $assignment->is_published ? 'Aktif (Dipublikasi)' : 'Draft' }}
                </span>
            </div>
        </div>

        @if($assignment->description)
            <div class="text-sm text-gray-700 whitespace-pre-line mb-4">
                {{ $assignment->description }}
            </div>
        @endif

        @if($assignment->file_attachment)
            <div class="p-3 bg-red-50/50 border border-red-100 rounded-xl flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-red-800 font-medium">
                    <span>📎</span> Berkas Lampiran Soal: {{ basename($assignment->file_attachment) }}
                </div>
                <a href="{{ Storage::url($assignment->file_attachment) }}" target="_blank"
                   class="px-3 py-1 bg-white border border-red-200 text-red-700 rounded-lg font-semibold hover:bg-red-50">
                    Unduh Soal
                </a>
            </div>
        @endif
    </div>

    {{-- Submissions List --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900">Daftar Pengumpulan Siswa</h2>
            <div class="text-xs text-gray-500">
                Terkumpul: <span class="font-semibold text-blue-600">{{ $submissions->count() }}</span> dari {{ $students->count() }} Siswa
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Siswa</th>
                        <th class="px-4 py-3 text-center">Waktu Kirim</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Nilai</th>
                        <th class="px-6 py-3">Jawaban / Berkas</th>
                        <th class="px-6 py-3 text-right">Aksi Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                    @php
                        $sub = $submissions->get($student->id);
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                     style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center text-xs text-gray-500">
                            @if($sub?->submitted_at)
                                <div>{{ $sub->submitted_at->format('d M, H:i') }}</div>
                                @if($sub->is_late)
                                    <span class="text-[10px] text-red-500 font-semibold">Terlambat</span>
                                @endif
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if(!$sub)
                                <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-semibold bg-gray-100 text-gray-500">
                                    Belum Kumpul
                                </span>
                            @elseif($sub->status === 'graded')
                                <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-semibold bg-green-100 text-green-700">
                                    Dinilai
                                </span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-100 text-blue-700">
                                    Sudah Kumpul
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center font-bold">
                            @if($sub?->score !== null)
                                <span class="text-gray-900">{{ number_format($sub->score, 1) }}</span>
                                <span class="text-xs text-gray-400">/ {{ $assignment->max_score }}</span>
                            @else
                                <span class="text-gray-300 font-normal">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            @if($sub)
                                @if($sub->file_submission)
                                    <div class="mb-1">
                                        <a href="{{ Storage::url($sub->file_submission) }}" target="_blank"
                                           class="inline-flex items-center gap-1 font-semibold text-blue-600 hover:underline">
                                            📄 Unduh Berkas Siswa
                                        </a>
                                    </div>
                                @endif
                                @if($sub->answer_text)
                                    <div class="text-gray-600 italic bg-gray-50 p-2 rounded-lg line-clamp-2">
                                        "{{ $sub->answer_text }}"
                                    </div>
                                @endif
                            @else
                                <span class="text-gray-300 italic">Belum ada respon</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($sub)
                            {{-- Grading form popover / modal inline --}}
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition hover:opacity-95"
                                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                                    ⭐ Beri Nilai
                                </button>

                                <div x-show="open" @click.away="open = false" x-cloak
                                     class="mt-3 p-4 bg-gray-50 border border-gray-200 rounded-xl text-left shadow-lg">
                                    <form method="POST" action="{{ route('admin.tugas.gradeSubmission', [$assignment, $sub]) }}" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-700 uppercase">Nilai (Maks {{ $assignment->max_score }})</label>
                                            <input type="number" step="0.1" min="0" max="{{ $assignment->max_score }}" name="score"
                                                   value="{{ old('score', $sub->score) }}" required
                                                   class="w-full text-xs border border-gray-300 rounded-lg px-2.5 py-1.5 font-bold focus:border-red-400 focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-700 uppercase">Catatan / Umpan Balik</label>
                                            <textarea name="feedback" rows="2" placeholder="Catatan untuk siswa..."
                                                      class="w-full text-xs border border-gray-300 rounded-lg px-2.5 py-1.5 focus:border-red-400 focus:outline-none">{{ old('feedback', $sub->feedback) }}</textarea>
                                        </div>
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="open = false" class="px-2.5 py-1 text-xs text-gray-500 hover:text-gray-700">Tutup</button>
                                            <button type="submit" class="px-3 py-1 text-xs font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">
                                                Simpan Nilai
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @else
                            <span class="text-xs text-gray-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-sm">Tidak ada siswa terdaftar pada kelas ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.admin>

