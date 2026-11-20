<x-layouts.public>
    <x-slot name="title">Formulir Pendaftaran Online</x-slot>

    <div class="pt-28 pb-16 min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 bg-red-50 text-red-600 rounded-full px-4 py-2 text-sm font-semibold mb-4">
                    🎌 Formulir Pendaftaran
                </div>
                <h1 class="text-3xl font-black text-gray-900">Daftar Sekarang</h1>
                <p class="mt-2 text-gray-500">Isi formulir di bawah ini untuk mendaftar ke program pelatihan Bahasa Jepang kami.</p>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

                {{-- Progress Indicator --}}
                <div class="px-8 py-4 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="text-green-600 font-semibold">✓ Gratis</span>
                        <span>•</span>
                        <span>Proses seleksi 1-3 hari kerja</span>
                        <span>•</span>
                        <span>Kami akan menghubungi via WhatsApp</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('enrollment.store') }}" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf

                    {{-- Errors --}}
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-5 py-4">
                        <div class="text-sm font-semibold text-red-700 mb-2">Mohon perbaiki kesalahan berikut:</div>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li class="text-sm text-red-600">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Bagian 1: Data Pribadi --}}
                    <div>
                        <h2 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                  style="background: linear-gradient(135deg, #e63946, #c1121f);">1</span>
                            Data Pribadi
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="form-label" for="full_name">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}"
                                       class="form-input @error('full_name') border-red-400 @enderror"
                                       placeholder="Nama sesuai KTP">
                                @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="email">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="form-input @error('email') border-red-400 @enderror"
                                       placeholder="email@contoh.com">
                                @error('email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="phone">No. WhatsApp <span class="text-red-500">*</span></label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="form-input @error('phone') border-red-400 @enderror"
                                       placeholder="0812-xxxx-xxxx">
                                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="birth_date">Tanggal Lahir</label>
                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                       class="form-input">
                            </div>
                            <div>
                                <label class="form-label" for="gender">Jenis Kelamin</label>
                                <select id="gender" name="gender" class="form-input">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label" for="address">Alamat Lengkap</label>
                                <textarea id="address" name="address" rows="2"
                                          class="form-input"
                                          placeholder="Jl. ...">{{ old('address') }}</textarea>
                            </div>
                            <div>
                                <label class="form-label" for="education_level">Pendidikan Terakhir</label>
                                <select id="education_level" name="education_level" class="form-input">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['SMP','SMA/SMK','D1','D2','D3','D4/S1','S2','S3'] as $edu)
                                    <option value="{{ $edu }}" {{ old('education_level') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label" for="photo">Foto (opsional)</label>
                                <input type="file" id="photo" name="photo" accept="image/*"
                                       class="form-input py-2 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Bagian 2: Program yang Diminati --}}
                    <div x-data="{
                        selectedProgramId: '{{ old('program_id', $selectedProgram ?? '') }}',
                        selectedClassId: '{{ old('class_id', '') }}',
                        programs: {{ Js::from($programs) }},
                        classes: {{ Js::from($classes) }},
                        get currentProgram() {
                            return this.programs.find(p => p.id == this.selectedProgramId);
                        },
                        get currentClass() {
                            return this.classes.find(c => c.id == this.selectedClassId);
                        },
                        get filteredClasses() {
                            if (!this.selectedProgramId) return this.classes;
                            return this.classes.filter(c => c.program_id == this.selectedProgramId);
                        },
                        onProgramChange() {
                            if (this.currentClass && this.currentClass.program_id != this.selectedProgramId) {
                                this.selectedClassId = '';
                            }
                        },
                        onClassChange() {
                            if (this.currentClass && this.currentClass.program_id) {
                                this.selectedProgramId = this.currentClass.program_id;
                            }
                        },
                        formatRupiah(num) {
                            return new Intl.NumberFormat('id-ID').format(num || 0);
                        }
                    }">
                        <h2 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                  style="background: linear-gradient(135deg, #e63946, #c1121f);">2</span>
                            Program & Kelas yang Didaftar
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="form-label" for="program_id">Program Pelatihan <span class="text-red-500">*</span></label>
                                <select id="program_id" name="program_id"
                                        x-model="selectedProgramId"
                                        @change="onProgramChange()"
                                        class="form-input @error('program_id') border-red-400 @enderror">
                                    <option value="">-- Pilih Program Pelatihan --</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ old('program_id', $selectedProgram ?? '') == $program->id ? 'selected' : '' }}>
                                        {{ $program->name }}
                                        @if($program->price > 0) — Rp {{ number_format($program->price, 0, ',', '.') }} @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('program_id')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            @if($classes->count() > 0)
                            <div class="sm:col-span-2">
                                <label class="form-label" for="class_id">Pilih Kelas / Gelombang Belajar</label>
                                <select id="class_id" name="class_id"
                                        x-model="selectedClassId"
                                        @change="onClassChange()"
                                        class="form-input">
                                    <option value="">-- Pilih Kelas Sesuai Program --</option>
                                    <template x-for="c in filteredClasses" :key="c.id">
                                        <option :value="c.id" x-text="c.name + ' (' + (c.program ? c.program.name : '') + ')' + (c.room ? ' — ' + c.room : '')"></option>
                                    </template>
                                </select>
                                <p class="text-[11px] text-gray-400 mt-1">Pilihan kelas akan menentukan jadwal sesi belajar dan tagihan Anda.</p>
                            </div>
                            @endif

                            {{-- Card Estimasi Tagihan Pembayaran --}}
                            <div class="sm:col-span-2 p-4 rounded-2xl bg-slate-50 border border-slate-200" x-show="currentProgram" x-cloak>
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200 text-xs">
                                    <span class="font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                                        <span>💳</span> Rincian Tagihan Pembayaran
                                    </span>
                                    <span class="font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                        Status: Diterbitkan Otomatis
                                    </span>
                                </div>
                                <div class="mt-3 space-y-1.5 text-xs text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Program:</span>
                                        <strong class="text-gray-900" x-text="currentProgram ? currentProgram.name : '-'"></strong>
                                    </div>
                                    <div class="flex justify-between" x-show="currentClass">
                                        <span>Kelas yang Dipilih:</span>
                                        <strong class="text-gray-900" x-text="currentClass ? currentClass.name : '-'"></strong>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t border-slate-200 text-sm font-bold text-gray-900">
                                        <span>Total Tagihan:</span>
                                        <span class="text-red-600" x-text="'Rp ' + formatRupiah(currentProgram ? currentProgram.price : 0)"></span>
                                    </div>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-2">
                                    ℹ️ Invoice resmi akan langsung dibuat setelah Anda mengirim pendaftaran ini dan dapat dibayar via transfer bank.
                                </p>
                            </div>

                            <div>
                                <label class="form-label" for="japanese_experience">Pengalaman Bahasa Jepang</label>
                                <select id="japanese_experience" name="japanese_experience" class="form-input">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Belum pernah belajar', 'Pernah belajar mandiri', 'Pernah kursus (< 6 bulan)', 'Sudah lulus JLPT N5', 'Sudah lulus JLPT N4', 'Lainnya'] as $exp)
                                    <option value="{{ $exp }}" {{ old('japanese_experience') == $exp ? 'selected' : '' }}>{{ $exp }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label" for="schedule_preference">Preferensi Jadwal</label>
                                <select id="schedule_preference" name="schedule_preference" class="form-input">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['Pagi (07.00-12.00)', 'Siang (12.00-15.00)', 'Sore (15.00-18.00)', 'Malam (18.00-21.00)', 'Fleksibel'] as $sch)
                                    <option value="{{ $sch }}" {{ old('schedule_preference') == $sch ? 'selected' : '' }}>{{ $sch }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label" for="notes">Catatan / Pertanyaan (opsional)</label>
                                <textarea id="notes" name="notes" rows="3" class="form-input"
                                          placeholder="Tulis pertanyaan atau informasi tambahan yang ingin Anda sampaikan...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Submit --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-gray-400 max-w-md">
                            Dengan mengirim formulir ini, Anda setuju bahwa data Anda akan digunakan untuk proses seleksi.
                            Kami tidak akan menyebarkan data Anda kepada pihak ketiga.
                        </p>
                        <button type="submit" class="btn-primary whitespace-nowrap px-8 py-3 text-base">
                            Kirim Pendaftaran
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info Kontak --}}
            <p class="text-center text-sm text-gray-400 mt-6">
                Ada pertanyaan? Hubungi kami via
                <a href="https://wa.me/08123456789" class="text-green-600 font-semibold hover:underline">WhatsApp</a>
                atau email
                <a href="mailto:info@nihongo-lc.id" class="text-red-600 font-semibold hover:underline">info@nihongo-lc.id</a>
            </p>
        </div>
    </div>
</x-layouts.public>
