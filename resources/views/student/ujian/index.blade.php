<x-layouts.student>
<x-slot name="title">Ujian Online</x-slot>
<x-slot name="pageTitle">Ujian & Evaluasi</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">📋 Ujian Online & Kuis</h1>
        <p class="text-sm text-gray-500 mt-0.5">Uji kemampuan Bahasa Jepang Anda secara berkala melalui ujian daring</p>
    </div>

    {{-- Upcoming / Available Exams --}}
    <div class="mb-8">
        <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span>📝</span> Ujian yang Tersedia
        </h2>

        @if($upcomingExams->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($upcomingExams as $exam)
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold
                            {{ $exam->type === 'simulasi_jlpt' ? 'bg-purple-100 text-purple-700' : 'bg-red-100 text-red-700' }}">
                            {{ $exam->jlpt_level ? 'JLPT ' . $exam->jlpt_level : ucfirst(str_replace('_', ' ', $exam->type)) }}
                        </span>
                        <span class="text-xs text-gray-400">⏱️ {{ $exam->duration_minutes }} Menit</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base mb-1">{{ $exam->title }}</h3>
                    <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $exam->description ?? 'Tidak ada deskripsi khusus.' }}</p>
                </div>

                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        KKM: <span class="font-semibold text-gray-700">{{ $exam->passing_score }}</span>
                    </div>
                    <a href="{{ route('student.ujian.start', $exam) }}"
                       class="px-4 py-2 rounded-xl text-xs font-semibold text-white transition hover:scale-105 shadow-sm"
                       style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        Mulai Ujian →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
            <div class="text-4xl mb-2">🎉</div>
            <div class="font-semibold text-gray-700 text-sm">Tidak ada ujian aktif saat ini.</div>
            <p class="text-xs text-gray-400 mt-1">Anda juga dapat berlatih soal mandiri di menu Latihan JLPT.</p>
            <a href="{{ route('student.jlpt.index') }}" class="mt-3 inline-block text-xs font-bold text-red-600 hover:underline">
                Buka Latihan JLPT →
            </a>
        </div>
        @endif
    </div>

    {{-- Past Exam Attempts --}}
    <div>
        <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span>📊</span> Riwayat Hasil Ujian
        </h2>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Nama Ujian</th>
                            <th class="px-6 py-3">Nilai</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Benar / Salah</th>
                            <th class="px-6 py-3">Waktu Selesai</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pastAttempts as $attempt)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ $attempt->exam?->title ?? 'Ujian' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-base font-bold {{ $attempt->is_passed ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $attempt->score }}
                                </span>
                                <span class="text-xs text-gray-400">/ 100</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($attempt->is_passed)
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Lulus
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Tidak Lulus
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <span class="text-green-600 font-bold">{{ $attempt->correct_count }}</span> Benar /
                                <span class="text-red-600 font-bold">{{ $attempt->wrong_count }}</span> Salah
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">
                                {{ $attempt->submitted_at ? $attempt->submitted_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('student.ujian.result', $attempt) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                    Lihat Pembahasan
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                Belum ada riwayat ujian yang diselesaikan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.student>
