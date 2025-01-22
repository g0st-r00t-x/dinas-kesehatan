<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dinas Kesehatan Kabupaten Sumenep</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <!-- AOS Animation -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.7/swiper-bundle.min.css">
    <!-- Custom styling -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-text {
            background: linear-gradient(45deg, #3b82f6, #2563eb);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: 0.5s;
        }

        .service-card:hover::before {
            transform: translateX(100%);
        }
    </style>
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#2563eb'
                    }
                }
            }
        }
    </script>
</head>
<body class="overflow-x-hidden">
    <!-- Navbar -->
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

    <!-- Hero Section -->
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


    <!-- Layanan Section -->
    <section id="layanan" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Layanan Unggulan</h2>
                <p class="text-gray-600">Pelayanan kesehatan modern dengan standar internasional</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service Cards -->
                <div class="service-card relative bg-white rounded-xl shadow-xl p-6 transform hover:scale-105 transition-all">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-blue-500 rounded-bl-full opacity-10"></div>
                    <img src="/api/placeholder/80/80" alt="Medical Service" class="w-16 h-16 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Pelayanan Medis</h3>
                    <p class="text-gray-600">Layanan kesehatan profesional dengan teknologi modern dan tim dokter berpengalaman</p>
                </div>
                <div class="service-card relative bg-white rounded-xl shadow-xl p-6 transform hover:scale-105 transition-all">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-green-500 rounded-bl-full opacity-10"></div>
                    <img src="/api/placeholder/80/80" alt="Prevention" class="w-16 h-16 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Program Preventif</h3>
                    <p class="text-gray-600">Program pencegahan penyakit dan edukasi kesehatan untuk masyarakat</p>
                </div>
                <div class="service-card relative bg-white rounded-xl shadow-xl p-6 transform hover:scale-105 transition-all">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-red-500 rounded-bl-full opacity-10"></div>
                    <img src="/api/placeholder/80/80" alt="Emergency" class="w-16 h-16 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Tanggap Darurat</h3>
                    <p class="text-gray-600">Layanan darurat 24/7 dengan response time cepat dan penanganan profesional</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Section -->
    <section id="statistik" class="py-20 bg-blue-600 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Statistik Kesehatan</h2>
                <p class="text-blue-100">Data pencapaian kesehatan Kabupaten Sumenep</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="stat-card glass-card p-8 rounded-xl text-white text-center">
                    <div class="text-5xl font-bold mb-2 counter">100+</div>
                    <div class="text-blue-100">Tenaga Medis</div>
                </div>
                <div class="stat-card glass-card p-8 rounded-xl text-white text-center">
                    <div class="text-5xl font-bold mb-2 counter">50+</div>
                    <div class="text-blue-100">Fasilitas Kesehatan</div>
                </div>
                <div class="stat-card glass-card p-8 rounded-xl text-white text-center">
                    <div class="text-5xl font-bold mb-2 counter">1000+</div>
                    <div class="text-blue-100">Pasien Per Bulan</div>
                </div>
                <div class="stat-card glass-card p-8 rounded-xl text-white text-center">
                    <div class="text-5xl font-bold mb-2">24/7</div>
                    <div class="text-blue-100">Pelayanan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Testimoni Masyarakat</h2>
                <p class="text-gray-600">Apa kata mereka tentang layanan kami</p>
            </div>
            <div class="swiper-container testimonial-slider">
                <div class="swiper-wrapper pb-12">
                    <div class="swiper-slide">
                        <div class="bg-white p-8 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <img src="/api/placeholder/50/50" alt="User" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="font-semibold">Ahmad Sudirman</h4>
                                    <p class="text-gray-600">Pasien</p>
                                </div>
                            </div>
                            <p class="text-gray-700">"Pelayanan yang sangat profesional dan ramah. Fasilitas modern dan bersih. Sangat membantu dalam pemulihan kesehatan saya."</p>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="bg-white p-8 rounded-xl shadow-lg">
                            <div class="flex items-center mb-4">
                                <img src="/api/placeholder/50/50" alt="User" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="font-semibold">Siti Aminah</h4>
                                    <p class="text-gray-600">Pasien</p>
                                </div>
                            </div>
                            <p class="text-gray-700">"Response time yang cepat untuk pelayanan darurat. Tim medis sangat kompeten dan informatif dalam memberikan penjelasan."</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="py-20 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-5">
            <div class="absolute transform rotate-45 -left-1/4 -top-1/4 w-1/2 h-1/2 bg-blue-500"></div>
            <div class="absolute transform rotate-45 -right-1/4 -bottom-1/4 w-1/2 h-1/2 bg-blue-700"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4">Hubungi Kami</h2>
                    <p class="text-gray-600">Kami siap melayani dan membantu Anda</p>
                </div>
                <div class="bg-white rounded-2xl shadow-xl p-8 transform hover:scale-[1.02] transition-transform">
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                    placeholder="Masukkan nama lengkap">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Email</label>
                                <input type="email" 
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                    placeholder="nama@email.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Subjek</label>
                            <input type="text" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                placeholder="Perihal pesan Anda">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Pesan</label>
                            <textarea 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                                rows="4"
                                placeholder="Tuliskan pesan Anda"></textarea>
                        </div>
                        <button class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transform hover:scale-[1.02] transition-all">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-20 pb-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div>
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="relative w-12 h-12">
                            <div class="absolute inset-0 bg-blue-500 rounded-lg transform rotate-6"></div>
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="relative w-12 h-12 rounded-lg">
                        </div>
                        <span class="text-xl font-bold">DINKES SUMENEP</span>
                    </div>
                    <p class="text-gray-400">Melayani dengan sepenuh hati untuk Sumenep yang lebih sehat.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6">Layanan</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Pelayanan Medis</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Program Preventif</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tanggap Darurat</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Konsultasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6">Kontak</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li>Jl. Dr. Cipto No.70, Sumenep</li>
                        <li>Tel: (0328) 662129</li>
                        <li>Email: dinkes@sumenepkab.go.id</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6">Media Sosial</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <!-- Facebook Icon -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77,7.46H14.5v-1.9c0-.9.6-1.1,1-1.1h3V.5L14.15.5C10.2.5,9,3.25,9,5.77V7.46h-3v3.4h3v10.3h5.5V10.86h2.77Z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-400 transition-colors">
                            <span class="sr-only">Twitter</span>
                            <!-- Twitter Icon -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44,4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96,1.32-2.02-.88.52-1.86.9-2.9,1.1-.82-.88-2-1.43-3.3-1.43-2.5,0-4.55,2.04-4.55,4.54,0,.36.03.7.1,1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6,1.45-.6,2.3,0,1.56.8,2.95,2,3.77-.74-.03-1.44-.23-2.05-.58v.06c0,2.2,1.56,4.03,3.64,4.44-.67.2-1.37.2-2.06.08.58,1.8,2.26,3.12,4.25,3.16C5.78,18.1,3.37,18.74,1,18.46c2,1.3,4.4,2.04,6.97,2.04,8.35,0,12.92-6.92,12.92-12.93,0-.2,0-.4-.02-.6.9-.63,1.96-1.22,2.56-2.14Z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-pink-600 transition-colors">
                            <span class="sr-only">Instagram</span>
                            <!-- Instagram Icon -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12,2.16c3.2,0,3.58.01,4.85.07,3.25.15,4.77,1.69,4.92,4.92.06,1.27.07,1.65.07,4.85,0,3.2-.01,3.58-.07,4.85-.15,3.23-1.69,4.77-4.92,4.92-1.27.06-1.65.07-4.85.07-3.2,0-3.58-.01-4.85-.07-3.25-.15-4.77-1.69-4.92-4.92-.06-1.27-.07-1.65-.07-4.85,0-3.2.01-3.58.07-4.85C2.38,3.92,3.92,2.38,7.15,2.23,8.42,2.17,8.8,2.16,12,2.16ZM12,0C8.74,0,8.33.01,7.05.07c-4.27.2-6.78,2.71-6.98,6.98C0.01,8.33,0,8.74,0,12s.01,3.67.07,4.95c.2,4.27,2.71,6.78,6.98,6.98,1.28.06,1.69.07,4.95.07s3.67-.01,4.95-.07c4.27-.2,6.78-2.71,6.98-6.98.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.2-4.27-2.71-6.78-6.98-6.98C15.67.01,15.26,0,12,0Zm0,5.84A6.16,6.16,0,1,0,18.16,12,6.16,6.16,0,0,0,12,5.84ZM12,16a4,4,0,1,1,4-4A4,4,0,0,1,12,16ZM18.41,4.15a1.44,1.44,0,1,0,1.44,1.44A1.44,1.44,0,0,0,18.41,4.15Z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Dinas Kesehatan Kabupaten Sumenep. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.7/swiper-bundle.min.js"></script>
    <script>
        //Responsive Navbar
        document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');
    const navbar = document.getElementById('navbar');

    // Toggle menu mobile
    mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });

});
        // Initialize GSAP ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // Navbar scroll effect
