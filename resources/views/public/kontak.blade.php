<x-layouts.public>
    <x-slot name="title">Hubungi Kami</x-slot>

    <div class="pt-28 pb-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12">
                <div class="section-tag">Kontak</div>
                <h1 class="text-3xl font-black text-gray-900">Hubungi Kami</h1>
                <p class="mt-2 text-gray-500">Ada pertanyaan? Kami siap membantu Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

                {{-- Info Kontak --}}
                <div class="lg:col-span-2 space-y-5">
                    <div class="card">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-green-50">📍</div>
                            <div>
                                <div class="font-bold text-gray-900 mb-1">Alamat</div>
                                <div class="text-gray-500 text-sm">Jl. Contoh No. 123<br>Kota Anda, Provinsi 00000</div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-blue-50">📞</div>
                            <div>
                                <div class="font-bold text-gray-900 mb-1">Telepon / WhatsApp</div>
                                <a href="https://wa.me/08123456789" class="text-green-600 font-semibold hover:underline">0812-3456-789</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-red-50">📧</div>
                            <div>
                                <div class="font-bold text-gray-900 mb-1">Email</div>
                                <a href="mailto:info@nihongo-lc.id" class="text-red-600 font-semibold hover:underline">info@nihongo-lc.id</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl bg-purple-50">🕐</div>
                            <div>
                                <div class="font-bold text-gray-900 mb-1">Jam Operasional</div>
                                <div class="text-gray-500 text-sm">Senin – Sabtu: 08.00 – 20.00<br>Minggu: 09.00 – 15.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Kontak --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>

                        @if(session('success'))
                        <div class="mb-5 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-green-700 text-sm">
                            ✅ {{ session('success') }}
                        </div>
                        @endif

                        <form class="space-y-5" action="#" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="form-label" for="contact_name">Nama Lengkap</label>
                                    <input type="text" id="contact_name" name="name" class="form-input" placeholder="Nama Anda">
                                </div>
                                <div>
                                    <label class="form-label" for="contact_email">Email</label>
                                    <input type="email" id="contact_email" name="email" class="form-input" placeholder="email@contoh.com">
                                </div>
                            </div>
                            <div>
                                <label class="form-label" for="contact_subject">Subjek</label>
                                <select id="contact_subject" name="subject" class="form-input">
                                    <option>Informasi Program</option>
                                    <option>Jadwal Kelas</option>
                                    <option>Biaya & Pembayaran</option>
                                    <option>Kerjasama</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" for="contact_message">Pesan</label>
                                <textarea id="contact_message" name="message" rows="5" class="form-input"
                                          placeholder="Tulis pesan Anda di sini..."></textarea>
                            </div>
                            <button type="submit" class="btn-primary w-full justify-center py-3">
                                Kirim Pesan
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
