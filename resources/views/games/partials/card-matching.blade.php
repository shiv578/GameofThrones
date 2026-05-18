<div id="card-matching-container" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center" x-data="cardMatchingGame()">
    
    <!-- Start Screen -->
    <div x-show="gameState === 'start'" class="max-w-md">
        <i class="fa-solid fa-clone text-6xl text-[var(--text-accent)] mb-6 animate-pulse-glow"></i>
        <h2 class="text-3xl font-cinzel font-bold mb-4">House Matcher</h2>
        <p class="text-[var(--text-secondary)] mb-8">Align the sigils of the Great Houses. Reveal and match identical house shields before the sands of time run out.</p>
        <button @click="startGame()" class="got-btn w-full rounded-lg text-lg py-4">Match Sigils</button>
    </div>

    <!-- Gameplay Screen -->
    <div x-show="gameState === 'playing'" style="display: none;" class="w-full max-w-md flex flex-col items-center">
        <div class="flex justify-between items-center w-full mb-6">
            <div class="text-[var(--text-secondary)] font-bold uppercase tracking-wider">Sigil Pairs Found: <span x-text="matchesFound"></span>/4</div>
            <div class="text-[var(--accent-color)] font-bold text-xl"><span x-text="score"></span> pts</div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-4 gap-4 w-72 h-72">
            <template x-for="(card, index) in cards" :key="index">
                <button @click="flipCard(index)"
                        class="w-16 h-20 rounded-lg flex items-center justify-center border transition-all duration-300 relative transform cursor-pointer"
                        :class="card.flipped || card.matched ? 'bg-slate-900 border-[var(--accent-color)] rotate-y-180 shadow-[0_0_10px_var(--accent-glow)]' : 'bg-gradient-to-br from-gray-800 to-black border-slate-700'"
                        :disabled="card.flipped || card.matched || lockBoard">
                    
                    <!-- Back side -->
                    <template x-if="!card.flipped && !card.matched">
                        <i class="fa-solid fa-shield text-slate-500 text-lg"></i>
                    </template>
                    
                    <!-- Front side -->
                    <template x-if="card.flipped || card.matched">
                        <span class="text-xs font-cinzel font-bold text-white uppercase text-center" x-text="card.name"></span>
                    </template>
                </button>
            </template>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if(!Alpine.data('cardMatchingGame')) {
            Alpine.data('cardMatchingGame', () => ({
                gameState: 'start',
                score: 100,
                cards: [],
                selectedCards: [],
                matchesFound: 0,
                lockBoard: false,

                startGame() {
                    this.gameState = 'playing';
                    this.score = 100;
                    this.matchesFound = 0;
                    this.selectedCards = [];
                    this.lockBoard = false;
                    window.GameSystem.startTimer();
                    this.generateCards();
                },

                generateCards() {
                    const houses = ['Stark', 'Lannister', 'Targaryen', 'Baratheon'];
                    let deck = [];
                    houses.forEach(h => {
                        deck.push({ name: h, flipped: false, matched: false });
                        deck.push({ name: h, flipped: false, matched: false });
                    });
                    this.cards = deck.sort(() => Math.random() - 0.5);
                },

                flipCard(index) {
                    if(this.lockBoard) return;
                    this.cards[index].flipped = true;
                    this.selectedCards.push(index);

                    if(this.selectedCards.length === 2) {
                        this.lockBoard = true;
                        this.checkMatch();
                    }
                },

                checkMatch() {
                    const [firstIdx, secondIdx] = this.selectedCards;
                    if(this.cards[firstIdx].name === this.cards[secondIdx].name) {
                        this.cards[firstIdx].matched = true;
                        this.cards[secondIdx].matched = true;
                        this.matchesFound++;
                        this.selectedCards = [];
                        this.lockBoard = false;
                        
                        if(this.matchesFound >= 4) {
                            this.endGame();
                        }
                    } else {
                        // Flip back
                        setTimeout(() => {
                            this.cards[firstIdx].flipped = false;
                            this.cards[secondIdx].flipped = false;
                            this.selectedCards = [];
                            this.lockBoard = false;
                            if(this.score > 10) this.score -= 5;
                        }, 1000);
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
