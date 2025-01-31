<section id="hero" class="relative bg-gradient-to-br from-blue-500 to-purple-600 dark:from-gray-800 dark:to-gray-900">
    <div class="absolute inset-0 bg-black/20"></div>
    
    <div class="container mx-auto px-6 py-24 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12" data-aos="fade-up">
            <div class="lg:w-1/2 text-center lg:text-left space-y-8">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    <span class="animate-fade-in-down">Sumenep</span> 
                    <span class="text-yellow-300 animate-fade-in-down delay-100">Sehat</span>
                    <br>
                    <span class="text-2xl md:text-3xl block mt-4 text-gray-200 animate-fade-in-down delay-200">
                        Masyarakat Kuat
                    </span>
                </h1>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <x-button.primary href="#layanan" class="animate-pop-in delay-300">
                        Layanan Kami
                    </x-button.primary>
                </div>
            </div>

            <div class="lg:w-1/2 mt-12 lg:mt-0" data-aos="zoom-in">
                <div class="relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-400 to-purple-500 rounded-3xl blur opacity-30 group-hover:opacity-50 transition duration-1000"></div>
                    <img 
                        src="{{ asset('images/hero.webp') }}" 
                        alt="Tim Medis Dinkes Sumenep" 
                        class="relative rounded-2xl shadow-2xl transform transition duration-500 hover:scale-105"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </div>
</section>