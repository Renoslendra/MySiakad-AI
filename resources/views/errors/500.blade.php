<x-guest-layout>
    <div class="text-center">
        <h1 class="text-9xl font-bold text-red-500 animate-pulse">500</h1>
        <h2 class="text-2xl font-semibold text-siakad-dark dark:text-white mt-4">Kesalahan Server</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-sm mx-auto">
            Maaf, terjadi kesalahan pada server kami. Kami sedang berusaha memperbaikinya secepat mungkin.
        </p>
        <div class="mt-10 flex flex-col gap-3">
            <a href="{{ url('/dashboard') }}" 
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-siakad-primary hover:bg-siakad-dark text-white rounded-xl shadow-lg transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kembali ke Dashboard
            </a>
            <button onclick="location.reload()" 
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-200 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Coba Lagi
            </button>
        </div>
    </div>
</x-guest-layout>
