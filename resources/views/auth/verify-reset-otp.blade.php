<x-guest-layout>
    <div class="flex items-center justify-center h-full w-full">
        <div id="themePanel" class="got-panel bg-transparent p-10 sm:p-16 w-full max-w-lg rounded-2xl relative overflow-hidden flex flex-col justify-center min-h-[600px]" style="background-image: url('/images/fire-panel-bg.png'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;">
            <div class="text-center mb-8 relative z-10 mt-4">
                <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
                    Verification Code
                </h1>
                <p class="text-[var(--text-secondary)] text-sm mt-2">
                    {{ __('We have sent a verification code to your email. Please enter it below to reset your password.') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('verify-reset-otp') }}" class="relative z-10 space-y-6">
                @csrf

                <!-- OTP -->
                <div>
                    <label for="otp" class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Verification Code</label>
                    <input id="otp" class="got-input fire-text rounded-lg w-full text-center tracking-[0.5em] font-bold text-2xl" type="text" name="otp" required autofocus placeholder="------" maxlength="6">
                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full font-cinzel font-bold text-white bg-red-900 hover:bg-red-800 rounded-lg py-3 tracking-wider transition-colors duration-300">
                        {{ __('Verify') }}
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
    const otp = document.getElementById("otp");
    if(otp) {
        if(savedTheme === "ice") {
            otp.classList.remove("fire-text");
            otp.classList.add("ice-text");
        } else {
            otp.classList.remove("ice-text");
            otp.classList.add("fire-text");
        }
    }
}
window.addEventListener("load", updateInputTheme);
setInterval(updateInputTheme, 500);
</script>
