<x-layouts.student>
<x-slot name="title">Tugas Saya</x-slot>
<x-slot name="pageTitle">Tugas Pembelajaran</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">📌 Tugas Saya</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pantau dan kumpulkan tugas latihan dari sensei Anda</p>
    </div>

    @if(isset($assignments) && $assignments->count() > 0)
    <div class="space-y-4">
        @foreach($assignments as $task)
        @php
        $submission = $task->submissions->first();
        $isOverdue = $task->due_date && now()->isAfter($task->due_date);
        @endphp
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                        {{ $task->classRoom?->name ?? 'Kelas' }}
                    </span>
                    @if($submission)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            {{ $submission->status === 'graded' ? 'Dinilai: ' . $submission->score . '/100' : '✅ Sudah Dikumpul' }}
                        </span>
                    @elseif($isOverdue)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            ⚠️ Lewat Tenggat
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                            ⏳ Belum Dikumpul
                        </span>
                    @endif
                </div>

                <h3 class="text-base font-bold text-gray-900 mb-1 truncate">{{ $task->title }}</h3>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span>Tenggat: <strong class="text-gray-600">{{ $task->due_date ? $task->due_date->format('d M Y, H:i') : 'Tanpa batas waktu' }}</strong></span>
                    <span>•</span>
                    <span>Maksimal Nilai: <strong class="text-gray-600">{{ $task->max_score }}</strong></span>
                </div>
            </div>

            <div>
                <a href="{{ route('student.tugas.show', $task) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white transition hover:opacity-95 shadow-sm"
                   style="background: {{ $submission ? '#4b5563' : '#e63946' }};">
                    {{ $submission ? 'Lihat Tugas →' : 'Kerjakan & Kumpul →' }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-400">
        <div class="text-5xl mb-3">🎉</div>
        <h3 class="font-bold text-gray-800 text-base mb-1">Tidak Ada Tugas Tertunda</h3>
        <p class="text-xs text-gray-400">Semua tugas kelas telah Anda selesaikan atau belum ada tugas baru dari pengajar.</p>
    </div>
    @endif

</x-layouts.student>
