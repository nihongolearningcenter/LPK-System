<x-layouts.admin>
<x-slot name="title">Tambah Pengguna</x-slot>
<x-slot name="pageTitle">Tambah Pengguna Baru</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Tambah Pengguna Baru</h1>
                <p class="text-sm text-gray-500 mt-0.5">Buat akun untuk administrator, pengajar, atau siswa</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                {{-- Nama & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Peran (Role) & Telepon --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Peran Pengguna (Role) <span class="text-red-500">*</span>
                        </label>
                        <select name="role" required
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Siswa (Student)</option>
                            <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }}>Pengajar / Sensei (Instructor)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Admin)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Password & Konfirmasi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Password Login <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required minlength="8"
                               placeholder="Minimal 8 karakter"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('password') border-red-400 @enderror">
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Status Aktif --}}
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded text-red-600 focus:ring-red-400 w-4 h-4">
                        <span class="text-sm text-gray-700 font-medium">Akun langsung aktif</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        💾 Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
