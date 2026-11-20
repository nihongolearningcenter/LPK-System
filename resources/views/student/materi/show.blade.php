<x-layouts.student>
<x-slot name="title">{{ $lesson->title }}</x-slot>
<x-slot name="pageTitle">Materi Pembelajaran</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('student.materi.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Materi
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium truncate">{{ $lesson->title }}</span>
        </div>

        {{-- Lesson Header --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                    {{ $lesson->subject?->name ?? 'Bahasa Jepang' }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                    {{ ucfirst($lesson->type) }}
                </span>
                @if($lesson->duration_minutes)
                <span class="text-xs text-gray-400">⏱️ {{ $lesson->duration_minutes }} Menit</span>
                @endif
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">{{ $lesson->title }}</h1>
            @if($lesson->description)
            <p class="text-sm text-gray-500 leading-relaxed">{{ $lesson->description }}</p>
            @endif

            {{-- Video embed if available --}}
            @if($lesson->video_url)
            <div class="mt-6 aspect-video rounded-2xl overflow-hidden bg-black shadow-md">
                <iframe src="{{ str_replace('watch?v=', 'embed/', $lesson->video_url) }}"
                        class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
            @endif

            {{-- File attachment if available --}}
            @if($lesson->file_attachment)
            <div class="mt-6 p-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📄</span>
                    <div>
                        <div class="text-sm font-semibold text-gray-800">Lampiran Materi Pembelajaran</div>
                        <div class="text-xs text-gray-400">Format dokumen pendukung pelajaran</div>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $lesson->file_attachment) }}" target="_blank"
                   class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-red-600 hover:bg-red-700 transition">
                    Unduh Dokumen
                </a>
            </div>
            @endif

            {{-- Text / HTML Content --}}
            @if($lesson->content)
            <div class="mt-8 pt-6 border-t border-gray-100 prose prose-red max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($lesson->content)) !!}
            </div>
            @endif
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('student.materi.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                ← Kembali ke Daftar Materi
            </a>
            <a href="{{ route('student.kosakata.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white transition hover:opacity-90 shadow-sm" style="background:#e63946;">
                Pelajari Kosakata Terkait →
            </a>
        </div>

    </div>

</x-layouts.student>
