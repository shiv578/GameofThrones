<x-guest-layout>
    <div x-data="{ step: 1, house: '', charClass: '' }" class="flex items-center justify-center h-full w-full">
        <div class="got-panel p-8 sm:p-12 w-full max-w-2xl rounded-2xl relative overflow-hidden transition-all duration-500">
            
            <div class="text-center mb-8 relative z-10">
                <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
                    Forge Your Legacy
                </h1>
                <p class="text-[var(--text-secondary)]" x-show="step === 1">Step 1: Account Details</p>
                <p class="text-[var(--text-secondary)]" x-show="step === 2" style="display: none;">Step 2: Choose Your Allegiance</p>
                <p class="text-[var(--text-secondary)]" x-show="step === 3" style="display: none;">Step 3: Select Character Class</p>
                
                <!-- Progress Bar -->
                <div class="w-full bg-black/50 h-2 rounded-full mt-4 overflow-hidden border border-[var(--panel-border)]">
                    <div class="bg-gradient-to-r from-[var(--accent-color)] to-yellow-500 h-full transition-all duration-500" :style="`width: ${(step / 3) * 100}%`"></div>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm" class="relative z-10">
                @csrf
                <input type="hidden" name="theme_preference" x-bind:value="theme">
                <input type="hidden" name="house" x-model="house">
                <input type="hidden" name="character_class" x-model="charClass">

                <!-- STEP 1: Account Details -->
                <div x-show="step === 1" x-transition.opacity.duration.500ms class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="name" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-1">Full Name</label>
                            <input id="name" class="got-input rounded-lg" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Jon Snow">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="email" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-1">Email Address</label>
                            <input id="email" class="got-input rounded-lg" type="email" name="email" value="{{ old('email') }}" required placeholder="lord@winterfell.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-1">Password</label>
                            <input id="password" class="got-input rounded-lg" type="password" name="password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-1">Confirm Password</label>
                            <input id="password_confirmation" class="got-input rounded-lg" type="password" name="password_confirmation" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-6 flex justify-between items-center">
                        <a href="{{ route('login') }}" class="text-sm text-[var(--text-accent)] hover:underline">Already registered?</a>
                        <button type="button" @click="step = 2" class="got-btn rounded-lg">Next <i class="fa-solid fa-arrow-right ml-2"></i></button>
                    </div>
                </div>

                <!-- STEP 2: Allegiance -->
                <div x-show="step === 2" style="display: none;" x-transition.opacity.duration.500ms>
                    <div class="grid grid-cols-2 gap-4">
                        <template x-for="h in ['Stark', 'Targaryen', 'Lannister', 'Baratheon']">
                            <div @click="house = h" 
                                 class="got-panel p-4 cursor-pointer text-center rounded-xl border-2 transition-all"
                                 :class="house === h ? 'border-[var(--accent-color)] shadow-[0_0_15px_var(--accent-glow)] scale-105' : 'border-transparent hover:border-[var(--panel-border)]'">
                                <i class="fa-brands fa-d-and-d text-4xl mb-2" :class="house === h ? 'text-[var(--accent-color)]' : 'text-gray-400'"></i>
                                <h3 class="font-cinzel text-xl font-bold" x-text="h"></h3>
                            </div>
                        </template>
                    </div>

                    <div class="pt-8 flex justify-between">
                        <button type="button" @click="step = 1" class="got-btn-outline rounded-lg px-6"><i class="fa-solid fa-arrow-left mr-2"></i> Back</button>
                        <button type="button" @click="if(house) step = 3; else alert('Please choose a house');" class="got-btn rounded-lg px-6">Next <i class="fa-solid fa-arrow-right ml-2"></i></button>
                    </div>
                </div>

                <!-- STEP 3: Character Class -->
                <div x-show="step === 3" style="display: none;" x-transition.opacity.duration.500ms>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <template x-for="c in [
                            {name: 'Warrior', icon: 'fa-khanda', desc: 'Strength & Honor'},
                            {name: 'Sorcerer', icon: 'fa-hat-wizard', desc: 'Magic & Intellect'},
                            {name: 'Ranger', icon: 'fa-bow-arrow', desc: 'Agility & Precision'}
                        ]">
                            <div @click="charClass = c.name" 
                                 class="got-panel p-4 cursor-pointer text-center rounded-xl border-2 transition-all flex flex-col items-center justify-center"
                                 :class="charClass === c.name ? 'border-[var(--accent-color)] shadow-[0_0_15px_var(--accent-glow)] scale-105' : 'border-transparent hover:border-[var(--panel-border)]'">
                                <i class="fa-solid text-3xl mb-2" :class="[c.icon, charClass === c.name ? 'text-[var(--accent-color)]' : 'text-gray-400']"></i>
                                <h3 class="font-cinzel text-lg font-bold" x-text="c.name"></h3>
                                <p class="text-xs text-[var(--text-secondary)] mt-1" x-text="c.desc"></p>
                            </div>
                        </template>
                    </div>

                    <div class="pt-8 flex justify-between">
                        <button type="button" @click="step = 2" class="got-btn-outline rounded-lg px-6"><i class="fa-solid fa-arrow-left mr-2"></i> Back</button>
                        <button type="button" @click="if(charClass) document.getElementById('registerForm').submit(); else alert('Please choose a class');" class="got-btn rounded-lg px-8">
                            Create Account <i class="fa-solid fa-dragon ml-2"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>
