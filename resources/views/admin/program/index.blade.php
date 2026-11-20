<x-layouts.admin>
<x-slot name="title">Data Program</x-slot>
<x-slot name="pageTitle">Data Program Pelatihan</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Program Pelatihan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua program kursus bahasa Jepang</p>
        </div>
        <a href="{{ route('admin.program.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Program
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
            <div class="relative flex-1 max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" placeholder="Cari program..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
            </div>
            <span class="text-xs text-gray-400">{{ $programs->total() ?? count($programs) }} program</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Program</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Durasi</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($programs as $program)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $program->name }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($program->short_description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                            $typeLabels = [
                                'dasar' => ['label'=>'Dasar','class'=>'bg-green-100 text-green-700'],
                                'jlpt_n5' => ['label'=>'JLPT N5','class'=>'bg-blue-100 text-blue-700'],
                                'jlpt_n4' => ['label'=>'JLPT N4','class'=>'bg-indigo-100 text-indigo-700'],
                                'kaiwa' => ['label'=>'Kaiwa','class'=>'bg-purple-100 text-purple-700'],
                                'kerja' => ['label'=>'Persiapan Kerja','class'=>'bg-orange-100 text-orange-700'],
                                'tokutei_ginou' => ['label'=>'Tokutei Ginou','class'=>'bg-red-100 text-red-700'],
                            ];
                            $type = $typeLabels[$program->type] ?? ['label'=>$program->type,'class'=>'bg-gray-100 text-gray-700'];
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $type['class'] }}">
                                {{ $type['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $program->price > 0 ? 'Rp '.number_format($program->price,0,',','.') : 'Hubungi Kami' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $program->duration_weeks ? $program->duration_weeks.' minggu' : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $program->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $program->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                {{ $program->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.program.edit', $program) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('admin.program.destroy', $program) }}" onsubmit="return confirm('Hapus program ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">📚</div>
                            <p class="text-sm">Belum ada program. <a href="{{ route('admin.program.create') }}" class="text-red-600 hover:underline">Tambah sekarang</a></p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($programs, 'links'))
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $programs->links() }}
        </div>
        @endif
    </div>

</x-layouts.admin>
