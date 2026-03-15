<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Welcome back</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Please enter your details to sign in.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="relative group">
            <x-input-label for="email" :value="__('Email address')" class="sr-only" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-siakad-primary transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <input id="email" 
                       class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-siakad-primary focus:border-siakad-primary transition-all duration-200 outline-none placeholder-gray-400 dark:placeholder-gray-500 font-medium sm:text-sm" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username" 
                       placeholder="Enter your email" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative group">
            <x-input-label for="password" :value="__('Password')" class="sr-only" />
            
            <div class="relative" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-siakad-primary transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input id="password" 
                       class="block w-full pl-11 pr-12 py-3.5 bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-siakad-primary focus:border-siakad-primary transition-all duration-200 outline-none placeholder-gray-400 dark:placeholder-gray-500 font-medium sm:text-sm" 
                       :type="show ? 'text' : 'password'"
                       name="password"
                       required 
                       autocomplete="current-password"
                       placeholder="Enter your password" />
                
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="h-5 w-5" x-show="!show" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg class="h-5 w-5" x-show="show" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.577-2.387M8 8.05A2.992 2.992 0 007.828 10.828l3.125 3.125a2.991 2.991 0 003.354-.055m1.515-2.074a2.992 2.992 0 00-.776-3.875" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input id="remember_me" 
                               type="checkbox" 
                               class="w-4 h-4 border border-gray-300 rounded text-siakad-primary focus:ring-siakad-primary/20 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-siakad-primary transition duration-150 ease-in-out" 
                               name="remember">
                    </div>
                    <div class="ml-2 text-sm">
                        <span class="text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors">{{ __('Remember for 30 days') }}</span>
                    </div>
                </div>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-siakad-primary hover:text-siakad-dark dark:text-cyan-400 dark:hover:text-cyan-300 transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-siakad-primary hover:bg-siakad-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-siakad-primary transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg">
                {{ __('Sign in') }}
            </button>
        </div>

        <!-- Divider -->
        <!-- Future: Social Login -->
        
        <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-8">
            Having trouble? <a href="https://wa.me/6285156064912" target="_blank" class="font-medium text-siakad-primary hover:text-siakad-dark dark:text-cyan-400 dark:hover:text-cyan-300 transition-colors">Contact Support</a>
        </p>
    </form>
    <!-- Login Credentials Helper Bubble -->
    <div x-data="{ open: false }" class="fixed bottom-6 left-6 z-50">
        <!-- Bubble Button -->
        <button @click="open = !open" 
                class="flex items-center justify-center w-14 h-14 bg-siakad-primary hover:bg-siakad-dark text-white rounded-full shadow-2xl transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-siakad-primary/30 group">
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <svg x-show="open" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Credentials Popup -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-10 scale-95"
             @click.away="open = false"
             class="absolute bottom-16 left-0 w-[350px] sm:w-[450px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden" 
             style="display: none;">
            
            <div class="p-5 bg-siakad-primary">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Credentials Guide
                </h3>
                <p class="text-blue-100 text-xs mt-1">Gunakan akun berikut untuk mencoba fitur Siakad AI.</p>
            </div>

            <div class="p-4 overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="py-2 px-2 font-semibold text-gray-900 dark:text-gray-100">Role</th>
                            <th class="py-2 px-2 font-semibold text-gray-900 dark:text-gray-100">Email</th>
                            <th class="py-2 px-2 font-semibold text-gray-900 dark:text-gray-100">Password</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        <tr>
                            <td class="py-2 px-2 font-medium text-gray-900 dark:text-gray-100">Superadmin</td>
                            <td class="py-2 px-2 text-gray-600 dark:text-gray-400">superadmin@siakad.test</td>
                            <td class="py-2 px-2"><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded">password</code></td>
                        </tr>
                        <tr>
                            <td class="py-2 px-2 font-medium text-gray-900 dark:text-gray-100">Admin Fak.</td>
                            <td class="py-2 px-2 text-gray-600 dark:text-gray-400">admin.ftik@siakad.test</td>
                            <td class="py-2 px-2"><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded">password</code></td>
                        </tr>
                        <tr>
                            <td class="py-2 px-2 font-medium text-gray-900 dark:text-gray-100">Dosen</td>
                            <td class="py-2 px-2 text-gray-600 dark:text-gray-400">dosen@siakad.test</td>
                            <td class="py-2 px-2"><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded">password</code></td>
                        </tr>
                        <tr>
                            <td class="py-2 px-2 font-medium text-gray-900 dark:text-gray-100">Mahasiswa</td>
                            <td class="py-2 px-2 text-gray-600 dark:text-gray-400">mahasiswa@siakad.test</td>
                            <td class="py-2 px-2"><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded">password</code></td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 leading-relaxed italic">
                        Tip: Klik email untuk menyalin (Segera hadir!)
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
