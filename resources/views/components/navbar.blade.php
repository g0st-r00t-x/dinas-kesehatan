<nav class="fixed w-full z-50 transition-all duration-300" id="navbar">
    <div class="container mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo dan Judul -->
            <div class="flex items-center space-x-4">
                <div class="relative w-10 h-10 sm:w-12 sm:h-12">
                    <div class="absolute inset-0 bg-blue-500 rounded-lg transform rotate-6 transition-transform group-hover:rotate-12"></div>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-lg">
                </div>
                <span class="text-lg sm:text-xl font-bold bg-gradient-to-r from-blue-500 to-blue-700 bg-clip-text text-transparent">
                    DINKES SUMENEP
                </span>
            </div>

            <!-- Tombol Menu Mobile -->
            <button class="list-menu md:hidden text-white hover:text-blue-600 focus:outline-none" id="mobile-menu-button">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="hidden" id="close-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#beranda" class="list-menu text-white hover:text-blue-600 transition-colors" id="list-menu">Beranda</a>
                <a href="#layanan" class="list-menu text-white hover:text-blue-600 transition-colors" id="list-menu">Layanan</a>
                <a href="#statistik" class="list-menu text-white hover:text-blue-600 transition-colors" id="list-menu">Statistik</a>
                <div class="flex items-center space-x-2">
                    <a href="#kontak" class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                        Hubungi Kami
                    </a>
                    <a href="/dinas-kesehatan/login" class="px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition-colors">
                        Login
                    </a>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="hidden md:hidden pt-4" id="mobile-menu">
            <div class="flex flex-col space-y-4">
                <a href="#beranda" class="list-menu text-white hover:text-blue-600 transition-colors" id="list-menu">Beranda</a>
                <a href="#layanan" class="list-menu text-white hover:text-blue-600 transition-colors" id="list-menu">Layanan</a>
                <a href="#statistik" class="list-menu text-white hover:text-blue-600 transition-colors" id="list-menu">Statistik</a>
                <div class="flex flex-col space-y-2">
                    <a href="#kontak" class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors text-center">
                        Hubungi Kami
                    </a>
                    <a href="/dinas-kesehatan/login" class="px-6 py-2 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition-colors text-center">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>