<x-layouts.admin>
<x-slot name="title">Edit Siswa</x-slot>
<x-slot name="pageTitle">Edit Profil Siswa</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Edit Siswa: {{ $siswa->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui biodata dan profil siswa</p>
            </div>
            <a href="{{ route('admin.siswa.show', $siswa) }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $siswa->name) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $siswa->email) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Password Baru (Opsional) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Ganti Password (Kosongkan jika tidak diubah)
                        </label>
                        <input type="password" name="password" minlength="8" placeholder="Password baru"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Ulangi Password Baru
                        </label>
                        <input type="password" name="password_confirmation" minlength="8" placeholder="Ulangi password baru"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- NIK & WhatsApp --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            NIK KTP
                        </label>
                        <input type="text" name="nik" value="{{ old('nik', $siswa->studentProfile?->nik) }}" maxlength="16"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nomor WhatsApp / HP
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $siswa->studentProfile?->phone) }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Gender & Target Level --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Jenis Kelamin
                        </label>
                        <select name="gender" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Pilih --</option>
                            <option value="male" {{ old('gender', $siswa->studentProfile?->gender) === 'male' ? 'selected' : '' }}>Laki-laki (男)</option>
                            <option value="female" {{ old('gender', $siswa->studentProfile?->gender) === 'female' ? 'selected' : '' }}>Perempuan (女)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Target Level JLPT
                        </label>
                        <select name="japanese_level_target" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="N5" {{ old('japanese_level_target', $siswa->studentProfile?->japanese_level_target) === 'N5' ? 'selected' : '' }}>JLPT N5</option>
                            <option value="N4" {{ old('japanese_level_target', $siswa->studentProfile?->japanese_level_target) === 'N4' ? 'selected' : '' }}>JLPT N4</option>
                            <option value="N3" {{ old('japanese_level_target', $siswa->studentProfile?->japanese_level_target) === 'N3' ? 'selected' : '' }}>JLPT N3</option>
                            <option value="N2" {{ old('japanese_level_target', $siswa->studentProfile?->japanese_level_target) === 'N2' ? 'selected' : '' }}>JLPT N2</option>
                        </select>
                    </div>
                </div>

                {{-- Tempat & Tanggal Lahir --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Tempat Lahir
                        </label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $siswa->studentProfile?->birth_place) }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Tanggal Lahir
                        </label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $siswa->studentProfile?->birth_date?->format('Y-m-d')) }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Alamat Domisili
                    </label>
                    <textarea name="address" rows="3"
                              class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('address', $siswa->studentProfile?->address) }}</textarea>
                </div>

                {{-- Status Aktif --}}
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $siswa->is_active) ? 'checked' : '' }}
                               class="rounded text-red-600 focus:ring-red-400 w-4 h-4">
                        <span class="text-sm text-gray-700 font-medium">Akun Siswa Aktif</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.siswa.show', $siswa) }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>

