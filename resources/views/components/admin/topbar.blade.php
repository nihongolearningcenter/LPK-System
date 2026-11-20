<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
    {{-- Toggle Sidebar + Page Title --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition lg:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <div>
            <h1 class="text-lg font-semibold text-gray-900">{{ $pageTitle ?? 'Dashboard' }}</h1>
            @isset($breadcrumb)
                <nav class="flex items-center gap-1.5 text-xs text-gray-500 mt-0.5">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Admin</a>
                    @foreach($breadcrumb as $bc)
                        <span>›</span>
                        @if(isset($bc['url']))
                            <a href="{{ $bc['url'] }}" class="hover:text-red-600 transition">{{ $bc['label'] }}</a>
                        @else
                            <span class="text-gray-700 font-medium">{{ $bc['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endisset
        </div>
    </div>

    {{-- Right Actions --}}
    <div class="flex items-center gap-3">
        {{-- Notifikasi --}}
        <button class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </button>

        {{-- Profile --}}
        <div class="flex items-center gap-2.5">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                 class="w-9 h-9 rounded-full object-cover ring-2 ring-red-500/20 shadow-xs">
            <div class="hidden sm:block">
                <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-500">Administrator</div>
            </div>
        </div>
    </div>
</header>
