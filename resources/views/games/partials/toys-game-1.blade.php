<div id="toys-game-1-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="toysGame1()">
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-robot text-6xl text-[var(--text-accent)] mb-6 animate-pulse"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Toy Collector</h2>
        <p class="text-[var(--text-secondary)] mb-8">Collect 10 toys as fast as you can to win!</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Start Collecting</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Toys: <span x-text="clicks"></span>/10</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <div class="relative w-64 h-64 border border-[var(--panel-border)] rounded-xl bg-black/50 overflow-hidden mb-6">
            <button @click="collectToy()" class="absolute w-16 h-16 bg-[var(--accent-color)] rounded-full shadow-[0_0_15px_var(--accent-glow)] flex items-center justify-center transition-all duration-200" :style="`left: ${toyX}px; top: ${toyY}px;`">
                <i class="fa-solid fa-puzzle-piece text-white text-2xl"></i>
            </button>
        </div>

        <div class="text-sm text-[var(--text-secondary)] italic">
            Click the toy to collect it!
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('toysGame1')) {
            Alpine.data('toysGame1', () => ({
                gameState: 'start',
                score: 0,
                clicks: 0,
                toyX: 96, // center of 256x256 (256-64)/2
                toyY: 96,

                startGame() {
                    this.gameState = 'playing';
                    this.score = 0;
                    this.clicks = 0;
                    window.GameSystem.startTimer();
                    this.moveToy();
                },

                moveToy() {
                    // Container is 256x256, button is 64x64, max left/top is 192
                    this.toyX = Math.floor(Math.random() * 192);
                    this.toyY = Math.floor(Math.random() * 192);
                },

                collectToy() {
                    this.clicks++;
                    this.score += 10;
                    
                    if (this.clicks >= 10) {
                        this.score = 100;
                        this.endGame(true);
                    } else {
                        this.moveToy();
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
