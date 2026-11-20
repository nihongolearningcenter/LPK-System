<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat {{ $certificate->certificate_number }} — Nihongo LC</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Noto+Serif+JP:wght@400;600;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { background: none; }
            .no-print { display: none !important; }
            .print-certificate {
                box-shadow: none !important;
                margin: 0 !important;
                border: 12px double #8b0000 !important;
                width: 100% !important;
                height: 100% !important;
            }
        }
        .japanese-font {
            font-family: 'Noto Serif JP', serif;
        }
        .title-font {
            font-family: 'Cinzel', serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4 flex flex-col items-center justify-center font-['Plus_Jakarta_Sans']">

    {{-- Controls --}}
    <div class="no-print mb-6 flex items-center gap-3">
        <a href="{{ route('admin.sertifikat.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50">
            ← Kembali ke Panel Admin
        </a>
        <button onclick="window.print()" class="px-5 py-2 text-sm font-semibold text-white rounded-xl shadow-md transition hover:opacity-95"
                style="background: linear-gradient(135deg, #e63946, #c1121f);">
            🖨️ Cetak / Unduh PDF
        </button>
    </div>

    {{-- Certificate Container (A4 Landscape aspect ratio) --}}
    <div class="print-certificate bg-white w-full max-w-4xl p-10 md:p-14 rounded-2xl shadow-2xl relative overflow-hidden border-[10px] border-double border-red-900">
        
        {{-- Japanese Watermark --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03] select-none text-[18rem] japanese-font font-bold">
            修了
        </div>

        {{-- Top Header --}}
        <div class="text-center relative z-10 mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-800 text-white font-bold text-2xl mb-2 shadow-sm">
                日
            </div>
            <h2 class="text-xs tracking-[0.3em] uppercase text-red-800 font-bold mb-1">
                LPK NIHONGO LEARNING CENTER
            </h2>
            <div class="text-[11px] text-gray-500 font-medium">
                Izin Operasional Lembaga Pelatihan Kerja No. 560/342/LPK/2024
            </div>
            <div class="w-24 h-0.5 bg-red-800 mx-auto mt-3"></div>
        </div>

        {{-- Main Title --}}
        <div class="text-center relative z-10 mb-8">
            <h1 class="title-font text-3xl md:text-4xl font-extrabold text-gray-900 tracking-wider">
                CERTIFICATE OF COMPLETION
            </h1>
            <div class="japanese-font text-lg text-red-900 font-bold mt-1 tracking-widest">
                修 了 証 書
            </div>
            <div class="text-xs text-gray-400 font-mono mt-1">
                No: {{ $certificate->certificate_number }}
            </div>
        </div>

        {{-- Recipient Section --}}
        <div class="text-center relative z-10 space-y-4 mb-8">
            <p class="text-xs text-gray-500 uppercase tracking-widest">Diberikan kepada (This is proudly presented to):</p>
            <div class="text-2xl md:text-3xl font-bold text-gray-900 border-b-2 border-dashed border-gray-300 pb-2 inline-block px-10">
                {{ $certificate->student?->name ?? 'Nama Siswa' }}
            </div>
            <p class="text-sm text-gray-600 max-w-2xl mx-auto leading-relaxed pt-2">
                Telah menyelesaikan seluruh rangkaian pelatihan dan ujian pada program
                <br>
                <strong class="text-gray-900 font-bold text-base">{{ $certificate->certificate_name }}</strong>
                @if($certificate->program)
                    ({{ $certificate->program->title }})
                @endif
                dengan capaian nilai <strong class="text-gray-900">{{ $certificate->final_grade ? number_format($certificate->final_grade, 1) : '-' }}</strong>
                @if($certificate->grade_letter)
                    (Predikat: <strong class="text-red-800">{{ $certificate->grade_letter }}</strong>)
                @endif.
            </p>
        </div>

        {{-- Footer Signatures & QR --}}
        <div class="grid grid-cols-3 items-end pt-6 relative z-10 border-t border-gray-100">
            {{-- Left: Verification --}}
            <div class="text-left text-xs text-gray-500 space-y-1">
                <div class="font-bold text-gray-700">Verifikasi Resmi:</div>
                <div class="font-mono text-[11px] text-gray-600 font-semibold">{{ $certificate->verification_code }}</div>
                <div class="text-[10px] text-gray-400">Dapat diverifikasi secara online pada portal siswa</div>
            </div>

            {{-- Center: Date & Place --}}
            <div class="text-center text-xs text-gray-500">
                <div>Diterbitkan di Jakarta</div>
                <div class="font-semibold text-gray-700 mt-0.5">{{ $certificate->issue_date?->locale('id')->isoFormat('D MMMM YYYY') }}</div>
            </div>

            {{-- Right: Director Signature --}}
            <div class="text-right text-xs">
                <div class="text-gray-500 mb-8">Direktur LPK Nihongo LC</div>
                <div class="font-bold text-gray-900 text-sm underline">{{ $certificate->issuer?->name ?? 'Kenji Sato, M.Ed.' }}</div>
                <div class="text-[10px] text-gray-400">Pimpinan Lembaga</div>
            </div>
        </div>

    </div>

</body>
</html>

