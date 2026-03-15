<x-app-layout>
    <x-slot name="header">
        Manajemen Tagihan UKT
    </x-slot>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Manajemen Tagihan UKT</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola dan pantau status pembayaran UKT mahasiswa</p>
        </div>
        <a href="{{ route('admin.tagihan-ukt.create') }}" class="btn-primary-saas px-6 py-3 rounded-xl text-sm font-bold flex items-center gap-2 shadow-lg shadow-siakad-primary/20 transition hover:scale-105 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Tagihan
        </a>
    </div>

    <div class="space-y-6">
        {{-- Summary Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" style="animation: slideUp 0.4s ease-out both">
            <div class="bento-card p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Tagihan</p>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bento-card p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-emerald-500">Lunas</p>
                <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['paid'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Rp {{ number_format($stats['total_paid'], 0, ',', '.') }}</p>
            </div>
            <div class="bento-card p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-amber-500">Belum Lunas</p>
                <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['unpaid'] }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Rp {{ number_format($stats['total_unpaid'], 0, ',', '.') }}</p>
            </div>
            <div class="bento-card p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Rasio Lunas</p>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">
                    {{ $stats['total'] > 0 ? round(($stats['paid'] / $stats['total']) * 100) : 0 }}%
                </p>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div class="bento-card p-4" style="animation: slideUp 0.4s ease-out both; animation-delay: 100ms">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari NIM atau nama mahasiswa..."
                           class="w-full input-saas px-4 py-2.5 text-sm rounded-xl focus:ring-2 focus:ring-siakad-primary/20 outline-none">
                </div>
                <select name="status" class="input-saas px-4 py-2.5 text-sm rounded-xl sm:w-44 focus:ring-2 focus:ring-siakad-primary/20 outline-none">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
                <button type="submit" class="btn-primary-saas px-5 py-2.5 rounded-xl text-sm font-semibold">
                    Filter
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="bento-card overflow-hidden" style="animation: slideUp 0.4s ease-out both; animation-delay: 200ms">
            <div class="overflow-x-auto">
                <table class="w-full table-saas">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-left border-b border-slate-100 dark:border-slate-700">
                            <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                            <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Mahasiswa</th>
                            <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Prodi</th>
                            <th class="px-5 py-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Periode</th>
                            <th class="px-5 py-4 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nominal</th>
                            <th class="px-5 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                            <th class="px-5 py-4 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($tagihan as $i => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-5 py-4 text-sm text-slate-500">{{ $tagihan->firstItem() + $i }}</td>
                            <td class="px-5 py-4">
                                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $item->mahasiswa->user->name ?? '-' }}</p>
                                <p class="text-[11px] font-mono text-slate-500">{{ $item->mahasiswa->nim ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $item->mahasiswa->prodi->nama ?? '-' }}</td>
                            <td class="px-5 py-4 text-sm text-slate-600 dark:text-slate-400">
                                {{ $item->tahun_akademik }} - {{ $item->semester }}
                            </td>
                            <td class="px-5 py-4 text-sm text-right font-bold text-slate-800 dark:text-white">
                                {{ $item->formatted_nominal }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($item->isPaid())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if(!$item->isPaid())
                                    <form action="{{ route('admin.tagihan-ukt.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors" title="Hapus Tagihan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-[10px] text-slate-400 italic">No Action</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada data tagihan UKT.</p>
                                    <a href="{{ route('admin.tagihan-ukt.create') }}" class="text-xs text-siakad-primary font-bold hover:underline">Buat Tagihan Pertama</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tagihan->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700/50">
                {{ $tagihan->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
