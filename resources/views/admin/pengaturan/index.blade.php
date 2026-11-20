<x-layouts.admin>
<x-slot name="title">Pengaturan Sistem</x-slot>
<x-slot name="pageTitle">Pengaturan Sistem & Lembaga</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Pengaturan Sistem (設定)</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola identitas LPK, kontak, rekening pembayaran, dan informasi lembaga</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-8">
                @csrf

                {{-- Bagian 1: Identitas Lembaga --}}
                <div>
                    <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <span>🏛️</span> Identitas Lembaga Pelatihan
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Nama Resmi Lembaga <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="institution_name" value="{{ old('institution_name', $settings['institution_name']) }}" required
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Nama Bahasa Jepang (Kanji)
                            </label>
                            <input type="text" name="japanese_name" value="{{ old('japanese_name', $settings['japanese_name']) }}"
                                   placeholder="例: 日本語学習センター"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Nomor Izin Operasional LPK
                            </label>
                            <input type="text" name="permit_number" value="{{ old('permit_number', $settings['permit_number']) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Nama Direktur / Pimpinan Lembaga
                            </label>
                            <input type="text" name="director_name" value="{{ old('director_name', $settings['director_name']) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Bagian 2: Kontak & Lokasi --}}
                <div>
                    <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <span>📍</span> Kontak & Alamat Lembaga
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Email Resmi <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $settings['email']) }}" required
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Telepon Kantor
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $settings['phone']) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                WhatsApp Customer Service
                            </label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp']) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Alamat Lengkap Lembaga
                        </label>
                        <textarea name="address" rows="2" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">{{ old('address', $settings['address']) }}</textarea>
                    </div>
                </div>

                {{-- Bagian 3: Pembayaran & Rekening Transfer --}}
                <div>
                    <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <span>💳</span> Rekening Transfer Pembayaran Siswa
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Nama Bank
                            </label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $settings['bank_name']) }}"
                                   placeholder="Contoh: Bank BCA"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Nomor Rekening
                            </label>
                            <input type="text" name="bank_account" value="{{ old('bank_account', $settings['bank_account']) }}"
                                   placeholder="Contoh: 1234567890"
                                   class="w-full text-sm font-mono border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Atas Nama (A/N)
                            </label>
                            <input type="text" name="bank_holder" value="{{ old('bank_holder', $settings['bank_holder']) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>
                    </div>
                </div>

                {{-- Bagian 4: Akademik --}}
                <div>
                    <h2 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <span>🎓</span> Parameter Akademik
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Biaya Pendaftaran Online (Rp)
                            </label>
                            <input type="number" step="1000" name="registration_fee" value="{{ old('registration_fee', $settings['registration_fee']) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Tahun Ajaran Aktif
                            </label>
                            <input type="text" name="academic_year" value="{{ old('academic_year', $settings['academic_year']) }}"
                                   class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        💾 Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
