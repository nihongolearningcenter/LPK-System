<x-layouts.admin>
<x-slot name="title">Profil Siswa: {{ $siswa->name }}</x-slot>
<x-slot name="pageTitle">Detail Profil Siswa</x-slot>

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $siswa->name }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Siswa terdaftar sejak {{ $siswa->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.siswa.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50">
                ← Kembali
            </a>
            <a href="{{ route('admin.siswa.edit', $siswa) }}"
               class="px-4 py-2 text-sm font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100">
                ✏️ Edit Profil
            </a>
        </div>
    </div>

    {{-- Profile Banner Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white text-3xl font-bold flex-shrink-0"
                 style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                {{ strtoupper(substr($siswa->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $siswa->name }}</h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                        {{ $siswa->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $siswa->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $siswa->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                        Target: {{ $siswa->studentProfile?->japanese_level_target ?? 'N4' }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-4 text-xs text-gray-500 mt-2">
                    <span>📧 {{ $siswa->email }}</span>
                    <span>📱 {{ $siswa->studentProfile?->phone ?? '-' }}</span>
                    <span>🪪 NIK: {{ $siswa->studentProfile?->nik ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Biodata Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <span>👤</span> Data Pribadi
            </h3>
            <dl class="space-y-3 text-xs">
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <dt class="text-gray-400">Jenis Kelamin</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $siswa->studentProfile?->gender === 'male' ? 'Laki-laki (男)' : ($siswa->studentProfile?->gender === 'female' ? 'Perempuan (女)' : '-') }}
                    </dd>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <dt class="text-gray-400">Tempat & Tanggal Lahir</dt>
                    <dd class="font-medium text-gray-800">
                        {{ $siswa->studentProfile?->birth_place ?? '-' }},
                        {{ $siswa->studentProfile?->birth_date ? $siswa->studentProfile->birth_date->format('d M Y') : '-' }}
                    </dd>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <dt class="text-gray-400">Pendidikan Terakhir</dt>
                    <dd class="font-medium text-gray-800">{{ $siswa->studentProfile?->last_education ?? '-' }}</dd>
                </div>
                <div class="py-1">
                    <dt class="text-gray-400 mb-1">Alamat Domisili</dt>
                    <dd class="font-medium text-gray-800">{{ $siswa->studentProfile?->address ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <span>🏫</span> Kelas yang Diikuti
            </h3>
            @if($siswa->classes->isEmpty())
                <p class="text-xs text-gray-400 italic">Siswa belum dimasukkan ke kelas manapun.</p>
            @else
                <div class="space-y-3">
                    @foreach($siswa->classes as $c)
                    <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-gray-900">{{ $c->name }}</div>
                            <div class="text-gray-500">{{ $c->program?->title ?? 'Umum' }}</div>
                        </div>
                        <a href="{{ route('admin.kelas.show', $c) }}" class="text-indigo-600 hover:underline font-semibold">
                            Lihat Kelas →
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Academic History Tabs --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6"
         x-data="{ activeTab: 'grades' }">
        <div class="px-6 py-3 border-b border-gray-100 flex gap-4 text-xs font-semibold">
            <button @click="activeTab = 'grades'" :class="activeTab === 'grades' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-800'" class="pb-2">
                📊 Rekap Nilai ({{ $siswa->grades->count() }})
            </button>
            <button @click="activeTab = 'exams'" :class="activeTab === 'exams' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-800'" class="pb-2">
                📝 Ujian / JLPT ({{ $siswa->examAttempts->count() }})
            </button>
            <button @click="activeTab = 'certificates'" :class="activeTab === 'certificates' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-gray-800'" class="pb-2">
                📜 Sertifikat ({{ $siswa->certificates->count() }})
            </button>
        </div>

        {{-- Grades Tab --}}
        <div x-show="activeTab === 'grades'" class="p-6">
            @if($siswa->grades->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">Belum ada rekap nilai yang tercatat untuk siswa ini.</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase">
                            <th class="p-3">Mata Pelajaran</th>
                            <th class="p-3 text-center">Absensi</th>
                            <th class="p-3 text-center">Tugas</th>
                            <th class="p-3 text-center">UTS</th>
                            <th class="p-3 text-center">UAS</th>
                            <th class="p-3 text-center">Nilai Akhir</th>
                            <th class="p-3 text-center">Huruf</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($siswa->grades as $g)
                        <tr>
                            <td class="p-3 font-semibold text-gray-800">{{ $g->subject?->name ?? 'Umum' }}</td>
                            <td class="p-3 text-center">{{ $g->score_attendance ?? '-' }}</td>
                            <td class="p-3 text-center">{{ $g->score_assignments ?? '-' }}</td>
                            <td class="p-3 text-center">{{ $g->score_midterm ?? '-' }}</td>
                            <td class="p-3 text-center">{{ $g->score_final ?? '-' }}</td>
                            <td class="p-3 text-center font-bold text-gray-900">{{ $g->final_grade ?? '-' }}</td>
                            <td class="p-3 text-center font-bold text-red-600">{{ $g->grade_letter ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Exams Tab --}}
        <div x-show="activeTab === 'exams'" class="p-6">
            @if($siswa->examAttempts->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">Siswa belum mengikuti tes atau ujian online.</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-left text-gray-500 uppercase">
                            <th class="p-3">Ujian / Simulasi</th>
                            <th class="p-3 text-center">Nilai</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($siswa->examAttempts as $ea)
                        <tr>
                            <td class="p-3 font-semibold text-gray-800">{{ $ea->exam?->title ?? 'Ujian' }}</td>
                            <td class="p-3 text-center font-bold text-gray-900">{{ number_format($ea->total_score, 1) }}</td>
                            <td class="p-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $ea->is_passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $ea->is_passed ? 'LULUS' : 'TIDAK LULUS' }}
                                </span>
                            </td>
                            <td class="p-3 text-right text-gray-400">{{ $ea->completed_at ? $ea->completed_at->format('d M Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Certificates Tab --}}
        <div x-show="activeTab === 'certificates'" class="p-6">
            @if($siswa->certificates->isEmpty())
                <p class="text-xs text-gray-400 text-center py-6">Belum ada sertifikat yang diterbitkan untuk siswa ini.</p>
            @else
                <div class="space-y-3">
                    @foreach($siswa->certificates as $cert)
                    <div class="p-3 bg-amber-50/50 border border-amber-100 rounded-xl flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-gray-900">{{ $cert->certificate_name }}</div>
                            <div class="text-gray-500 font-mono">{{ $cert->certificate_number }}</div>
                        </div>
                        <a href="{{ route('admin.sertifikat.show', $cert) }}" target="_blank"
                           class="px-3 py-1 bg-white border border-amber-200 text-amber-800 font-semibold rounded-lg hover:bg-amber-100">
                            Lihat / Cetak
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-layouts.admin>
