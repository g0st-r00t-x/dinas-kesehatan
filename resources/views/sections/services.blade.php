<section id="layanan" class="py-20 bg-white dark:bg-gray-800">
    <div class="container mx-auto px-6">
        <x-section.title 
            title="Layanan Unggulan"
            subtitle="Teknologi Kesehatan Modern dengan Pelayanan Prima"
            data-aos="fade-up"
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="200">
            @foreach($services as $service)
                <x-card.service 
                    :icon="$service['icon']"
                    :title="$service['title']"
                    :description="$service['description']"
                    class="transform transition hover:-translate-y-2 duration-300"
                />
            @endforeach
        </div>
    </div>
</section>