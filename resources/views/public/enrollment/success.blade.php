<x-layouts.public>
    <x-slot name="title">Pendaftaran Berhasil & Tagihan Pembayaran</x-slot>

    <div class="pt-28 pb-16 min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-50">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 sm:p-10">
                <div class="w-16 h-16 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">Pendaftaran Berhasil Diterima!</h1>
                <p class="text-gray-500 text-sm mb-6">
                    Terima kasih, <strong class="text-gray-900">{{ $enrollment->full_name }}</strong>!<br>
                    Data pendaftaran dan tagihan pembayaran kelas Anda telah berhasil diterbitkan.
                </p>

                {{-- Flash Message --}}
                @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-green-50 border border-green-200 text-left flex items-start gap-3">
                    <span class="text-xl">✅</span>
                    <div class="text-xs text-green-800">
                        <strong class="font-bold block text-sm mb-0.5">Berhasil!</strong>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-left">
                    <strong class="text-xs font-bold text-red-800 block mb-1">Gagal Mengunggah Berkas:</strong>
                    <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Detail Pendaftaran & Tagihan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-left">
                    {{-- Box 1: Data Pendaftaran --}}
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 space-y-2.5">
                        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Data Pendaftaran</div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">No. Registrasi:</span>
                            <span class="font-bold text-gray-900 font-mono">{{ $enrollment->registration_number }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Program:</span>
                            <span class="font-semibold text-gray-900 truncate max-w-[150px] text-right">{{ $enrollment->program->name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Pilihan Kelas:</span>
                            <span class="font-semibold text-gray-900 truncate max-w-[150px] text-right">{{ $enrollment->classRoom->name ?? 'Akan Ditentukan' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs pt-1 border-t border-gray-200">
                            <span class="text-gray-500">Status Pendaftaran:</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                                Menunggu Verifikasi
                            </span>
                        </div>
                    </div>

                    {{-- Box 2: Tagihan Pembayaran --}}
                    @if(isset($payment) && $payment)
                    <div class="bg-red-50/60 rounded-2xl p-5 border border-red-200 space-y-2.5">
                        <div class="text-[11px] font-bold text-red-600 uppercase tracking-wider flex items-center justify-between">
                            <span>Tagihan Pembayaran</span>
                            @if($payment->payment_proof)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">
                                    Bukti Terkirim
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">
                                    Belum Dibayar
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">No. Invoice:</span>
                            <span class="font-bold text-gray-900 font-mono">{{ $payment->invoice_number }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Batas Waktu:</span>
                            <span class="font-semibold text-gray-700">{{ $payment->due_date ? $payment->due_date->format('d M Y') : '7 hari' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs pt-1 border-t border-red-200">
                            <span class="text-gray-700 font-semibold">Total Biaya:</span>
                            <span class="font-black text-red-600 text-sm font-mono">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-center justify-center text-xs text-gray-400">
                        Tagihan akan diterbitkan admin setelah peninjauan berkas.
                    </div>
                    @endif
                </div>

                {{-- Rekening Pembayaran Resmi --}}
                @if(isset($payment) && $payment)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left mb-6">
                    <div class="text-xs font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <span>🏦</span> Rekening Pembayaran Resmi LPK Nihongo LC
                    </div>
                    <div class="text-xs text-gray-600 space-y-1">
                        <div>Bank: <strong>Bank Central Asia (BCA)</strong></div>
                        <div>No. Rekening: <strong class="font-mono text-sm text-gray-900">123-456-7890</strong></div>
                        <div>Atas Nama: <strong>LPK NIHONGO LEARNING CENTER</strong></div>
                    </div>
                </div>
                @endif

                {{-- ═══════════════════════════════════════════════════════════
                     UPLOAD BUKTI PEMBAYARAN
                ═══════════════════════════════════════════════════════════ --}}
                @if(isset($payment) && $payment)
                <div class="p-6 rounded-3xl bg-white border-2 {{ $payment->payment_proof ? 'border-blue-200 bg-blue-50/20' : 'border-red-200 bg-red-50/20' }} text-left mb-6 shadow-sm"
                     x-data="{
                        openForm: {{ $payment->payment_proof ? 'false' : 'true' }},
                        fileName: '',
                        previewUrl: null,
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            this.fileName = file.name;
                            if (file.type.startsWith('image/')) {
                                this.previewUrl = URL.createObjectURL(file);
                            } else {
                                this.previewUrl = null;
                            }
                        }
                     }">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🧾</span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">Bukti Transfer / Pembayaran</h3>
                                <p class="text-[11px] text-gray-500">Unggah foto bukti transfer atau tangkapan layar m-Banking Anda di sini.</p>
                            </div>
                        </div>
                        @if($payment->payment_proof)
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700 flex items-center gap-1">
                            <span>✓</span> Sudah Diunggah
                        </span>
                        @endif
                    </div>

                    {{-- Bukti yang Sudah Diunggah --}}
                    @if($payment->payment_proof)
                    <div class="p-4 rounded-2xl bg-white border border-gray-200 mb-4 flex flex-col sm:flex-row items-center gap-4">
                        @php
                            $isPdf = str_ends_with(strtolower($payment->payment_proof), '.pdf');
                            $proofUrl = asset('storage/' . $payment->payment_proof);
                        @endphp

                        @if($isPdf)
                            <div class="w-16 h-16 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                PDF
                            </div>
                        @else
                            <a href="{{ $proofUrl }}" target="_blank" class="block group relative flex-shrink-0">
                                <img src="{{ $proofUrl }}" alt="Bukti Pembayaran" class="w-20 h-20 object-cover rounded-xl border border-gray-200 shadow-sm group-hover:opacity-80 transition">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 transition text-white text-[10px] font-semibold">
                                    Perbesar
                                </div>
                            </a>
                        @endif

                        <div class="flex-1 text-xs space-y-1 text-center sm:text-left">
                            <div class="font-bold text-gray-900">Bukti Pembayaran Terlampir</div>
                            <div class="text-gray-500">{{ $payment->notes ?? 'Menunggu konfirmasi dan verifikasi admin' }}</div>
                            <div class="pt-1">
                                <a href="{{ $proofUrl }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 font-semibold hover:underline">
                                    <span>🔍</span> Buka / Unduh Berkas Bukti
                                </a>
                            </div>
                        </div>

                        <button type="button" @click="openForm = !openForm"
                                class="px-3 py-2 rounded-xl text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <span x-text="openForm ? 'Tutup Formulir' : 'Ganti Bukti'">Ganti Bukti</span>
                        </button>
                    </div>
                    @endif

                    {{-- Form Upload (Tampil jika belum upload atau klik 'Ganti Bukti') --}}
                    <div x-show="openForm" x-cloak>
                        <form method="POST" action="{{ route('enrollment.uploadProof', $enrollment->registration_number) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                    Pilih Berkas Bukti Transfer <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="payment_proof" accept="image/jpeg,image/png,image/webp,application/pdf" required
                                       @change="handleFile"
                                       class="block w-full text-xs text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer border border-gray-200 rounded-xl bg-white p-1">
                                <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, WEBP, atau PDF (Maksimal 3 MB).</p>
                            </div>

                            {{-- Live Image Preview --}}
                            <div x-show="previewUrl" x-cloak class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center gap-3">
                                <img :src="previewUrl" class="w-14 h-14 object-cover rounded-lg border border-gray-300">
                                <div class="text-xs">
                                    <div class="font-semibold text-gray-800" x-text="fileName"></div>
                                    <div class="text-green-600 text-[11px]">Pratinjau gambar siap diunggah</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pemilik Rekening Pengirim (Opsional)</label>
                                    <input type="text" name="sender_name" placeholder="Contoh: Budi Santoso"
                                           class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-400">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pembayaran</label>
                                    <select name="payment_method" class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-400">
                                        <option value="transfer" selected>Transfer Bank (BCA / Lainnya)</option>
                                        <option value="tunai">Tunai / Bayar di Tempat</option>
                                        <option value="lainnya">QRIS / E-Wallet / Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" rows="2" placeholder="Tuliskan keterangan transfer (misal: Transfer dari BCA a.n Budi Santoso tgl 30 Okt)..."
                                          class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-400"></textarea>
                            </div>

                            <button type="submit"
                                    class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white shadow-md transition hover:opacity-95 flex items-center justify-center gap-2"
                                    style="background: linear-gradient(135deg, #e63946, #c1121f);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Unggah Bukti Pembayaran</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Info Status Verifikasi Akun oleh Admin --}}
                <div class="p-5 rounded-2xl bg-amber-50/80 border border-amber-200 text-left mb-6">
                    <div class="text-xs font-bold text-amber-900 mb-1 flex items-center gap-1.5">
                        <span>⏳</span> Akun Siswa & Akses Masuk Menunggu Verifikasi Admin
                    </div>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Data pendaftaran Anda sedang dalam proses peninjauan oleh tim admin. Kredensial akun (email & kata sandi) serta akses ke portal siswa akan diaktifkan dan diinformasikan oleh Admin setelah pendaftaran dan pembayaran Anda diverifikasi.
                    </p>
                </div>

                {{-- Tombol Aksi --}}
                @php
                    $waText = 'Halo Admin LPK, saya sudah mendaftar dengan No Registrasi ' . $enrollment->registration_number;
                    if (isset($payment) && $payment) {
                        $waText .= ' dan Invoice ' . $payment->invoice_number;
                        if ($payment->payment_proof) {
                            $waText .= '. Saya juga sudah MENGUNGGAH BUKTI PEMBAYARAN di formulir online.';
                        }
                    }
                    $waText .= '. Mohon verifikasi pendaftaran saya. Terima kasih!';
                @endphp
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="https://wa.me/08123456789?text={{ urlencode($waText) }}"
                       target="_blank"
                       class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-white shadow-md transition hover:opacity-95 flex items-center justify-center gap-2"
                       style="background: linear-gradient(135deg, #25d366, #128c7e);">
                        <span>💬</span> Konfirmasi Pendaftaran ke Admin via WhatsApp
                    </a>

                    <a href="{{ route('home') }}" class="btn-secondary py-3 px-6 text-xs font-semibold justify-center">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
