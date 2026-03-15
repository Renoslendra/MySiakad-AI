<x-app-layout>
    <x-slot name="header">
        Tambah Tagihan UKT
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.tagihan-ukt.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-siakad-primary transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Tagihan
            </a>
        </div>

        <div class="bento-card p-6 md:p-8">
            <form action="{{ route('admin.tagihan-ukt.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    {{-- Mahasiswa --}}
                    <div>
                        <label for="mahasiswa_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih Mahasiswa</label>
                        <select name="mahasiswa_id" id="mahasiswa_id" class="w-full input-saas px-4 py-2.5 text-sm rounded-xl select2" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($mahasiswa as $mhs)
                                <option value="{{ $mhs->id }}">[{{ $mhs->nim }}] {{ $mhs->user->name }} - {{ $mhs->prodi->nama }}</option>
                            @endforeach
                        </select>
                        @error('mahasiswa_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tahun Akademik --}}
                        <div>
                            <label for="tahun_akademik" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tahun Akademik</label>
                            <select name="tahun_akademik" id="tahun_akademik" class="w-full input-saas px-4 py-2.5 text-sm rounded-xl" required>
                                @foreach($tahunAkademik as $ta)
                                    <option value="{{ $ta->tahun }}">{{ $ta->tahun }}</option>
                                @endforeach
                            </select>
                            @error('tahun_akademik') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Semester --}}
                        <div>
                            <label for="semester" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Semester</label>
                            <select name="semester" id="semester" class="w-full input-saas px-4 py-2.5 text-sm rounded-xl" required>
                                <option value="Gasal">Gasal</option>
                                <option value="Genap">Genap</option>
                            </select>
                            @error('semester') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Nominal --}}
                    <div>
                        <label for="nominal" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nominal Tagihan (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">Rp</span>
                            <input type="number" name="nominal" id="nominal" value="500000"
                                   class="w-full input-saas pl-12 pr-4 py-2.5 text-sm rounded-xl font-semibold" 
                                   placeholder="0" required>
                        </div>
                        <p class="mt-1.5 text-[11px] text-slate-400 italic">* Masukkan angka tanpa titik atau koma.</p>
                        @error('nominal') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Payment Link --}}
                    <div>
                        <label for="payment_link" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Link Pembayaran (Mayar)</label>
                        <input type="url" name="payment_link" id="payment_link" 
                               value="https://reno-tugascmt.myr.id"
                               class="w-full input-saas px-4 py-2.5 text-sm rounded-xl" 
                               placeholder="https://example.com/pay" required>
                        <p class="mt-1.5 text-[11px] text-slate-400">Link ini akan digunakan mahasiswa untuk membayar tagihan.</p>
                        @error('payment_link') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" class="flex-1 btn-primary-saas py-3 rounded-xl text-sm font-bold shadow-lg shadow-siakad-primary/20">
                        Buat Tagihan Sekarang
                    </button>
                    <a href="{{ route('admin.tagihan-ukt.index') }}" class="px-6 py-3 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "-- Pilih Mahasiswa --",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            height: 42px;
            background-color: var(--bg-card);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-left: 1rem;
            color: var(--text-primary);
            font-size: 0.875rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
        .dark .select2-dropdown {
            background-color: #1F2937;
            border-color: #374151;
            color: white;
        }
        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #374151;
            border-color: #4B5563;
            color: white;
        }
        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #234C6A;
        }
    </style>
    @endpush
</x-app-layout>
