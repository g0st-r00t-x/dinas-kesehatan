<!-- resources/views/components/hero.blade.php -->
<section 
    id="beranda"
    x-data="{ shown: false }"
    x-intersect="shown = true"
    class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-primary-500 to-secondary-600 dark:from-primary-900 dark:to-secondary-900"
>
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute w-full h-full">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
            <!-- Text Content -->
            <div class="lg:w-1/2 text-white">
                <h1 
                    class="text-4xl md:text-6xl font-bold mb-6"
                    x-show="shown"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    Sumenep <span class="text-yellow-400">Sehat</span>
                    <span class="block text-2xl md:text-3xl mt-2">Masyarakat Kuat</span>
                </h1>
                
                <p 
                    class="text-xl mb-8 text-gray-100"
                    x-show="shown"
                    x-transition:enter="transition ease-out duration-500 delay-300"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    Memberikan pelayanan kesehatan terbaik dengan teknologi modern dan tim profesional untuk masyarakat Sumenep yang lebih sehat.
                </p>

                <div 
                    class="flex flex-wrap gap-4"
                    x-show="shown"
                    x-transition:enter="transition ease-out duration-500 delay-500"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <a 
                        href="#layanan"
                        class="btn-primary group"
                    >
                        <span>Layanan Kami</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                      </a>
                    <a 
                        href="#kontak"
                        class="btn-secondary group"
                    >
                        <span>Hubungi Kami</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Image/Illustration -->
            <div 
                class="lg:w-1/2"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500 delay-700"
                x-transition:enter-start="opacity-0 translate-x-8"
                x-transition:enter-end="opacity-100 translate-x-0"
            >
                <img 
                    src="{{ asset('images/hero-illustration.svg') }}" 
                    alt="Healthcare Illustration" 
                    class="w-full h-auto max-w-lg mx-auto drop-shadow-2xl"
                >
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div 
        class="absolute bottom-8 left-1/2 transform -translate-x-1/2"
        x-show="shown"
        x-transition:enter="transition ease-out duration-500 delay-1000"
        x-transition:enter-start="opacity-0 translate-y-8"
        x-transition:enter-end="opacity-100 translate-y-0"
    >
        <a 
            href="#layanan"
            class="flex flex-col items-center text-white hover:text-yellow-400 transition-colors duration-300"
        >
            <span class="text-sm mb-2">Scroll ke bawah</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </a>
    </div>
</section>