<x-layouts.student>
<x-slot name="title">Materi Pembelajaran</x-slot>
<x-slot name="pageTitle">Materi Pembelajaran</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">📖 Materi Pembelajaran</h1>
        <p class="text-sm text-gray-500 mt-0.5">Akses semua materi dan modul belajar Bahasa Jepang Anda</p>
    </div>

    @if(isset($lessons) && $lessons->count() > 0)
    {{-- Subject Groups --}}
    @foreach($lessons->groupBy('subject.name') as $subjectName => $subjectLessons)
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-3">
            <h2 class="font-semibold text-gray-800">{{ $subjectName ?? 'Materi Umum' }}</h2>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $subjectLessons->count() }} materi</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($subjectLessons as $lesson)
            <a href="{{ route('student.materi.show', $lesson) }}"
               class="block bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md hover:border-red-100 transition group">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-red-50 flex-shrink-0">
                        {{ $lesson->type === 'video' ? '🎬' : ($lesson->type === 'pdf' ? '📄' : '📝') }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-semibold text-gray-900 group-hover:text-red-600 transition mb-1">{{ $lesson->title }}</div>
                        <p class="text-xs text-gray-400 line-clamp-2">{{ $lesson->description ?? 'Tidak ada deskripsi' }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            @if($lesson->duration_minutes)
                            <span class="text-xs text-gray-400">⏱️ {{ $lesson->duration_minutes }} menit</span>
                            @endif
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $lesson->type === 'video' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($lesson->type) }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endforeach

    @else
    {{-- Empty State / Demo Materi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        @php
        $demoMateri = [
            ['icon' => '🔤', 'title' => 'Pengenalan Hiragana & Katakana', 'type' => 'Materi', 'durasi' => '30 menit', 'link' => route('student.hiragana-katakana')],
            ['icon' => '🈲', 'title' => 'Kanji Dasar JLPT N5', 'type' => 'Materi', 'durasi' => '45 menit', 'link' => route('student.kanji')],
            ['icon' => '📖', 'title' => 'Kosakata Sehari-hari', 'type' => 'Materi', 'durasi' => '20 menit', 'link' => route('student.kosakata.index')],
            ['icon' => '📝', 'title' => 'Latihan JLPT', 'type' => 'Latihan', 'durasi' => '60 menit', 'link' => route('student.jlpt.index')],
        ];
        @endphp
        @foreach($demoMateri as $m)
        <a href="{{ $m['link'] }}"
           class="block bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md hover:border-red-100 transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-red-50 flex-shrink-0">{{ $m['icon'] }}</div>
                <div>
                    <div class="font-semibold text-gray-900 group-hover:text-red-600 transition">{{ $m['title'] }}</div>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs text-gray-400">⏱️ {{ $m['durasi'] }}</span>
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $m['type'] }}</span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 text-center">
        <div class="text-4xl mb-3">📚</div>
        <h3 class="font-semibold text-gray-900 mb-1">Belum Ada Materi Kelas</h3>
        <p class="text-sm text-gray-500">Materi kelas akan tersedia setelah Anda terdaftar di kelas aktif. Gunakan modul di atas untuk mulai belajar!</p>
    </div>
    @endif

</x-layouts.student>
