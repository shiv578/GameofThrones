<x-guest-layout>
    <div class="flex items-center justify-center h-full w-full">
        <div id="themePanel" class="got-panel bg-transparent p-10 sm:p-16 w-full max-w-lg rounded-2xl relative overflow-hidden flex flex-col justify-center min-h-[600px]" style="background-image: url('/images/fire-panel-bg.png'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;">
            <div class="text-center mb-8 relative z-10 mt-4">
                <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
                    Forgot Password
                </h1>
                <p class="text-[var(--text-secondary)] text-sm mt-2">
                    {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="relative z-10 space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Email Address</label>
                    <input id="email" class="got-input fire-text rounded-lg w-full" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="lord@winterfell.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full font-cinzel font-bold text-white bg-red-900 hover:bg-red-800 rounded-lg py-3 tracking-wider transition-colors duration-300">
                        {{ __('Email Password Reset Link') }}
                    </button>
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
        panel.style.backgroundImage = "url('/images/ice-panel-bg.png')";
    } else {
        panel.style.backgroundImage = "url('/images/fire-panel-bg.png')";
    }
}
window.addEventListener("load", updatePanelTheme);
setInterval(updatePanelTheme, 500);

function updateInputTheme() {
    const savedTheme = localStorage.getItem("got_theme") || "fire";
    const email = document.getElementById("email");
    if(email) {
        if(savedTheme === "ice") {
            email.classList.remove("fire-text");
            email.classList.add("ice-text");
        } else {
            email.classList.remove("ice-text");
            email.classList.add("fire-text");
        }
    }
}
window.addEventListener("load", updateInputTheme);
setInterval(updateInputTheme, 500);
</script>
