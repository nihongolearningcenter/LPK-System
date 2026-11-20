<x-layouts.admin>
<x-slot name="title">Detail Pendaftaran #{{ $pendaftaran->registration_number }}</x-slot>
<x-slot name="pageTitle">Detail Pendaftaran</x-slot>

    {{-- Breadcrumb & Back button --}}
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.pendaftaran.index') }}" class="hover:text-gray-700 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Daftar
            </a>
            <span>/</span>
            <span class="text-gray-900 font-medium">#{{ $pendaftaran->registration_number }}</span>
        </div>

        {{-- Status badge --}}
        @php
        $statusConfig = [
            'pending'  => ['label' => 'Menunggu Review', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
            'reviewing'=> ['label' => 'Sedang Ditinjau', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
            'accepted' => ['label' => 'Diterima',        'class' => 'bg-green-100 text-green-800 border-green-200'],
            'rejected' => ['label' => 'Ditolak',         'class' => 'bg-red-100 text-red-800 border-red-200'],
            'enrolled' => ['label' => 'Terdaftar Kelas', 'class' => 'bg-purple-100 text-purple-800 border-purple-200'],
        ];
        $sc = $statusConfig[$pendaftaran->status] ?? ['label' => $pendaftaran->status, 'class' => 'bg-gray-100 text-gray-800 border-gray-200'];
        @endphp
        <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $sc['class'] }}">
            {{ $sc['label'] }}
        </span>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 cols: Profile & Application Info --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Applicant Header Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex items-start gap-5">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-bold flex-shrink-0"
                     style="background: linear-gradient(135deg, #e63946, #c1121f);">
                    {{ strtoupper(substr($pendaftaran->full_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-bold text-gray-900 mb-1 truncate">{{ $pendaftaran->full_name }}</h1>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $pendaftaran->email }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $pendaftaran->phone }}
                        </span>
                        <span>•</span>
                        <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                            {{ $pendaftaran->registration_number }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Biodata Detail --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span>👤</span> Data Pribadi Calon Siswa
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Tanggal Lahir</div>
                        <div class="font-medium text-gray-800">{{ $pendaftaran->birth_date ? $pendaftaran->birth_date->format('d F Y') : '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Jenis Kelamin</div>
                        <div class="font-medium text-gray-800">{{ $pendaftaran->gender === 'L' ? 'Laki-laki' : ($pendaftaran->gender === 'P' ? 'Perempuan' : '-') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Pendidikan Terakhir</div>
                        <div class="font-medium text-gray-800">{{ $pendaftaran->education_level ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">No. WhatsApp</div>
                        <div class="font-medium text-gray-800">{{ $pendaftaran->phone ?? '-' }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="text-xs text-gray-400 mb-1">Alamat Domisili</div>
                        <div class="font-medium text-gray-800">{{ $pendaftaran->address ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Application Detail --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span>📚</span> Detail Program & Pembelajaran
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Program yang Dipilih</div>
                        <div class="font-semibold text-red-600">{{ $pendaftaran->program?->name ?? 'Program tidak ditemukan' }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Biaya: Rp {{ number_format($pendaftaran->program?->price ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Pengalaman Bahasa Jepang</div>
                        <div class="font-medium text-gray-800">{{ $pendaftaran->japanese_experience ?? 'Belum pernah belajar (Pemula)' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Preferensi Jadwal</div>
                        <div class="font-medium text-gray-800">{{ $pendaftaran->schedule_preference ?? 'Fleksibel' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Tanggal Pendaftaran</div>
                        <div class="font-medium text-gray-800">{{ $pendaftaran->created_at->format('d M Y, H:i') }} WIB</div>
                    </div>
                    @if($pendaftaran->notes)
                    <div class="sm:col-span-2">
                        <div class="text-xs text-gray-400 mb-1">Catatan Tambahan dari Pendaftar</div>
                        <div class="p-3 bg-gray-50 rounded-xl text-gray-700 text-sm">{{ $pendaftaran->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Informasi Tagihan & Bukti Pembayaran --}}
            @php
                $payment = $pendaftaran->payments->first();
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                        <span>💳</span> Status Tagihan & Bukti Pembayaran
                    </h2>
                    @if($payment)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : ($payment->payment_proof ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                            {{ $payment->status === 'paid' ? 'Lunas' : ($payment->payment_proof ? 'Bukti Diunggah' : 'Belum Dibayar') }}
                        </span>
                    @endif
                </div>

                @if($payment)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <div class="text-xs text-gray-400 mb-1">No. Invoice</div>
                            <div class="font-mono font-bold text-gray-900">{{ $payment->invoice_number }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 mb-1">Total Tagihan</div>
                            <div class="font-bold text-red-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 mb-1">Metode Pembayaran</div>
                            <div class="font-medium text-gray-800 uppercase text-xs">{{ $payment->payment_method ?? 'Transfer' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 mb-1">Batas Waktu Pembayaran</div>
                            <div class="font-medium text-gray-800">{{ $payment->due_date ? $payment->due_date->format('d M Y') : '-' }}</div>
                        </div>
                    </div>

                    {{-- Bukti Pembayaran --}}
                    @if($payment->payment_proof)
                        @php
                            $isPdf = str_ends_with(strtolower($payment->payment_proof), '.pdf');
                            $proofUrl = asset('storage/' . $payment->payment_proof);
                        @endphp
                        <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-blue-900">Bukti Transfer yang Dilampirkan:</span>
                                <a href="{{ $proofUrl }}" target="_blank" class="text-xs text-blue-600 font-semibold hover:underline flex items-center gap-1">
                                    <span>🔍</span> Buka Ukuran Penuh
                                </a>
                            </div>

                            @if(!$isPdf)
                                <div class="max-w-xs">
                                    <a href="{{ $proofUrl }}" target="_blank">
                                        <img src="{{ $proofUrl }}" alt="Bukti Transfer" class="rounded-lg border border-gray-200 max-h-56 object-cover hover:opacity-95 transition">
                                    </a>
                                </div>
                            @else
                                <div class="p-3 bg-white rounded-lg border border-gray-200 flex items-center gap-3">
                                    <span class="text-2xl">📄</span>
                                    <div>
                                        <div class="font-semibold text-xs text-gray-800">Berkas Dokumen PDF</div>
                                        <a href="{{ $proofUrl }}" target="_blank" class="text-xs text-blue-600 hover:underline">Unduh Berkas Bukti Transfer</a>
                                    </div>
                                </div>
                            @endif

                            @if($payment->notes)
                                <div class="text-xs text-gray-600 pt-2 border-t border-blue-100">
                                    <strong>Catatan:</strong> {{ $payment->notes }}
                                </div>
                            @endif

                            <div class="pt-2">
                                <a href="{{ route('admin.pembayaran.show', $payment) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700 transition">
                                    <span>Kelola Tagihan & Konfirmasi Pembayaran</span> &rarr;
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="p-4 rounded-xl bg-amber-50/60 border border-amber-200 text-xs text-amber-800 flex items-center gap-2">
                            <span>ℹ️</span> Calon siswa belum mengunggah bukti pembayaran di formulir online.
                        </div>
                    @endif
                @else
                    <div class="p-4 rounded-xl bg-gray-50 text-xs text-gray-500">
                        Belum ada tagihan pembayaran yang diterbitkan untuk pendaftaran ini.
                    </div>
                @endif
            </div>

        </div>

        {{-- Right 1 col: Review & Actions --}}
        <div class="space-y-6">

            {{-- Review Action Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm" x-data="{ showRejectModal: false }">
                <h2 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span>⚡</span> Tindakan Pendaftaran
                </h2>

                @if($pendaftaran->status === 'pending')
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                        Menerima pendaftaran akan secara otomatis membuatkan akun Siswa dan profil di database jika belum tersedia.
                    </p>
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('admin.pendaftaran.accept', $pendaftaran) }}" onsubmit="return confirm('Apakah Anda yakin ingin menerima pendaftaran ini?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full py-2.5 rounded-xl text-sm font-semibold text-white transition flex items-center justify-center gap-2 shadow-sm hover:opacity-95"
                                    style="background: linear-gradient(135deg, #10b981, #059669);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Terima Pendaftaran
                            </button>
                        </form>

                        <button @click="showRejectModal = true"
                                class="w-full py-2.5 rounded-xl text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tolak Pendaftaran
                        </button>
                    </div>

                    {{-- Reject Modal --}}
                    <div x-show="showRejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                        <div @click.away="showRejectModal = false" class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Tolak Pendaftaran</h3>
                            <p class="text-xs text-gray-500 mb-4">Berikan alasan penolakan pendaftaran untuk catatan administrasi.</p>
                            <form method="POST" action="{{ route('admin.pendaftaran.reject', $pendaftaran) }}">
                                @csrf @method('PATCH')
                                <textarea name="reason" rows="3" required placeholder="Tulis alasan penolakan..."
                                          class="w-full text-sm border border-gray-200 rounded-xl p-3 focus:outline-none focus:border-red-400 mb-4"></textarea>
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="showRejectModal = false" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Batal</button>
                                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700">Tolak Sekarang</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl p-4 text-xs {{ $pendaftaran->status === 'accepted' ? 'bg-green-50 border border-green-100 text-green-800' : 'bg-red-50 border border-red-100 text-red-800' }}">
                        <div class="font-bold mb-1">
                            {{ $pendaftaran->status === 'accepted' ? '✅ Sudah Diterima' : '❌ Pendaftaran Ditolak' }}
                        </div>
                        @if($pendaftaran->reviewed_at)
                            <div>Ditinjau pada: {{ $pendaftaran->reviewed_at->format('d M Y, H:i') }}</div>
                        @endif
                        @if($pendaftaran->reviewer)
                            <div>Oleh: {{ $pendaftaran->reviewer->name }}</div>
                        @endif
                        @if($pendaftaran->admin_notes)
                            <div class="mt-2 pt-2 border-t border-red-200/50"><strong>Alasan:</strong> {{ $pendaftaran->admin_notes }}</div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Linked User Account Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span>🔑</span> Akun Siswa Terkait
                </h2>
                @if($pendaftaran->user)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-400">Status Akun:</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $pendaftaran->user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $pendaftaran->user->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-400">ID Pengguna:</span>
                            <span class="font-mono text-xs text-gray-700">#{{ $pendaftaran->user->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-400">Email:</span>
                            <span class="text-xs text-gray-700">{{ $pendaftaran->user->email }}</span>
                        </div>
                    </div>
                    @if($pendaftaran->status === 'accepted')
                    <div class="pt-3 border-t border-gray-100 mt-3">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pendaftaran->phone) }}?text=Halo%20{{ urlencode($pendaftaran->full_name) }}%2C%20pendaftaran%20Anda%20di%20LPK%20Nihongo%20Learning%20Center%20telah%20DIVERIFIKASI%20dan%20DISETUJUI.%20Akun%20siswa%20Anda%20kini%20telah%20aktif%20dengan%20Email%3A%20{{ urlencode($pendaftaran->email) }}."
                           target="_blank"
                           class="w-full py-2 px-3 rounded-xl text-xs font-semibold text-white flex items-center justify-center gap-1.5 transition hover:opacity-90"
                           style="background: linear-gradient(135deg, #25d366, #128c7e);">
                            <span>💬</span> Kirim Info Akun ke WhatsApp Siswa
                        </a>
                    </div>
                    @endif
                @else
                    <p class="text-xs text-gray-400 italic">
                        Belum ada akun siswa yang terhubung. Akun akan dibuat otomatis saat pendaftaran disetujui.
                    </p>
                @endif
            </div>

        </div>

    </div>

</x-layouts.admin>
