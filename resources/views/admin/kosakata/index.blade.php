<x-layouts.admin>
<x-slot name="title">Manajemen Kosakata</x-slot>
<x-slot name="pageTitle">Master Kosakata</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">📖 Master Kosakata Bahasa Jepang</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola repositori kosakata dasar hingga mahir (N5 - N1) untuk flashcard dan materi siswa</p>
        </div>
        <a href="{{ route('admin.kosakata.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:scale-105"
           style="background: linear-gradient(135deg, #e63946, #c1121f);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kosakata Baru
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kanji, kana, romaji, atau arti..."
                       class="w-full pl-4 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
            </div>
            <select name="jlpt_level" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Level</option>
                @foreach(['N5', 'N4', 'N3', 'N2', 'N1'] as $lvl)
                <option value="{{ $lvl }}" {{ request('jlpt_level') === $lvl ? 'selected' : '' }}>JLPT {{ $lvl }}</option>
                @endforeach
            </select>
            <select name="category" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-red-300">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-lg" style="background:#e63946;">Filter</button>
            <a href="{{ route('admin.kosakata.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        </form>
    </div>

    {{-- Vocab Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Kata (日本語)</th>
                        <th class="px-6 py-3">Cara Baca (Kana / Romaji)</th>
                        <th class="px-6 py-3">Arti Bahasa Indonesia</th>
                        <th class="px-6 py-3">Level / Tema</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($vocabularies as $v)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="text-xl font-bold text-gray-900" style="font-family:'Noto Sans JP';">{{ $v->japanese }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">
                            <div>{{ $v->hiragana }}</div>
                            <div class="text-gray-400 font-mono">{{ $v->romaji }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $v->meaning_id }}</div>
                            @if($v->example_sentence_jp)
                            <div class="text-xs text-gray-400 mt-1 line-clamp-1 italic">{{ $v->example_sentence_jp }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                                {{ $v->jlpt_level ?? 'N5' }}
                            </span>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $v->category ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.kosakata.edit', $v) }}"
                                   class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                                    ✏️ Edit
                                </a>
                                <form method="POST" action="{{ route('admin.kosakata.destroy', $v) }}"
                                      onsubmit="return confirm('Hapus kosakata ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="text-4xl mb-3">📖</div>
                            Belum ada kosakata yang tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vocabularies->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $vocabularies->links() }}</div>
        @endif
    </div>

</x-layouts.admin>