window.addEventListener('scroll', handleScroll);

function handleScroll() {
    const navbar = document.getElementById('navbar');
    const listMenu = document.querySelectorAll('.list-menu');

    // Check if listMenu is a NodeList or a single element
    if (listMenu instanceof NodeList) {
        listMenu.forEach((list) => {
            toggleListMenuClass(list);
        });
    } else {
        toggleListMenuClass(listMenu);
    }

    toggleNavbarClass(navbar);
}

function toggleListMenuClass(list) {
    if (window.scrollY > 50) {
        list.classList.add('text-black');
        list.classList.remove('text-white');
    } else {
        list.classList.remove('text-black');
        list.classList.add('text-white');
    }
}

function toggleNavbarClass(navbar) {
    if (window.scrollY > 50) {
        navbar.classList.add('bg-white', 'shadow-md');
    } else {
        navbar.classList.remove('bg-white', 'shadow-md');
    }
}

        // Parallax effect
        gsap.to('.parallax-bg', {
            scrollTrigger: {
                trigger: '#beranda',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            },
            y: 200,
            ease: 'none'
        });

        // Initialize Swiper
        new Swiper('.testimonial-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });

        // Animate statistics on scroll
        const stats = document.querySelectorAll('.counter');
        stats.forEach(stat => {
            const value = parseFloat(stat.innerText);
            gsap.to(stat, {
                scrollTrigger: {
                    trigger: stat,
                    start: 'top center+=100',
                    once: true
                },
                innerText: value,
                duration: 2,
                snap: { innerText: 1 },
                ease: 'power1.out'
            });
        });

        // Animate service cards
        gsap.utils.toArray('.service-card').forEach(card => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top center+=100',
                    once: true
                },
                y: 60,
                opacity: 0,
                duration: 1,
                ease: 'power3.out'
            });
        });
    </script>
</body>
</html>