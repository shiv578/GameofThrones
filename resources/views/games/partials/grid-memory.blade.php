<div id="grid-memory-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="gridMemoryGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-snowflake text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Frozen Pathway</h2>
        <p class="text-[var(--text-secondary)] mb-8">Follow the safe path through the freezing blizzard. Remember the correct grid route walked by the Rangers.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Seek Pathway</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Secure Steps Found: <span x-text="stepsChecked"></span>/3</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- 3x3 Grid -->
        <div class="got-panel p-4 rounded-xl border border-[var(--panel-border)] mb-6 bg-black/60 shadow-inner">
            <div class="grid grid-cols-3 gap-3 w-64 h-64">
                <template x-for="(cell, index) in 9" :key="index">
                    <button @click="playerClick(index)"
                            class="w-18 h-18 rounded border transition-all duration-300 cursor-pointer"
                            :class="activeTile === index ? 'bg-sky-400 border-sky-300 shadow-[0_0_15px_rgba(56,189,248,0.8)] scale-95' : 'bg-slate-900/50 border-slate-700 hover:bg-slate-800/40'"></button>
                </template>
            </div>
        </div>

        <div class="text-sm text-[var(--text-secondary)] italic">
            <span x-text="isFlashing ? 'Memorize the path of ice...' : 'Recreate the Ranger steps!'"></span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('gridMemoryGame')) {
            Alpine.data('gridMemoryGame', () => ({
                gameState: 'start',
                score: 0,
                sequence: [],
                playerSequence: [],
                activeTile: null,
                isFlashing: false,
                stepsChecked: 0,

                startGame() {
                    this.gameState = 'playing';
                    this.score = 0;
                    this.stepsChecked = 0;
                    this.sequence = [];
                    this.playerSequence = [];
                    window.GameSystem.startTimer();
                    this.generateSequence();
                },

                generateSequence() {
                    // Generate 3 random tiles in a pathway (0-8)
                    while(this.sequence.length < 3) {
                        const tile = Math.floor(Math.random() * 9);
                        if(!this.sequence.includes(tile)) this.sequence.push(tile);
                    }
                    this.flashSequence();
                },

                flashSequence() {
                    this.isFlashing = true;
                    let i = 0;
                    const interval = setInterval(() => {
                        this.activeTile = this.sequence[i];
                        setTimeout(() => {
                            this.activeTile = null;
                        }, 500);

                        i++;
                        if(i >= this.sequence.length) {
                            clearInterval(interval);
                            this.isFlashing = false;
                        }
                    }, 900);
                },

                playerClick(tile) {
                    if(this.isFlashing) return;
                    this.activeTile = tile;
                    setTimeout(() => { this.activeTile = null; }, 200);

                    this.playerSequence.push(tile);
                    const currentIdx = this.playerSequence.length - 1;
                    this.stepsChecked = this.playerSequence.length;

                    // Verify step
                    if(this.playerSequence[currentIdx] !== this.sequence[currentIdx]) {
                        alert('The ice collapsed! You stepped off the path.');
                        this.endGame(false);
                        return;
                    }

                    // Complete?
                    if(this.playerSequence.length === this.sequence.length) {
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
