<button 
    @click="darkMode = !darkMode"
    class="p-2 rounded-full bg-white dark:bg-gray-700 shadow-lg hover:shadow-xl transition-shadow"
    aria-label="Toggle Dark Mode"
>
    <x-icon.sun class="w-6 h-6 text-yellow-400 dark:hidden" />
    <x-icon.moon class="w-6 h-6 text-blue-400 hidden dark:block" />
</button>