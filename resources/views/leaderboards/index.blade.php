<x-app-layout>
    <div class="mb-8 text-center" data-aos="fade-down">
        <h1 class="text-4xl sm:text-5xl font-cinzel font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-700 mb-2 drop-shadow-[0_0_15px_rgba(250,204,21,0.5)]">
            <i class="fa-solid fa-trophy mr-2 text-yellow-500"></i> Hall of Legends
        </h1>
        <p class="text-lg text-[var(--text-secondary)] font-cinzel tracking-wider">The most glorious warriors in the Realm</p>
    </div>

    <!-- Podium (Top 3) -->
    <div class="flex justify-center items-end h-64 mb-16 space-x-2 sm:space-x-6 px-4" data-aos="fade-up">
        
        <!-- Second Place -->
        @if(isset($podium[1]))
        <div class="flex flex-col items-center">
            <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-gray-400 bg-black mb-2 overflow-hidden shadow-[0_0_20px_rgba(156,163,175,0.5)] z-10">
                @if($podium[1]->avatar) <img src="{{ asset('storage/'.$podium[1]->avatar) }}" class="w-full h-full object-cover"> @else <i class="fa-solid fa-user text-gray-500 text-3xl mt-4"></i> @endif
                <div class="absolute bottom-0 left-0 right-0 bg-gray-400 text-black text-[10px] font-bold text-center">#2</div>
            </div>
            <div class="w-24 sm:w-32 h-32 bg-gradient-to-t from-[var(--panel-bg)] to-gray-800/80 border-t-2 border-l-2 border-r-2 border-gray-500 rounded-t-lg flex flex-col items-center justify-start pt-4 shadow-xl">
                <span class="font-cinzel font-bold text-gray-300 text-sm truncate w-full text-center px-2">{{ $podium[1]->name }}</span>
                <span class="text-xs text-[var(--text-accent)] mt-1">{{ number_format($podium[1]->xp) }} XP</span>
            </div>
        </div>
        @endif
        
        <!-- First Place -->
        @if(isset($podium[0]))
        <div class="flex flex-col items-center">
            <div class="relative w-20 h-20 sm:w-28 sm:h-28 rounded-full border-4 border-yellow-500 bg-black mb-2 overflow-hidden shadow-[0_0_30px_rgba(250,204,21,0.6)] z-20">
                @if($podium[0]->avatar) <img src="{{ asset('storage/'.$podium[0]->avatar) }}" class="w-full h-full object-cover"> @else <i class="fa-solid fa-crown text-yellow-500 text-4xl mt-6"></i> @endif
                <div class="absolute bottom-0 left-0 right-0 bg-yellow-500 text-black text-xs font-bold text-center">#1</div>
            </div>
            <div class="w-28 sm:w-40 h-40 bg-gradient-to-t from-[var(--panel-bg)] to-yellow-900/80 border-t-2 border-l-2 border-r-2 border-yellow-500 rounded-t-lg flex flex-col items-center justify-start pt-6 shadow-xl relative">
                <i class="fa-solid fa-star text-yellow-300 absolute -top-4 text-2xl animate-pulse"></i>
                <span class="font-cinzel font-bold text-white text-base truncate w-full text-center px-2">{{ $podium[0]->name }}</span>
                <span class="text-sm font-bold text-yellow-400 mt-1">{{ number_format($podium[0]->xp) }} XP</span>
            </div>
        </div>
        @endif
        
        <!-- Third Place -->
        @if(isset($podium[2]))
        <div class="flex flex-col items-center">
            <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-amber-700 bg-black mb-2 overflow-hidden shadow-[0_0_15px_rgba(180,83,9,0.5)] z-10">
                @if($podium[2]->avatar) <img src="{{ asset('storage/'.$podium[2]->avatar) }}" class="w-full h-full object-cover"> @else <i class="fa-solid fa-user text-amber-700 text-3xl mt-4"></i> @endif
                <div class="absolute bottom-0 left-0 right-0 bg-amber-700 text-white text-[10px] font-bold text-center">#3</div>
            </div>
            <div class="w-24 sm:w-32 h-24 bg-gradient-to-t from-[var(--panel-bg)] to-amber-900/80 border-t-2 border-l-2 border-r-2 border-amber-700 rounded-t-lg flex flex-col items-center justify-start pt-2 shadow-xl">
                <span class="font-cinzel font-bold text-amber-300 text-sm truncate w-full text-center px-2">{{ $podium[2]->name }}</span>
                <span class="text-xs text-[var(--text-accent)] mt-1">{{ number_format($podium[2]->xp) }} XP</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6" data-aos="fade-up">
        <h2 class="text-2xl font-cinzel font-bold mb-4 sm:mb-0">Global Rankings</h2>
        
        <form method="GET" action="{{ route('leaderboards.index') }}" class="flex space-x-2">
