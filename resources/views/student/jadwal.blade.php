<x-layouts.student>
<x-slot name="title">Jadwal Belajar</x-slot>
<x-slot name="pageTitle">Jadwal Belajar</x-slot>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">🗓️ Jadwal Pembelajaran Mingguan</h1>
        <p class="text-sm text-gray-500 mt-0.5">Jadwal sesi tatap muka dan kelas daring reguler Anda</p>
    </div>

    @if(isset($schedules) && $schedules->count() > 0)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Hari</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Kelas & Program</th>
                        <th class="px-6 py-4">Ruang / Tipe</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($schedules as $sch)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $sch->day }}</td>
                        <td class="px-6 py-4 text-xs font-semibold text-red-600 font-mono">
                            {{ substr($sch->start_time, 0, 5) }} - {{ substr($sch->end_time, 0, 5) }} WIB
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $sch->classRoom?->name ?? 'Kelas' }}</div>
                            <div class="text-xs text-gray-400">{{ $sch->classRoom?->program?->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                {{ $sch->room ?? 'Ruang Kelas LPK' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    {{-- Demo Schedule Card --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50">
            <h2 class="font-bold text-gray-900 text-base">Jadwal Sesi Reguler (Contoh Sesi Aktif)</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @php
            $demoJadwal = [
                ['hari'=>'Senin', 'jam'=>'08:30 - 11:30 WIB', 'materi'=>'Grammar & Pola Kalimat N5 (Bunpou)', 'sensei'=>'Sensei Hiroshi', 'ruang'=>'Ruang Fuji (Lantai 2)'],
                ['hari'=>'Rabu',  'jam'=>'08:30 - 11:30 WIB', 'materi'=>'Kosakata & Kanji N5 (Moji/Goi)', 'sensei'=>'Sensei Kenji', 'ruang'=>'Ruang Sakura (Lantai 2)'],
                ['hari'=>'Jumat', 'jam'=>'08:30 - 11:30 WIB', 'materi'=>'Percakapan & Pendengaran (Kaiwa/Choukai)', 'sensei'=>'Sensei Yoko', 'ruang'=>'Lab Bahasa (Lantai 1)'],
            ];
            @endphp
            @foreach($demoJadwal as $j)
            <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 hover:bg-gray-50 transition">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center text-white flex-shrink-0"
                         style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        <span class="text-xs font-medium uppercase text-white/80">HARI</span>
                        <span class="text-sm font-black">{{ $j['hari'] }}</span>
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 text-base mb-1">{{ $j['materi'] }}</div>
                        <div class="flex items-center gap-3 text-xs text-gray-400">
                            <span>👨‍🏫 {{ $j['sensei'] }}</span>
                            <span>•</span>
                            <span>📍 {{ $j['ruang'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-sm font-mono font-bold text-red-600 bg-red-50 px-4 py-2 rounded-xl self-start sm:self-auto">
                    ⏱️ {{ $j['jam'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</x-layouts.student>
