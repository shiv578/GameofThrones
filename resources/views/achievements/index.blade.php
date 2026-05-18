<x-app-layout>
    <div class="mb-8 text-center" data-aos="fade-down">
        <h1 class="text-4xl sm:text-5xl font-cinzel font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-700 mb-2 drop-shadow-[0_0_15px_rgba(168,85,247,0.5)]">
            <i class="fa-solid fa-medal mr-2 text-purple-500"></i> Vault of Glory
        </h1>
        <p class="text-lg text-[var(--text-secondary)] font-cinzel tracking-wider">Achievements unlocked by the worthy</p>
    </div>

    <!-- Quick Stats -->
    <div class="flex justify-center space-x-6 mb-12" data-aos="fade-up">
        <div class="text-center">
            <div class="text-3xl font-cinzel font-bold text-white">{{ count($unlockedIds) }}</div>
            <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Unlocked</div>
        </div>
        <div class="w-px bg-[var(--panel-border)]"></div>
        <div class="text-center">
            <div class="text-3xl font-cinzel font-bold text-gray-500">{{ count($allAchievements) - count($unlockedIds) }}</div>
            <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Locked</div>
        </div>
        <div class="w-px bg-[var(--panel-border)]"></div>
        <div class="text-center">
            <div class="text-3xl font-cinzel font-bold text-[var(--accent-color)]">{{ round((count($unlockedIds) / max(1, count($allAchievements))) * 100) }}%</div>
            <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Completion</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($allAchievements as $index => $ach)
            @php
                $isUnlocked = in_array($ach->id, $unlockedIds);
            @endphp
            
            <div class="got-panel p-6 rounded-xl relative overflow-hidden transition-all duration-300 {{ $isUnlocked ? 'border-[var(--accent-color)] shadow-[0_0_15px_var(--accent-glow)]' : 'opacity-60 grayscale hover:grayscale-0' }}" data-aos="zoom-in" data-aos-delay="{{ ($index % 4) * 100 }}">
                
                <!-- Background Icon -->
                <i class="fa-solid fa-medal absolute -right-6 -bottom-6 text-9xl opacity-5 {{ $isUnlocked ? 'text-[var(--accent-color)]' : 'text-white' }}"></i>
                
                <!-- Header -->
                <div class="flex items-start justify-between mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 {{ $isUnlocked ? 'bg-[var(--accent-glow)] border-[var(--accent-color)] text-[var(--accent-color)]' : 'bg-gray-800 border-gray-600 text-gray-400' }}">
                        @if($isUnlocked)
                            <i class="fa-solid fa-check text-xl"></i>
                        @else
                            <i class="fa-solid fa-lock text-lg"></i>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-bold text-yellow-400">+{{ $ach->xp_reward }} XP</div>
                        <div class="text-[10px] text-yellow-500">+{{ $ach->coin_reward }} Coins</div>
                    </div>
                </div>
                
                <!-- Details -->
                <div class="relative z-10">
                    <h3 class="font-cinzel font-bold text-lg mb-1 {{ $isUnlocked ? 'text-white' : 'text-gray-400' }}">{{ $ach->name }}</h3>
                    <p class="text-sm text-[var(--text-secondary)]">{{ $ach->description }}</p>
                </div>
                
                <!-- Status Banner -->
                @if($isUnlocked)
                <div class="absolute top-0 right-0 bg-[var(--accent-color)] text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-bl-lg z-20">
                    Unlocked
                </div>
                @endif
                
            </div>
        @endforeach
    </div>
</x-app-layout>
