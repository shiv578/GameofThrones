<x-guest-layout>
    <div x-data="{ step: 1, house: '', charClass: '' }" class="flex items-center justify-center h-full w-full">
<div id="themePanel"
class="got-panel mx-auto bg-transparent p-6 sm:p-12 w-full max-w-xl rounded-2xl relative overflow-hidden"    style="
        background-image: url('{{ asset('images/fire-panel-bg.png') }}');
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
    "
>      
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

                          <!-- STE P 1: Account Details -->
                <div x-show="step === 1" x-transition.opacity.duration.500ms class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="name" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-1">Full Name</label>
                            <input id="name" class="got-input rounded-lg" type="text" name="name" value="{{ old('name') }}" autofocus placeholder="Jon Snow" @input="document.getElementById('name_error').classList.add('hidden')">
                            <span id="name_error" class="text-red-500 text-sm mt-1 block hidden"></span>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="email" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-1">Email Address</label>
                            <input id="email" class="got-input rounded-lg" type="email" name="email" value="{{ old('email') }}" placeholder="lord@winterfell.com" @input="document.getElementById('email_error').classList.add('hidden')">
                            <span id="email_error" class="text-red-500 text-sm mt-1 block hidden"></span>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-1">Password</label>
                            <input id="password" class="got-input rounded-lg" type="password" name="password" autocomplete="new-password" @input="document.getElementById('password_error').classList.add('hidden')">
                            <span id="password_error" class="text-red-500 text-sm mt-1 block hidden"></span>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-1">Confirm Password</label>
                            <input id="password_confirmation" class="got-input rounded-lg" type="password" name="password_confirmation" autocomplete="new-password" @input="document.getElementById('password_confirmation_error').classList.add('hidden')">
                            <span id="password_confirmation_error" class="text-red-500 text-sm mt-1 block hidden"></span>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm text-[var(--text-accent)] hover:underline order-2 sm:order-1">Already registered?</a>
                        <button type="button" @click="
                            let btn = $event.currentTarget;
                            let originalHtml = btn.innerHTML;
                            btn.innerHTML = 'Validating... <i class=\'fa-solid fa-spinner fa-spin ml-2\'></i>';
                            btn.disabled = true;
                            
                            // Clear previous errors
                            ['name', 'email', 'password', 'password_confirmation'].forEach(id => {
                                let el = document.getElementById(id + '_error');
                                if (el) { el.classList.add('hidden'); el.textContent = ''; }
                            });
                            
                            fetch('/register/validate-step-1', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    name: document.getElementById('name').value,
                                    email: document.getElementById('email').value,
                                    password: document.getElementById('password').value,
                                    password_confirmation: document.getElementById('password_confirmation').value
                                })
                            })
                            .then(response => {
                                if (response.ok) {
                                    step = 2;
                                } else if (response.status === 422) {
                                    return response.json().then(data => {
                                        if (data.errors) {
                                            if (data.errors.name) {
                                                let ne = document.getElementById('name_error');
                                                ne.textContent = data.errors.name[0];
                                                ne.classList.remove('hidden');
                                            }
                                            if (data.errors.email) {
                                                let ee = document.getElementById('email_error');
                                                ee.textContent = data.errors.email[0];
                                                ee.classList.remove('hidden');
                                            }
                                            if (data.errors.password) {
                                                let pe = document.getElementById('password_error');
                                                pe.textContent = data.errors.password[0];
                                                pe.classList.remove('hidden');
                                            }
                                        }
                                    });
                                } else {
                                    alert('Something went wrong. Please try again.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred during validation.');
                            })
                            .finally(() => {
                                btn.innerHTML = originalHtml;
                                btn.disabled = false;
                            });
                        " class="got-btn rounded-lg disabled:opacity-50 transition-opacity">Next <i class="fa-solid fa-arrow-right ml-2"></i></button>
                    </div>
                </div>

      <!-- STEP 2: Allegiance -->
<div x-show="step === 2" style="display: none;" x-transition.opacity.duration.500ms>

   <div class="houses-grid">

    <!-- STARK -->
    <div>
        <div @click="house = 'Stark'"
            class="house-card cursor-pointer"
            :class="house === 'Stark' ? 'active-house' : ''">

            <img src="{{ asset('images/stark.png') }}" class="house-logo" alt="">
        </div>

        <h2 class="house-name">STARK</h2>
    </div>

    <!-- LANNISTER -->
    <div>
        <div @click="house = 'Lannister'"
            class="house-card cursor-pointer"
            :class="house === 'Lannister' ? 'active-house' : ''">

            <img src="{{ asset('images/lannister.png') }}" class="house-logo" alt="">
        </div>

        <h2 class="house-name">LANNISTER</h2>
    </div>

    <!-- TARGARYEN -->
    <div>
        <div @click="house = 'Targaryen'"
            class="house-card cursor-pointer"
            :class="house === 'Targaryen' ? 'active-house' : ''">

            <img src="{{ asset('images/targaryen.png') }}" class="house-logo" alt="">
        </div>

        <h2 class="house-name">TARGARYEN</h2>
    </div>

    <!-- BARATHEON -->
    <div>
        <div @click="house = 'Baratheon'"
            class="house-card cursor-pointer"
            :class="house === 'Baratheon' ? 'active-house' : ''">

            <img src="{{ asset('images/baratheon.png') }}" class="house-logo" alt="">
        </div>

        <h2 class="house-name">BARATHEON</h2>
    </div>

</div>

    <div class="pt-8 flex justify-between">
        <button type="button" @click="step = 1"
            class="got-btn-outline rounded-lg px-6">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back
        </button>

        <button type="button"
            @click="if(house) step = 3; else alert('Please choose a house');"
            class="got-btn rounded-lg px-6">
            Next <i class="fa-solid fa-arrow-right ml-2"></i>
        </button>
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
<script>
function updatePanelTheme() {
    const savedTheme = localStorage.getItem("got_theme") || "fire";
    const panel = document.getElementById("themePanel");
    if(panel) {
        if(savedTheme === "ice") {
            panel.style.backgroundImage = "url('/images/ice-panel-bg.png')";
        } else {
            panel.style.backgroundImage = "url('/images/fire-panel-bg.png')";
        }
    }
}
window.addEventListener("load", updatePanelTheme);
setInterval(updatePanelTheme, 500);
</script>
