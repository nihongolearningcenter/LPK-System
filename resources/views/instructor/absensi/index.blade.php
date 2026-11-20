<x-layouts.instructor>
<x-slot name="title">Presensi Kehadiran — {{ $class->name }}</x-slot>
<x-slot name="pageTitle">Presensi Kehadiran Kelas {{ $class->name }}</x-slot>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('instructor.kelas.show', $class) }}" class="text-xs text-indigo-600 hover:underline">← Kembali ke Detail Kelas</a>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mt-1">Presensi Kehadiran (出席管理)</h1>
            <p class="text-sm text-gray-500">Kelas: <strong class="text-gray-800">{{ $class->name }}</strong> ({{ $class->program?->title ?? 'Reguler' }})</p>
        </div>
    </div>

    {{-- Date Filter Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('instructor.absensi.index', $class) }}" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    Pilih Tanggal Pertemuan
                </label>
                <input type="date" name="date" value="{{ $selectedDate }}"
                       class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95"
                    style="background: linear-gradient(135deg, #e63946, #c1121f);">
                📅 Ubah Tanggal
            </button>
        </form>
    </div>

    {{-- Attendance Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 text-sm">
                Daftar Hadir Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </h2>
            <span class="text-xs text-gray-500">{{ $students->count() }} Siswa</span>
        </div>

        @if($students->isEmpty())
        <div class="p-12 text-center text-gray-400">
            <p class="text-sm">Belum ada siswa terdaftar pada kelas ini.</p>
        </div>
        @else
        <form method="POST" action="{{ route('instructor.absensi.store', $class) }}">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Siswa</th>
                            <th class="px-6 py-3 text-center">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($students as $student)
                        @php
                            $status = $existingAttendance[$student->id] ?? 'present';
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-4">
                                    <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-green-700 bg-green-50 px-3 py-1.5 rounded-lg border border-green-200 hover:bg-green-100">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="present" {{ $status === 'present' ? 'checked' : '' }}
                                               class="text-green-600 focus:ring-green-400">
                                        <span>Hadir (出席)</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200 hover:bg-blue-100">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="permission" {{ $status === 'permission' ? 'checked' : '' }}
                                               class="text-blue-600 focus:ring-blue-400">
                                        <span>Izin</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-amber-700 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200 hover:bg-amber-100">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="sick" {{ $status === 'sick' ? 'checked' : '' }}
                                               class="text-amber-600 focus:ring-amber-400">
                                        <span>Sakit</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer text-xs font-medium text-red-700 bg-red-50 px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-100">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="absent" {{ $status === 'absent' ? 'checked' : '' }}
                                               class="text-red-600 focus:ring-red-400">
                                        <span>Alpa (欠席)</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">Centang kehadiran seluruh siswa untuk sesi pertemuan ini.</span>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95"
                        style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    💾 Simpan Presensi Kehadiran
                </button>
            </div>
        </form>
        @endif
    </div>

</x-layouts.instructor>

