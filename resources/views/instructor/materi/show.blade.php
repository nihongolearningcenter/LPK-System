<x-layouts.instructor>
<x-slot name="title">{{ $lesson->title }}</x-slot>
<x-slot name="pageTitle">Detail Materi Pembelajaran</x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('instructor.materi.index') }}" class="text-xs text-indigo-600 hover:underline">← Semua Materi</a>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mt-1">{{ $lesson->title }}</h1>
            <p class="text-sm text-gray-500">
                Mapel: <strong class="text-gray-800">{{ $lesson->subject?->name ?? 'Umum' }}</strong>
                @if($lesson->classRoom) | Kelas: {{ $lesson->classRoom->name }} @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('instructor.materi.edit', $lesson) }}"
               class="px-4 py-2 text-sm font-semibold text-white rounded-xl shadow-sm hover:opacity-95"
               style="background: linear-gradient(135deg, #e63946, #c1121f);">
                ✏️ Edit Materi
            </a>
        </div>
    </div>

    {{-- Content --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        @if($lesson->video_url)
            <div class="aspect-video rounded-xl overflow-hidden bg-black shadow-md">
                @php
                    $embedUrl = $lesson->video_url;
                    if (str_contains($embedUrl, 'youtube.com/watch?v=')) {
                        $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                    } elseif (str_contains($embedUrl, 'youtu.be/')) {
                        $embedUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $embedUrl);
                    }
                @endphp
                <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
        @endif

        @if($lesson->description)
            <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-700 italic border-l-4 border-red-500">
                {{ $lesson->description }}
            </div>
        @endif

        @if($lesson->content)
            <div class="prose max-w-none text-sm text-gray-800 leading-relaxed whitespace-pre-line">
                {{ $lesson->content }}
            </div>
        @endif

        @if($lesson->file_attachment)
            <div class="p-4 bg-red-50/60 border border-red-100 rounded-xl flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-red-900 font-semibold">
                    <span>📎</span> Berkas Lampiran: {{ basename($lesson->file_attachment) }}
                </div>
                <a href="{{ Storage::url($lesson->file_attachment) }}" target="_blank"
                   class="px-4 py-1.5 bg-white border border-red-200 text-red-700 rounded-lg font-bold hover:bg-red-50">
                    Unduh Berkas
                </a>
            </div>
        @endif
    </div>

</x-layouts.instructor>

