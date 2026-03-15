<x-app-layout>
    <x-slot name="header">
        AI Academic Advisor
    </x-slot>

    <div class="h-[calc(100vh-120px)] flex flex-col" x-data="aiAdvisor()">
        <div class="flex-1 overflow-hidden flex flex-col max-w-4xl mx-auto w-full">
            {{-- Messages Container --}}
            <div class="flex-1 overflow-y-auto" x-ref="chatContainer">
                <div class="px-4 py-8 space-y-6">
                    {{-- Welcome Card --}}
                    <div class="max-w-3xl mx-auto" style="animation: slideUp 0.5s ease-out both">
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-indigo-950 to-violet-950 p-6 border border-violet-500/20 shadow-lg shadow-violet-500/5">
                            {{-- Decorative --}}
                            <div class="absolute top-0 right-0 w-48 h-48 bg-violet-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl translate-y-1/3 -translate-x-1/4"></div>
                            <div class="absolute inset-0 overflow-hidden rounded-2xl">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/[0.03] to-transparent animate-shimmer"></div>
                            </div>

                            <div class="relative z-10 flex gap-4">
                                <div class="sparkle-icon w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-violet-500/30">
                                    <svg class="w-5 h-5 text-white animate-sparkle" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white/90 leading-relaxed text-sm">
                                        Halo, <span class="font-bold text-white">{{ explode(' ', $mahasiswa->user->name)[0] }}</span>! Saya <span class="font-semibold text-violet-300">Academic Advisor AI</span> yang siap membantu Anda dengan pertanyaan seputar akademik — analisis nilai, saran pengambilan KRS, jadwal kuliah, dan lainnya.
                                    </p>
                                    <div class="flex flex-wrap gap-2 mt-4">
                                        <button @click="input = 'Analisis nilai saya semester ini'; sendMessage()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium bg-white/10 text-white/80 border border-white/10 hover:bg-white/20 hover:text-white transition-all duration-200 cursor-pointer">
                                            <svg class="w-3 h-3 text-violet-400" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                            Analisis Nilai
                                        </button>
                                        <button @click="input = 'Bantu saya menyusun KRS semester depan'; sendMessage()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium bg-white/10 text-white/80 border border-white/10 hover:bg-white/20 hover:text-white transition-all duration-200 cursor-pointer">
                                            <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                            Saran KRS
                                        </button>
                                        <button @click="input = 'Berikan tips strategi belajar yang efektif'; sendMessage()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium bg-white/10 text-white/80 border border-white/10 hover:bg-white/20 hover:text-white transition-all duration-200 cursor-pointer">
                                            <svg class="w-3 h-3 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                            Strategi Belajar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Messages --}}
                    <template x-for="(msg, index) in messages" :key="index">
                        <div class="max-w-3xl mx-auto animate-fade-in">
                            {{-- User Message --}}
                            <div x-show="msg.role === 'user'" class="flex justify-end mb-4">
                                <div class="bg-gradient-to-br from-indigo-600 to-violet-600 text-white px-5 py-3 rounded-2xl rounded-br-md max-w-[80%] shadow-lg shadow-indigo-500/10">
                                    <p class="text-sm leading-relaxed" x-text="msg.content"></p>
                                </div>
                            </div>

                            {{-- AI Message --}}
                            <div x-show="msg.role !== 'user'" class="flex gap-3.5">
                                <div class="sparkle-icon w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-violet-500/20 mt-0.5">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                    </svg>
                                </div>
                                <div class="flex-1 pt-0.5 prose prose-sm dark:prose-invert max-w-none prose-p:leading-relaxed prose-p:text-slate-700 dark:prose-p:text-slate-200 prose-headings:text-slate-800 dark:prose-headings:text-white prose-strong:text-slate-800 dark:prose-strong:text-white" x-html="formatMessage(msg.content)"></div>
                            </div>
                        </div>
                    </template>

                    {{-- Thinking Indicator --}}
                    <div x-show="isLoading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-3xl mx-auto">
                        <div class="flex gap-3.5">
                            <div class="sparkle-icon w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-violet-500/20 mt-0.5 animate-pulse">
                                <svg class="w-4 h-4 text-white animate-sparkle" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                </svg>
                            </div>
                            <div class="flex-1 pt-1">
                                <div class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    <span class="text-sm text-slate-500 dark:text-slate-400 font-medium" x-text="thinkingStatus"></span>
                                    <span class="flex gap-1">
                                        <span class="w-1.5 h-1.5 bg-violet-500 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                        <span class="w-1.5 h-1.5 bg-violet-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                        <span class="w-1.5 h-1.5 bg-violet-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                                    </span>
                                </div>
                                <div class="mt-2.5 h-1 w-48 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-violet-500 to-indigo-500 rounded-full" style="width: 60%; animation: shimmer 2s ease-in-out infinite"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input Area --}}
            <div class="flex-shrink-0 px-4 pb-6 pt-4">
                <div class="max-w-3xl mx-auto">
                    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-bento border border-slate-200 dark:border-slate-700 overflow-hidden transition-all duration-200 focus-within:shadow-bento-hover focus-within:border-violet-300 dark:focus-within:border-violet-500/50">
                        <textarea
                            x-model="input"
                            @keydown.enter.prevent="if (!$event.shiftKey) sendMessage()"
                            :disabled="isLoading"
                            placeholder="Tanyakan sesuatu tentang akademik Anda..."
                            rows="1"
                            class="w-full px-5 py-4 pr-14 bg-transparent text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm resize-none border-0 focus:ring-0 focus:outline-none"
                            style="min-height: 56px; max-height: 150px;"
                            x-ref="inputField"
                            @input="$el.style.height = '56px'; $el.style.height = Math.min($el.scrollHeight, 150) + 'px'"
                        ></textarea>
                        <button
                            type="button"
                            @click="sendMessage"
                            :disabled="isLoading || !input.trim()"
                            class="absolute right-3 bottom-3 p-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 text-white disabled:bg-slate-200 dark:disabled:bg-slate-700 disabled:from-slate-200 disabled:to-slate-200 dark:disabled:from-slate-700 dark:disabled:to-slate-700 disabled:text-slate-400 dark:disabled:text-slate-500 disabled:cursor-not-allowed hover:from-violet-500 hover:to-indigo-500 transition-all duration-200 shadow-md shadow-violet-500/20 disabled:shadow-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h14M12 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-center mt-3 text-[11px] text-slate-400 dark:text-slate-500">
                        <span>Enter untuk kirim &nbsp;&bull;&nbsp; Shift+Enter baris baru &nbsp;&bull;&nbsp; Powered by Qwen AI</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function aiAdvisor() {
            return {
                input: '',
                messages: [],
                isLoading: false,
                thinkingStatus: 'Menganalisis pertanyaan',
                thinkingInterval: null,

                startThinking() {
                    const statuses = [
                        'Menganalisis pertanyaan',
                        'Memuat data akademik',
                        'Memeriksa kurikulum',
                        'Menganalisis progress',
                        'Menyusun rekomendasi',
                        'Memverifikasi data',
                        'Menyiapkan jawaban',
                        'Menyelesaikan analisis'
                    ];
                    let index = 0;
                    this.thinkingStatus = statuses[0];
                    this.thinkingInterval = setInterval(() => {
                        if (index < statuses.length - 1) {
                            index++;
                            this.thinkingStatus = statuses[index];
                        }
                    }, 2500);
                },

                stopThinking() {
                    if (this.thinkingInterval) {
                        clearInterval(this.thinkingInterval);
                        this.thinkingInterval = null;
                    }
                },

                async sendMessage() {
                    if (!this.input.trim() || this.isLoading) return;

                    const userMessage = this.input.trim();
                    this.input = '';
                    this.$refs.inputField.style.height = '56px';

                    this.messages.push({ role: 'user', content: userMessage });
                    this.scrollToBottom();

                    this.isLoading = true;
                    this.startThinking();

                    try {
                        const response = await fetch('{{ route("mahasiswa.ai-advisor.chat") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                message: userMessage,
                                history: this.messages.slice(-10),
                            }),
                        });

                        const data = await response.json();

                        this.messages.push({
                            role: 'assistant',
                            content: data.success ? data.message : 'Maaf, terjadi kesalahan: ' + data.message
                        });
                    } catch (error) {
                        this.messages.push({
                            role: 'assistant',
                            content: 'Maaf, terjadi kesalahan jaringan. Silakan coba lagi.'
                        });
                    } finally {
                        this.stopThinking();
                        this.isLoading = false;
                        this.scrollToBottom();
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.chatContainer;
                        container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
                    });
                },

                formatMessage(text) {
                    return text
                        .replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold text-slate-800 dark:text-white">$1</strong>')
                        .replace(/\*(.*?)\*/g, '<em>$1</em>')
                        .replace(/`(.*?)`/g, '<code class="bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded text-xs font-mono text-violet-600 dark:text-violet-400">$1</code>')
                        .replace(/\n\n/g, '</p><p class="mt-4">')
                        .replace(/\n/g, '<br>')
                        .replace(/^- /gm, '<span class="text-violet-500 mr-2">&bull;</span>')
                        .replace(/^(\d+)\. /gm, '<span class="font-semibold text-violet-500 mr-2">$1.</span>');
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
