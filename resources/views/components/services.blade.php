<!-- resources/views/components/services.blade.php -->
<section 
    id="layanan" 
    class="py-20 bg-white dark:bg-gray-800 transition-colors duration-300"
    x-data="{ shown: false }"
    x-intersect="shown = true"
>
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 
                class="text-4xl font-bold mb-4 text-gray-900 dark:text-white"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                Layanan Unggulan
            </h2>
            <p 
                class="text-gray-600 dark:text-gray-400"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500 delay-200"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                Pelayanan kesehatan modern dengan standar internasional
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div 
                class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-700 p-8 shadow-xl transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:shadow-gray-700/30"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                style="transition-delay: {{ $loop->index * 200 }}ms"
            >
                <!-- Decorative Background -->
                <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-primary-500 opacity-10 transition-transform group-hover:scale-150"></div>

                <!-- Icon -->
                <div class="relative mb-6">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-lg bg-primary-500 text-white">
                        {!! $service['icon'] !!}
                    </div>
                </div>

                <!-- Content -->
                <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $service['title'] }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ $service['description'] }}
                </p>

                <!-- Action Button -->
                <a 
                    href="{{ $service['link'] }}" 
                    class="mt-6 inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-500"
                >
                    Pelajari Lebih Lanjut
                    <svg class="ml-2 h-5 w-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>