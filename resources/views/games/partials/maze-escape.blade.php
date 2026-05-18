<div id="maze-escape-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="mazeEscapeGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-dungeon text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Maze Escape</h2>
        <p class="text-[var(--text-secondary)] mb-8">Navigate through dark, frozen corridors of the Crypts. Reach the exit before the cold freezes you forever.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Enter Crypts</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Escape the Crypts</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- Maze Grid -->
        <div class="got-panel p-4 rounded-xl border border-[var(--panel-border)] mb-6 bg-black/60 shadow-inner">
            <div class="grid grid-cols-6 gap-1 w-72 h-72">
                <template x-for="(cell, index) in grid" :key="index">
                    <div class="w-11 h-11 flex items-center justify-center rounded transition-all duration-200"
                         :class="cell === '#' ? 'bg-slate-800/80 border border-slate-700/50' : 
                                 (cell === 'U' ? 'bg-[var(--accent-color)] shadow-[0_0_10px_var(--accent-glow)]' : 
                                 (cell === 'E' ? 'bg-green-600 animate-pulse' : 'bg-black/30'))">
                        
                        <template x-if="cell === 'U'">
                            <i class="fa-solid fa-person-walking text-white text-lg"></i>
                        </template>
                        <template x-if="cell === 'E'">
                            <i class="fa-solid fa-door-open text-white text-lg"></i>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <!-- Controls -->
        <div class="grid grid-cols-3 gap-2 w-48">
            <div></div>
            <button @click="move('up')" class="got-btn-outline !p-3 rounded-lg flex items-center justify-center"><i class="fa-solid fa-arrow-up text-lg"></i></button>
            <div></div>
            
            <button @click="move('left')" class="got-btn-outline !p-3 rounded-lg flex items-center justify-center"><i class="fa-solid fa-arrow-left text-lg"></i></button>
            <button @click="move('down')" class="got-btn-outline !p-3 rounded-lg flex items-center justify-center"><i class="fa-solid fa-arrow-down text-lg"></i></button>
            <button @click="move('right')" class="got-btn-outline !p-3 rounded-lg flex items-center justify-center"><i class="fa-solid fa-arrow-right text-lg"></i></button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('mazeEscapeGame')) {
            Alpine.data('mazeEscapeGame', () => ({
                gameState: 'start',
                score: 100, // Starts at 100, tick down every move/sec to simulate urgency
                userPos: {r: 0, c: 0},
                exitPos: {r: 5, c: 5},
                gridSize: 6,
                grid: [],
                mazeLayout: [
                    ['U', '.', '#', '.', '.', '.'],
                    ['#', '.', '#', '.', '#', '.'],
                    ['.', '.', '.', '.', '#', '.'],
                    ['.', '#', '#', '#', '#', '.'],
                    ['.', '.', '.', '.', '.', '.'],
                    ['#', '#', '#', '#', '#', 'E']
                ],

                startGame() {
                    this.gameState = 'playing';
                    this.score = 100;
                    this.userPos = {r: 0, c: 0};
                    window.GameSystem.startTimer();
                    this.renderGrid();
                    
                    // Listen to keyboard arrows
                    window.addEventListener('keydown', (e) => {
                        if(this.gameState !== 'playing') return;
                        if(e.key === 'ArrowUp') this.move('up');
                        if(e.key === 'ArrowDown') this.move('down');
                        if(e.key === 'ArrowLeft') this.move('left');
                        if(e.key === 'ArrowRight') this.move('right');
                    });
                },

                renderGrid() {
                    let temp = [];
                    for(let r=0; r<this.gridSize; r++) {
                        for(let c=0; c<this.gridSize; c++) {
                            if(r === this.userPos.r && c === this.userPos.c) {
                                temp.push('U');
                            } else if(r === this.exitPos.r && c === this.exitPos.c) {
                                temp.push('E');
                            } else {
                                temp.push(this.mazeLayout[r][c]);
                            }
                        }
                    }
                    this.grid = temp;
                },

                move(dir) {
                    let newR = this.userPos.r;
                    let newC = this.userPos.c;

                    if(dir === 'up') newR--;
                    else if(dir === 'down') newR++;
                    else if(dir === 'left') newC--;
                    else if(dir === 'right') newC++;

                    // Boundary checks
                    if(newR >= 0 && newR < this.gridSize && newC >= 0 && newC < this.gridSize) {
                        // Collision check
                        if(this.mazeLayout[newR][newC] !== '#') {
                            this.userPos.r = newR;
                            this.userPos.c = newC;
                            if(this.score > 10) this.score -= 2; // small penalty for moves to encourage direct path
                            this.renderGrid();
                            
                            // Check win
                            if(newR === this.exitPos.r && newC === this.exitPos.c) {
                                this.endGame();
                            }
                        }
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
