<div id="science-quiz-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="scienceQuizGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-flask text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Science & Alchemy Quiz</h2>
        <p class="text-[var(--text-secondary)] mb-8">Unlock the secrets of alchemical mixtures, Wildfire creation, and the natural elements of the Citadel.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Consult the Citadel</button>
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
        if(!Alpine.data('scienceQuizGame')) {
            Alpine.data('scienceQuizGame', () => ({
                gameState: 'start',
                currentQuestion: 0,
                score: 0,
                questions: [
                    {
                        q: "Which Guild produces Wildfire in King's Landing?",
                        options: ["The Alchemists' Guild", "The Citadel Maesters", "The Iron Bank", "The Faceless Men"],
                        a: "The Alchemists' Guild"
                    },
                    {
                        q: "What metal is legendary for being extremely light, durable, and immune to simple fire?",
                        options: ["Valyrian Steel", "Mithril", "Dragonglass", "Bronze"],
                        a: "Valyrian Steel"
                    },
                    {
                        q: "What color does Wildfire burn?",
                        options: ["Bright Green", "Intense Crimson", "Deep Blue", "Pure White"],
                        a: "Bright Green"
                    },
                    {
                        q: "What poison was used to assassinate Joffrey Baratheon?",
                        options: ["The Strangler", "Tears of Lys", "Essence of Nightshade", "Manticore Venom"],
                        a: "The Strangler"
                    },
                    {
                        q: "What is the primary substance used to kill White Walkers?",
                        options: ["Dragonglass (Obsidian)", "Valyrian Gold", "Alchemical Fire", "Wildfire"],
                        a: "Dragonglass (Obsidian)"
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
