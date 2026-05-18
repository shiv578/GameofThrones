<div id="iq-game-container" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center" x-data="iqGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-brain text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">IQ Challenge</h2>
        <p class="text-[var(--text-secondary)] mb-8">Identify the pattern and select the correct next number in the sequence. You have 10 rounds to prove your intellect to the realm.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Begin Challenge</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-2xl">
        <div class="flex justify-between items-center mb-8">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Round <span x-text="currentRound"></span>/10</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <div class="got-panel p-8 rounded-xl border border-[var(--panel-border)] mb-8 relative">
            <!-- Progress bar for round time if we wanted one, keeping it simple for now -->
            <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold absolute top-4 left-4">The Sequence</div>
            
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <template x-for="(num, index) in currentSequence" :key="index">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-black/50 border-2 border-[var(--panel-border)] flex items-center justify-center shadow-inner">
                        <span class="text-2xl sm:text-3xl font-cinzel font-bold text-white" x-text="num"></span>
                    </div>
                </template>
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-[var(--accent-glow)] border-2 border-[var(--accent-color)] border-dashed flex items-center justify-center shadow-[0_0_15px_var(--accent-glow)]">
                    <i class="fa-solid fa-question text-2xl text-[var(--text-accent)] animate-pulse"></i>
                </div>
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
        Alpine.data('iqGame', () => ({
            gameState: 'start', // start, playing, ended
            currentRound: 1,
            score: 0,
            currentSequence: [],
            correctAnswer: 0,
            options: [],
            
            // Pattern Generators
            patterns: [
                (n) => n * 2,           // 2, 4, 8, 16
                (n) => n + 3,           // 3, 6, 9, 12
                (n) => n * n,           // 1, 4, 9, 16
                (n, prev) => n + prev,  // Fibonacci
                (n) => (n * 3) - 1      // 2, 5, 8, 11
            ],

            startGame() {
                this.gameState = 'playing';
                this.currentRound = 1;
                this.score = 0;
                window.GameSystem.startTimer();
                this.generateRound();
            },

            generateRound() {
                const type = Math.floor(Math.random() * 4); // Pick a pattern type
                let seq = [];
                let nextNum = 0;
                let start = Math.floor(Math.random() * 5) + 1;
                
                if(type === 0) { // Arithmetic +x
                    const diff = Math.floor(Math.random() * 10) + 2;
                    for(let i=0; i<4; i++) seq.push(start + (i*diff));
                    nextNum = start + (4*diff);
                } else if (type === 1) { // Geometric *x
                    const mult = Math.floor(Math.random() * 2) + 2;
                    let curr = start;
                    for(let i=0; i<4; i++) { seq.push(curr); curr *= mult; }
                    nextNum = curr;
                } else if (type === 2) { // Squares
                    start = Math.floor(Math.random() * 5) + 2;
                    for(let i=0; i<4; i++) seq.push((start+i) * (start+i));
                    nextNum = (start+4) * (start+4);
                } else { // Fibonacci-ish
                    let a = Math.floor(Math.random() * 5) + 1;
                    let b = Math.floor(Math.random() * 5) + 1;
                    seq.push(a, b);
                    for(let i=2; i<4; i++) {
                        let c = a + b;
                        seq.push(c);
                        a = b; b = c;
                    }
                    nextNum = a + b;
                }

                this.currentSequence = seq;
                this.correctAnswer = nextNum;
                this.generateOptions(nextNum);
            },

            generateOptions(correct) {
                let opts = [correct];
                while(opts.length < 4) {
                    let offset = Math.floor(Math.random() * 20) - 10;
                    if(offset === 0) offset = 1;
                    let fake = correct + offset;
                    if(fake > 0 && !opts.includes(fake)) opts.push(fake);
                }
                // Shuffle
                this.options = opts.sort(() => Math.random() - 0.5);
            },

            makeGuess(guessedNum) {
                if(guessedNum === this.correctAnswer) {
                    this.score += 10;
                    // Could play a success sound here
                } else {
                    // Could play a fail sound here
                }

                if(this.currentRound >= 10) {
                    this.endGame();
                } else {
                    this.currentRound++;
                    this.generateRound();
                }
            },

            endGame() {
                this.gameState = 'ended';
                // Call global GameSystem
                window.GameSystem.endGame(this.score, this.score > 0);
            }
        }));
    });
</script>
