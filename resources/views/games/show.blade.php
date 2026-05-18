<x-app-layout>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('games.index') }}" class="text-[var(--text-secondary)] hover:text-[var(--text-accent)] transition-colors text-sm font-bold uppercase tracking-wider mb-2 inline-block">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Arena
            </a>
            <h1 class="text-3xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)]">
                {{ $game->name }}
            </h1>
        </div>
        
        <div class="flex space-x-4">
            <div class="got-panel px-4 py-2 rounded-lg text-center">
                <div class="text-[10px] text-[var(--text-secondary)] uppercase tracking-wider font-bold">Personal Best</div>
                <div class="font-cinzel font-bold text-yellow-400 text-lg">{{ $highScore }}</div>
            </div>
            <div class="got-panel px-4 py-2 rounded-lg text-center">
                <div class="text-[10px] text-[var(--text-secondary)] uppercase tracking-wider font-bold">Max Score</div>
                <div class="font-cinzel font-bold text-white text-lg">{{ $game->max_score }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Main Game Area -->
        <div class="lg:col-span-3">
            <div class="got-panel p-1 rounded-xl shadow-[0_0_30px_rgba(0,0,0,0.5)] border border-[var(--panel-border)] min-h-[600px] flex flex-col relative overflow-hidden" id="game-container">
                
                <!-- Game Header Bar -->
                <div class="bg-black/40 border-b border-[var(--panel-border)] p-3 flex justify-between items-center z-10 shrink-0">
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-1 rounded bg-[var(--bg-primary)] border border-[var(--panel-border)] text-xs font-bold uppercase text-[var(--text-accent)]">
                            {{ $game->category }}
                        </span>
                        <span class="text-sm font-bold text-gray-300">
                            Difficulty: <span class="uppercase {{ $game->difficulty == 'easy' ? 'text-green-400' : ($game->difficulty == 'medium' ? 'text-yellow-400' : 'text-red-400') }}">{{ $game->difficulty }}</span>
                        </span>
                    </div>
                    
                    <!-- Timer Display (Controlled by game script) -->
                    <div class="font-cinzel font-bold text-xl text-white flex items-center" id="game-timer">
                        <i class="fa-regular fa-clock mr-2 text-[var(--text-secondary)]"></i>
                        <span id="time-display">00:00</span>
                    </div>
                </div>

                <!-- Actual Game Canvas/UI goes here -->
                <div class="flex-1 relative z-0">
                    @includeIf('games.partials.' . $game->slug)
                    
                    @if(!view()->exists('games.partials.' . $game->slug))
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center bg-black/60">
                            <i class="fa-solid fa-hammer text-6xl text-[var(--text-secondary)] mb-6 opacity-50"></i>
                            <h2 class="text-3xl font-cinzel font-bold mb-4">Under Construction</h2>
                            <p class="text-lg text-[var(--text-secondary)] mb-8 max-w-lg">The maesters are currently forging this challenge. Please return later.</p>
                            <a href="{{ route('games.index') }}" class="got-btn-outline rounded-lg">Return to Arena</a>
                        </div>
                    @endif
                </div>

                <!-- Game Over Overlay (Hidden by default) -->
                <div id="game-over-overlay" class="absolute inset-0 bg-black/90 backdrop-blur-sm z-50 flex flex-col items-center justify-center hidden opacity-0 transition-opacity duration-500">
                    <h2 id="game-result-title" class="text-5xl font-cinzel font-bold text-yellow-500 mb-2 drop-shadow-[0_0_15px_rgba(234,179,8,0.5)]">VICTORY</h2>
                    <p class="text-xl text-[var(--text-secondary)] mb-8 font-cinzel">The Realm Remembers</p>
                    
                    <div class="grid grid-cols-2 gap-6 mb-8 text-center w-full max-w-sm">
                        <div class="got-panel p-4 rounded-xl border border-[var(--accent-color)] shadow-[0_0_15px_var(--accent-glow)]">
                            <div class="text-sm text-[var(--text-secondary)] uppercase tracking-wider font-bold mb-1">Score</div>
                            <div class="text-3xl font-cinzel font-bold text-white" id="final-score">0</div>
                        </div>
                        <div class="got-panel p-4 rounded-xl border border-[var(--panel-border)]">
                            <div class="text-sm text-[var(--text-secondary)] uppercase tracking-wider font-bold mb-1">Time</div>
                            <div class="text-3xl font-cinzel font-bold text-white" id="final-time">0s</div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-6 text-center mb-10">
                        <div>
                            <div class="text-yellow-400 font-bold text-xl mb-1" id="reward-xp">+0 XP</div>
                            <i class="fa-solid fa-star text-yellow-400"></i>
                        </div>
                        <div>
                            <div class="text-yellow-500 font-bold text-xl mb-1" id="reward-coins">+0 Coins</div>
                            <i class="fa-solid fa-coins text-yellow-500"></i>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button onclick="location.reload()" class="got-btn rounded-lg"><i class="fa-solid fa-rotate-right mr-2"></i> Play Again</button>
                        <a href="{{ route('games.index') }}" class="got-btn-outline rounded-lg">Exit</a>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Sidebar: Info & History -->
        <div class="lg:col-span-1 space-y-6">
            
            <div class="got-panel p-6 rounded-xl">
                <h3 class="font-cinzel font-bold text-lg mb-4 border-b border-[var(--panel-border)] pb-2">Mission Briefing</h3>
                <p class="text-sm text-gray-300 mb-4 leading-relaxed">{{ $game->description }}</p>
                <div class="bg-black/30 p-3 rounded border border-[var(--panel-border)] text-xs text-[var(--text-secondary)]">
                    <i class="fa-solid fa-circle-info mr-1 text-[var(--text-accent)]"></i> Completing this challenge rewards XP and Coins based on your performance.
                </div>
            </div>

            <div class="got-panel p-6 rounded-xl">
                <h3 class="font-cinzel font-bold text-lg mb-4 border-b border-[var(--panel-border)] pb-2"><i class="fa-solid fa-clock-rotate-left mr-2 text-[var(--text-accent)]"></i> Recent Attempts</h3>
                
                <div class="space-y-3">
                    @forelse($recentScores as $rs)
                    <div class="flex items-center justify-between bg-black/20 p-2 rounded border border-[var(--panel-border)]">
                        <div>
                            <div class="text-sm font-bold text-white">{{ $rs->score }} pts</div>
                            <div class="text-[10px] text-[var(--text-secondary)]">{{ $rs->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-bold text-yellow-400">+{{ $rs->xp_earned }} XP</div>
                            <div class="text-[10px] text-gray-400"><i class="fa-regular fa-clock mr-1"></i>{{ $rs->time_taken }}s</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-sm text-[var(--text-secondary)] italic text-center py-2">No attempts yet. Claim the first victory!</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Global Game Script Wrapper -->
    <script>
        window.GameSystem = {
            slug: '{{ $game->slug }}',
            maxScore: {{ $game->max_score }},
            startTime: null,
            timerInterval: null,
            timeElapsed: 0,
            
            startTimer() {
                this.startTime = Date.now();
                this.timerInterval = setInterval(() => {
                    this.timeElapsed = Math.floor((Date.now() - this.startTime) / 1000);
                    const mins = Math.floor(this.timeElapsed / 60).toString().padStart(2, '0');
                    const secs = (this.timeElapsed % 60).toString().padStart(2, '0');
                    document.getElementById('time-display').innerText = `${mins}:${secs}`;
                }, 1000);
            },
            
            stopTimer() {
                clearInterval(this.timerInterval);
                return this.timeElapsed;
            },
            
            endGame(score, isVictory = true) {
                const timeTaken = this.stopTimer();
                
                // Show Overlay
                const overlay = document.getElementById('game-over-overlay');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 50);
                
                document.getElementById('game-result-title').innerText = isVictory ? 'VICTORY' : 'DEFEAT';
                document.getElementById('game-result-title').className = isVictory 
                    ? 'text-5xl font-cinzel font-bold text-yellow-500 mb-2 drop-shadow-[0_0_15px_rgba(234,179,8,0.5)]'
                    : 'text-5xl font-cinzel font-bold text-red-500 mb-2 drop-shadow-[0_0_15px_rgba(239,68,68,0.5)]';
                
                document.getElementById('final-score').innerText = score;
                document.getElementById('final-time').innerText = timeTaken + 's';
                
                // Save Score via API
                fetch(`{{ route('games.score', $game->slug) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        score: score,
                        time_taken: timeTaken,
                        difficulty: '{{ $game->difficulty }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('reward-xp').innerText = '+' + data.xp_earned + ' XP';
                        document.getElementById('reward-coins').innerText = '+' + data.coins_earned + ' Coins';
                    }
                });
            }
        };
    </script>
</x-app-layout>
