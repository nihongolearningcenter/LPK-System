<x-layouts.admin>
<x-slot name="title">Terbitkan Sertifikat</x-slot>
<x-slot name="pageTitle">Terbitkan Sertifikat Baru</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Terbitkan Sertifikat Kelulusan</h1>
                <p class="text-sm text-gray-500 mt-0.5">Isi data kelulusan siswa untuk membuat sertifikat resmi</p>
            </div>
            <a href="{{ route('admin.sertifikat.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.sertifikat.store') }}" class="space-y-6">
                @csrf

                {{-- Nomor & Nama Sertifikat --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nomor Sertifikat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="certificate_number" value="{{ old('certificate_number', $suggestedNumber) }}" required
                               class="w-full text-sm font-mono border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('certificate_number') border-red-400 @enderror">
                        @error('certificate_number')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nama Sertifikat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="certificate_name" value="{{ old('certificate_name', 'Sertifikat Kelulusan Pelatihan Bahasa Jepang') }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('certificate_name') border-red-400 @enderror">
                        @error('certificate_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Siswa Penerima --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Pilih Siswa <span class="text-red-500">*</span>
                    </label>
                    <select name="student_id" required
                            class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none @error('student_id') border-red-400 @enderror">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Program & Kelas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Program Pelatihan
                        </label>
                        <select name="program_id"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Pilih Program (Opsional) --</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id }}" {{ old('program_id') == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->title }} ({{ $prog->level }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Kelas
                        </label>
                        <select name="class_id"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Pilih Kelas (Opsional) --</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ old('class_id') == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tanggal Terbit & Masa Berlaku --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Tanggal Penerbitan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Masa Berlaku (Opsional)
                        </label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Nilai Akhir & Predikat --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Nilai Akhir (Skala 0-100)
                        </label>
                        <input type="number" step="0.01" min="0" max="100" name="final_grade" value="{{ old('final_grade') }}" placeholder="Contoh: 88.5"
                               class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                            Predikat Nilai
                        </label>
                        <select name="grade_letter"
                                class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 focus:border-red-400 focus:outline-none">
                            <option value="">-- Pilih Predikat --</option>
                            <option value="A" {{ old('grade_letter') == 'A' ? 'selected' : '' }}>A (Sangat Memuaskan / 優)</option>
                            <option value="B" {{ old('grade_letter') == 'B' ? 'selected' : '' }}>B (Memuaskan / 良)</option>
                            <option value="C" {{ old('grade_letter') == 'C' ? 'selected' : '' }}>C (Cukup / 可)</option>
                            <option value="Lulus" {{ old('grade_letter') == 'Lulus' ? 'selected' : '' }}>Lulus / 合格</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.sertifikat.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        📜 Terbitkan Sertifikat
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>

