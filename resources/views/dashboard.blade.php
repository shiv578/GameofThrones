<x-app-layout>
    
    <!-- Hero Banner -->
    <div class="relative w-full h-80 rounded-2xl overflow-hidden mb-8 shadow-[0_15px_40px_rgba(0,0,0,0.7)] border border-[var(--panel-border)] group" data-aos="fade-up">
        <!-- Raging split background -->
     <div class="absolute inset-0 bg-cover transition-transform duration-1000 group-hover:scale-105" 
     style="
        background-image: url('/images/dragons-bg.png');
        background-position: center 20%;
     ">
</div>
        <!-- Vignette & Theme responsive overlay mask -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/75 via-black/45 to-black/75"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_35%,rgba(0,0,0,0.9)_100%)]"></div>
        
        <!-- Inner content -->
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center px-6">
            <div class="mb-3 animate-float">
<img src="{{ asset('favicon.ico') }}"
     alt="Logo"
     class="w-14 h-14 rounded-full object-cover drop-shadow-[0_0_20px_var(--accent-glow)]">            </div>
            
            <!-- Runic welcome border -->
            <div class="border-y border-[var(--panel-border)]/45 py-2 px-6 mb-4">
                <span class="text-xs uppercase tracking-[0.4em] text-[var(--text-accent)] font-black">Realm Conquest Command</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-cinzel font-black text-white drop-shadow-[0_4px_12px_rgba(0,0,0,0.95)] mb-2">
                WELCOME, {{ strtoupper($user->name) }}
            </h1>
            
            <p class="text-xs sm:text-sm text-gray-300 font-cinzel tracking-widest italic max-w-2xl">
                "{{ $user->character_class }} of House {{ $user->house }} &bull; Level {{ $user->level }} Sovereign of the Winter Arena"
            </p>
            
            <!-- Interactive Call to Action inside Banner -->
            <div class="mt-6 flex space-x-4">
                <a href="#quick-play" class="got-btn !py-2 !px-6 rounded-lg text-xs flex items-center shadow-[0_0_20px_var(--accent-glow)]">
                    <i class="fa-solid fa-swords mr-2"></i> Play Battle
                </a>
                <a href="{{ route('analytics.index') }}" class="got-btn-outline !py-2 !px-6 rounded-lg text-xs flex items-center">
                      Insights <i class="fa-solid fa-arrow-right ml-2 text-[10px]"></i>
                 </a>
             </div>
         </div>
     </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        @php
            $stats = [
                [
                    'label' => 'Total XP', 
                    'value' => number_format($user->xp), 
                    'icon' => 'fa-star text-yellow-400',
                    'desc' => 'Cognitive Power',
                    'glow' => 'rgba(234,179,8,0.15)',
                    'trend' => '+15% this week'
                ],
                [
                    'label' => 'Gold Coins', 
                    'value' => number_format($user->coins), 
                    'icon' => 'fa-coins text-yellow-500',
                    'desc' => 'Kingdom Treasury',
                    'glow' => 'rgba(202,138,4,0.15)',
                    'trend' => 'Rewards Ready'
                ],
                [
                    'label' => 'Conquest Level', 
                    'value' => $user->level, 
                    'icon' => 'fa-shield-halved text-[var(--accent-color)]',
                    'desc' => $user->character_class,
                    'glow' => 'var(--accent-glow)',
                    'progress' => $levelProgressPercent
                ],
                [
                    'label' => 'Global Rank', 
                    'value' => '#12', 
                    'icon' => 'fa-trophy text-purple-400',
                    'desc' => 'Winter Leaderboard',
                    'glow' => 'rgba(168,85,247,0.15)',
                    'trend' => 'Top 2% of Realms'
                ],
            ];
        @endphp

        @foreach($stats as $index => $stat)
        <div class="got-panel rounded-xl p-5 relative overflow-hidden flex flex-col justify-between hover:translate-y-[-6px] transition-all duration-300" 
             style="box-shadow: 0 10px 30px rgba(0,0,0,0.5), inset 0 0 15px {{ $stat['glow'] }}"
             data-aos="fade-up" 
             data-aos-delay="{{ $index * 80 }}">
            
            <!-- Decorative background runic character -->
            <div class="absolute -right-2 -bottom-2 opacity-5 text-7xl font-cinzel select-none pointer-events-none">
                ⚔️
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] sm:text-xs text-[var(--text-secondary)] uppercase tracking-widest font-bold">{{ $stat['label'] }}</p>
                    <h3 class="text-xl sm:text-3xl font-cinzel font-black text-white mt-1">{{ $stat['value'] }}</h3>
                </div>
                <div class="w-11 h-11 rounded-xl bg-black/50 border border-[var(--panel-border)] flex items-center justify-center shadow-[inset_0_0_10px_rgba(0,0,0,0.8)]">
                    <i class="fa-solid {{ $stat['icon'] }} text-lg"></i>
                </div>
            </div>
            
            <div class="mt-2 border-t border-[var(--panel-border)]/30 pt-3 flex items-center justify-between text-xs text-[var(--text-secondary)]">
                @if(isset($stat['progress']))
                    <div class="w-full">
                        <div class="flex justify-between text-[10px] uppercase font-bold tracking-wider mb-1 text-[var(--text-accent)]">
                             <span>XP to next Lvl</span>
                             <span>{{ $stat['progress'] }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-black/60 rounded-full border border-[var(--panel-border)]/50 overflow-hidden">
                             <div class="bg-gradient-to-r from-[var(--accent-color)] to-yellow-500 h-full rounded-full animate-pulse-glow" style="width: {{ $stat['progress'] }}%"></div>
                        </div>
                    </div>
                @else
                    <span class="text-[9px] uppercase tracking-wider font-bold text-gray-400">{{ $stat['desc'] }}</span>
                    <span class="text-[9px] font-bold text-green-400 bg-green-500/10 px-2 py-0.5 rounded-full border border-green-500/20 shadow-[0_0_10px_rgba(34,197,94,0.1)]">{{ $stat['trend'] ?? '' }}</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Tactical Insights (Charts) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8" data-aos="fade-up" data-aos-delay="200">
        <!-- Experience Growth Card -->
        <div class="got-panel p-5 rounded-xl lg:col-span-2">
            <div class="flex items-center justify-between border-b border-[var(--panel-border)] pb-3 mb-4">
                <h3 class="font-cinzel font-bold text-sm sm:text-base flex items-center text-white">
                    <i class="fa-solid fa-chart-line mr-2 text-[var(--text-accent)]"></i> Experience Growth Weekly
                </h3>
                <span class="text-[9px] font-bold uppercase text-[var(--text-accent)] bg-black/50 border border-[var(--panel-border)]/40 px-3 py-1 rounded-full">
                    Realtime Log
                </span>
            </div>
            <div id="dashboard-xp-chart" class="w-full h-72"></div>
        </div>
        
        <!-- Mastery Categories Card -->
        <div class="got-panel p-5 rounded-xl">
            <div class="flex items-center justify-between border-b border-[var(--panel-border)] pb-3 mb-4">
                <h3 class="font-cinzel font-bold text-sm sm:text-base flex items-center text-white">
                    <i class="fa-solid fa-brain mr-2 text-[var(--text-accent)]"></i> Cognitive Mastery
                </h3>
                <span class="text-[9px] font-bold uppercase text-[var(--text-accent)] bg-black/50 border border-[var(--panel-border)]/40 px-3 py-1 rounded-full">
                    Radar Profile
                </span>
            </div>
            <div id="dashboard-mastery-chart" class="w-full h-72 flex justify-center"></div>
        </div>
    </div>

    <!-- Quick Play Section -->
    <div id="quick-play" class="mb-8" data-aos="fade-up" data-aos-delay="250">
        <div class="flex items-center justify-between mb-4 border-b border-[var(--panel-border)] pb-2">
            <h2 class="text-2xl font-cinzel font-bold text-white"><i class="fa-solid fa-gamepad mr-2 text-[var(--text-accent)]"></i> Battle Arena: Quick Play</h2>
            <a href="{{ route('games.index') }}" class="text-sm text-[var(--text-accent)] hover:underline flex items-center">
                View Full Vault <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @php
                $quickGames = [
                    [
                        'name' => 'IQ Challenge', 
                        'cat' => 'Brain', 
                        'icon' => 'fa-brain', 
                        'text' => 'text-cyan-400',
                        'slug' => 'iq-challenge',
                        'desc' => 'Test pattern matching, spatial logic, and cognitive analytical reasoning.'
                    ],
                    [
                        'name' => 'Maze Escape', 
                        'cat' => 'Puzzle', 
                        'icon' => 'fa-puzzle-piece', 
                        'text' => 'text-emerald-400',
                        'slug' => 'maze-escape',
                        'desc' => 'Guide your character through procedurally generated runic pathways.'
                    ],
                    [
                        'name' => 'History Quiz', 
                        'cat' => 'Quiz', 
                        'icon' => 'fa-scroll', 
                        'text' => 'text-amber-400',
                        'slug' => 'history-quiz',
                        'desc' => 'Answer inquiries about the ancient timeline and history of the realms.'
                    ],
                ];
            @endphp
            @foreach($quickGames as $qg)
            <div class="sigil-card rounded-xl p-5 flex flex-col justify-between h-64 border-t-2" style="background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, rgba(20,20,25,0.5) 100%)">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[10px] font-black uppercase tracking-wider {{ $qg['text'] }} bg-black/60 border border-[var(--panel-border)] px-2.5 py-0.5 rounded-full">{{ $qg['cat'] }}</span>
                        <i class="fa-solid {{ $qg['icon'] }} {{ $qg['text'] }} text-2xl drop-shadow-[0_0_8px_rgba(255,255,255,0.15)]"></i>
                    </div>
                    <h4 class="font-cinzel font-black text-lg text-white mb-1.5 tracking-wide">{{ $qg['name'] }}</h4>
                    <p class="text-xs text-[var(--text-secondary)] leading-relaxed mb-4">{{ $qg['desc'] }}</p>
                </div>
                
                <a href="{{ route('games.show', $qg['slug']) }}" class="w-full text-center py-2.5 rounded-lg border border-[var(--accent-color)] hover:bg-[var(--accent-glow)] transition-all font-cinzel font-bold text-[10px] uppercase tracking-widest text-[var(--text-accent)] hover:text-white flex items-center justify-center shadow-[inset_0_0_10px_rgba(0,0,0,0.5)]">
                    Engage Runic Battle &nbsp;<i class="fa-solid fa-swords text-[10px]"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Active Quests and Leaderboard -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Quests Section -->
        <div class="xl:col-span-2 space-y-8" data-aos="fade-right">
            <div>
                <h2 class="text-2xl font-cinzel font-bold text-white mb-4 border-b border-[var(--panel-border)] pb-2 flex items-center">
                    <i class="fa-solid fa-scroll mr-2 text-[var(--text-accent)]"></i> Quest Log: Royal Orders
                </h2>
                
                <!-- Parchment Scroll Design -->
                <div class="parchment-scroll animate-scroll-glow border-4">
                    <!-- Scroll curls decoration -->
                    <div class="absolute -top-3 left-4 right-4 h-2.5 bg-[#4a2f0f] rounded-full opacity-70"></div>
                    <div class="absolute -bottom-3 left-4 right-4 h-2.5 bg-[#4a2f0f] rounded-full opacity-70"></div>
                    
                    <div class="flex items-center justify-between mb-4 border-b-2 border-dashed border-[#5a4120]/40 pb-2">
                        <span class="font-cinzel font-black text-sm text-[#4a2f0f] tracking-widest uppercase"><i class="fa-solid fa-shield-halved mr-1"></i> Active Mandates</span>
                        <span class="text-[9px] font-bold text-[#623e16] uppercase bg-white/20 border border-[#5a4120]/30 px-3 py-0.5 rounded-full">Reset in 14h</span>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Quest 1 -->
                        <div class="parchment-item p-4 rounded flex items-center justify-between">
                <div class="flex items-center gap-3 px-4 py-3">

    <img src="{{ asset('favicon.ico') }}"
         alt="Logo"
         class="w-10 h-10 rounded-full object-cover shadow-[0_0_15px_rgba(255,80,0,0.6)]">

    <div class="leading-tight">
        <h1 class="text-2xl font-bold text-white">
            Conquest of
        </h1>
        <h1 class="text-2xl font-bold text-white">
            Winter
        </h1>
    </div>

</div>
                            <div class="text-right">
                                <div class="text-xs font-black text-[#854d0e] mb-1 font-cinzel flex items-center justify-end">
                                    <i class="fa-solid fa-star text-xs mr-1"></i> +50 XP</div>
                                <div class="w-24 h-2 bg-[#d97706]/10 rounded-full overflow-hidden border border-[#5a4120]/30 shadow-inner">
                                    <div class="bg-[#854d0e] h-full w-[33%]"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quest 2 (Completed) -->
                        <div class="parchment-item p-4 rounded flex items-center justify-between opacity-60 relative overflow-hidden">
                            <!-- Completing stamp badge -->
                            <div class="absolute -right-2 -bottom-2 -rotate-12 opacity-15 text-6xl font-black text-green-800 pointer-events-none select-none font-cinzel">
                                SEALED
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-800/10 border border-green-800/60 flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-circle-check text-green-800"></i>
                                </div>
                                <div>
                                    <h4 class="font-cinzel font-bold text-[#2b1d0c] text-sm line-through tracking-wide">First Conquest</h4>
                                    <p class="text-xs text-[#623e16] tracking-wide line-through">Claim a high score victory on any challenge</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black font-cinzel text-green-800 uppercase tracking-widest bg-green-800/15 border border-green-800/30 px-3 py-1 rounded">Fulfilled</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Leaderboard & Activity -->
        <div class="space-y-8" data-aos="fade-left">
            <!-- Top Players Leaderboard -->
            <div class="got-panel rounded-xl overflow-hidden">
                <div class="bg-black/50 p-4 border-b border-[var(--panel-border)] flex items-center justify-between">
                    <h3 class="font-cinzel font-bold text-sm flex items-center text-white"><i class="fa-solid fa-crown text-yellow-500 mr-2 animate-bounce"></i> Scroll of Honor</h3>
                    <a href="{{ route('leaderboards.index') }}" class="text-xs text-[var(--text-accent)] hover:underline">Standings <i class="fa-solid fa-chevron-right ml-0.5 text-[8px]"></i></a>
                </div>
                
                <div class="divide-y divide-[var(--panel-border)]/20">
                    @foreach($topPlayers as $idx => $p)
                    <div class="flex items-center justify-between p-3.5 hover:bg-white/5 transition-all duration-200">
                        <div class="flex items-center">
                            <!-- Position / Crown -->
                            <span class="w-6 text-center text-xs font-black mr-2">
                                @if($idx == 0)
                                    👑
                                @elseif($idx == 1)
                                    🥈
                                @elseif($idx == 2)
                                    🥉
                                @else
                                    <span class="text-gray-500 font-cinzel">{{ $idx + 1 }}</span>
                                @endif
                            </span>
                            
                            <!-- Avatar with theme responsive border -->
                            <div class="w-9 h-9 rounded-full bg-black border-2 {{ $idx==0?'border-yellow-500':($idx==1?'border-gray-400':'border-[var(--panel-border)]') }} flex items-center justify-center overflow-hidden mr-3 shadow-inner">
                                @if($p->avatar)
                                    <img src="{{ asset('storage/'.$p->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user text-gray-500 text-sm"></i>
                                @endif
                            </div>
                            
                            <div>
                                <div class="text-sm font-bold text-white">{{ $p->name }}</div>
                                <div class="text-[9px] text-[var(--text-accent)] uppercase font-cinzel font-bold tracking-wider"><i class="fa-brands fa-d-and-d mr-1 text-[var(--accent-color)]"></i>{{ $p->house }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-cinzel font-black text-yellow-400">{{ number_format($p->xp) }}</div>
                            <div class="text-[8px] text-[var(--text-secondary)] font-bold uppercase tracking-wider">Total XP</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity Chronicles -->
            <div class="got-panel rounded-xl p-5">
                <h3 class="font-cinzel font-bold text-sm mb-4 border-b border-[var(--panel-border)] pb-2 flex items-center text-white">
                    <i class="fa-solid fa-hourglass-start text-[var(--text-accent)] mr-2"></i> Realm Chronicles
                </h3>
                
                <div class="space-y-5">
                    @forelse($recentActivity as $act)
                    <div class="flex border-l-2 border-[var(--panel-border)] pl-4 relative">
                        <!-- Runic glowing timeline node -->
                        <div class="absolute -left-[5px] top-1.5 w-2 h-2 rounded-full bg-[var(--accent-color)] shadow-[0_0_8px_var(--accent-glow)] animate-pulse-glow"></div>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-200 leading-relaxed font-semibold">{{ $act->details }}</p>
                            <p class="text-[9px] text-[var(--text-secondary)] font-bold uppercase tracking-wider mt-1.5 flex items-center">
                                <i class="fa-solid fa-clock mr-1 text-[var(--text-accent)]"></i>{{ $act->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-xs text-[var(--text-secondary)] italic p-2 border border-[var(--panel-border)]/20 rounded-lg bg-black/30 text-center">
                        "The battle lines are silent. The realm awaits your command."
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Script to render ApexCharts on Dashboard -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dynamic Chart Color Theme based on active user theme
            const isFire = '{{ $user->theme_preference ?? "fire" }}' === 'fire';
            const primaryColor = isFire ? '#ea580c' : '#0ea5e9'; // Orange vs Sky Blue
            
            // Area Chart: Experience Growth
            var xpOptions = {
                series: [{
                    name: 'XP Acquired',
                    data: {!! json_encode($weeklyXpData) !!}
                }],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: { show: false },
                    background: 'transparent',
                    fontFamily: 'Rajdhani, sans-serif'
                },
                colors: [primaryColor],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.6,
                        opacityTo: 0.05,
                        stops: [0, 95, 100],
                        colorStops: [
                            { offset: 0, color: primaryColor, opacity: 0.5 },
                            { offset: 100, color: primaryColor, opacity: 0.01 }
                        ]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3, colors: [primaryColor] },
                xaxis: {
                    categories: {!! json_encode($weeklyXpLabels) !!},
                    labels: { style: { colors: '#9ca3af', fontFamily: 'Rajdhani, sans-serif', fontWeight: 'bold' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { style: { colors: '#9ca3af', fontFamily: 'Rajdhani, sans-serif', fontWeight: 'bold' } }
                },
                grid: {
                    borderColor: 'rgba(255,255,255,0.06)',
                    strokeDashArray: 4,
                },
                tooltip: {
                    theme: 'dark',
                    y: { formatter: function(v) { return v + " XP"; } }
                },
                theme: { mode: 'dark' }
            };

            var xpChart = new ApexCharts(document.querySelector("#dashboard-xp-chart"), xpOptions);
            xpChart.render();

            // Radar Chart: Mastery profile
            var masteryOptions = {
                series: [{
                    name: 'Mastery Rating',
                    data: {!! json_encode($masteryData) !!}
                }],
                chart: {
                    type: 'radar',
                    height: 290,
                    toolbar: { show: false },
                    background: 'transparent',
                    fontFamily: 'Cinzel, serif'
                },
                labels: {!! json_encode($masteryLabels) !!},
                stroke: {
                    width: 2,
                    colors: [primaryColor]
                },
                fill: {
                    opacity: 0.35,
                    colors: [primaryColor]
                },
                markers: { size: 4, colors: ['#fff'], strokeColors: primaryColor, strokeWidth: 2 },
                yaxis: { show: false, min: 0, max: 100 },
                xaxis: {
                    labels: {
                        style: {
                            colors: ['#cbd5e1', '#cbd5e1', '#cbd5e1', '#cbd5e1', '#cbd5e1'],
                            fontSize: '11px',
                            fontFamily: 'Cinzel, serif',
                            fontWeight: 'bold'
                        }
                    }
                },
                plotOptions: {
                    radar: {
                        polygons: {
                            strokeColors: 'rgba(255,255,255,0.08)',
                            strokeWidth: 1,
                            connectorColors: 'rgba(255,255,255,0.08)'
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: { formatter: function(v) { return v + " Mastery pts"; } }
                },
                theme: { mode: 'dark' }
            };

            var masteryChart = new ApexCharts(document.querySelector("#dashboard-mastery-chart"), masteryOptions);
            masteryChart.render();
        });
    </script>

</x-app-layout>
