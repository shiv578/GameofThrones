<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="appData()" :class="'theme-' + theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Conquest of Winter') }}</title>
<link rel="icon" href="{{ asset('favicon.ico') }}?v=3">


    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- GSAP & Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        /* Base Background Layers */
        .bg-layer {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            z-index: -2;
            transition: all 1s ease-in-out;
        }
        
        /* Master Backgrounds */
        .bg-dragons {
            background-image: url('/images/dragons-bg.png');
            opacity: 0.35;
        }
        
        .theme-fire .bg-dragons {
            filter: sepia(0.05) saturate(1.25) hue-rotate(345deg) contrast(1.05);
            opacity: 0.38;
        }
        .theme-ice .bg-dragons {
            filter: saturate(1.25) hue-rotate(195deg) contrast(1.05);
            opacity: 0.42;
        }
        
        html {
            background-color: var(--bg-primary);
        }
        body {
            background: transparent !important;
        }
        
        #particles-canvas {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            pointer-events: none;
        }

        /* Sidebar Styling */
        .got-sidebar {
            background: var(--panel-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--panel-border);
            transition: all 0.3s ease;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            color: var(--text-secondary);
            font-family: 'Cinzel', serif;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.05);
            color: var(--text-accent);
            text-shadow: 0 0 8px var(--accent-glow);
        }
        
        .nav-item.active {
            border-left: 3px solid var(--accent-color);
            background: linear-gradient(90deg, var(--accent-glow) 0%, transparent 100%);
        }
        
        .nav-icon {
            width: 24px;
            text-align: center;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--panel-border); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-color); }

        .nav-sub-item {
            display: block;
            padding: 0.35rem 1rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 0.05em;
            transition: all 0.2s ease;
            border-left: 1px solid var(--panel-border);
            margin-left: 2.2rem;
        }
        .nav-sub-item:hover, .nav-sub-item.active {
            color: var(--text-accent);
            border-left: 2px solid var(--accent-color);
            padding-left: 1.2rem;
            text-shadow: 0 0 5px var(--accent-glow);
        }
    </style>
</head>
<body class="antialiased min-h-screen text-[var(--text-primary)]">
    
    <!-- Backgrounds -->
    <div class="bg-layer bg-dragons"></div>
    <canvas id="particles-canvas"></canvas>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="got-sidebar w-64 flex-shrink-0 flex flex-col hidden md:flex h-full relative z-20">
            <div class="p-6 flex items-center justify-center border-b border-[var(--panel-border)]">
