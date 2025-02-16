<!-- resources/views/components/navbar.blade.php -->
<nav 
    x-data="{ isOpen: false }"
    class="fixed w-full z-50 transition-all duration-300"
    @scroll.window="
        $el.classList.toggle('bg-white/80', window.scrollY > 50);
        $el.classList.toggle('backdrop-blur-md', window.scrollY > 50);
        $el.classList.toggle('shadow-lg', window.scrollY > 50);
    "
>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center space-x-3">
                <img class="h-10 w-10" src="{{ asset('images/logo.png') }}" alt="Logo">
                <span class="font-bold text-xl bg-gradient-to-r from-primary-600 to-secondary-600 bg-clip-text text-transparent">
                    DINKES SUMENEP
                </span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <x-nav-link href="#beranda" :active="request()->is('/')">Beranda</x-nav-link>
                <x-nav-link href="#layanan" :active="request()->is('layanan')">Layanan</x-nav-link>
                <x-nav-link href="#statistik" :active="request()->is('statistik')">Statistik</x-nav-link>
                
                <!-- Dark Mode Toggle -->
                <button 
                    @click="darkMode = !darkMode"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                >
                    <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <!-- Sun icon -->
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <!-- Moon icon -->
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                </button>

                <a href="#kontak" class="btn-primary">Hubungi Kami</a>
                <a href="/login" class="btn-secondary">Login</a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button 
                    @click="isOpen = !isOpen" 
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
                >
                    <svg 
                        class="h-6 w-6" 
                        x-bind:class="{ 'hidden': isOpen, 'block': !isOpen }"
                        stroke="currentColor" 
                        fill="none" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg 
                        class="h-6 w-6" 
                        x-bind:class="{ 'block': isOpen, 'hidden': !isOpen }"
                        stroke="currentColor" 
                        fill="none" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div 
        x-show="isOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="md:hidden bg-white dark:bg-gray-800 shadow-lg"
        @click.away="isOpen = false"
    >
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="#beranda" :active="request()->is('/')">
                Beranda
            <a href="#layanan" :active="request()->is('layanan')">
                Layanan
            <a href="#statistik" :active="request()->is('statistik')">
                Statistik
            <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
                <a href="#kontak" class="btn-primary w-full text-center">Hubungi Kami</a>
                <a href="/login" class="btn-secondary w-full text-center mt-2">Login</a>
            </div>
        </div>
    </div>
</nav>