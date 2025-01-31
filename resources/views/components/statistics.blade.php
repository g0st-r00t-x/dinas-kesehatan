<!-- resources/views/components/statistics.blade.php -->
<section 
    id="statistik" 
    class="py-20 bg-primary-600 dark:bg-primary-900 relative overflow-hidden"
    x-data="{ shown: false }"
    x-intersect="shown = true"
>
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <h2 
                class="text-4xl font-bold text-white mb-4"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                Statistik Kesehatan
            </h2>
            <p 
                class="text-primary-100"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500 delay-200"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                Data pencapaian kesehatan Kabupaten Sumenep
            </p>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach($statistics as $stat)
            <div 
                class="relative overflow-hidden rounded-2xl bg-white/10 backdrop-blur-lg p-8 text-center text-white transition-all duration-300 hover:-translate-y-2"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                style="transition-delay: {{ $loop->index * 200 }}ms"
            >
                <div 
                    class="text-5xl font-bold mb-2"
                    x-data="{ count: 0 }"
                    x-intersect="() => {
                        const target = {{ $stat['value'] }};
                        const duration = 2000;
                        const fps = 60;
                        const steps = duration / (1000 / fps);
                        const increment = target / steps;
                        const interval = setInterval(() => {
                            count = count + increment;
                            if (count >= target) {
                                count = target;
                                clearInterval(interval);
                            }
                        }, 1000 / fps);
                    }"
                >
                    <span x-text="Math.floor(count)"></span>{{ $stat['suffix'] }}
                </div>
                <div class="text-primary-100">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>