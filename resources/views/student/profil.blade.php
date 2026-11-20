<x-layouts.student>
<x-slot name="title">Profil Saya</x-slot>
<x-slot name="pageTitle">Pengaturan Profil</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-900">👤 Profil Akun Siswa</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola informasi pribadi, kontak, dan data akademik Anda</p>
        </div>

        {{-- Profile Summary Header --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm flex items-center gap-6">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                 class="w-20 h-20 rounded-2xl object-cover flex-shrink-0 shadow-md ring-2 ring-red-500/20">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-black text-gray-900 truncate">{{ $user->name }}</h2>
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mt-1">
                    <span>{{ $user->email }}</span>
                    <span>•</span>
                    <span class="font-mono text-gray-700 bg-gray-100 px-2 py-0.5 rounded">
                        {{ $profile->registration_number ?? 'STD-' . str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <span>•</span>
                    <span class="px-2.5 py-0.5 rounded-full font-semibold bg-green-100 text-green-700">
                        Status: Aktif
                    </span>
                </div>
            </div>
        </div>

        {{-- Edit Form --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('student.profil.update') }}">
                @csrf

                <div class="space-y-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        Data Pribadi
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Alamat Email (Akun)</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 text-gray-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">No. WhatsApp / HP</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $profile->birth_date?->format('Y-m-d')) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                            <select name="gender" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender', $profile->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $profile->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Pendidikan Terakhir</label>
                            <select name="education_level" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-red-400">
                                <option value="">Pilih...</option>
                                @foreach(['SMA / SMK', 'D3', 'D4 / S1', 'S2', 'Lainnya'] as $edu)
                                <option value="{{ $edu }}" {{ old('education_level', $profile->education_level) === $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Tingkat Bahasa Jepang</label>
                            <input type="text" value="{{ $profile->japanese_level ?? 'Dasar / Pemula' }}" disabled
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 text-gray-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Alamat Domisili</label>
                        <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap..."
                                  class="w-full text-sm border border-gray-200 rounded-xl p-4 focus:outline-none focus:border-red-400">{{ old('address', $profile->address) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        <a href="{{ route('profile.edit') }}" class="text-xs text-red-600 font-semibold hover:underline">
                            Ganti Kata Sandi Akun →
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold text-white transition hover:opacity-90 shadow-sm"
                                style="background: linear-gradient(135deg, #e63946, #c1121f);">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

</x-layouts.student>

