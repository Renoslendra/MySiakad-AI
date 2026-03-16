<x-guest-layout>
    <div class="text-center">
        <h1 class="text-9xl font-bold text-siakad-primary animate-bounce">404</h1>
        <h2 class="text-2xl font-semibold text-siakad-dark dark:text-white mt-4">Halaman Tidak Ditemukan</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-sm mx-auto">
            Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan ke dimensi lain.
        </p>
        <div class="mt-10">
            <a href="{{ url('/dashboard') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-siakad-primary hover:bg-siakad-dark text-white rounded-xl shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</x-guest-layout>
