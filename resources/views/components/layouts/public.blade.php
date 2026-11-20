<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Nihongo Learning Center — Lembaga Pelatihan Bahasa Jepang Terpercaya. Program JLPT N5, N4, Kaiwa, dan Persiapan Kerja ke Jepang.' }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Nihongo Learning Center</title>

    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">

    <!-- Google Fonts Preconnect & Optimized Font Loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', 'Noto Sans JP', sans-serif; }
        .jp-text { font-family: 'Noto Sans JP', sans-serif; }
    </style>

    {{ $head ?? '' }}
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Navbar --}}
    @include('components.public.navbar')

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('components.public.footer')

    {{ $scripts ?? '' }}
</body>
</html>
