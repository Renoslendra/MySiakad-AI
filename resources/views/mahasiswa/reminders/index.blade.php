<x-app-layout>
    <div class="py-6 md:py-10 min-h-screen" style="background-color: var(--bg-body);">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ═══ PAGE HEADER ═══ --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold tracking-tight" style="color: var(--text-primary);">MyReminder</h2>
                            <p class="text-sm" style="color: var(--text-secondary);">Atur pengingat untuk menghubungi dosen via WhatsApp</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $reminders->where('status', 'pending')->count() }} Alarm Aktif
                    </div>
                    <div class="px-4 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-500/20">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217s.231.006.332.013c.101.007.237-.038.37.284.133.321.454 1.109.494 1.19s.067.171.013.284c-.054.113-.081.183-.16.27-.079.087-.166.19-.237.253-.079.069-.161.144-.07.3.091.156.405.666.867 1.077.595.529 1.096.69 1.25.767s.273.058.375-.06c.101-.118.434-.505.549-.679.116-.174.231-.144.39-.087s1.011.477 1.184.564c.173.087.289.13.332.202.043.072.043.418-.101.823z"/></svg>
                        WhatsApp Ready
                    </div>
                </div>
            </div>

            {{-- ═══ MAIN GRID ═══ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ showForm: false }">

                {{-- ═══ LEFT COLUMN: ALARM LIST + QUICK CONTACTS ═══ --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- ─── NEW ALARM FORM ─── --}}
                    <div class="rounded-2xl overflow-hidden border border-indigo-200 dark:border-indigo-500/30 shadow-sm" style="background-color: var(--bg-card);">
                        {{-- Form Header --}}
                        <button @click="showForm = !showForm" class="w-full px-6 py-4 flex items-center justify-between bg-gradient-to-r from-indigo-500 to-violet-600 text-white cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <div class="text-left">
                                    <h3 class="font-bold text-base">Buat Alarm Baru</h3>
                                    <p class="text-xs text-white/70">Atur pengingat chat dosen via WhatsApp</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 transition-transform duration-300" :class="showForm ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Form Body --}}
                        <div x-show="showForm || {{ $errors->any() ? 'true' : 'false' }}" x-collapse x-cloak>
                            <form action="{{ route('mahasiswa.reminders.store') }}" method="POST" class="p-6 space-y-5">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    {{-- Pilih Dosen --}}
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--text-secondary);">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                Pilih Dosen
                                            </span>
                                        </label>
                                        <select name="dosen_id" id="dosen_id" class="w-full rounded-xl text-sm font-medium p-3 input-saas focus:ring-2 focus:ring-indigo-500/20" required>
                                            @if($dosenPa)
                                                <option value="{{ $dosenPa->id }}" data-phone="{{ preg_replace('/[^0-9]/', '', $dosenPa->user->phone ?? '085156064912') }}">
                                                    PA: {{ $dosenPa->user->name }}
                                                </option>
                                            @endif
                                            @foreach($dosenLain as $dosen)
                                                <option value="{{ $dosen->id }}" data-phone="{{ preg_replace('/[^0-9]/', '', $dosen->user->phone ?? '085156064912') }}">
                                                    MK: {{ $dosen->user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('dosen_id')
                                            <p class="mt-1 flex items-center gap-1 text-[10px] font-bold text-red-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                    
                                    {{-- Nomor WA override (opsional) --}}
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--text-secondary);">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                No. WA Dosen <span class="text-[9px] font-normal text-slate-400 lowercase">(opsional jika beda)</span>
                                            </span>
                                        </label>
                                        <input type="text" name="phone_override" id="phone_override" placeholder="Contoh: +628123456789"
                                               class="w-full rounded-xl text-sm font-medium p-3 input-saas focus:ring-2 focus:ring-indigo-500/20" value="{{ old('phone_override') }}">
                                        @error('phone_override')
                                            <p class="mt-1 flex items-center gap-1 text-[10px] font-bold text-red-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                    
                                    {{-- Kategori --}}
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--text-secondary);">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                                Kategori
                                            </span>
                                        </label>
                                        <select name="category" id="category" class="w-full rounded-xl text-sm font-medium p-3 input-saas focus:ring-2 focus:ring-indigo-500/20" required>
                                            <option value="Konsultasi Akademik">Konsultasi Akademik</option>
                                            <option value="Bimbingan Skripsi">Bimbingan Skripsi</option>
                                            <option value="Bimbingan Kerja Praktek">Bimbingan KP</option>
                                            <option value="Izin Perkuliahan">Izin Kuliah</option>
                                            <option value="Tanya Tugas">Diskusi Tugas</option>
                                        </select>
                                        @error('category')
                                            <p class="mt-1 flex items-center gap-1 text-[10px] font-bold text-red-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    {{-- Topik --}}
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--text-secondary);">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                                Topik Chat
                                            </span>
                                        </label>
                                        <input type="text" name="title" id="title" placeholder="Contoh: Konsultasi BAB 1"
                                               class="w-full rounded-xl text-sm font-medium p-3 input-saas focus:ring-2 focus:ring-indigo-500/20" value="{{ old('title') }}" required>
                                        @error('title')
                                            <p class="mt-1 flex items-center gap-1 text-[10px] font-bold text-red-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    {{-- Waktu --}}
                                    <div>
                                        <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--text-secondary);">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Kapan Kita Chat?
                                            </span>
                                        </label>
                                        <input type="datetime-local" name="scheduled_at"
                                               class="w-full rounded-xl text-sm font-medium p-3 input-saas focus:ring-2 focus:ring-indigo-500/20" value="{{ old('scheduled_at') }}" required>
                                        @error('scheduled_at')
                                            <p class="mt-1 flex items-center gap-1 text-[10px] font-bold text-red-500">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Waktu alarm tidak valid (harus setelah waktu sekarang).
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Draf Pesan --}}
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Draf Pesan
                                            </span>
                                        </label>
                                        <button type="button" onclick="generateAiMessage()" id="ai-btn"
                                                class="text-xs font-bold bg-gradient-to-r from-violet-600 to-indigo-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:from-violet-700 hover:to-indigo-700 transition-all shadow-md shadow-violet-500/20 active:scale-95">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            TULIS AI
                                        </button>
                                    </div>
                                    <textarea name="message" id="message" rows="4" placeholder="Gunakan AI untuk menulis pesan otomatis atau ketik manual..."
                                              class="w-full rounded-xl text-sm font-medium p-3 input-saas focus:ring-2 focus:ring-indigo-500/20 leading-relaxed">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1 flex items-center gap-1 text-[10px] font-bold text-red-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                    <button type="submit" class="flex-1 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Set Alarm
                                    </button>
                                    <button type="button" onclick="sendWhatsAppNow()" class="flex-1 py-3.5 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-emerald-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217s.231.006.332.013c.101.007.237-.038.37.284.133.321.454 1.109.494 1.19s.067.171.013.284c-.054.113-.081.183-.16.27-.079.087-.166.19-.237.253-.079.069-.161.144-.07.3.091.156.405.666.867 1.077.595.529 1.096.69 1.25.767s.273.058.375-.06c.101-.118.434-.505.549-.679.116-.174.231-.144.39-.087s1.011.477 1.184.564c.173.087.289.13.332.202.043.072.043.418-.101.823z"/></svg>
                                        Chat WA Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- ─── ALARM LIST ─── --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold flex items-center gap-2" style="color: var(--text-primary);">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Daftar Alarm
                            </h3>
                            <span class="text-xs font-medium px-3 py-1 rounded-lg" style="color: var(--text-secondary); background-color: var(--bg-card); border: 1px solid var(--border-color);">
                                {{ $reminders->count() }} Total
                            </span>
                        </div>

                        <div class="space-y-3" id="alarm-list">
                            @forelse($reminders as $reminder)
                                @php
                                    $isPending = $reminder->status === 'pending';
                                    $colorMap = [
                                        'Konsultasi Akademik' => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-50 dark:bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-200 dark:border-blue-500/20'],
                                        'Bimbingan Skripsi' => ['bg' => 'bg-violet-500', 'light' => 'bg-violet-50 dark:bg-violet-500/10', 'text' => 'text-violet-600 dark:text-violet-400', 'border' => 'border-violet-200 dark:border-violet-500/20'],
                                        'Bimbingan Kerja Praktek' => ['bg' => 'bg-amber-500', 'light' => 'bg-amber-50 dark:bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400', 'border' => 'border-amber-200 dark:border-amber-500/20'],
                                        'Izin Perkuliahan' => ['bg' => 'bg-rose-500', 'light' => 'bg-rose-50 dark:bg-rose-500/10', 'text' => 'text-rose-600 dark:text-rose-400', 'border' => 'border-rose-200 dark:border-rose-500/20'],
                                        'Tanya Tugas' => ['bg' => 'bg-emerald-500', 'light' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-500/20'],
                                    ];
                                    $colors = $colorMap[$reminder->category ?? ''] ?? $colorMap['Konsultasi Akademik'];
                                @endphp
                                <div class="rounded-xl p-4 border transition-all hover:shadow-md group {{ $colors['border'] }}"
                                     style="background-color: var(--bg-card);"
                                     data-time="{{ $reminder->scheduled_at->format('Y-m-d H:i') }}"
                                     data-message="{{ $reminder->message }}"
                                     data-phone="{{ preg_replace('/[^0-9]/', '', $reminder->phone_override ?? $reminder->dosen->user->phone ?? '085156064912') }}"
                                     data-id="{{ $reminder->id }}">
                                    <div class="flex items-start gap-4">
                                        {{-- Color Indicator --}}
                                        <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $colors['light'] }} flex items-center justify-center {{ $colors['text'] }}">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-sm font-bold truncate" style="color: var(--text-primary);">{{ $reminder->title }}</h4>
                                                <span class="flex-shrink-0 text-[10px] font-bold py-0.5 px-2 rounded-full {{ $isPending ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400' }}">
                                                    {{ strtoupper($reminder->status) }}
                                                </span>
                                            </div>
                                            <p class="text-xs mb-2" style="color: var(--text-secondary);">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    {{ $reminder->dosen->user->name }}
                                                </span>
                                            </p>

                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-lg font-black {{ $colors['text'] }}">{{ $reminder->scheduled_at->format('H:i') }}</span>
                                                    <span class="text-xs font-medium px-2 py-0.5 rounded-md" style="color: var(--text-secondary); background: var(--bg-body);">{{ $reminder->scheduled_at->format('d M Y') }}</span>
                                                </div>
                                                <form action="{{ route('mahasiswa.reminders.destroy', $reminder) }}" method="POST" onsubmit="return confirm('Hapus pengingat ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl p-12 text-center border border-dashed" style="border-color: var(--border-color); background-color: var(--bg-card);">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="font-bold text-sm mb-1" style="color: var(--text-primary);">Belum Ada Alarm</p>
                                    <p class="text-xs" style="color: var(--text-secondary);">Klik "Buat Alarm Baru" untuk membuat pengingat pertamamu</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ═══ RIGHT COLUMN: QUICK CONTACTS ═══ --}}
                <div class="space-y-6">
                    {{-- Quick Contact Card --}}
                    <div class="rounded-2xl border overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <div class="px-5 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217s.231.006.332.013c.101.007.237-.038.37.284.133.321.454 1.109.494 1.19s.067.171.013.284c-.054.113-.081.183-.16.27-.079.087-.166.19-.237.253-.079.069-.161.144-.07.3.091.156.405.666.867 1.077.595.529 1.096.69 1.25.767s.273.058.375-.06c.101-.118.434-.505.549-.679.116-.174.231-.144.39-.087s1.011.477 1.184.564c.173.087.289.13.332.202.043.072.043.418-.101.823z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm">Quick Chat</h3>
                                    <p class="text-[11px] text-white/70">Langsung hubungi dosen</p>
                                </div>
                            </div>
                        </div>

                        <div class="divide-y" style="border-color: var(--border-color);">
                            @php
                                $allLecturers = collect();
                                if($dosenPa) $allLecturers->push(['dosen' => $dosenPa, 'type' => 'PA']);
                                foreach($dosenLain as $d) $allLecturers->push(['dosen' => $d, 'type' => 'Dosen MK']);
                            @endphp

                            @forelse($allLecturers as $entry)
                                @php
                                    $waPhone = preg_replace('/[^0-9]/', '', $entry['dosen']->user->phone ?? '085156064912');
                                    $waText = $entry['dosen']->latest_reminder ? $entry['dosen']->latest_reminder->message : "Halo Bapak/Ibu, saya mahasiswa Anda.";
                                    $typeColors = $entry['type'] === 'PA'
                                        ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400'
                                        : 'bg-slate-100 text-slate-600 dark:bg-slate-600 dark:text-slate-300';
                                @endphp
                                <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors" style="border-color: var(--border-color);">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 relative shadow-inner">
                                            {{ substr($entry['dosen']->user->name, 0, 1) }}{{ substr(explode(' ', $entry['dosen']->user->name)[1] ?? '', 0, 1) }}
                                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 dark:border-gray-800" style="border-color: var(--bg-card);"></div>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <span class="text-[10px] font-bold py-0.5 px-2 rounded-md {{ $typeColors }}">{{ $entry['type'] }}</span>
                                            </div>
                                            <p class="text-sm font-semibold truncate" style="color: var(--text-primary);">{{ $entry['dosen']->user->name }}</p>
                                            @if($entry['dosen']->latest_reminder)
                                                <div class="flex items-center gap-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-500 mt-0.5">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                    Draf Ready
                                                </div>
                                            @else
                                                <div class="flex items-center gap-1 text-[10px] font-medium text-slate-400 dark:text-slate-500 mt-0.5">
                                                    Belum ada draf
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 w-full sm:w-auto mt-1 sm:mt-0">
                                        <div class="relative w-full sm:w-32">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            </div>
                                            <input type="text" id="qc-phone-{{ $entry['dosen']->id }}" placeholder="No. WA"
                                                   class="w-full text-xs pl-8 pr-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700/50 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all outline-none"
                                                   style="background-color: var(--bg-body); color: var(--text-primary);"
                                                   oninput="updateQuickChatHref({{ $entry['dosen']->id }}, '{{ $waPhone }}', '{{ urlencode($waText) }}')">
                                        </div>
                                        <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waText) }}" target="_blank" id="qc-btn-{{ $entry['dosen']->id }}"
                                           class="w-[42px] h-[42px] rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 transition-all flex-shrink-0 group-hover:ring-2 group-hover:ring-emerald-500/20">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217s.231.006.332.013c.101.007.237-.038.37.284.133.321.454 1.109.494 1.19s.067.171.013.284c-.054.113-.081.183-.16.27-.079.087-.166.19-.237.253-.079.069-.161.144-.07.3.091.156.405.666.867 1.077.595.529 1.096.69 1.25.767s.273.058.375-.06c.101-.118.434-.505.549-.679.116-.174.231-.144.39-.087s1.011.477 1.184.564c.173.087.289.13.332.202.043.072.043.418-.101.823z"/></svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="px-5 py-8 text-center">
                                    <p class="text-xs" style="color: var(--text-secondary);">Belum ada dosen terdaftar</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tips Card --}}
                    <div class="rounded-2xl p-5 bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-lg shadow-indigo-500/15">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            <h4 class="font-bold text-sm">Tips Penggunaan</h4>
                        </div>
                        <ul class="space-y-2 text-xs text-white/80">
                            <li class="flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-white/20 flex-shrink-0 flex items-center justify-center text-[10px] font-bold mt-0.5">1</span>
                                <span>Gunakan tombol <strong class="text-white">TULIS AI</strong> untuk generate pesan otomatis</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-white/20 flex-shrink-0 flex items-center justify-center text-[10px] font-bold mt-0.5">2</span>
                                <span><strong class="text-white">Set Alarm</strong> untuk jadwalkan pengingat di waktu tertentu</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-white/20 flex-shrink-0 flex items-center justify-center text-[10px] font-bold mt-0.5">3</span>
                                <span><strong class="text-white">Chat WA</strong> untuk langsung kirim pesan sekarang</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-5 h-5 rounded-full bg-white/20 flex-shrink-0 flex items-center justify-center text-[10px] font-bold mt-0.5">4</span>
                                <span>Gunakan <strong class="text-white">Quick Chat</strong> di samping untuk akses cepat</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ ALARM MODAL ═══ --}}
    <div id="alarm-ring-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/80 backdrop-blur-xl">
        <div class="rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl relative" style="background-color: var(--bg-card);">
            <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-full mx-auto mb-5 flex items-center justify-center shadow-xl shadow-indigo-500/30 animate-pulse">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">Waktunya Chat!</h2>
            <p id="modal-alarm-title" class="text-sm font-medium mb-4" style="color: var(--text-secondary);">Judul Pengingat</p>

            <div id="modal-message-container" class="rounded-xl p-4 text-left mb-5 max-h-40 overflow-y-auto border" style="background-color: var(--bg-body); border-color: var(--border-color);">
                <p id="modal-alarm-message" class="text-xs leading-relaxed" style="color: var(--text-secondary);">Pesan belum diatur...</p>
            </div>

            <div class="space-y-3">
                <a id="modal-wa-link" href="#" target="_blank" onclick="closeAlarm()"
                   class="w-full py-4 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217s.231.006.332.013c.101.007.237-.038.37.284.133.321.454 1.109.494 1.19s.067.171.013.284c-.054.113-.081.183-.16.27-.079.087-.166.19-.237.253-.079.069-.161.144-.07.3.091.156.405.666.867 1.077.595.529 1.096.69 1.25.767s.273.058.375-.06c.101-.118.434-.505.549-.679.116-.174.231-.144.39-.087s1.011.477 1.184.564c.173.087.289.13.332.202.043.072.043.418-.101.823z"/></svg>
                    Kirim ke WhatsApp
                </a>
                <button onclick="closeAlarm()" class="w-full py-3 rounded-xl font-semibold text-sm transition-colors" style="color: var(--text-secondary);">Selesaikan Saja</button>
            </div>
        </div>
        <audio id="alarm-sound" loop src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"></audio>
    </div>

    @push('scripts')
    <script>
        function checkAlarms() {
            const now = new Date();
            const currentDateTime = now.getFullYear() + '-' +
                                  String(now.getMonth() + 1).padStart(2, '0') + '-' +
                                  String(now.getDate()).padStart(2, '0') + ' ' +
                                  String(now.getHours()).padStart(2, '0') + ':' +
                                  String(now.getMinutes()).padStart(2, '0');

            document.querySelectorAll('#alarm-list [data-time]').forEach(alarmEl => {
                const alarmTime = alarmEl.getAttribute('data-time');
                if (alarmTime === currentDateTime && !alarmEl.classList.contains('alarm-triggered')) {
                    triggerAlarm(alarmEl);
                }
            });
        }

        function triggerAlarm(el) {
            const title = el.querySelector('h4').innerText;
            const message = el.getAttribute('data-message') || "Halo Bapak/Ibu...";
            const phone = el.getAttribute('data-phone');

            document.getElementById('modal-alarm-title').innerText = title;
            document.getElementById('modal-alarm-message').innerText = message;
            document.getElementById('modal-wa-link').href = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;

            document.getElementById('alarm-ring-modal').style.display = 'flex';
            document.getElementById('alarm-sound').play();
            el.classList.add('alarm-triggered');

            if (Notification.permission === "granted") {
                new Notification("MyReminder: Waktunya Chat!", { body: title });
            }
        }

        async function generateAiMessage() {
            const btn = document.getElementById('ai-btn');
            const dosenId = document.querySelector('select[name="dosen_id"]').value;
            const category = document.getElementById('category').value;
            const title = document.getElementById('title').value;

            if (!title) {
                alert('Silakan isi topik chat terlebih dahulu.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> MENULIS...';

            try {
                const response = await fetch('{{ route("mahasiswa.reminders.generate-ai") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ dosen_id: dosenId, category: category, title: title })
                });

                const data = await response.json();
                if (data.success) {
                    document.getElementById('message').value = data.message;
                } else {
                    alert('Gagal: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan koneksi.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> TULIS AI';
            }
        }

        function sendWhatsAppNow() {
            const select = document.getElementById('dosen_id');
            const defaultPhone = select.options[select.selectedIndex].getAttribute('data-phone');
            const phoneOverride = document.getElementById('phone_override').value.replace(/[^0-9]/g, '');
            const phone = phoneOverride ? phoneOverride : defaultPhone;
            const message = document.getElementById('message').value;

            if (!message) {
                alert('Tolong tulis pesan Anda atau biarkan AI menulisnya dulu!');
                return;
            }

            const waUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        }

        function updateQuickChatHref(id, defaultPhone, messageUrlEncoded) {
            const inputVal = document.getElementById('qc-phone-' + id).value.replace(/[^0-9]/g, '');
            const phone = inputVal ? inputVal : defaultPhone;
            document.getElementById('qc-btn-' + id).href = `https://wa.me/${phone}?text=${messageUrlEncoded}`;
        }

        function closeAlarm() {
            document.getElementById('alarm-ring-modal').style.display = 'none';
            document.getElementById('alarm-sound').pause();
            document.getElementById('alarm-sound').currentTime = 0;
        }

        // Request notification permission
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }

        // Check every 30 seconds
        setInterval(checkAlarms, 30000);
        checkAlarms();
    </script>
    @endpush
</x-app-layout>
