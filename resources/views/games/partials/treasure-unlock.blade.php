<div id="treasure-unlock-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="treasureUnlockGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-key text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">Treasure Unlock</h2>
        <p class="text-[var(--text-secondary)] mb-8">Decipher the riddle of the ancient vault lock. Set the correct dial combination by reading the runic math clues.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Unlock Vault</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Break the Combination</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- Math Riddles Clues -->
        <div class="got-panel p-6 rounded-xl border border-[var(--panel-border)] mb-8 bg-black/40 w-full text-left">
            <h4 class="font-cinzel text-[var(--text-accent)] font-bold mb-3">Ancient Scroll Clues:</h4>
            <ul class="space-y-2 text-sm text-gray-300">
                <li><i class="fa-solid fa-feather mr-2"></i>Dial 1: <strong class="text-white" x-text="clues[0]"></strong></li>
                <li><i class="fa-solid fa-feather mr-2"></i>Dial 2: <strong class="text-white" x-text="clues[1]"></strong></li>
                <li><i class="fa-solid fa-feather mr-2"></i>Dial 3: <strong class="text-white" x-text="clues[2]"></strong></li>
            </ul>
        </div>

        <!-- Lock Wheels -->
        <div class="flex space-x-6 mb-8">
            <template x-for="(dial, index) in dials" :key="index">
                <div class="flex flex-col items-center">
                    <button @click="spinDial(index, 1)" class="w-12 h-10 bg-slate-800 border border-slate-700 rounded-t-lg flex items-center justify-center text-white hover:bg-slate-700"><i class="fa-solid fa-chevron-up"></i></button>
                    <div class="w-12 h-16 bg-black border-y-2 border-yellow-600 flex items-center justify-center font-cinzel font-bold text-3xl text-yellow-400" x-text="dial"></div>
                    <button @click="spinDial(index, -1)" class="w-12 h-10 bg-slate-800 border border-slate-700 rounded-b-lg flex items-center justify-center text-white hover:bg-slate-700"><i class="fa-solid fa-chevron-down"></i></button>
                </div>
            </template>
        </div>

        <button @click="verifyCombination()" class="got-btn w-full rounded-lg text-lg py-4">Trigger Vault Lever</button>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('treasureUnlockGame')) {
            Alpine.data('treasureUnlockGame', () => ({
                gameState: 'start',
                score: 100,
                dials: [0, 0, 0],
                combination: [3, 7, 2],
                clues: ['', '', ''],

                startGame() {
                    this.gameState = 'playing';
                    this.score = 100;
                    this.dials = [0, 0, 0];
                    window.GameSystem.startTimer();
                    this.generateClues();
                },

                generateClues() {
                    const c1 = Math.floor(Math.random() * 8) + 1; // 1-8
                    const c2 = Math.floor(Math.random() * 8) + 1;
                    const c3 = Math.floor(Math.random() * 8) + 1;
                    this.combination = [c1, c2, c3];

                    this.clues = [
                        `Half of ${c1 * 2}`,
                        `${c2 + 3} minus 3`,
                        `Three times ${c3} minus ${c3 * 2}`
                    ];
                },

                spinDial(index, direction) {
                    let next = this.dials[index] + direction;
                    if(next > 9) next = 0;
                    if(next < 0) next = 9;
                    this.dials[index] = next;
                },

                verifyCombination() {
                    const matches = this.dials.every((val, idx) => val === this.combination[idx]);
                    if(matches) {
                        this.endGame();
                    } else {
                        // Flashes error, subtracts points
                        if(this.score > 20) this.score -= 15;
                        alert('The lever jam! The combination is incorrect.');
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
