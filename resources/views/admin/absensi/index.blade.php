<x-layouts.admin>
<x-slot name="title">Presensi Kehadiran</x-slot>
<x-slot name="pageTitle">Absensi Kelas</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">✅ Presensi Kehadiran Siswa</h1>
        <p class="text-xs text-gray-500 mt-0.5">Catat kehadiran tatap muka atau kelas daring berdasarkan sesi tanggal</p>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pilih Kelas</label>
                <select name="class_id" onchange="this.form.submit()" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $selectedClass?->id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Pertemuan</label>
                <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()"
                       class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
            </div>

            <div class="pt-5">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg" style="background:#e63946;">Muat Presensi</button>
            </div>
        </form>
    </div>

    @if($selectedClass)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6">
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-900">{{ $selectedClass->name }}</h2>
                <p class="text-xs text-gray-400">Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-red-50 text-red-600">
                Total Siswa: {{ count($students) }}
            </span>
        </div>

        @if(count($students) > 0)
        <form method="POST" action="{{ route('admin.absensi.store') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3">No. Registrasi</th>
                            <th class="px-4 py-3 text-center">Hadir</th>
                            <th class="px-4 py-3 text-center">Izin</th>
                            <th class="px-4 py-3 text-center">Sakit</th>
                            <th class="px-4 py-3 text-center">Alpha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($students as $cs)
                        @php
                        $stId = $cs->student?->id;
                        $currentStatus = $existingAttendance[$stId] ?? 'hadir';
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-semibold text-gray-900">
                                {{ $cs->student?->name }}
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-400">
                                {{ $cs->student?->studentProfile?->registration_number ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <label class="cursor-pointer inline-flex items-center">
                                    <input type="radio" name="attendance[{{ $stId }}]" value="hadir" {{ $currentStatus === 'hadir' ? 'checked' : '' }}
                                           class="w-4 h-4 text-green-600 focus:ring-green-500">
                                </label>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <label class="cursor-pointer inline-flex items-center">
                                    <input type="radio" name="attendance[{{ $stId }}]" value="izin" {{ $currentStatus === 'izin' ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                </label>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <label class="cursor-pointer inline-flex items-center">
                                    <input type="radio" name="attendance[{{ $stId }}]" value="sakit" {{ $currentStatus === 'sakit' ? 'checked' : '' }}
                                           class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                </label>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <label class="cursor-pointer inline-flex items-center">
                                    <input type="radio" name="attendance[{{ $stId }}]" value="alpha" {{ $currentStatus === 'alpha' ? 'checked' : '' }}
                                           class="w-4 h-4 text-red-600 focus:ring-red-500">
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white transition hover:opacity-95 shadow-sm"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    Simpan Presensi Kehadiran
                </button>
            </div>
        </form>
        @else
        <div class="text-center py-10 text-xs text-gray-400">
            Belum ada siswa yang dialokasikan di kelas ini. Masukkan siswa melalui menu Kelas.
        </div>
        @endif
    </div>
    @endif

</x-layouts.admin>
