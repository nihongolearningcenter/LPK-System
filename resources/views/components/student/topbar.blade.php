<header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-4 flex items-center justify-between sticky top-0 z-10">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition lg:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-base lg:text-lg font-semibold text-gray-900">{{ $pageTitle ?? 'Dashboard' }}</h1>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-600 transition" title="Website Publik">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <a href="{{ route('student.profil.edit') }}">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                 class="w-9 h-9 rounded-full object-cover ring-2 ring-red-500/20 shadow-xs hover:ring-red-500 transition">
        </a>
    </div>
</header>
