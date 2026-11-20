<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar" x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
     :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg' : 'bg-transparent'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/icon.png') }}" alt="NLC Logo"
                     class="w-11 h-11 rounded-2xl object-cover shadow-md transition-transform group-hover:scale-105 flex-shrink-0">
                <div>
                    <div class="font-bold text-lg leading-tight tracking-tight" :class="scrolled ? 'text-gray-900' : 'text-white'">
                        Nihongo LC
                    </div>
                    <div class="text-[11px] font-medium leading-tight tracking-wider uppercase" :class="scrolled ? 'text-gray-500' : 'text-white/75'">
                        Learning Center
                    </div>
                </div>
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden lg:flex items-center gap-1">
                @php
                    $menuItems = [
                        ['label' => 'Beranda',   'route' => 'home'],
                        ['label' => 'Tentang',   'route' => 'tentang'],
                        ['label' => 'Program',   'route' => 'program.index'],
                        ['label' => 'Pengajar',  'route' => 'pengajar'],
                        ['label' => 'Artikel',   'route' => 'artikel.index'],
                        ['label' => 'FAQ',       'route' => 'faq'],
                        ['label' => 'Kontak',    'route' => 'kontak'],
                    ];
                @endphp

                @foreach($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                       :class="scrolled
                           ? 'text-gray-700 hover:text-red-600 hover:bg-red-50'
                           : 'text-white/90 hover:text-white hover:bg-white/10'">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- CTA Buttons --}}
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <a href="{{ match(auth()->user()->role) {
                        'admin'      => route('admin.dashboard'),
                        'instructor' => route('instructor.dashboard'),
                        'student'    => route('student.dashboard'),
                        default      => '/'
                    } }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                       :class="scrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                       :class="scrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'">
                        Masuk
                    </a>
                    <a href="{{ route('enrollment.create') }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-lg transition-all hover:shadow-xl hover:scale-105"
                       style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        Daftar Sekarang
                    </a>
                @endauth
            </div>

            {{-- Hamburger Mobile --}}
            <button @click="open = !open" class="lg:hidden p-2 rounded-lg transition"
                    :class="scrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-cloak x-transition
         class="lg:hidden bg-white border-t border-gray-100 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            @foreach($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="block px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                    {{ $item['label'] }}
                </a>
            @endforeach
            <div class="pt-3 border-t border-gray-100 flex flex-col gap-2">
                @auth
                    <a href="{{ match(auth()->user()->role) {
                        'admin'      => route('admin.dashboard'),
                        'instructor' => route('instructor.dashboard'),
                        'student'    => route('student.dashboard'),
                        default      => '/'
                    } }}"
                       class="block px-4 py-3 rounded-lg text-sm font-medium text-center text-gray-700 border border-gray-200 hover:bg-gray-50 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="block px-4 py-3 rounded-lg text-sm font-medium text-center text-gray-700 border border-gray-200 hover:bg-gray-50 transition">
                        Masuk
                    </a>
                    <a href="{{ route('enrollment.create') }}"
                       class="block px-4 py-3 rounded-xl text-sm font-semibold text-center text-white transition"
                       style="background: linear-gradient(135deg, #e63946, #c1121f);">
                        Daftar Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
