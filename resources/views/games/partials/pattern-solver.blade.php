<div id="pattern-solver-container" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center" x-data="patternSolverGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-calculator text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Pattern Solver</h2>
        <p class="text-[var(--text-secondary)] mb-8">Decipher the missing rune in the matrix. Align the patterns to unlock the vault. Complete 5 matrix riddles.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Begin Deciphering</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-xl">
        <div class="flex justify-between items-center mb-8">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Riddle <span x-text="currentRound"></span>/5</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- 2x2 Grid Matrix Puzzle -->
        <div class="got-panel p-8 rounded-xl border border-[var(--panel-border)] mb-8 relative">
            <div class="grid grid-cols-2 gap-6 max-w-[280px] mx-auto">
                <div class="w-24 h-24 rounded-lg bg-black/60 border border-[var(--panel-border)] flex items-center justify-center font-cinzel font-bold text-3xl text-white shadow-inner" x-text="matrix[0]"></div>
                <div class="w-24 h-24 rounded-lg bg-black/60 border border-[var(--panel-border)] flex items-center justify-center font-cinzel font-bold text-3xl text-white shadow-inner" x-text="matrix[1]"></div>
                <div class="w-24 h-24 rounded-lg bg-black/60 border border-[var(--panel-border)] flex items-center justify-center font-cinzel font-bold text-3xl text-white shadow-inner" x-text="matrix[2]"></div>
                <div class="w-24 h-24 rounded-lg bg-[var(--accent-glow)] border-2 border-[var(--accent-color)] border-dashed flex items-center justify-center shadow-[0_0_15px_var(--accent-glow)] font-cinzel font-bold text-3xl text-[var(--text-accent)]">?</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <template x-for="(opt, index) in options" :key="index">
                <button @click="makeGuess(opt)" 
                        class="got-btn-outline rounded-lg py-4 text-xl font-bold font-cinzel transition-all hover:-translate-y-1 hover:shadow-[0_5px_15px_var(--accent-glow)]"
                        x-text="opt"></button>
            </template>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('patternSolverGame')) {
            Alpine.data('patternSolverGame', () => ({
                gameState: 'start',
                currentRound: 1,
                score: 0,
                matrix: [0, 0, 0],
                correctAnswer: 0,
                options: [],

                startGame() {
                    this.gameState = 'playing';
                    this.currentRound = 1;
                    this.score = 0;
                    window.GameSystem.startTimer();
                    this.generateRound();
                },

                generateRound() {
                    const ruleType = Math.floor(Math.random() * 3); // 0: Sum, 1: Product, 2: Difference
                    let a = Math.floor(Math.random() * 12) + 2;
                    let b = Math.floor(Math.random() * 12) + 2;
                    let c, d;

                    if(ruleType === 0) { // Top + Left relationship
                        c = Math.floor(Math.random() * 12) + 2;
                        d = b + (c - a); // custom addition pattern
                        this.matrix = [a, b, c];
                        this.correctAnswer = d;
                    } else if(ruleType === 1) { // Multiplicative
                        c = a * 2;
                        d = b * 2;
                        this.matrix = [a, b, c];
                        this.correctAnswer = d;
                    } else { // Grid diagonal match
                        c = a + 5;
                        d = b + 5;
                        this.matrix = [a, b, c];
                        this.correctAnswer = d;
                    }

                    this.generateOptions(this.correctAnswer);
                },

                generateOptions(correct) {
                    let opts = [correct];
                    while(opts.length < 4) {
                        let offset = Math.floor(Math.random() * 10) - 5;
                        if(offset === 0) offset = 1;
                        let fake = correct + offset;
                        if(fake > 0 && !opts.includes(fake)) opts.push(fake);
                    }
                    this.options = opts.sort(() => Math.random() - 0.5);
                },

                makeGuess(guessedNum) {
                    if(guessedNum === this.correctAnswer) {
                        this.score += 20;
                    }
                    if(this.currentRound >= 5) {
                        this.endGame();
                    } else {
                        this.currentRound++;
                        this.generateRound();
                    }
                },

                endGame() {
                    this.gameState = 'ended';
                    window.GameSystem.endGame(this.score, this.score > 0);
                }
            }));
        }
    });
</script>