<select name="house" class="got-input fantasy-select rounded-lg !py-2" onchange="this.form.submit()">
                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Houses</option>
                <option value="Stark" {{ $filter == 'Stark' ? 'selected' : '' }}>House Stark</option>
                <option value="Targaryen" {{ $filter == 'Targaryen' ? 'selected' : '' }}>House Targaryen</option>
                <option value="Lannister" {{ $filter == 'Lannister' ? 'selected' : '' }}>House Lannister</option>
                <option value="Baratheon" {{ $filter == 'Baratheon' ? 'selected' : '' }}>House Baratheon</option>
            </select>
        </form>
    </div>

    <!-- Full Table -->
    <div class="got-panel rounded-xl overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/60 border-b border-[var(--panel-border)]">
                        <th class="p-4 font-cinzel text-[var(--text-secondary)] uppercase tracking-wider text-sm">Rank</th>
                        <th class="p-4 font-cinzel text-[var(--text-secondary)] uppercase tracking-wider text-sm">Warrior</th>
                        <th class="p-4 font-cinzel text-[var(--text-secondary)] uppercase tracking-wider text-sm hidden sm:table-cell">House</th>
                        <th class="p-4 font-cinzel text-[var(--text-secondary)] uppercase tracking-wider text-sm hidden md:table-cell">Class</th>
                        <th class="p-4 font-cinzel text-[var(--text-secondary)] uppercase tracking-wider text-sm text-right">XP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topUsers as $index => $u)
                    <tr class="border-b border-[var(--panel-border)] last:border-0 hover:bg-white/5 transition-colors">
                        <td class="p-4">
                            @if($index == 0)
                                <i class="fa-solid fa-crown text-yellow-500 text-xl ml-2"></i>
                            @elseif($index == 1)
                                <i class="fa-solid fa-medal text-gray-400 text-xl ml-2"></i>
                            @elseif($index == 2)
                                <i class="fa-solid fa-medal text-amber-600 text-xl ml-2"></i>
                            @else
                                <span class="font-bold text-lg text-gray-500 ml-4">{{ $topUsers->firstItem() + $index }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-black border border-[var(--panel-border)] mr-4 overflow-hidden">
                                    @if($u->avatar) <img src="{{ asset('storage/'.$u->avatar) }}" class="w-full h-full object-cover"> @else <i class="fa-solid fa-user text-gray-500 flex items-center justify-center h-full"></i> @endif
                                </div>
                                <div>
                                    <div class="font-bold text-white">{{ $u->name }}</div>
                                    <div class="text-xs text-[var(--text-secondary)] sm:hidden">{{ $u->house }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 hidden sm:table-cell">
                            <span class="px-3 py-1 rounded-full bg-black/50 border border-[var(--panel-border)] text-xs font-bold uppercase tracking-wider">
                                <i class="fa-brands fa-d-and-d mr-1"></i> {{ $u->house }}
                            </span>
                        </td>
                        <td class="p-4 hidden md:table-cell text-[var(--text-secondary)] text-sm">
                            {{ $u->character_class }}
                        </td>
                        <td class="p-4 text-right">
                            <div class="font-cinzel font-bold text-lg text-yellow-400">{{ number_format($u->xp) }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-black/30 border-t border-[var(--panel-border)]">
            {{ $topUsers->links() }}
        </div>
    </div>
    <style>

/* ===== FANTASY SELECT DROPDOWN ===== */

.fantasy-select{

    background: rgba(15,0,0,0.88) !important;

    border: 2px solid rgba(255,140,0,0.7) !important;

color: #ffffff !important;
    font-family: 'Cinzel', serif !important;

    font-size: 20px;

    font-weight: 700;

    padding: 14px 22px !important;

    border-radius: 18px !important;

    outline: none;

    min-width: 260px;

    backdrop-filter: blur(12px);

    box-shadow:
        0 0 15px rgba(255,120,0,0.35),
        inset 0 0 10px rgba(255,140,0,0.15);

    transition: 0.3s ease;

    cursor: pointer;

}

/* HOVER */

.fantasy-select:hover{

    border-color: #ffae42 !important;

    box-shadow:
        0 0 25px rgba(255,140,0,0.7),
        0 0 40px rgba(0,180,255,0.25);

}

/* OPTIONS */
.fantasy-select option{

    background: #140404 !important;

    color: #ffddaa !important;

    font-family: 'Cinzel', serif !important;

    font-size: 20px !important;

    font-weight: bold;

    padding: 15px;

}

/* Selected Option */

.fantasy-select option:checked{

    background: #ff6b00 !important;

    color: white !important;

}

/* Dropdown Open */

.fantasy-select:focus{

    background: rgba(15,0,0,0.95) !important;

    color: white !important;

}

</style>
</x-app-layout>
