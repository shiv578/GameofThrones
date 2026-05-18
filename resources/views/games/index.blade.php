<x-app-layout>
    <div class="mb-8" data-aos="fade-down">
        <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
            <i class="fa-solid fa-gamepad mr-2 text-[var(--text-accent)]"></i> Games Arena
        </h1>
        <p class="text-[var(--text-secondary)]">Test your mind, prove your worth, and conquer the realm.</p>
    </div>

    <!-- Category Filters -->
    <div class="flex space-x-4 mb-8 overflow-x-auto pb-2" data-aos="fade-up">
        <a href="#all" class="got-btn-outline rounded-full !px-6 !py-2 whitespace-nowrap active border-[var(--accent-color)] text-[var(--accent-color)] shadow-[0_0_10px_var(--accent-glow)]">All Games</a>
        @foreach($categories as $cat)
            <a href="#{{ strtolower($cat) }}" class="got-btn-outline border-transparent text-gray-400 hover:text-[var(--text-accent)] rounded-full !px-6 !py-2 whitespace-nowrap">{{ ucfirst($cat) }}</a>
        @endforeach
    </div>

    <!-- Games Grid by Category -->
    <div class="space-y-12">
        @foreach($games as $category => $categoryGames)
        <div id="{{ strtolower($category) }}" class="pt-4" data-aos="fade-up">
            <h2 class="text-2xl font-cinzel font-bold mb-6 border-b border-[var(--panel-border)] pb-2 capitalize">
                {{ $category }} Category
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categoryGames as $game)
                <div class="got-panel rounded-xl overflow-hidden group hover:border-[var(--accent-color)] transition-all duration-300 transform hover:-translate-y-2 hover:shadow-[0_10px_30px_rgba(0,0,0,0.8)]">
                    <!-- Game Image Banner Placeholder -->
                    <div class="h-32 bg-black/60 relative overflow-hidden">
                        <!-- Abstract category color background -->
                        <div class="absolute inset-0 opacity-40 
                            @if($category=='brain') bg-blue-600
                            @elseif($category=='puzzle') bg-green-600
                            @elseif($category=='quiz') bg-yellow-600
                            @elseif($category=='strategy') bg-red-600
                            @else bg-purple-600 @endif">
                        </div>
                        
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fa-solid 
                                @if($category=='brain') fa-brain text-blue-300
                                @elseif($category=='puzzle') fa-puzzle-piece text-green-300
                                @elseif($category=='quiz') fa-clipboard-question text-yellow-300
                                @elseif($category=='strategy') fa-chess-knight text-red-300
                                @else fa-eye text-purple-300 @endif 
                                text-5xl opacity-50 group-hover:scale-125 transition-transform duration-500"></i>
                        </div>
                        
                        <!-- Difficulty Badge -->
                        <div class="absolute top-2 right-2 px-2 py-1 rounded bg-black/80 border border-[var(--panel-border)] text-xs font-bold uppercase
                            @if($game->difficulty == 'easy') text-green-400
                            @elseif($game->difficulty == 'medium') text-yellow-400
                            @else text-red-400 @endif">
                            {{ $game->difficulty }}
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-cinzel font-bold mb-2 group-hover:text-[var(--text-accent)] transition-colors">{{ $game->name }}</h3>
                        <p class="text-sm text-[var(--text-secondary)] mb-6 h-10 overflow-hidden">{{ $game->description }}</p>
                        
                        <a href="{{ route('games.show', $game->slug) }}" class="got-btn w-full rounded-lg text-sm block text-center">
                            Enter Challenge <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
