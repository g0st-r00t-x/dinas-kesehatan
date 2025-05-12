<!DOCTYPE html>
<html lang="id">
@include('components.head')
<body class="overflow-x-hidden">
    @include('components.navbar')
    @include('components.hero')
    @include('components.layanan')
    @include('components.fitur')
    @include('components.statistik')
    @include('components.testimonial')
    @include('components.kontak')
    @include('components.footer')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.7/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>
</html>