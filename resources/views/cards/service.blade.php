@props(['icon', 'title', 'description'])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-700 rounded-xl shadow-lg p-8 relative overflow-hidden group']) }}>
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 dark:from-gray-600 dark:to-gray-800"></div>
    
    <div class="relative z-10">
        <div class="w-16 h-16 mb-6 bg-blue-500 rounded-lg flex items-center justify-center text-white">
            <x-dynamic-component :component="$icon" class="w-8 h-8" />
        </div>
        
        <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">{{ $title }}</h3>
        <p class="text-gray-600 dark:text-gray-300">{{ $description }}</p>
    </div>
</div>