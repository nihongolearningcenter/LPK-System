<x-layouts.admin>
<x-slot name="title">Daftar Pendaftaran</x-slot>
<x-slot name="pageTitle">Manajemen Pendaftaran</x-slot>

    {{-- Status Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @foreach([
            ['label'=>'Menunggu',  'count'=>$statusCounts['pending'],  'color'=>'from-yellow-400 to-yellow-500', 'icon'=>'⏳', 'status'=>'pending'],
            ['label'=>'Diterima',  'count'=>$statusCounts['accepted'], 'color'=>'from-green-500 to-green-600',  'icon'=>'✅', 'status'=>'accepted'],
            ['label'=>'Ditolak',   'count'=>$statusCounts['rejected'], 'color'=>'from-red-500 to-red-600',     'icon'=>'❌', 'status'=>'rejected'],
        ] as $card)
        <a href="{{ route('admin.pendaftaran.index', ['status' => $card['status']]) }}"
           class="bg-white rounded-2xl p-5 border {{ request('status') === $card['status'] ? 'border-red-300 shadow-md' : 'border-gray-100' }} shadow-sm hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl bg-gradient-to-br {{ $card['color'] }}">
                {{ $card['icon'] }}
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $card['count'] }}</div>
                <div class="text-xs text-gray-500">{{ $card['label'] }}</div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <form method="GET" class="flex items-center gap-3 flex-wrap">
                <div class="relative flex-1 min-w-48">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no. registrasi..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                </div>
                <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg" style="background:#e63946;">Filter</button>
                <a href="{{ route('admin.pendaftaran.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">No. Registrasi</th>
                        <th class="px-6 py-3">Nama Pendaftar</th>
                        <th class="px-6 py-3">Program</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($enrollments as $enrollment)
                    @php
                    $statusConfig = [
                        'pending'  => ['label'=>'Menunggu',  'class'=>'bg-yellow-100 text-yellow-700'],
                        'reviewing'=> ['label'=>'Ditinjau', 'class'=>'bg-blue-100 text-blue-700'],
                        'accepted' => ['label'=>'Diterima', 'class'=>'bg-green-100 text-green-700'],
                        'rejected' => ['label'=>'Ditolak',  'class'=>'bg-red-100 text-red-700'],
                        'enrolled' => ['label'=>'Terdaftar','class'=>'bg-purple-100 text-purple-700'],
                    ];
                    $sc = $statusConfig[$enrollment->status] ?? ['label'=>$enrollment->status,'class'=>'bg-gray-100 text-gray-600'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $enrollment->registration_number ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $enrollment->full_name }}</div>
                            <div class="text-xs text-gray-400">{{ $enrollment->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $enrollment->program?->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc['class'] }}">{{ $sc['label'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $enrollment->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.pendaftaran.show', $enrollment) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                    👁️ Detail
                                </a>
                                @if($enrollment->status === 'pending')
                                <form method="POST" action="{{ route('admin.pendaftaran.accept', $enrollment) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium text-green-600 bg-green-50 hover:bg-green-100 transition">
                                        ✅ Terima
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">📋</div>
                            <p class="text-sm">Belum ada pendaftaran masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($enrollments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $enrollments->links() }}</div>
        @endif
    </div>

</x-layouts.admin>
