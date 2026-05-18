<div id="block-puzzle-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="blockPuzzleGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-cubes text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Block Puzzle</h2>
        <p class="text-[var(--text-secondary)] mb-8">Fit the runic blocks perfectly into the empty slots. Click the active tiles to toggle them, filling all highlighted spaces.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Forge Runic Pattern</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Solve the Grid</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <div class="got-panel p-6 rounded-xl border border-[var(--panel-border)] mb-6 bg-black/60 shadow-inner">
            <div class="grid grid-cols-4 gap-2 w-64 h-64">
                <template x-for="(cell, index) in grid" :key="index">
                    <button @click="toggleCell(index)"
                            class="w-14 h-14 flex items-center justify-center rounded border transition-all duration-300"
                            :class="cell.required ? 
                                    (cell.active ? 'bg-[var(--accent-color)] border-[var(--accent-color)] text-white shadow-[0_0_10px_var(--accent-glow)]' : 'bg-gray-800/40 border-dashed border-gray-600 text-gray-500 hover:bg-gray-700/30') : 
                                    'bg-black/20 border-transparent cursor-not-allowed'"></button>
                </template>
            </div>
        </div>

        <div class="text-sm text-[var(--text-secondary)] italic">
            Complete the highlight to align the runes!
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('blockPuzzleGame')) {
            Alpine.data('blockPuzzleGame', () => ({
                gameState: 'start',
                score: 100,
                grid: [],

                startGame() {
                    this.gameState = 'playing';
                    this.score = 100;
                    window.GameSystem.startTimer();
                    this.generateGrid();
                },

                generateGrid() {
                    let temp = [];
                    // Generate a 4x6 grid. Or 4x4. Let's do 4x4 (16 cells)
                    // Randomly mark 6 cells as 'required'
                    for(let i=0; i<16; i++) {
                        const required = [0, 2, 5, 7, 8, 10, 13, 15].includes(i); // a beautiful chessboard pattern
                        temp.push({
                            required: required,
                            active: false
                        });
                    }
                    this.grid = temp;
                },

                toggleCell(index) {
                    if(!this.grid[index].required) return;
                    this.grid[index].active = !this.grid[index].active;
                    this.checkWin();
                },

                checkWin() {
                    const won = this.grid.every(cell => !cell.required || cell.active);
                    if(won) {
                        this.endGame();
                    }
                },

                endGame() {
                    this.gameState = 'ended';
                    window.GameSystem.endGame(this.score, true);
                }
            }));
        }
    });
</script>
