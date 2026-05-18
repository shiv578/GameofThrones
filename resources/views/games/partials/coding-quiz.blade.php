<div id="coding-quiz-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="codingQuizGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-code text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Coding & Runes Quiz</h2>
        <p class="text-[var(--text-secondary)] mb-8">Decipher the runic patterns of software design. Solve 5 logical and programming language riddles.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Begin Examination</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-xl">
        <div class="flex justify-between items-center mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Question <span x-text="currentQuestion + 1"></span>/5</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- Question Panel -->
        <div class="got-panel p-8 rounded-xl border border-[var(--panel-border)] mb-8 relative bg-black/40">
            <div class="text-lg font-cinzel font-bold text-white mb-2" x-text="questions[currentQuestion].q"></div>
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
        if(!Alpine.data('codingQuizGame')) {
            Alpine.data('codingQuizGame', () => ({
                gameState: 'start',
                currentQuestion: 0,
                score: 0,
                questions: [
                    {
                        q: "Which keyword is used to declare a variable that cannot be reassigned in modern JavaScript?",
                        options: ["const", "let", "var", "immutable"],
                        a: "const"
                    },
                    {
                        q: "What does the '=== ' operator check in JavaScript?",
                        options: ["Both value and type equality", "Value equality only", "Variable assignment", "Reference matching only"],
                        a: "Both value and type equality"
                    },
                    {
                        q: "In Laravel, which console tool is used to run CLI commands and build tables?",
                        options: ["Artisan", "Composer", "NPM", "Vite"],
                        a: "Artisan"
                    },
                    {
                        q: "Which HTTP method is universally recommended for creating new records?",
                        options: ["POST", "GET", "PUT", "DELETE"],
                        a: "POST"
                    },
                    {
                        q: "What is the primary engine used to run asynchronous loops in browser-side JavaScript?",
                        options: ["The Event Loop", "The Thread Pool", "V8 Engine directly", "AJAX handler"],
                        a: "The Event Loop"
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
                        this.score += 20;
                    }
                    if(this.currentQuestion >= 4) {
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