<img src="{{ asset('favicon.ico') }}"
     alt="Logo"
     class="w-10 h-10 rounded-full object-cover mr-3">
                     <h2 class="font-cinzel font-bold text-xl leading-tight">Conquest of<br>Winter</h2>
            </div>
            
            <div class="flex-1 overflow-y-auto py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chess-board nav-icon"></i> Dashboard
                </a>
                
                <div class="px-4 py-2 mt-4 mb-2 text-xs uppercase tracking-wider text-[var(--text-secondary)] font-bold">Games Arena</div>
                
                <a href="{{ route('games.index') }}#brain" class="nav-item">
                    <i class="fa-solid fa-brain nav-icon"></i> Brain Games
                </a>
                <div class="space-y-1 mb-2">
                    <a href="{{ route('games.show', 'iq-challenge') }}" class="nav-sub-item {{ request()->is('games/iq-challenge') ? 'active' : '' }}">IQ Challenge</a>
                    <a href="{{ route('games.show', 'pattern-solver') }}" class="nav-sub-item {{ request()->is('games/pattern-solver') ? 'active' : '' }}">Pattern Solver</a>
                    <a href="{{ route('games.show', 'logic-master') }}" class="nav-sub-item {{ request()->is('games/logic-master') ? 'active' : '' }}">Logic Master</a>
                </div>

                <a href="{{ route('games.index') }}#puzzle" class="nav-item">
                    <i class="fa-solid fa-puzzle-piece nav-icon"></i> Puzzle Arena
                </a>
                <div class="space-y-1 mb-2">
                    <a href="{{ route('games.show', 'maze-escape') }}" class="nav-sub-item {{ request()->is('games/maze-escape') ? 'active' : '' }}">Maze Escape</a>
                    <a href="{{ route('games.show', 'block-puzzle') }}" class="nav-sub-item {{ request()->is('games/block-puzzle') ? 'active' : '' }}">Block Puzzle</a>
                    <a href="{{ route('games.show', 'treasure-unlock') }}" class="nav-sub-item {{ request()->is('games/treasure-unlock') ? 'active' : '' }}">Treasure Unlock</a>
                </div>

                <a href="{{ route('games.index') }}#quiz" class="nav-item">
                    <i class="fa-solid fa-clipboard-question nav-icon"></i> Quiz Kingdom
                </a>
                <div class="space-y-1 mb-2">
                    <a href="{{ route('games.show', 'history-quiz') }}" class="nav-sub-item {{ request()->is('games/history-quiz') ? 'active' : '' }}">History Quiz</a>
                    <a href="{{ route('games.show', 'science-quiz') }}" class="nav-sub-item {{ request()->is('games/science-quiz') ? 'active' : '' }}">Science Quiz</a>
                    <a href="{{ route('games.show', 'coding-quiz') }}" class="nav-sub-item {{ request()->is('games/coding-quiz') ? 'active' : '' }}">Coding Quiz</a>
                </div>

                <a href="{{ route('games.index') }}#strategy" class="nav-item">
                    <i class="fa-solid fa-chess-knight nav-icon"></i> Strategy Lab
                </a>
                <div class="space-y-1 mb-2">
                    <a href="{{ route('games.show', 'kingdom-defense') }}" class="nav-sub-item {{ request()->is('games/kingdom-defense') ? 'active' : '' }}">Kingdom Defense</a>
                    <a href="{{ route('games.show', 'chess-war') }}" class="nav-sub-item {{ request()->is('games/chess-war') ? 'active' : '' }}">Chess War</a>
                    <a href="{{ route('games.show', 'empire-builder') }}" class="nav-sub-item {{ request()->is('games/empire-builder') ? 'active' : '' }}">Empire Builder</a>
                </div>

                <a href="{{ route('games.index') }}#memory" class="nav-item">
                    <i class="fa-solid fa-eye nav-icon"></i> Memory Challenge
                </a>
                <div class="space-y-1 mb-2">
                    <a href="{{ route('games.show', 'memory-flip') }}" class="nav-sub-item {{ request()->is('games/memory-flip') ? 'active' : '' }}">Memory Flip</a>
                    <a href="{{ route('games.show', 'sequence-recall') }}" class="nav-sub-item {{ request()->is('games/sequence-recall') ? 'active' : '' }}">Sequence Recall</a>
                    <a href="{{ route('games.show', 'hidden-object') }}" class="nav-sub-item {{ request()->is('games/hidden-object') ? 'active' : '' }}">Hidden Object</a>
                </div>

                <a href="{{ route('games.index') }}#toys" class="nav-item">
                    <i class="fa-solid fa-gamepad nav-icon"></i> Toys Games
                </a>
                <div class="space-y-1 mb-4">
                    <a href="{{ route('games.show', 'toys-game-1') }}" class="nav-sub-item {{ request()->is('games/toys-game-1') ? 'active' : '' }}">Toys Game 1</a>
                    <a href="{{ route('games.show', 'toys-game-2') }}" class="nav-sub-item {{ request()->is('games/toys-game-2') ? 'active' : '' }}">Toys Game 2</a>
                    <a href="{{ route('games.show', 'toys-game-3') }}" class="nav-sub-item {{ request()->is('games/toys-game-3') ? 'active' : '' }}">Toys Game 3</a>
                </div>
                
                <div class="px-4 py-2 mt-4 mb-2 text-xs uppercase tracking-wider text-[var(--text-secondary)] font-bold">Competition</div>
                
                <a href="{{ route('leaderboards.index') }}" class="nav-item {{ request()->routeIs('leaderboards.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-trophy nav-icon"></i> Leaderboards
                </a>
                <a href="{{ route('analytics.index') }}" class="nav-item {{ request()->routeIs('analytics.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line nav-icon"></i> Analytics
                </a>
                <a href="{{ route('achievements.index') }}" class="nav-item {{ request()->routeIs('achievements.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-medal nav-icon"></i> Achievements
                </a>
                <a href="#" class="nav-item">
                    <i class="fa-solid fa-gift nav-icon"></i> Rewards Center
                </a>
                <a href="#" class="nav-item">
                    <i class="fa-solid fa-users-rays nav-icon"></i> Multiplayer Zone
                </a>
                
                <div class="px-4 py-2 mt-4 mb-2 text-xs uppercase tracking-wider text-[var(--text-secondary)] font-bold">Account</div>
                
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield nav-icon"></i> User Profile
                </a>
                <a href="#" class="nav-item">
                    <i class="fa-solid fa-envelope nav-icon"></i> Messages
                </a>
                <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear nav-icon"></i> Settings
                </a>
            </div>
            
            <div class="p-4 border-t border-[var(--panel-border)]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full got-btn-outline !py-2 text-sm">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full relative z-10 overflow-hidden">
            
            <!-- Topbar -->
            <header class="h-20 got-panel border-b-0 border-l-0 border-r-0 flex items-center justify-between px-6 shrink-0">
                
                <div class="flex items-center space-x-6">
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-[var(--text-secondary)] hover:text-white">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Stats Badges -->
                    <div class="hidden sm:flex space-x-4">
                        <div class="flex items-center space-x-2 bg-black/40 px-3 py-1.5 rounded-full border border-[var(--panel-border)]">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <span class="font-bold font-cinzel">{{ auth()->user()->xp ?? 0 }} XP</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-black/40 px-3 py-1.5 rounded-full border border-[var(--panel-border)]">
                            <i class="fa-solid fa-coins text-yellow-500"></i>
                            <span class="font-bold font-cinzel">{{ auth()->user()->coins ?? 0 }}</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-black/40 px-3 py-1.5 rounded-full border border-[var(--panel-border)]">
                            <i class="fa-solid fa-shield-halved text-[var(--accent-color)]"></i>
                            <span class="font-bold font-cinzel">Lvl {{ auth()->user()->level ?? 1 }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Theme Switcher -->
                    <button @click="toggleTheme()" class="got-btn-outline !p-2 !flex items-center justify-center w-10 h-10 rounded-full">
                        <i class="fa-solid" :class="theme === 'fire' ? 'fa-snowflake' : 'fa-fire'"></i>
                    </button>
                    
                    <!-- Notifications -->
                    <button class="relative text-[var(--text-secondary)] hover:text-white transition">
                        <i class="fa-regular fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">3</span>
                    </button>
                    
                    <!-- User Dropdown -->
                    <div class="flex items-center space-x-3 pl-4 border-l border-[var(--panel-border)] cursor-pointer">
                        <div class="text-right hidden md:block">
                            <div class="text-sm font-bold font-cinzel text-[var(--text-primary)]">{{ auth()->user()->name ?? 'Lord Commander' }}</div>
                            <div class="text-xs text-[var(--text-accent)]">House {{ auth()->user()->house ?? 'Stark' }}</div>
                        </div>
                        <div class="w-10 h-10 rounded-full border-2 border-[var(--accent-color)] bg-black flex items-center justify-center overflow-hidden">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user text-gray-400"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto" id="main-scroll-area">
                <div class="p-6 md:p-8 w-full max-w-7xl mx-auto gs-reveal">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('appData', () => ({
                theme: '{{ auth()->user()->theme_preference ?? "fire" }}',
                
                toggleTheme() {
                    this.theme = this.theme === 'fire' ? 'ice' : 'fire';
                    
                    // Save to DB via AJAX
                    fetch('{{ route("theme.toggle") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ theme: this.theme })
                    });
                    
                    initParticles(this.theme);
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({ 
                duration: 800, 
                once: true,
                scrollContainer: '#main-scroll-area'
            });
            
            gsap.fromTo('.gs-reveal', 
                { opacity: 0, y: 30 }, 
                { opacity: 1, y: 0, duration: 1, ease: 'power3.out' }
            );
            
            initParticles('{{ auth()->user()->theme_preference ?? "fire" }}');
        });

        let particleInterval;
        function initParticles(theme) {
            const canvas = document.getElementById('particles-canvas');
            if(!canvas) return;
            const ctx = canvas.getContext('2d');
            
            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resize);
            resize();
            
            let particles = [];
            if(particleInterval) cancelAnimationFrame(particleInterval);

            // Create initial particles
            for (let i = 0; i < 180; i++) {
                const type = Math.random() > 0.5 ? 'fire' : 'ice';
                particles.push(createParticle(canvas, type, true));
            }

            function createParticle(canvas, type, randomStart = false) {
                const p = { type: type };
                if (type === 'fire') {
                    // Spawns from Red Dragon's mouth (Left side: bottom-left / middle-left)
                    p.x = randomStart ? Math.random() * canvas.width * 0.4 : Math.random() * canvas.width * 0.2;
                    p.y = randomStart ? Math.random() * canvas.height : canvas.height * (0.5 + Math.random() * 0.3);
                    p.radius = Math.random() * 2 + 0.8;
                    // Fire particles rise up and drift rightwards (from left to center-right)
                    p.speedY = -(Math.random() * 1.5 + 0.5);
                    p.speedX = Math.random() * 1.5 + 0.2;
                    p.opacity = Math.random() * 0.8 + 0.2;
                    p.maxLife = Math.random() * 100 + 100;
                    p.life = randomStart ? Math.random() * p.maxLife : 0;
                } else {
                    // Spawns from Blue Dragon's mouth (Right side: top-right / middle-right)
                    p.x = randomStart ? canvas.width * (0.6 + Math.random() * 0.4) : canvas.width * (0.8 + Math.random() * 0.2);
                    p.y = randomStart ? Math.random() * canvas.height : canvas.height * (0.1 + Math.random() * 0.3);
                    p.radius = Math.random() * 2 + 0.5;
                    // Ice particles fall down and drift leftwards (from right to center-left)
                    p.speedY = Math.random() * 1.2 + 0.4;
                    p.speedX = -(Math.random() * 1.2 + 0.2);
                    p.opacity = Math.random() * 0.7 + 0.3;
                    p.maxLife = Math.random() * 120 + 120;
                    p.life = randomStart ? Math.random() * p.maxLife : 0;
                }
                return p;
            }

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach((p, index) => {
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    
                    // Boost opacity/density based on active theme
                    let finalOpacity = p.opacity;
                    if (p.type === 'fire' && theme !== 'fire') finalOpacity *= 0.35; // Dim fire when ice is selected
                    if (p.type === 'ice' && theme !== 'ice') finalOpacity *= 0.35;   // Dim ice when fire is selected

                    if (p.type === 'fire') {
                        // Glowing embers from Red Dragon
                        ctx.fillStyle = `rgba(255, ${Math.floor(Math.random()*120 + 60)}, 0, ${finalOpacity})`;
                        ctx.shadowBlur = theme === 'fire' ? 8 : 4;
                        ctx.shadowColor = "rgba(255, 80, 0, 0.6)";
                    } else {
                        // Ice frost crystals from Blue Dragon
                        ctx.fillStyle = `rgba(180, 225, 255, ${finalOpacity})`;
                        ctx.shadowBlur = theme === 'ice' ? 6 : 2;
                        ctx.shadowColor = "rgba(100, 200, 255, 0.4)";
                    }
                    
                    ctx.fill();

                    p.y += p.speedY;
                    p.x += p.speedX;
                    
                    // Slight wind wave
                    p.x += Math.sin(p.y * 0.015) * 0.4;
                    p.life++;

                    // Respawn once out of bounds or dead
                    if (p.life >= p.maxLife || p.x < -20 || p.x > canvas.width + 20 || p.y < -20 || p.y > canvas.height + 20) {
                        particles[index] = createParticle(canvas, p.type, false);
                    }
                });
                particleInterval = requestAnimationFrame(draw);
            }
            draw();
        }
    </script>
</body>
</html>
