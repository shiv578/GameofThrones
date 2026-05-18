<div id="resource-wars-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="resourceWarsGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-castle text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Resource Wars</h2>
        <p class="text-[var(--text-secondary)] mb-8">Deploy resources to conquer neighboring territories. Collect Gold, mine Iron, and draft Soldiers to siege 3 rebel castles.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Raise Banners</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-xl">
        <div class="flex justify-between items-center mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Sieges Complete: <span x-text="castlesCaptured"></span>/3</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- Resources Dashboard -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="got-panel p-4 rounded-xl border border-[var(--panel-border)] bg-black/40">
                <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Gold</div>
                <div class="text-2xl font-cinzel font-bold text-yellow-500 mt-1" x-text="gold"></div>
            </div>
            <div class="got-panel p-4 rounded-xl border border-[var(--panel-border)] bg-black/40">
                <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Iron</div>
                <div class="text-2xl font-cinzel font-bold text-slate-300 mt-1" x-text="iron"></div>
            </div>
            <div class="got-panel p-4 rounded-xl border border-[var(--panel-border)] bg-black/40">
                <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Soldiers</div>
                <div class="text-2xl font-cinzel font-bold text-red-500 mt-1" x-text="soldiers"></div>
            </div>
        </div>

        <!-- Action Grid -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <button @click="harvest('gold')" class="got-btn-outline rounded-lg py-4 flex flex-col items-center">
                <i class="fa-solid fa-coins text-2xl text-yellow-500 mb-2"></i>
                <span>Tax Kingdom (+10 Gold)</span>
            </button>
            <button @click="harvest('iron')" class="got-btn-outline rounded-lg py-4 flex flex-col items-center">
                <i class="fa-solid fa-hammer text-2xl text-slate-300 mb-2"></i>
                <span>Mine Iron (+10 Iron)</span>
            </button>
            <button @click="trainSoldier()" class="got-btn-outline rounded-lg py-4 flex flex-col items-center col-span-2">
                <i class="fa-solid fa-shield-halved text-2xl text-red-500 mb-2"></i>
                <span>Draft Soldier (Costs: 20 Gold, 20 Iron)</span>
            </button>
        </div>

        <button @click="siegeCastle()" 
                class="got-btn w-full rounded-lg text-lg py-4 shadow-[0_0_15px_rgba(239,68,68,0.3)]"
                :class="soldiers < 3 ? 'opacity-50 cursor-not-allowed' : ''">
            <i class="fa-solid fa-skull-crossbones mr-2"></i> Siege Castle (Requires 3 Soldiers)
        </button>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('resourceWarsGame')) {
            Alpine.data('resourceWarsGame', () => ({
                gameState: 'start',
                score: 0,
                gold: 0,
                iron: 0,
                soldiers: 0,
                castlesCaptured: 0,

                startGame() {
                    this.gameState = 'playing';
                    this.score = 0;
                    this.gold = 0;
                    this.iron = 0;
                    this.soldiers = 0;
                    this.castlesCaptured = 0;
                    window.GameSystem.startTimer();
                },

                harvest(type) {
                    if(type === 'gold') this.gold += 10;
                    if(type === 'iron') this.iron += 10;
                },

                trainSoldier() {
                    if(this.gold >= 20 && this.iron >= 20) {
                        this.gold -= 20;
                        this.iron -= 20;
                        this.soldiers++;
                    } else {
                        alert('Insufficient resources to forge army!');
                    }
                },

                siegeCastle() {
                    if(this.soldiers >= 3) {
                        this.soldiers -= 3;
                        this.castlesCaptured++;
                        this.score += 33;
                        
                        if(this.castlesCaptured >= 3) {
                            this.score = 100; // Perfect score
                            this.endGame();
                        }
                    } else {
                        alert('Your forces are too weak! Draft more soldiers.');
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
