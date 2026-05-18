<div id="logic-master-container" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center" x-data="logicMasterGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-microchip text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Logic Master</h2>
        <p class="text-[var(--text-secondary)] mb-8">Master the flow of energy. Evaluate logic gate expressions (AND, OR, XOR, NOT) to complete the crystal circuit.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Activate Circuit</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-xl">
        <div class="flex justify-between items-center mb-8">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Circuit <span x-text="currentRound"></span>/5</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- Logic Expression -->
        <div class="got-panel p-8 rounded-xl border border-[var(--panel-border)] mb-8 relative">
            <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold mb-6">Current Flow</div>
            
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="flex space-x-6 text-lg font-bold">
                    <div class="px-4 py-2 bg-black/40 rounded border border-[var(--panel-border)]">
                        <span class="text-gray-400 mr-2">Input A:</span>
                        <span :class="valA ? 'text-green-400' : 'text-red-400'" x-text="valA ? 'TRUE' : 'FALSE'"></span>
                    </div>
                    <div class="px-4 py-2 bg-black/40 rounded border border-[var(--panel-border)]">
                        <span class="text-gray-400 mr-2">Input B:</span>
                        <span :class="valB ? 'text-green-400' : 'text-red-400'" x-text="valB ? 'TRUE' : 'FALSE'"></span>
                    </div>
                </div>

                <div class="text-3xl font-cinzel font-black tracking-wider text-yellow-500 my-4" x-text="expression"></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <button @click="makeGuess(true)" class="got-btn rounded-lg py-4 text-xl font-bold font-cinzel bg-green-950/40 border-2 border-green-600/50 hover:bg-green-600 text-white">TRUE</button>
            <button @click="makeGuess(false)" class="got-btn rounded-lg py-4 text-xl font-bold font-cinzel bg-red-950/40 border-2 border-red-600/50 hover:bg-red-600 text-white">FALSE</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('logicMasterGame')) {
            Alpine.data('logicMasterGame', () => ({
                gameState: 'start',
                currentRound: 1,
                score: 0,
                valA: true,
                valB: false,
                expression: '',
                correctAnswer: false,

                startGame() {
                    this.gameState = 'playing';
                    this.currentRound = 1;
                    this.score = 0;
                    window.GameSystem.startTimer();
                    this.generateRound();
                },

                generateRound() {
                    this.valA = Math.random() > 0.5;
                    this.valB = Math.random() > 0.5;
                    const gates = ['AND', 'OR', 'XOR', 'NOT A'];
                    const gate = gates[Math.floor(Math.random() * gates.length)];

                    if(gate === 'AND') {
                        this.expression = 'A AND B';
                        this.correctAnswer = this.valA && this.valB;
                    } else if (gate === 'OR') {
                        this.expression = 'A OR B';
                        this.correctAnswer = this.valA || this.valB;
                    } else if (gate === 'XOR') {
                        this.expression = 'A XOR B';
                        this.correctAnswer = (this.valA !== this.valB);
                    } else {
                        this.expression = 'NOT A';
                        this.correctAnswer = !this.valA;
                    }
                },

                makeGuess(guessedVal) {
                    if(guessedVal === this.correctAnswer) {
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
