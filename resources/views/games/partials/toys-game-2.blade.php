<div id="toys-game-2-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="toysGame2()">
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-shapes text-6xl text-[var(--text-accent)] mb-6 animate-pulse"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Toy Sorter</h2>
        <p class="text-[var(--text-secondary)] mb-8">Sort the toys correctly! Click the box that matches the required color.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Start Sorting</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Round: <span x-text="round"></span>/5</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <div class="mb-8">
            <h3 class="text-xl font-cinzel text-[var(--text-secondary)] mb-2">Find the color:</h3>
            <div class="text-4xl font-bold uppercase" :class="targetColorClass" x-text="targetColorName"></div>
        </div>

        <div class="grid grid-cols-2 gap-4 w-64 mb-6">
            <template x-for="(color, index) in options" :key="index">
                <button @click="checkColor(color.name)" 
                        class="w-full h-24 rounded-xl border border-white/20 transition-all duration-200 hover:scale-105"
                        :class="color.bgClass">
                </button>
            </template>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('toysGame2')) {
            Alpine.data('toysGame2', () => ({
                gameState: 'start',
                score: 0,
                round: 1,
                colors: [
                    { name: 'Red', bgClass: 'bg-red-500 hover:bg-red-400', textClass: 'text-red-500' },
                    { name: 'Blue', bgClass: 'bg-blue-500 hover:bg-blue-400', textClass: 'text-blue-500' },
                    { name: 'Green', bgClass: 'bg-green-500 hover:bg-green-400', textClass: 'text-green-500' },
                    { name: 'Yellow', bgClass: 'bg-yellow-500 hover:bg-yellow-400', textClass: 'text-yellow-500' }
                ],
                options: [],
                targetColorName: '',
                targetColorClass: '',

                startGame() {
                    this.gameState = 'playing';
                    this.score = 0;
                    this.round = 1;
                    window.GameSystem.startTimer();
                    this.nextRound();
                },

                nextRound() {
                    // Shuffle colors for options
                    this.options = [...this.colors].sort(() => 0.5 - Math.random());
                    
                    // Pick a random target from the options
                    const target = this.options[Math.floor(Math.random() * this.options.length)];
                    this.targetColorName = target.name;
                    
                    // Pick a random text color for the target text (Stroop effect!)
                    const randomTextColor = this.colors[Math.floor(Math.random() * this.colors.length)];
                    this.targetColorClass = randomTextColor.textClass;
                },

                checkColor(selectedName) {
                    if (selectedName === this.targetColorName) {
                        this.score += 20;
                        if (this.round >= 5) {
                            this.score = 100;
                            this.endGame(true);
                        } else {
                            this.round++;
                            this.nextRound();
                        }
                    } else {
                        alert('Wrong toy sorted! The sorting failed.');
                        this.endGame(false);
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
