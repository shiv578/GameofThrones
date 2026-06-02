<x-guest-layout>
    <div class="flex items-center justify-center h-full">
<div 
id="themePanel"
class="got-panel p-6 sm:p-12 w-full max-w-lg rounded-2xl relative overflow-hidden mx-auto" style="background-size: 100% 100%; background-position: center; background-repeat: no-repeat;">            <!-- Decorative Runes Top -->


            <div class="text-center mb-8 relative z-10 mt-4">
                <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
                    Enter the Realm
                </h1>
                <p class="text-[var(--text-secondary)]">𝐂𝐨𝐧𝐪𝐮𝐞𝐬𝐭 𝐨𝐟 𝐖𝐢𝐧𝐭𝐞𝐫 𝐚𝐰𝐚𝐢𝐭𝐬 𝐲𝐨𝐮𝐫 𝐫𝐞𝐭𝐮𝐫𝐧</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="relative z-10 space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Email Address</label>
               <input id="email" class="got-input fire-text rounded-lg" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="lord@winterfell.com">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Password</label>
                      <input id="password" class="got-input fire-text rounded-lg" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">                </div>

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

<div class="pt-6">
<button type="submit" class="image-login-btn mx-auto block">
  
    <img   
        id="themeLoginBtn"
        src="{{ asset('images/fire-login.png') }}"               
        alt="Login Button"
        class="max-w-full h-auto"
    >

</button>
</div>
<script>

function updateLoginButtonTheme() {

    const loginBtn = document.getElementById("themeLoginBtn");

    const savedTheme = localStorage.getItem("got_theme") || "fire";

    if(savedTheme === "ice") {

        loginBtn.src = "/images/ice-login.png";

    } else {

        loginBtn.src = "/images/fire-login.png";

    }
}

window.addEventListener("load", updateLoginButtonTheme);

window.addEventListener("storage", updateLoginButtonTheme);

setInterval(updateLoginButtonTheme, 500);

</script>


<script>

function updateInputTheme() {

    const savedTheme = localStorage.getItem("got_theme") || "fire";

    const email = document.getElementById("email");
    const password = document.getElementById("password");

    if(savedTheme === "ice") {

        email.classList.remove("fire-text");
        password.classList.remove("fire-text");

        email.classList.add("ice-text");
        password.classList.add("ice-text");

    } else {

        email.classList.remove("ice-text");
        password.classList.remove("ice-text");

        email.classList.add("fire-text");
        password.classList.add("fire-text");
    }
}

window.addEventListener("load", updateInputTheme);

setInterval(updateInputTheme, 500);

</script>
<script>

function createParticle(x, y, theme) {

    const particle = document.createElement("span");

    particle.classList.add("typing-particle");
    particle.style.width = "4px";
particle.style.height = "4px";

    document.body.appendChild(particle);

    particle.style.left = x + "px";
    particle.style.top = y + "px";

    if(theme === "ice") {

        particle.style.background = "#7dd3fc";
        particle.style.boxShadow = `
            0 0 8px #7dd3fc,
            0 0 16px #38bdf8,
            0 0 24px #0ea5e9
        `;

    } else {

        particle.style.background = "#ff6600";
        particle.style.boxShadow = `
            0 0 8px #ff6600,
            0 0 16px #ff3300,
            0 0 24px #ff0000
        `;
    }

    const randomX = (Math.random() - 0.5) * 120;
    const randomY = Math.random() * -120;

    particle.animate([
        {
            transform: "translate(0,0) scale(1)",
            opacity: 1
        },
        {
            transform: `translate(${randomX}px, ${randomY}px) scale(0)`,
            opacity: 0
        }
    ], {
        duration: 1200,
        easing: "ease-out"
    });

    setTimeout(() => {
        particle.remove();
    }, 1200);
}


function typingEffect(e) {

    const savedTheme = localStorage.getItem("got_theme") || "fire";

    const rect = e.target.getBoundingClientRect();

    for(let i = 0; i < 6; i++) {

        createParticle(
            rect.left + Math.random() * rect.width,
            rect.top + rect.height / 2,
            savedTheme
        );
    }
}


document.getElementById("email").addEventListener("input", typingEffect);

document.getElementById("password").addEventListener("input", typingEffect);

</script>
                   <p class="text-[var(--text-secondary)]">New to the realm?</p>
                    <a href="{{ route('register') }}" class="text-[var(--text-accent)] font-bold hover:underline font-cinzel tracking-wider block mt-2">Forge Your Legacy</a>
                </div>
            </form>


        </div>
    </div>
</x-guest-layout>
<script>

function updatePanelTheme() {

    const savedTheme = localStorage.getItem("got_theme") || "fire";

    const panel = document.getElementById("themePanel");

    if(savedTheme === "ice") {

        panel.style.backgroundImage =
        "url('/images/ice-panel-bg.png')";

    } else {

        panel.style.backgroundImage =
        "url('/images/fire-panel-bg.png')";
    }
}

window.addEventListener("load", updatePanelTheme);

setInterval(updatePanelTheme, 500);

</script>
