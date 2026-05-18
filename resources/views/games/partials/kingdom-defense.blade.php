<div id="kingdom-defense-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center overflow-hidden" x-data="kingdomDefenseGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-shield-halved text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Defend The Wall</h2>
        <p class="text-[var(--text-secondary)] mb-8">White Walkers are marching on the Wall! Click on incoming enemies to shoot them down with fire arrows before they breach the gates. 3 lives!</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">To The Battlements!</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full h-full flex flex-col relative select-none">
        
        <!-- Score and Health -->
        <div class="flex justify-between items-center w-full p-4 bg-black/40 border-b border-[var(--panel-border)] z-10 shrink-0">
            <div class="flex space-x-2 text-red-500 font-bold">
                <template x-for="i in Array.from({length: lives})">
                    <i class="fa-solid fa-heart"></i>
                </template>
            </div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- Battlefield Grid / Walkers Area -->
        <div class="flex-1 relative w-full bg-black/80" id="battlefield">
            <!-- Walkers -->
            <template x-for="w in walkers" :key="w.id">
                <button @click="killWalker(w.id)" 
                        class="absolute w-12 h-12 rounded-full bg-blue-900/60 border border-blue-400 flex items-center justify-center text-blue-300 font-bold transition-all shadow-[0_0_15px_rgba(59,130,246,0.5)] cursor-pointer"
                        :style="`top: ${w.y}px; left: ${w.x}px;`"></button>
            </template>
            
            <!-- Bottom Wall indicator -->
            <div class="absolute bottom-0 left-0 right-0 h-4 bg-slate-700/80 border-t border-slate-500 flex items-center justify-center text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                The Wall
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('kingdomDefenseGame')) {
            Alpine.data('kingdomDefenseGame', () => ({
                gameState: 'start',
                score: 0,
                lives: 3,
                walkers: [],
                walkerId: 0,
                gameLoopInterval: null,
                spawnInterval: null,
                targetWalkers: 10,
                walkersSpawned: 0,

                startGame() {
                    this.gameState = 'playing';
                    this.score = 0;
                    this.lives = 3;
                    this.walkers = [];
                    this.walkersSpawned = 0;
                    window.GameSystem.startTimer();

                    // Start Game Loops
                    this.gameLoopInterval = setInterval(() => this.updatePhysics(), 50);
                    this.spawnInterval = setInterval(() => this.spawnWalker(), 1500);
                },

                spawnWalker() {
                    if(this.walkersSpawned >= this.targetWalkers) return;
                    
                    const field = document.getElementById('battlefield');
                    if(!field) return;

                    const width = field.clientWidth - 50;
                    const x = Math.max(10, Math.floor(Math.random() * width));
                    
                    this.walkers.push({
                        id: this.walkerId++,
                        x: x,
                        y: 10,
                        speed: Math.random() * 2 + 1.5
                    });
                    this.walkersSpawned++;
                },

                updatePhysics() {
                    const field = document.getElementById('battlefield');
                    if(!field) return;
                    const height = field.clientHeight - 40;

                    this.walkers.forEach(w => {
                        w.y += w.speed;
                        
                        // Check if hit the Wall
                        if(w.y >= height) {
                            this.lives--;
                            this.walkers = this.walkers.filter(x => x.id !== w.id);
                            
                            // Check lose
                            if(this.lives <= 0) {
                                this.endGame(false);
                            }
                        }
                    });

                    // Check win (survived all spawns and cleared field)
                    if(this.walkersSpawned >= this.targetWalkers && this.walkers.length === 0 && this.lives > 0) {
                        this.endGame(true);
                    }
                },

                killWalker(id) {
                    this.walkers = this.walkers.filter(w => w.id !== id);
                    this.score += 10;
                },

                endGame(victory = true) {
                    clearInterval(this.gameLoopInterval);
                    clearInterval(this.spawnInterval);
                    this.gameState = 'ended';
                    window.GameSystem.endGame(this.score, victory);
                }
            }));
        }
    });
</script>
