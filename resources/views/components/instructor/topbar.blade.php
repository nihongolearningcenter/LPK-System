<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
    <h1 class="text-lg font-semibold text-gray-900">{{ $pageTitle ?? 'Dashboard Pengajar' }}</h1>
    <div class="flex items-center gap-2.5">
        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
             class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-500/20 shadow-xs">
        <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
    </div>
</header>
