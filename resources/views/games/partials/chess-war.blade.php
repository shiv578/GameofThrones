<div id="chess-war-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="chessWarGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-chess text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Chess Tactics</h2>
        <p class="text-[var(--text-secondary)] mb-8">Deploy your knights and queens. Solve 3 tactical medieval chess setups to prove your military strategy.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Command the Board</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-xl">
        <div class="flex justify-between items-center mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Tactical Setup <span x-text="currentQuestion + 1"></span>/3</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- Question Panel -->
        <div class="got-panel p-8 rounded-xl border border-[var(--panel-border)] mb-8 relative bg-black/40">
            <div class="text-lg font-cinzel font-bold text-white mb-4" x-text="questions[currentQuestion].q"></div>
            <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold mb-2">Scenario Hint:</div>
            <div class="text-sm text-yellow-400 italic" x-text="questions[currentQuestion].hint"></div>
        </div>

        <!-- Options Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <template x-for="(opt, index) in questions[currentQuestion].options" :key="index">
                <button @click="makeGuess(opt)" 
                        class="got-btn-outline rounded-lg py-4 px-6 text-left text-sm font-bold font-cinzel transition-all hover:bg-[var(--accent-color)] hover:text-white"
                        x-text="opt"></button>
            </template>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('chessWarGame')) {
            Alpine.data('chessWarGame', () => ({
                gameState: 'start',
                currentQuestion: 0,
                score: 0,
                questions: [
                    {
                        q: "You have a Queen at h5 and a Bishop at c4. The opponent's King is at e8, guarded by a Pawn at f7. What is the winning checkmate in one move?",
                        hint: "Target the weakest pawn next to the king.",
                        options: ["Qxf7#", "Qh7#", "Bxf7+", "Qe5+"],
                        a: "Qxf7#"
                    },
                    {
                        q: "A Knight at f6 checks the King at h8. The King has no escape squares and the Knight cannot be captured. What type of checkmate is this?",
                        hint: "The king is entirely boxed in by its own forces.",
                        options: ["Smothered Mate", "Back-Rank Mate", "Scholar's Mate", "Fool's Mate"],
                        a: "Smothered Mate"
                    },
                    {
                        q: "White plays Rook to e8, checking the Black King who is trapped behind a wall of Pawns on the 7th rank. What mate is this?",
                        hint: "Think about the layout of the back rank.",
                        options: ["Back-Rank Mate", "Anastasia's Mate", "Boden's Mate", "smothered Mate"],
                        a: "Back-Rank Mate"
                    }
                ],

                startGame() {
                    this.gameState = 'playing';
                    this.currentQuestion = 0;
                    this.score = 0;
                    window.GameSystem.startTimer();
                },

                makeGuess(guessedAns) {
                    if(guessedAns === this.questions[this.currentQuestion].a) {
                        this.score += 33;
                    }
                    if(this.currentQuestion >= 2) {
                        if(this.score > 90) this.score = 100;
                        this.endGame();
                    } else {
                        this.currentQuestion++;
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
