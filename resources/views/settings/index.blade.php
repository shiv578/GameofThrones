<x-app-layout>
    <div class="mb-8">
        <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
            <i class="fa-solid fa-gear mr-2 text-[var(--text-accent)]"></i> Realm Settings
        </h1>
        <p class="text-[var(--text-secondary)]">Configure your experience in Conquest of Winter</p>
    </div>

    @if (session('status') === 'settings-updated')
        <div class="mb-6 p-4 rounded-lg bg-green-900/50 border border-green-500 text-green-200" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <i class="fa-solid fa-check-circle mr-2"></i> Settings saved successfully.
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Preferences -->
        <div class="got-panel p-6 sm:p-8 rounded-xl" data-aos="fade-up">
            <h2 class="text-2xl font-cinzel font-bold mb-6 border-b border-[var(--panel-border)] pb-2">Preferences</h2>
            
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')
                
                <!-- Volume -->
                <div class="mb-6">
                    <label class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Master Volume</label>
                    <div class="flex items-center space-x-4">
                        <i class="fa-solid fa-volume-low text-[var(--text-secondary)]"></i>
                        <input type="range" name="volume" min="0" max="100" value="{{ old('volume', $settings->volume) }}" class="w-full accent-[var(--accent-color)]">
                        <i class="fa-solid fa-volume-high text-[var(--text-accent)]"></i>
                    </div>
                </div>
                
                <!-- Language -->
                <div class="mb-6">
                    <label class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Language</label>
                    <select name="language" class="got-input rounded-lg w-full bg-black/50">
                        <option value="en" {{ $settings->language == 'en' ? 'selected' : '' }}>Common Tongue (English)</option>
                        <option value="es" {{ $settings->language == 'es' ? 'selected' : '' }}>High Valyrian (Spanish)</option>
                        <option value="fr" {{ $settings->language == 'fr' ? 'selected' : '' }}>Braavosi (French)</option>
                    </select>
                </div>
                <!-- MUSIC CONTROL -->
<div class="mb-6">

    <label class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-4">
        Music Control
    </label>

    <!-- ENABLE / DISABLE -->
    <div class="flex items-center justify-between mb-5">

        <span class="text-sm font-cinzel tracking-wider text-[var(--text-secondary)] uppercase">
            Enable Music
        </span>

        <label class="music-switch">
            <input type="checkbox" id="musicToggle" onchange="toggleMusicSetting()">
            <span class="music-slider"></span>
        </label>

    </div>

    <!-- MUSIC VOLUME -->
    <div class="mb-2 flex justify-between">

        <span class="text-sm font-cinzel tracking-wider text-[var(--text-secondary)] uppercase">
            Music Volume
        </span>

        <span id="musicVolumeText" class="text-[var(--text-accent)] font-bold">
            30%
        </span>

    </div>

    <div class="flex items-center space-x-4">

        <i class="fa-solid fa-volume-low text-[var(--text-secondary)]"></i>

        <input
            type="range"
            id="musicVolume"
            min="0"
            max="100"
            value="30"
            class="w-full music-range"
            oninput="changeMusicVolume(this.value)"
        >

        <i class="fa-solid fa-volume-high text-[var(--text-accent)]"></i>

    </div>

</div>
                <!-- Notifications -->
                <div class="mb-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="notifications_enabled" value="0">
                        <input type="checkbox" name="notifications_enabled" value="1" class="sr-only peer" {{ $settings->notifications_enabled ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[var(--accent-color)] relative"></div>
                        <span class="ml-3 text-sm font-cinzel tracking-wider text-[var(--text-secondary)] uppercase">Enable Ravens (Notifications)</span>
                    </label>
                </div>

                <button class="got-btn w-full rounded-lg text-sm py-3">Save Preferences</button>
            </form>
        </div>

        <!-- Security -->
        <div class="got-panel p-6 sm:p-8 rounded-xl" data-aos="fade-up" data-aos-delay="100">
            <h2 class="text-2xl font-cinzel font-bold mb-6 border-b border-[var(--panel-border)] pb-2 text-red-400">Security</h2>
            
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="volume" value="{{ $settings->volume }}">
                <input type="hidden" name="language" value="{{ $settings->language }}">
                <input type="hidden" name="notifications_enabled" value="{{ $settings->notifications_enabled }}">
                
                <div class="mb-4">
                    <label class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Current Password</label>
                    <input class="got-input rounded-lg" type="password" name="current_password">
                    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <label class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">New Password</label>
                    <input class="got-input rounded-lg" type="password" name="password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                
                <div class="mb-6">
                    <label class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Confirm New Password</label>
                    <input class="got-input rounded-lg" type="password" name="password_confirmation">
                </div>

                <button class="got-btn-outline border-red-500 text-red-500 hover:bg-red-900/50 hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] w-full rounded-lg text-sm py-3">
                    Update Password
                </button>
            </form>
        </div>
        
    </div>

    {{-- ═══ Music Settings JS ═══ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Wait a tick for MusicSystem to be available
            setTimeout(initMusicSettings, 100);
        });

        function initMusicSettings() {
            if (typeof MusicSystem === 'undefined') {
                // Retry if music-system.js hasn't loaded yet
                setTimeout(initMusicSettings, 200);
                return;
            }

            const state   = MusicSystem.getState();
            const toggle  = document.getElementById('musicToggle');
            const slider  = document.getElementById('musicVolume');
            const volText = document.getElementById('musicVolumeText');

            // Set initial values from localStorage
            if (toggle)  toggle.checked = state.enabled;
            if (slider)  slider.value   = Math.round(state.volume * 100);
            if (volText) volText.textContent = Math.round(state.volume * 100) + '%';
        }

        function toggleMusicSetting() {
            const toggle = document.getElementById('musicToggle');
            if (typeof MusicSystem !== 'undefined') {
                MusicSystem.toggle(toggle.checked);
            }
        }

        function changeMusicVolume(val) {
            const volText = document.getElementById('musicVolumeText');
            if (volText) volText.textContent = val + '%';
            if (typeof MusicSystem !== 'undefined') {
                MusicSystem.setVolume(val / 100);
            }
        }
    </script>
</x-app-layout>
