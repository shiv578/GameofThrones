<div id="simon-says-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="simonSaysGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-volume-high text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Runic Echoes</h2>
        <p class="text-[var(--text-secondary)] mb-8">Repeat the sequence of illuminated runic symbols. Memorize the glowing patterns to secure the gate locks.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Echo the Runes</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Sequence Level: <span x-text="level"></span>/4</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- 2x2 Grid -->
        <div class="grid grid-cols-2 gap-4 w-64 h-64 mb-6">
            <button @click="playerClick(0)" 
                    class="w-28 h-28 rounded-tl-full border border-red-500/30 transition-all duration-100 cursor-pointer"
                    :class="activePad === 0 ? 'bg-red-500 shadow-[0_0_20px_rgba(239,68,68,0.8)] scale-95' : 'bg-red-950/40 hover:bg-red-900/20'"></button>
            
            <button @click="playerClick(1)" 
                    class="w-28 h-28 rounded-tr-full border border-blue-500/30 transition-all duration-100 cursor-pointer"
                    :class="activePad === 1 ? 'bg-blue-500 shadow-[0_0_20px_rgba(59,130,246,0.8)] scale-95' : 'bg-blue-950/40 hover:bg-blue-900/20'"></button>
            
            <button @click="playerClick(2)" 
                    class="w-28 h-28 rounded-bl-full border border-yellow-500/30 transition-all duration-100 cursor-pointer"
                    :class="activePad === 2 ? 'bg-yellow-500 shadow-[0_0_20px_rgba(234,179,8,0.8)] scale-95' : 'bg-yellow-950/40 hover:bg-yellow-900/20'"></button>
            
            <button @click="playerClick(3)" 
                    class="w-28 h-28 rounded-br-full border border-green-500/30 transition-all duration-100 cursor-pointer"
                    :class="activePad === 3 ? 'bg-green-500 shadow-[0_0_20px_rgba(34,197,94,0.8)] scale-95' : 'bg-green-950/40 hover:bg-green-900/20'"></button>
        </div>

        <div class="text-sm text-[var(--text-secondary)] italic">
            <span x-text="isFlashing ? 'Memorize sequence...' : 'Your turn to echo!'"></span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('simonSaysGame')) {
            Alpine.data('simonSaysGame', () => ({
                gameState: 'start',
                score: 0,
                level: 1,
                sequence: [],
                playerSequence: [],
                activePad: null,
                isFlashing: false,

                startGame() {
                    this.gameState = 'playing';
                    this.score = 0;
                    this.level = 1;
                    this.sequence = [];
                    window.GameSystem.startTimer();
                    this.nextLevel();
                },

                nextLevel() {
                    this.playerSequence = [];
                    // Add new random pad to sequence
                    this.sequence.push(Math.floor(Math.random() * 4));
                    this.flashSequence();
                },

                flashSequence() {
                    this.isFlashing = true;
                    let i = 0;
                    const interval = setInterval(() => {
                        this.activePad = this.sequence[i];
                        setTimeout(() => {
                            this.activePad = null;
                        }, 500);

                        i++;
                        if(i >= this.sequence.length) {
                            clearInterval(interval);
                            this.isFlashing = false;
                        }
                    }, 800);
                },

                playerClick(pad) {
                    if(this.isFlashing) return;
                    this.activePad = pad;
                    setTimeout(() => { this.activePad = null; }, 200);

                    this.playerSequence.push(pad);
                    const currentIdx = this.playerSequence.length - 1;

                    // Verify step
                    if(this.playerSequence[currentIdx] !== this.sequence[currentIdx]) {
                        alert('A rune collapsed! The echo failed.');
                        this.endGame(false);
                        return;
                    }

                    // Level complete?
                    if(this.playerSequence.length === this.sequence.length) {
                        this.score += 25;
                        if(this.level >= 4) {
                            this.score = 100;
                            this.endGame(true);
                        } else {
                            this.level++;
                            setTimeout(() => this.nextLevel(), 1000);
                        }
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
