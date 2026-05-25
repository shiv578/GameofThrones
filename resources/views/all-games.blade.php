<x-app-layout>

<div class="p-6 md:p-8 max-w-[1600px] mx-auto">

    <h1 class="text-4xl md:text-5xl font-bold text-orange-400 mb-8 font-cinzel text-center">
        ⚔ ALL GAMES ⚔
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">

        @foreach($games as $game)
        <div class="game-card relative overflow-hidden group">
            
            <!-- Game Image Header -->
            <div class="h-36 overflow-hidden relative border-b border-orange-500/30">
                @php
                    $imageName = str_replace('-', '_', $game->slug) . '.png';
                    $imagePath = public_path('images/games/' . $imageName);
                    $imageUrl = file_exists($imagePath) ? asset('images/games/' . $imageName) : 'https://placehold.co/600x400/1a0a2e/ffcc00?text=' . urlencode($game->name);
                @endphp
                <img src="{{ $imageUrl }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" alt="{{ $game->name }}">
                
                <!-- Overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0000] via-black/30 to-transparent"></div>
                
                <!-- Level Badge -->
                <div class="absolute top-3 right-3 bg-black/80 border border-yellow-500/60 rounded px-2.5 py-1 flex items-center shadow-[0_0_10px_rgba(255,200,0,0.5)] z-10 backdrop-blur-sm">
                    <i class="fa-solid fa-star text-yellow-400 text-[10px] mr-1.5"></i>
                    <span class="text-yellow-400 font-cinzel font-black text-xs tracking-wider">LVL {{ $game->level }}</span>
                </div>
            </div>

            <!-- Game Info -->
            <div class="p-4 flex-1 flex flex-col justify-between z-10 bg-gradient-to-b from-[#0a0000] to-[#120000]">
                <div>
                    <h2 class="text-xl font-bold text-white font-cinzel mb-1.5 group-hover:text-yellow-400 transition">{{ $game->name }}</h2>
                    <p class="text-gray-400 text-xs line-clamp-2 mb-3 leading-relaxed">{{ $game->description }}</p>
                    
                    <div class="mini-tags mb-4">
                        <span>{{ ucfirst($game->category) }}</span>
                        <span>{{ ucfirst($game->difficulty) }}</span>
                    </div>
                </div>

                <!-- Progress & Action -->
                <div>
                    <div class="mb-3.5 bg-black/50 p-2.5 rounded-lg border border-red-900/30">
                        <div class="flex justify-between text-[10px] text-gray-400 mb-1.5 font-bold uppercase tracking-wider">
                            <span>EXP to Lvl {{ min(100, $game->level + 1) }}</span>
                            <span class="text-orange-400">{{ $game->level == 100 ? 'MAX' : round($game->progress_to_next) . '%' }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-gray-900 rounded-full overflow-hidden border border-gray-800 shadow-inner">
                            <div class="h-full bg-gradient-to-r from-red-600 via-orange-500 to-yellow-400 rounded-full shadow-[0_0_5px_rgba(255,150,0,0.5)]" style="width: {{ $game->level == 100 ? 100 : $game->progress_to_next }}%"></div>
                        </div>
                    </div>
                    
                    <a href="{{ route('games.show', $game->slug) }}" class="block w-full text-center play-btn py-2.5 rounded-lg text-sm font-black font-cinzel tracking-widest uppercase">
                        ▶ PLAY NOW
                    </a>
                </div>
            </div>
        </div>
        @endforeach

    </div>

</div>

<style>
.game-card {
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(255, 69, 0, 0.4);
    border-radius: 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.6);
    height: 100%;
}

.game-card:hover {
    transform: translateY(-6px);
    border-color: rgba(255, 153, 0, 0.9);
    box-shadow: 0 12px 30px rgba(255, 69, 0, 0.3), inset 0 0 15px rgba(255, 100, 0, 0.1);
}

.mini-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.mini-tags span {
    padding: 3px 8px;
    border-radius: 4px;
    background: rgba(255, 69, 0, 0.15);
    border: 1px solid rgba(255, 69, 0, 0.3);
    color: #ffaa66;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.play-btn {
    background: linear-gradient(135deg, #7a0000 0%, #cc3300 50%, #ff6600 100%);
    color: white;
    text-shadow: 0 1px 3px rgba(0,0,0,0.8);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 150, 0, 0.4);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.2), 0 2px 5px rgba(0,0,0,0.5);
}

.play-btn:hover {
    background: linear-gradient(135deg, #990000 0%, #ff4500 50%, #ff8800 100%);
    box-shadow: 0 0 20px rgba(255, 69, 0, 0.6), inset 0 1px 0 rgba(255,255,255,0.3);
    transform: scale(1.02);
}
</style>

</x-app-layout>