<section id="beranda" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600">
  <!-- Parallax Background -->
  <div class="parallax-bg absolute inset-0 bg-cover bg-center" style="background-image: url('/api/placeholder/1920/1080'); z-index: -2;"></div>
  
  <!-- Overlay -->
  <div class="absolute inset-0 bg-black bg-opacity-50"></div>
  
  <!-- Content -->
  <div class="container mx-auto px-6 relative z-10">
    <div class="flex flex-col space-x-8 md:flex-row items-center justify-between">
      
      <!-- Text Content -->
      <div class="md:w-1/2 text-white">
        <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight animate-fade-in">
          Sumenep <span class="text-yellow-400">Sehat</span>
          <br>
          <span class="text-3xl md:text-4xl block mt-2">Masyarakat Kuat</span>
        </h1>
        <p class="text-xl mb-8 text-gray-200 animate-fade-in delay-200">
          Memberikan pelayanan kesehatan terbaik dengan teknologi modern dan tim profesional.
        </p>
        <div class="flex space-x-4">
          <a href="#layanan" class="px-8 py-3 bg-yellow-400 text-black rounded-full font-medium hover:bg-yellow-500 hover:scale-105 transition-all flex items-center gap-2">
            <span>Layanan Kami</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </a>
          <a href="#kontak" class="px-8 py-3 border-2 border-white text-white rounded-full font-medium hover:bg-white hover:text-yellow-400 hover:scale-105 transition-all flex items-center gap-2">
            <span>Konsultasi</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5l-7 7m0 0l7 7m-7-7h18" />
            </svg>
          </a>
        </div>
      </div>
      
      <!-- Image Content -->
      <div class="md:w-1/2 mt-10 md:mt-0">
        <img src="{{ asset('images/Dinkes.jpg') }}" alt="Healthcare" class="animate-float rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300">
      </div>

    </div>
  </div>
  
  <!-- Hero Wave -->
  <div class="hero-wave absolute bottom-0 w-full">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
      <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-current text-white"></path>
    </svg>
  </div>
</section>