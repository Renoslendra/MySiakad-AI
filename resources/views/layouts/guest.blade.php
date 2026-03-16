<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased text-gray-900 bg-white dark:bg-gray-900 selection:bg-siakad-primary selection:text-white">
    <div class="min-h-screen flex">
        
        <!-- Left Side - Form -->
        <div class="w-full lg:w-[480px] xl:w-[560px] flex flex-col justify-center px-8 lg:px-16 relative z-10 bg-white dark:bg-gray-900">
            <!-- Mobile Logo -->
            <div class="lg:hidden absolute top-8 left-8">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-siakad-primary flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="font-bold text-xl text-siakad-dark dark:text-white">{{ config('app.name') }}</span>
                </a>
            </div>

            <div class="w-full max-w-[400px] mx-auto">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>

            <!-- Footer -->
            <div class="absolute bottom-8 left-0 right-0 text-center text-xs text-gray-400 dark:text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>

        <!-- Right Side - Visual (Full-Clarity Slider) -->
        <div class="hidden lg:flex flex-1 relative bg-black overflow-hidden items-center justify-center" 
             x-data="{ 
                activeSlide: 0,
                activeTagline: 0,
                mouseX: 0,
                mouseY: 0,
                slides: [
                    'https://binus.ac.id/wp-content/uploads/2022/06/kampus-itb_169.jpeg',
                    'https://tse3.mm.bing.net/th/id/OIP.KzyV_uQM21mIqnHEHbKbnQHaE7?rs=1&pid=ImgDetMain&o=7&rm=3',
                    'https://tse1.mm.bing.net/th/id/OIP.4WqNy5LSCcRbkbC-yFfVjwHaFY?rs=1&pid=ImgDetMain&o=7&rm=3',
                    'https://tse2.mm.bing.net/th/id/OIP.C5wOyz8LHxqzZuSJ1PrJhAHaD6?rs=1&pid=ImgDetMain&o=7&rm=3',
                    'https://thumbs.dreamstime.com/b/gadjah-mada-university-universitas-gadjah-mada-abbreviated-as-ugm-public-research-university-239242899.jpg'
                ],
                taglines: [
                    'Keunggulan Akademik Berbasis AI',
                    'Manajemen Kampus yang Transparan',
                    'Transformasi Digital Pendidikan Tinggi',
                    'Efisiensi Data dalam Genggaman',
                    'Inovasi Tanpa Batas'
                ],
                startTimers() {
                    setInterval(() => {
                        this.activeSlide = (this.activeSlide + 1) % this.slides.length
                    }, 8000);
                    setInterval(() => {
                        this.activeTagline = (this.activeTagline + 1) % this.taglines.length
                    }, 4000);
                },
                handleMouse(e) {
                    let rect = $el.getBoundingClientRect();
                    this.mouseX = (e.clientX - rect.left - rect.width / 2) / 30;
                    this.mouseY = (e.clientY - rect.top - rect.height / 2) / 30;
                }
             }" 
             x-init="startTimers()"
             @mousemove="handleMouse($event)">
            
            <!-- Dynamic Background Images (Full Opacity) -->
            <template x-for="(slide, index) in slides" :key="index">
                <div class="absolute inset-0 transition-opacity duration-[2000ms] ease-in-out"
                     :class="activeSlide === index ? 'opacity-100' : 'opacity-0'">
                    <img :src="slide" class="w-full h-full object-cover" 
                         :class="activeSlide === index ? 'animate-ken-burns' : ''" alt="Campus View">
                </div>
            </template>

            <!-- Very Light Overlay for legibility -->
            <div class="absolute inset-0 bg-black/30 pointer-events-none"></div>

            <!-- Light Mesh Gradient for high-end feel -->
            <div class="absolute inset-0 opacity-20 animate-mesh pointer-events-none" 
                 style="background: radial-gradient(circle at 70% 30%, #7c3aed 0%, transparent 50%), radial-gradient(circle at 30% 70%, #06b6d4 0%, transparent 50%);"></div>

            <!-- Refined Glassmorphism Card -->
            <div class="relative z-10 max-w-md text-center p-8 transition-transform duration-200 ease-out"
                 :style="`transform: perspective(1000px) rotateX(${-mouseY}deg) rotateY(${mouseX}deg)`">
                
                <div class="bg-black/40 backdrop-blur-2xl border border-white/20 rounded-[2.5rem] p-10 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.7)] relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-transparent opacity-20"></div>
                    
                    <!-- Floating Icon -->
                    <div class="w-20 h-20 bg-gradient-to-br from-siakad-primary via-indigo-600 to-violet-600 rounded-[1.5rem] mx-auto mb-8 flex items-center justify-center shadow-[0_20px_40px_rgba(124,58,237,0.4)] transform animate-float border border-white/20">
                        <svg class="w-10 h-10 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>

                    <!-- Main Title -->
                    <h2 class="text-4xl font-black text-white mb-4 tracking-tighter animate-fade-in-up uppercase [text-shadow:_0_4px_8px_rgba(0,0,0,0.5)]">
                        <span class="text-gradient-premium">MY</span> <br>
                        <span class="text-2xl font-bold opacity-90 tracking-normal">SIAKAD-AI</span>
                    </h2>

                    <!-- Dynamic Subtitle -->
                    <div class="h-12 relative overflow-hidden mb-6 animate-fade-in-up" style="animation-delay: 200ms">
                        <template x-for="(tag, idx) in taglines" :key="idx">
                            <p x-show="activeTagline === idx" 
                               x-transition:enter="transition ease-out duration-500 transform"
                               x-transition:enter-start="translate-y-full opacity-0"
                               x-transition:enter-end="translate-y-0 opacity-100"
                               x-transition:leave="transition ease-in duration-500 transform absolute inset-0"
                               x-transition:leave-start="translate-y-0 opacity-100"
                               x-transition:leave-end="-translate-y-full opacity-0"
                               class="text-white text-lg font-bold leading-tight [text-shadow:_0_2px_10px_rgba(0,0,0,1)]">
                                <span x-text="tag"></span>
                            </p>
                        </template>
                    </div>

                    <p class="text-white opacity-90 text-xs leading-relaxed max-w-xs mx-auto animate-fade-in-up [text-shadow:_0_1px_4px_rgba(0,0,0,1)]" style="animation-delay: 400ms">
                        Platform manajemen akademik modern berbasis AI untuk <span class="text-white font-semibold underline decoration-siakad-primary/60">efisiensi</span> dan <span class="text-white font-semibold underline decoration-siakad-primary/60">transparansi</span>.
                    </p>

                    <!-- Indicators -->
                    <div class="mt-10 flex justify-center gap-2 animate-fade-in-up" style="animation-delay: 600ms">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="activeSlide = index" 
                                    class="h-1.5 rounded-full transition-all duration-700"
                                    :class="activeSlide === index ? 'w-10 bg-white' : 'w-2.5 bg-white/20 hover:bg-white/40'"></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
