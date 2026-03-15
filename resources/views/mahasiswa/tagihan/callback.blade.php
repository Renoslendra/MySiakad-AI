<x-app-layout>
    <x-slot name="header">
        Status Pembayaran
    </x-slot>

    <div class="max-w-lg mx-auto" style="animation: slideUp 0.5s ease-out both">
        @if($tagihan && $tagihan->isPaid())
            <div class="bento-card p-8 text-center border-emerald-200 dark:border-emerald-500/30">
                <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Pembayaran Berhasil!</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Terima kasih, pembayaran UKT Anda telah diterima.</p>
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn-primary-saas px-6 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
                    Kembali ke Dashboard
                </a>
            </div>
        @else
            <div class="bento-card p-8 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-amber-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Menunggu Konfirmasi</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Pembayaran Anda sedang diproses. Status akan diperbarui secara otomatis.</p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ route('mahasiswa.tagihan.index') }}" class="btn-ghost-saas px-5 py-2.5 rounded-xl text-sm font-semibold">
                        Lihat Tagihan
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn-primary-saas px-5 py-2.5 rounded-xl text-sm font-semibold">
                        Dashboard
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
