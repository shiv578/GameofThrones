<div id="toys-game-3-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="toysGame3()">
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-box-open text-6xl text-[var(--text-accent)] mb-6 animate-bounce"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Toy Unboxer</h2>
        <p class="text-[var(--text-secondary)] mb-8">Click the box rapidly to tear off the wrapping and reveal the toy!</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Start Unboxing</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Unboxing: <span x-text="progress"></span>%</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-full h-4 bg-black/50 rounded-full mb-10 overflow-hidden border border-[var(--panel-border)]">
            <div class="h-full bg-[var(--accent-color)] transition-all duration-100" :style="`width: ${progress}%`"></div>
        </div>

        <button @click="mash()" class="relative group outline-none">
            <div class="absolute inset-0 bg-yellow-600 rounded-xl blur opacity-75 group-hover:opacity-100 transition duration-200"></div>
            <div class="relative w-48 h-48 bg-yellow-700 border-4 border-yellow-800 rounded-xl flex items-center justify-center transform active:scale-95 transition-transform shadow-2xl overflow-hidden">
                <!-- Box details -->
                <div class="absolute w-full h-1 bg-yellow-900/50 top-1/2 -mt-0.5"></div>
                <div class="absolute w-1 h-full bg-yellow-900/50 left-1/2 -ml-0.5"></div>
                
                <i class="fa-solid fa-gift text-6xl text-yellow-300 drop-shadow-md" :class="{'animate-ping': progress > 80}"></i>
            </div>
        </button>

        <div class="mt-8 text-sm text-[var(--text-secondary)] font-bold animate-pulse">
            MASH THE BOX!
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('toysGame3')) {
            Alpine.data('toysGame3', () => ({
                gameState: 'start',
                score: 0,
                clicks: 0,
                requiredClicks: 25,
                progress: 0,
                decayInterval: null,

                startGame() {
                    this.gameState = 'playing';
                    this.score = 0;
                    this.clicks = 0;
                    this.progress = 0;
                    window.GameSystem.startTimer();
                    
                    // Slightly decay progress over time to make it a bit challenging
                    this.decayInterval = setInterval(() => {
                        if (this.clicks > 0) {
                            this.clicks = Math.max(0, this.clicks - 0.5);
                            this.updateProgress();
                        }
                    }, 500);
                },
                
                updateProgress() {
                    this.progress = Math.min(100, Math.floor((this.clicks / this.requiredClicks) * 100));
                    this.score = this.progress;
                },

                mash() {
                    this.clicks++;
                    this.updateProgress();
                    
                    if (this.progress >= 100) {
                        clearInterval(this.decayInterval);
                        this.score = 100;
                        this.endGame(true);
                    }
                },

                endGame(victory = true) {
                    this.gameState = 'ended';
                    window.GameSystem.endGame(this.score, victory);
                }
            }));
        }
    });
</script>
