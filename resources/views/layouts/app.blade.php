<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dinas Kesehatan Kabupaten Sumenep - Melayani dengan sepenuh hati untuk Sumenep yang lebih sehat">
    <meta name="keywords" content="dinas kesehatan, sumenep, layanan kesehatan, kesehatan masyarakat">
    <meta name="author" content="Dinas Kesehatan Kabupaten Sumenep">
    <meta property="og:title" content="Dinas Kesehatan Kabupaten Sumenep">
    <meta property="og:description" content="Melayani dengan sepenuh hati untuk Sumenep yang lebih sehat">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="twitter:card" content="summary_large_image">
    
    <title>@yield('title', 'Dinas Kesehatan Kabupaten Sumenep')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <!-- Schema.org markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "GovernmentOrganization",
        "name": "Dinas Kesehatan Kabupaten Sumenep",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+62-328-662129",
            "contactType": "customer service",
            "availableLanguage": ["Indonesian"]
        },
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Jl. Dr. Cipto No.70",
            "addressLocality": "Sumenep",
            "addressRegion": "Jawa Timur",
            "postalCode": "69417",
            "addressCountry": "ID"
        }
    }
    </script>
</head>
<body 
    x-data="{ darkMode: false }" 
    :class="{ 'dark': darkMode }"
    class="font-sans antialiased transition-colors duration-300 ease-in-out"
>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        @include('components.navbar')
        
        <main>
            @yield('content')
        </main>
        
        @include('components.footer')
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    @stack('scripts')
</body>
</html>