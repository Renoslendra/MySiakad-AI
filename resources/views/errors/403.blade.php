<x-guest-layout>
    <div class="text-center">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-siakad-dark dark:text-white mb-2">Akses Ditolak</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-sm mx-auto">
            {{ $message ?? 'Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi admin jika ini adalah kesalahan.' }}
        </p>
        <a href="{{ url('/dashboard') }}" 
           class="inline-flex items-center justify-center px-6 py-3 bg-siakad-primary hover:bg-siakad-dark text-white rounded-xl shadow-lg transition-all duration-200 font-medium">
            Kembali ke Dashboard
        </a>
    </div>
</x-guest-layout>
