<x-app-layout>
    <x-slot name="header">
        Tagihan UKT
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('error'))
        <div class="bento-card p-4 border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10" style="animation: slideUp 0.3s ease-out both">
            <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ session('error') }}</p>
        </div>
        @endif

        @forelse($tagihan as $item)
        <div class="bento-card overflow-hidden" style="animation: slideUp 0.4s ease-out both; animation-delay: {{ $loop->index * 80 }}ms">
            @if($item->isPaid())
                {{-- Paid Card --}}
                <div class="p-6 border-l-4 border-emerald-500">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-base font-bold text-slate-800 dark:text-white">UKT {{ $item->tahun_akademik }}</h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    Lunas
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Semester {{ $item->semester }}</p>
                            <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-2">{{ $item->formatted_nominal }}</p>
                            <p class="text-xs text-slate-400 mt-1">Dibayar pada: {{ $item->paid_at?->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            @else
                {{-- Unpaid Card (Dark gradient style) --}}
                <div class="ukt-card">
                    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-indigo-400/30 to-transparent"></div>
                    <div class="relative z-10">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="text-base font-bold text-white">UKT {{ $item->tahun_akademik }}</h3>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                                        Belum Lunas
                                    </span>
                                </div>
                                <p class="text-sm text-slate-400">Semester {{ $item->semester }}</p>
                                <p class="text-3xl font-extrabold text-white mt-2">{{ $item->formatted_nominal }}</p>
                            </div>
                            <form action="{{ route('mahasiswa.tagihan.bayar') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="group inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 text-white text-sm font-bold hover:from-indigo-400 hover:to-violet-400 transition-all duration-300 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Bayar via Mayar
                                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @empty
        <div class="bento-card p-12 text-center" style="animation: slideUp 0.4s ease-out both">
            <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            <p class="text-slate-400 dark:text-slate-500 text-sm">Belum ada tagihan UKT aktif.</p>
        </div>
        @endforelse
    </div>
</x-app-layout>
