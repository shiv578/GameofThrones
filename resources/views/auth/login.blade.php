<x-guest-layout>
    <div class="flex items-center justify-center h-full">
        <div class="got-panel p-8 sm:p-12 w-full max-w-lg rounded-2xl relative overflow-hidden">
            <!-- Decorative Runes Top -->
            <div class="absolute top-0 left-0 w-full flex justify-center mt-2 opacity-30 text-2xl font-cinzel tracking-[0.5em]">
                ᚱ ᚢ ᚾ ᛖ ᛋ
            </div>

            <div class="text-center mb-8 relative z-10 mt-4">
                <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
                    Enter the Realm
                </h1>
                <p class="text-[var(--text-secondary)]">Conquest of Winter awaits your return</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="relative z-10 space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Email Address</label>
                    <input id="email" class="got-input rounded-lg" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="lord@winterfell.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Password</label>
                    <input id="password" class="got-input rounded-lg" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-600 bg-gray-900 text-[var(--accent-color)] shadow-sm focus:ring-[var(--accent-color)]" name="remember">
                        <span class="ms-2 text-sm text-[var(--text-secondary)]">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-[var(--text-accent)] hover:underline" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <div class="pt-4">
                    <button class="got-btn w-full rounded-lg text-lg py-4">
                        <i class="fa-solid fa-dragon mr-2"></i> Log In
                    </button>
                </div>
                
                <div class="text-center mt-6">
                    <p class="text-[var(--text-secondary)]">New to the realm?</p>
                    <a href="{{ route('register') }}" class="text-[var(--text-accent)] font-bold hover:underline font-cinzel tracking-wider block mt-2">Forge Your Legacy</a>
                </div>
            </form>

            <!-- Decorative Runes Bottom -->
            <div class="absolute bottom-0 left-0 w-full flex justify-center mb-2 opacity-30 text-2xl font-cinzel tracking-[0.5em] rotate-180">
                ᚱ ᚢ ᚾ ᛖ ᛋ
            </div>
        </div>
    </div>
</x-guest-layout>
