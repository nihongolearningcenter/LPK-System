<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Dashboard Siswa | Nihongo LC</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    {{-- DNS prefetch & preconnect --}}
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Non-blocking font load --}}
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;700&display=swap"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    </noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="bg-slate-50 font-['Inter'] antialiased" x-data="{ sidebarOpen: false, mobileMenu: false }">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('components.student.sidebar')

        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

        {{-- Content Area --}}
        <div class="flex flex-col flex-1 min-w-0">
            @include('components.student.topbar')

            <main class="flex-1 p-4 lg:p-6">
                @if(session('success'))
                    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm flex items-center gap-2">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm flex items-center gap-2">
                        <span>⚠️</span> {{ session('error') }}
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>

    {{ $scripts ?? '' }}
</body>
</html>
