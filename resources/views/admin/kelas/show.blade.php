<x-layouts.admin>
<x-slot name="title">{{ $class->name }} — Detail Kelas</x-slot>
<x-slot name="pageTitle">Detail Kelas & Siswa</x-slot>

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.kelas.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar Kelas
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $class->name }}</span>
        </div>

        <a href="{{ route('admin.kelas.edit', $class) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 shadow-sm transition">
            ✏️ Edit Kelas
        </a>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ showAddStudentModal: false, showAddScheduleModal: false }">

        {{-- Left 2 Cols: Enrolled Students & Schedules --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 1. Siswa Terdaftar --}}
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">👥 Siswa Terdaftar</h2>
                        <p class="text-xs text-gray-400">Total: {{ $class->classStudents->count() }} / {{ $class->max_students ?? '∞' }} siswa</p>
                    </div>

                    <button @click="showAddStudentModal = true"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-sm"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        + Masukkan Siswa ke Kelas
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs text-gray-400 uppercase tracking-wider">
                                <th class="px-4 py-3">Nama Siswa</th>
                                <th class="px-4 py-3">No. Registrasi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($class->classStudents as $cs)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900">{{ $cs->student?->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $cs->student?->email }}</div>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-gray-600">
                                    {{ $cs->student?->studentProfile?->registration_number ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-100 text-green-700">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.kelas.removeStudent', [$class, $cs->student]) }}"
                                          onsubmit="return confirm('Keluarkan siswa ini dari kelas?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 font-semibold hover:underline">
                                            Keluarkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-400 text-xs">
                                    Belum ada siswa yang dimasukkan ke kelas ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. Jadwal Sesi Pertemuan Mingguan --}}
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">🗓️ Jadwal Sesi Pertemuan Mingguan</h2>
                        <p class="text-xs text-gray-400">Jadwal hari dan jam sesi belajar yang tampil di portal pengajar dan siswa</p>
                    </div>

                    <button @click="showAddScheduleModal = true"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-sm"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        + Tambah Jadwal Sesi
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs text-gray-400 uppercase tracking-wider">
                                <th class="px-4 py-3">Hari</th>
                                <th class="px-4 py-3">Waktu Belajar</th>
                                <th class="px-4 py-3">Ruangan / Tempat</th>
                                <th class="px-4 py-3">Catatan</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($class->schedules as $sched)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <span class="font-bold text-gray-900 px-2.5 py-1 bg-red-50 text-red-700 rounded-lg text-xs">
                                        {{ $sched->day_of_week }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono font-semibold text-xs text-gray-700">
                                    ⏰ {{ substr($sched->start_time, 0, 5) }} - {{ substr($sched->end_time, 0, 5) }} WIB
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    🚪 {{ $sched->room ?? ($class->room ?? 'Ruang Kelas LPK') }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-400">
                                    {{ $sched->notes ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.kelas.removeSchedule', [$class, $sched]) }}"
                                          onsubmit="return confirm('Hapus jadwal sesi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 font-semibold hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-xs">
                                    Belum ada jadwal sesi mingguan untuk kelas ini. Klik tombol "+ Tambah Jadwal Sesi" di atas.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal Add Student --}}
            <div x-show="showAddStudentModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                <div @click.away="showAddStudentModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Tambahkan Siswa ke Kelas</h3>
                    <p class="text-xs text-gray-500 mb-4">Pilih akun siswa yang sudah terdaftar untuk dimasukkan ke kelas ini.</p>
                    <form method="POST" action="{{ route('admin.kelas.addStudent', $class) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Pilih Siswa</label>
                            <select name="student_id" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                                <option value="">Cari dan pilih siswa...</option>
                                @foreach($allStudents as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="showAddStudentModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 rounded-xl hover:bg-red-700">Tambahkan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Add Schedule --}}
            <div x-show="showAddScheduleModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                <div @click.away="showAddScheduleModal = false" class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Tambah Jadwal Sesi Belajar</h3>
                    <p class="text-xs text-gray-500 mb-4">Tentukan hari dan jam belajar mingguan untuk kelas ini.</p>
                    <form method="POST" action="{{ route('admin.kelas.addSchedule', $class) }}">
                        @csrf
                        <div class="space-y-4 mb-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Pilih Hari <span class="text-red-500">*</span></label>
                                <select name="day_of_week" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                                    <option value="Senin">Senin (月曜日)</option>
                                    <option value="Selasa">Selasa (火曜日)</option>
                                    <option value="Rabu">Rabu (水曜日)</option>
                                    <option value="Kamis">Kamis (木曜日)</option>
                                    <option value="Jumat">Jumat (金曜日)</option>
                                    <option value="Sabtu">Sabtu (土曜日)</option>
                                    <option value="Minggu">Minggu (日曜日)</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                                    <input type="time" name="start_time" value="08:30" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                                    <input type="time" name="end_time" value="11:30" required class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Ruangan / Tempat</label>
                                <input type="text" name="room" placeholder="Contoh: Ruang Sakura (Lt. 2) atau Zoom" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Catatan Sesi</label>
                                <input type="text" name="notes" placeholder="Contoh: Modul Bab 1-5" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                            <button type="button" @click="showAddScheduleModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 rounded-xl hover:bg-red-700">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- Right 1 Col: Class Info Card --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-100">
                    Informasi Kelas
                </h3>

                <div>
                    <div class="text-xs text-gray-400">Program Kursus</div>
                    <div class="font-bold text-red-600 text-sm mt-0.5">{{ $class->program?->name }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-400">Pengajar Utama</div>
                    <div class="font-semibold text-gray-900 text-sm mt-0.5">{{ $class->instructor?->name ?? 'Belum ditentukan' }}</div>
                </div>

                <div>
                    <div class="text-xs text-gray-400">Status</div>
                    <div class="mt-0.5">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            {{ ucfirst($class->status) }}
                        </span>
                    </div>
                </div>

                @if($class->meeting_link)
                <div>
                    <div class="text-xs text-gray-400">Tautan Virtual</div>
                    <a href="{{ $class->meeting_link }}" target="_blank" class="text-xs text-blue-600 font-semibold hover:underline truncate block mt-0.5">
                        {{ $class->meeting_link }}
                    </a>
                </div>
                @endif

                @if($class->notes)
                <div>
                    <div class="text-xs text-gray-400">Catatan</div>
                    <div class="text-xs text-gray-600 bg-gray-50 p-3 rounded-xl mt-1">{{ $class->notes }}</div>
                </div>
                @endif
            </div>
        </div>

    </div>

</x-layouts.admin>
