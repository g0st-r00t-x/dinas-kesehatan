<!-- resources/views/components/contact.blade.php -->
<section 
    id="kontak" 
    class="py-20 bg-white dark:bg-gray-800 relative overflow-hidden"
    x-data="{ shown: false }"
    x-intersect="shown = true"
>
    <!-- Decorative Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute transform rotate-45 -left-1/4 -top-1/4 w-1/2 h-1/2 bg-primary-500"></div>
        <div class="absolute transform rotate-45 -right-1/4 -bottom-1/4 w-1/2 h-1/2 bg-secondary-500"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 
                    class="text-4xl font-bold mb-4 text-gray-900 dark:text-white"
                    x-show="shown"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    Hubungi Kami
                </h2>
                <p 
                    class="text-gray-600 dark:text-gray-400"
                    x-show="shown"
                    x-transition:enter="transition ease-out duration-500 delay-200"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    Kami siap melayani dan membantu Anda
                </p>
            </div>

            <!-- Contact Form -->
            <div 
                class="bg-white dark:bg-gray-700 rounded-2xl shadow-xl p-8 transform transition-all duration-300 hover:scale-[1.02]"
                x-show="shown"
                x-transition:enter="transition ease-out duration-500 delay-400"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
            >
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Input -->
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2" for="name">
                                Nama Lengkap
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                name="name"
                                class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-primary-500 dark:focus:border-primary-500 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-500"
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2" for="email">
                                Email
                            </label>
                            <input 
                                type="email" 
                                id="email"
                                name="email"
                                class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-primary-500 dark:focus:border-primary-500 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-500"
                                placeholder="nama@email.com"
                                required
                            >
                        </div>
                    </div>

                    <!-- Subject Input -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="subject">
                            Subjek
                        </label>
                        <input 
                            type="text" 
                            id="subject"
                            name="subject"
                            class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-primary-500 dark:focus:border-primary-500 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-500"
                            placeholder="Perihal pesan Anda"
                            required
                        >
                    </div>

                    <!-- Message Input -->
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 mb-2" for="message">
                            Pesan
                        </label>
                        <textarea 
                            id="message"
                            name="message"
                            rows="4"
                            class="form-textarea w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-primary-500 dark:focus:border-primary-500 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-500"
                            placeholder="Tuliskan pesan Anda"
                            required
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                    >
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>