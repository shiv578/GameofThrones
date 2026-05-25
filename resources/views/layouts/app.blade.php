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

.got-sidebar {

    background: var(--panel-bg);

    backdrop-filter: blur(20px);

    -webkit-backdrop-filter: blur(20px);

    border-right: 1px solid var(--panel-border);

    transition: all 0.35s ease;

    width: 16rem;

}

/* HIDDEN SIDEBAR */

.got-sidebar.sidebar-collapsed{

    transform: translateX(-100%);

}
        .got-sidebar.sidebar-collapsed {
            transform: translateX(-100%);
            width: 0;
            opacity: 0;
            overflow: hidden;
            border-right: none;
            pointer-events: none;
        }

        /* Hamburger Toggle Button */
        .sidebar-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--panel-border);
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }
        .sidebar-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            color: var(--text-accent);
            box-shadow: 0 0 12px var(--accent-glow);
            border-color: var(--accent-color);
        }

        /* Close button pinned to top-right corner of sidebar */
        .sidebar-close-corner {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 25;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: 1px solid var(--panel-border);
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.25s ease;
            font-size: 0.8rem;
        }
        .sidebar-close-corner:hover {
            background: rgba(255, 255, 255, 0.15);
            color: var(--text-accent);
            box-shadow: 0 0 10px var(--accent-glow);
            border-color: var(--accent-color);
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
        }

        /* ===== Premium Daily Rewards Button ===== */
        .daily-reward-btn {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 18px 6px 12px;
            background: linear-gradient(135deg, #1a0a2e 0%, #2d0a0a 30%, #0a1628 70%, #1a0a2e 100%);
            border: 2px solid;
            border-image: linear-gradient(135deg, #ffd700, #ff8c00, #ffd700, #ff6347, #ffd700) 1;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow:
                0 0 15px rgba(255, 165, 0, 0.3),
                0 0 30px rgba(255, 69, 0, 0.15),
                inset 0 1px 0 rgba(255, 215, 0, 0.15),
                inset 0 -1px 0 rgba(0, 0, 0, 0.5);
            overflow: visible;
            font-family: 'Cinzel', serif;
        }
        .daily-reward-btn::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(255,215,0,0.15), transparent 40%, rgba(255,69,0,0.1), transparent);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .daily-reward-btn:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow:
                0 0 25px rgba(255, 165, 0, 0.5),
                0 0 50px rgba(255, 69, 0, 0.25),
                0 4px 15px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 215, 0, 0.25);
        }
        .daily-reward-btn:hover::before { opacity: 1; }
        .daily-reward-btn .gift-icon {
            font-size: 1.6rem;
            background: linear-gradient(180deg, #ff4500 0%, #8b0000 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 6px rgba(255, 69, 0, 0.8)) drop-shadow(0 2px 3px rgba(0,0,0,0.6));
            animation: gift-pulse 2s ease-in-out infinite;
        }
        @keyframes gift-pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            25% { transform: scale(1.08) rotate(-3deg); }
            50% { transform: scale(1) rotate(0deg); }
            75% { transform: scale(1.05) rotate(3deg); }
        }
        .daily-reward-btn .btn-text {
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            background: linear-gradient(180deg, #ffd700 0%, #ff8c00 50%, #ffd700 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: none;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.8));
        }
        .daily-reward-btn .notif-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 22px;
            height: 22px;
            background: linear-gradient(135deg, #ff0000, #cc0000);
            border: 2px solid #ffd700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 900;
            color: #fff;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.6), 0 2px 4px rgba(0,0,0,0.5);
            animation: badge-bounce 1.5s ease-in-out infinite;
            z-index: 5;
        }
        @keyframes badge-bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .daily-reward-btn.claimed .notif-badge { display: none; }
        .daily-reward-btn.claimed { opacity: 0.5; pointer-events: none; }
        .daily-reward-btn.claimed .btn-text {
            background: linear-gradient(180deg, #888 0%, #666 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ===== Reward Popup Animations ===== */
        @keyframes reward-slide-in {
            from { opacity: 0; transform: scale(0.7) translateY(40px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes reward-coin-rain {
            0% { transform: translateY(-20px) rotate(0deg); opacity: 0; }
            20% { opacity: 1; }
            100% { transform: translateY(60px) rotate(360deg); opacity: 0; }
        }
        .reward-popup-enter { animation: reward-slide-in 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .coin-particle {
            position: absolute;
            font-size: 1.2rem;
            animation: reward-coin-rain 1.5s ease-out forwards;
            pointer-events: none;
        }
            padding-left: 1.2rem;
            text-shadow: 0 0 5px var(--accent-glow);
        }
        .nav-text-wrap{
    display:flex;
    flex-direction:column;
    line-height:1.1;
}

.coming-soon-badge{
    font-size:9px;
    font-weight:700;
    letter-spacing:1.8px;
    color:#f7c58a;
    text-transform:uppercase;
    margin-top:3px;
    font-family:'Cinzel', serif;

    animation:comingGlow 1.5s ease-in-out infinite;
}

@keyframes comingGlow{

    0%{
        opacity:0.45;
        text-shadow:
        0 0 2px rgba(255,180,90,0.3),
        0 0 4px rgba(255,140,0,0.2);
    }

    50%{
        opacity:1;
        text-shadow:
        0 0 4px rgba(255,220,150,0.9),
        0 0 8px rgba(255,170,70,0.9),
        0 0 14px rgba(255,120,0,0.8);
    }

    100%{
        opacity:0.45;
        text-shadow:
        0 0 2px rgba(255,180,90,0.3),
        0 0 4px rgba(255,140,0,0.2);
    }
}

.music-switch{
    position:relative;
    width:65px;
    height:32px;
    display:inline-block;
}

.music-switch input{
    opacity:0;
    width:0;
    height:0;
}

.music-slider{
    position:absolute;
    cursor:pointer;
    inset:0;
    background:rgba(255,255,255,0.15);
    border:1px solid rgba(255,140,0,0.4);
    transition:.4s;
    border-radius:50px;
}

.music-slider:before{
    position:absolute;
    content:"";
    height:24px;
    width:24px;
    left:4px;
    bottom:3px;
    background:white;
    transition:.4s;
    border-radius:50%;
}

.music-switch input:checked + .music-slider{
    background:linear-gradient(90deg,#ff5e00,#ff9900);
    box-shadow:0 0 15px rgba(255,120,0,0.6);
}

.music-switch input:checked + .music-slider:before{
    transform:translateX(32px);
}

.music-range{
    height:7px;
    appearance:none;
    border-radius:20px;
    background:rgba(255,255,255,0.15);
}

.music-range::-webkit-slider-thumb{
    appearance:none;
    width:18px;
    height:18px;
    border-radius:50%;
    background:#ff8800;
    cursor:pointer;
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen text-[var(--text-primary)]">
    
    <!-- Backgrounds -->
    <div class="bg-layer bg-dragons"></div>
    <canvas id="particles-canvas"></canvas>

    <div class="flex h-screen overflow-hidden">
        

        <!-- Sidebar -->
         <!-- MOBILE OVERLAY -->

<div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9998] md:hidden"
></div>
<aside
id="sidebar"
class="got-sidebar fixed md:relative top-0 left-0 h-full z-[9999] flex flex-col"
:class="{ 'sidebar-collapsed': !sidebarOpen }"
>
            <!-- Close button pinned to top-right corner -->
            <button @click="sidebarOpen = false" class="sidebar-close-corner" title="Hide Sidebar">
                <i class="fa-solid fa-xmark"></i>
            </button>
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


                <!-- ALL GAMES -->
<a href="{{ route('all.games') }}"
   class="nav-item {{ request()->routeIs('all.games') ? 'active' : '' }}">
    
    <i class="fa-solid fa-gamepad nav-icon"></i>
    <span>All Games</span>

</a>
                
      <!-- Brain Games -->
<div x-data="{ openBrain: false }" class="mb-2">

    <button
        @click="openBrain = !openBrain"
        class="nav-item w-full flex items-center justify-between"
    >
        <div class="flex items-center">
            <i class="fa-solid fa-brain nav-icon"></i>
            <span>Brain Games</span>
        </div>

        <i class="fa-solid text-xs"
           :class="openBrain ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
    </button>

    <div x-show="openBrain" x-transition class="space-y-1 overflow-hidden">
        <a href="{{ route('games.show', 'iq-challenge') }}" class="nav-sub-item">IQ Challenge</a>
        <a href="{{ route('games.show', 'pattern-solver') }}" class="nav-sub-item">Pattern Solver</a>
        <a href="{{ route('games.show', 'logic-master') }}" class="nav-sub-item">Logic Master</a>
    </div>

</div>

<!-- Puzzle Arena -->
<div x-data="{ openPuzzle: false }" class="mb-2">

    <button
        @click="openPuzzle = !openPuzzle"
        class="nav-item w-full flex items-center justify-between"
    >
        <div class="flex items-center">
            <i class="fa-solid fa-puzzle-piece nav-icon"></i>
            <span>Puzzle Arena</span>
        </div>

        <i class="fa-solid text-xs"
           :class="openPuzzle ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
    </button>

    <div x-show="openPuzzle" x-transition class="space-y-1 overflow-hidden">
        <a href="{{ route('games.show', 'maze-escape') }}" class="nav-sub-item">Maze Escape</a>
        <a href="{{ route('games.show', 'block-puzzle') }}" class="nav-sub-item">Block Puzzle</a>
        <a href="{{ route('games.show', 'treasure-unlock') }}" class="nav-sub-item">Treasure Unlock</a>
    </div>

</div>

<!-- Quiz Kingdom -->
<div x-data="{ openQuiz: false }" class="mb-2">

    <button
        @click="openQuiz = !openQuiz"
        class="nav-item w-full flex items-center justify-between"
    >
        <div class="flex items-center">
            <i class="fa-solid fa-clipboard-question nav-icon"></i>
            <span>Quiz Kingdom</span>
        </div>

        <i class="fa-solid text-xs"
           :class="openQuiz ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
    </button>

    <div x-show="openQuiz" x-transition class="space-y-1 overflow-hidden">
        <a href="{{ route('games.show', 'history-quiz') }}" class="nav-sub-item">History Quiz</a>
        <a href="{{ route('games.show', 'science-quiz') }}" class="nav-sub-item">Science Quiz</a>
        <a href="{{ route('games.show', 'coding-quiz') }}" class="nav-sub-item">Coding Quiz</a>
    </div>

</div>

<!-- Strategy Lab -->
<div x-data="{ openStrategy: false }" class="mb-2">

    <button
        @click="openStrategy = !openStrategy"
        class="nav-item w-full flex items-center justify-between"
    >
        <div class="flex items-center">
            <i class="fa-solid fa-chess-knight nav-icon"></i>
            <span>Strategy Lab</span>
        </div>

        <i class="fa-solid text-xs"
           :class="openStrategy ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
    </button>

    <div x-show="openStrategy" x-transition class="space-y-1 overflow-hidden">
        <a href="{{ route('games.show', 'kingdom-defense') }}" class="nav-sub-item">Kingdom Defense</a>
        <a href="{{ route('games.show', 'chess-war') }}" class="nav-sub-item">Chess War</a>
        <a href="{{ route('games.show', 'empire-builder') }}" class="nav-sub-item">Empire Builder</a>
    </div>

</div>

<!-- Memory Challenge -->
<div x-data="{ openMemory: false }" class="mb-2">

    <button
        @click="openMemory = !openMemory"
        class="nav-item w-full flex items-center justify-between"
    >
        <div class="flex items-center">
            <i class="fa-solid fa-eye nav-icon"></i>
            <span>Memory Game</span>
        </div>

        <i class="fa-solid text-xs"
           :class="openMemory ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
    </button>

    <div x-show="openMemory" x-transition class="space-y-1 overflow-hidden">
        <a href="{{ route('games.show', 'memory-flip') }}" class="nav-sub-item">Memory Flip</a>
        <a href="{{ route('games.show', 'sequence-recall') }}" class="nav-sub-item">Sequence Recall</a>
        <a href="{{ route('games.show', 'hidden-object') }}" class="nav-sub-item">Hidden Object</a>
    </div>

</div>

<!-- Toys Games -->
<div x-data="{ openToys: false }" class="mb-4">

    <button
        @click="openToys = !openToys"
        class="nav-item w-full flex items-center justify-between"
    >
        <div class="flex items-center">
            <i class="fa-solid fa-gamepad nav-icon"></i>
            <span>Toys Games</span>
        </div>

        <i class="fa-solid text-xs"
           :class="openToys ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
    </button>

    <div x-show="openToys" x-transition class="space-y-1 overflow-hidden">
        <a href="{{ route('games.show', 'toys-game-1') }}" class="nav-sub-item">Toys Game 1</a>
        <a href="{{ route('games.show', 'toys-game-2') }}" class="nav-sub-item">Toys Game 2</a>
        <a href="{{ route('games.show', 'toys-game-3') }}" class="nav-sub-item">Toys Game 3</a>
    </div>

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
    <i class="fa-solid fa-gift nav-icon"></i>

    <div class="nav-text-wrap">
        <span>Rewards Center</span>
        <span class="coming-soon-badge">Locked</span>
    </div>
</a>
          <a href="#" class="nav-item">
    <i class="fa-solid fa-users-rays nav-icon"></i>

    <div class="nav-text-wrap">
        <span>Multiplayer Zone</span>
        <span class="coming-soon-badge">Locked</span>
    </div>
</a>
                <div class="px-4 py-2 mt-4 mb-2 text-xs uppercase tracking-wider text-[var(--text-secondary)] font-bold">Account</div>
                
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield nav-icon"></i> User Profile
                </a>
           <a href="#" class="nav-item">
    <i class="fa-solid fa-users-rays nav-icon"></i>

    <div class="nav-text-wrap">
        <span>MESSAGES</span>
        <span class="coming-soon-badge">Locked</span>
    </div>
</a>
                <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear nav-icon"></i> Settings
                </a>
            </div>
            
      <div class="p-4 border-t border-[var(--panel-border)] flex justify-center">
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;">
           <img 
    src="{{ asset('images/logout-dragon-btn.png') }}" 
    alt="Logout"
    style="
        width: 150px;
        height: auto;
        display: block;
        transition: all 0.3s ease;
    "
    onmouseover="
        this.style.filter='drop-shadow(0 0 8px #ff4500) drop-shadow(0 0 18px #ff2200)';
    "
    onmouseout="
        this.style.filter='none';
    "
>
        </button>
    </form>
</div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full relative z-10 overflow-hidden">
            
            <!-- Topbar -->
            <header class="h-20 got-panel border-b-0 border-l-0 border-r-0 flex items-center justify-between px-6 shrink-0">
                
                <div class="flex items-center space-x-6">
                    <!-- Sidebar Toggle (hamburger) -->
                    <button 
                        x-show="!sidebarOpen" 
                        @click="sidebarOpen = true" 
                        class="sidebar-toggle-btn" 
                        title="Open Sidebar"
                    >
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    
                    <!-- Stats Badges -->
                    <div class="hidden sm:flex space-x-3 items-center">
                        <div class="flex items-center space-x-2 bg-black/40 px-3 py-1.5 rounded-full border border-[var(--panel-border)]">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <span class="font-bold font-cinzel">{{ auth()->user()->xp ?? 0 }} XP</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-black/40 px-3 py-1.5 rounded-full border border-[var(--panel-border)]">
                            <i class="fa-solid fa-coins text-yellow-500"></i>
                            <span class="font-bold font-cinzel" id="topbar-coins">{{ auth()->user()->coins ?? 0 }}</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-black/40 px-3 py-1.5 rounded-full border border-[var(--panel-border)]">
                            <i class="fa-solid fa-gem text-blue-400"></i>
                            <span class="font-bold font-cinzel" id="topbar-diamonds">{{ auth()->user()->diamonds ?? 0 }}</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-black/40 px-3 py-1.5 rounded-full border border-[var(--panel-border)]">
                            <i class="fa-solid fa-shield-halved text-[var(--accent-color)]"></i>
                            <span class="font-bold font-cinzel">Lvl {{ auth()->user()->level ?? 1 }}</span>
                        </div>

                        <!-- Premium Daily Rewards Button -->
                        @php
                            $now = now('Asia/Kolkata');
                            $resetTime = $now->copy()->startOfDay()->addHours(5)->addMinutes(30);
                            if ($now->lt($resetTime)) {
                                $resetTime = $resetTime->subDay();
                            }
                            $canClaim = !auth()->user()->last_reward_claimed_at || auth()->user()->last_reward_claimed_at->lt($resetTime);
                            $unopenedBoxCount = \App\Models\UserBox::where('user_id', auth()->id())->where('is_opened', false)->count();
                        @endphp
                        <button type="button" class="daily-reward-btn {{ !$canClaim ? 'claimed' : '' }}" id="daily-reward-trigger" onclick="claimDailyReward()">
                            @if($canClaim)
                                <span class="notif-badge" id="dr-badge">1</span>
                            @endif
                            <i class="fa-solid fa-gift gift-icon"></i>
                            <span class="btn-text">DAILY REWARDS</span>
                        </button>
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
                sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
                
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
                },

                init() {
                    this.$watch('sidebarOpen', (val) => {
                        localStorage.setItem('sidebarOpen', val);
                    });
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

    <!-- ====== Daily Reward Claimed Modal ====== -->
    <div id="daily-reward-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden" style="perspective: 1000px;">
        <div class="absolute inset-0 bg-black/85 backdrop-blur-md" onclick="closeDailyModal()"></div>
        <div class="relative w-full max-w-md mx-4 reward-popup-enter" id="daily-reward-box">
            <!-- Particle rain container -->
            <div id="coin-rain" class="absolute inset-0 overflow-hidden pointer-events-none z-10"></div>

            <div class="relative bg-gradient-to-b from-[#1a0a2e] via-[#0d0d2b] to-[#0a0a1a] rounded-2xl border-2 border-yellow-600/60 shadow-[0_0_60px_rgba(255,165,0,0.3),0_0_120px_rgba(255,69,0,0.15)] overflow-hidden">
                <!-- Top glow bar -->
                <div class="h-1 bg-gradient-to-r from-transparent via-yellow-500 to-transparent"></div>
                
                <!-- Floating gift icon -->
                <div class="flex justify-center -mt-1 pt-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-red-700 to-red-900 border-4 border-yellow-500 flex items-center justify-center shadow-[0_0_30px_rgba(255,69,0,0.6),0_0_60px_rgba(255,215,0,0.3)]" style="animation: gift-pulse 2s ease-in-out infinite;">
                        <i class="fa-solid fa-gift text-4xl text-yellow-300 drop-shadow-[0_2px_8px_rgba(255,215,0,0.8)]"></i>
                    </div>
                </div>

                <div class="text-center pt-5 pb-2 px-6">
                    <h2 class="text-3xl font-cinzel font-black bg-gradient-to-r from-yellow-300 via-yellow-500 to-orange-400 bg-clip-text text-transparent drop-shadow-lg">Daily Reward Claimed!</h2>
                    <p class="text-sm text-gray-400 mt-2 font-rajdhani">Your royal provisions for today have been granted.</p>
                </div>

                <!-- Reward Items -->
                <div class="grid grid-cols-3 gap-4 px-6 py-5">
                    <div class="bg-black/60 border border-yellow-700/40 rounded-xl p-4 text-center shadow-[inset_0_0_15px_rgba(255,165,0,0.08)]">
                        <i class="fa-solid fa-coins text-3xl text-yellow-500 mb-2 drop-shadow-[0_0_10px_rgba(255,200,0,0.5)]"></i>
                        <div class="text-xl font-black text-yellow-300 font-cinzel" id="modal-coins">+200</div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mt-1">Gold Coins</div>
                    </div>
                    <div class="bg-black/60 border border-blue-600/40 rounded-xl p-4 text-center shadow-[inset_0_0_15px_rgba(59,130,246,0.08)]">
                        <i class="fa-solid fa-gem text-3xl text-blue-400 mb-2 drop-shadow-[0_0_10px_rgba(59,130,246,0.5)]"></i>
                        <div class="text-xl font-black text-blue-300 font-cinzel" id="modal-diamonds">+1</div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mt-1">Diamond</div>
                    </div>
                    <div class="bg-black/60 border border-purple-600/40 rounded-xl p-4 text-center shadow-[inset_0_0_15px_rgba(168,85,247,0.08)]">
                        <i class="fa-solid fa-box-open text-3xl text-purple-400 mb-2 drop-shadow-[0_0_10px_rgba(168,85,247,0.5)]"></i>
                        <div class="text-xl font-black text-purple-300 font-cinzel" id="modal-boxes">+1</div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mt-1">Mystery Box</div>
                    </div>
                </div>

                <div class="px-6 pb-6">
                    <button onclick="closeDailyModal()" class="w-full py-3 rounded-xl font-cinzel font-black text-lg tracking-wider bg-gradient-to-r from-yellow-700 via-yellow-600 to-orange-600 hover:from-yellow-600 hover:via-yellow-500 hover:to-orange-500 text-black shadow-[0_0_20px_rgba(255,165,0,0.4)] hover:shadow-[0_0_30px_rgba(255,165,0,0.6)] transition-all transform hover:scale-[1.02]">
                        <i class="fa-solid fa-check mr-2"></i> Collect Rewards
                    </button>
                </div>
                <div class="h-1 bg-gradient-to-r from-transparent via-yellow-500 to-transparent"></div>
            </div>
        </div>
    </div>

    <!-- ====== Mystery Box Modal ====== -->
    <div id="mystery-box-modal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/85 backdrop-blur-md" onclick="closeMysteryModal()"></div>
        <div class="relative w-full max-w-sm mx-4 reward-popup-enter">
            <div class="bg-gradient-to-b from-[#1a0a2e] via-[#150d30] to-[#0a0a1a] rounded-2xl border-2 border-purple-600/60 shadow-[0_0_60px_rgba(168,85,247,0.3)] overflow-hidden text-center">
                <div class="h-1 bg-gradient-to-r from-transparent via-purple-500 to-transparent"></div>
                <h2 class="text-2xl font-cinzel font-black text-purple-300 pt-6 mb-1">Mystery Box</h2>
                <p class="text-xs text-gray-500 font-bold mb-5">Unveil the ancient secrets within.</p>

                <div class="relative h-36 flex items-center justify-center mb-5">
                    <i id="mbox-idle" class="fa-solid fa-box text-8xl text-purple-500 drop-shadow-[0_0_25px_rgba(168,85,247,0.8)] cursor-pointer hover:scale-110 transition-transform" style="animation: gift-pulse 2s ease-in-out infinite;"></i>
                    <div id="mbox-spinning" class="hidden absolute inset-0 flex items-center justify-center">
                        <i class="fa-solid fa-box-open text-8xl text-purple-400" style="animation: spin 0.4s linear infinite;"></i>
                    </div>
                    <div id="mbox-result" class="hidden absolute inset-0 flex flex-col items-center justify-center" style="animation: reward-slide-in 0.5s ease forwards;">
                        <i id="mbox-result-icon" class="fa-solid fa-coins text-6xl text-yellow-400 mb-2 drop-shadow-[0_0_20px_rgba(255,200,0,0.8)]"></i>
                        <span id="mbox-result-text" class="text-2xl font-black text-yellow-300 font-cinzel">+0</span>
                    </div>
                </div>

                <div class="px-6 pb-6 space-y-3">
                    <button id="mbox-open-btn" onclick="openMysteryBox()" class="w-full py-3 rounded-xl font-cinzel font-black text-base tracking-wider bg-gradient-to-r from-purple-800 to-purple-600 hover:from-purple-700 hover:to-purple-500 text-white shadow-[0_0_15px_rgba(168,85,247,0.5)] transition-all">
                        <i class="fa-solid fa-lock-open mr-2"></i> Open Mystery Box
                    </button>
                    <button id="mbox-close-btn" onclick="closeMysteryModal()" class="hidden w-full py-2.5 rounded-xl font-cinzel font-bold text-sm bg-gray-800 hover:bg-gray-700 text-gray-300 transition-all">
                        Close
                    </button>
                </div>
                <div class="h-1 bg-gradient-to-r from-transparent via-purple-500 to-transparent"></div>
            </div>
        </div>
    </div>

    <!-- ====== Reward System JS ====== -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function claimDailyReward() {
            const btn = document.getElementById('daily-reward-trigger');
            if (btn.classList.contains('claimed')) {
                // Already claimed — open mystery box modal if user has boxes
                showMysteryModal();
                return;
            }

            fetch('{{ route("rewards.daily") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('modal-coins').innerText = '+' + data.coins;
                    document.getElementById('modal-diamonds').innerText = '+' + data.diamonds;
                    document.getElementById('modal-boxes').innerText = '+' + data.box;

                    // Update topbar
                    document.getElementById('topbar-coins').innerText = data.new_coins_balance.toLocaleString();
                    document.getElementById('topbar-diamonds').innerText = data.new_diamonds_balance.toLocaleString();

                    // Mark button as claimed
                    btn.classList.add('claimed');
                    const badge = document.getElementById('dr-badge');
                    if (badge) badge.style.display = 'none';

                    // Show modal
                    showDailyModal();
                    spawnCoinRain();
                }
            })
            .catch(err => console.error('Daily reward error:', err));
        }

        function showDailyModal() {
            const modal = document.getElementById('daily-reward-modal');
            modal.classList.remove('hidden');
        }

        function closeDailyModal() {
            document.getElementById('daily-reward-modal').classList.add('hidden');
        }

        function showMysteryModal() {
            const modal = document.getElementById('mystery-box-modal');
            modal.classList.remove('hidden');
            // Reset states
            document.getElementById('mbox-idle').classList.remove('hidden');
            document.getElementById('mbox-spinning').classList.add('hidden');
            document.getElementById('mbox-result').classList.add('hidden');
            document.getElementById('mbox-open-btn').classList.remove('hidden');
            document.getElementById('mbox-close-btn').classList.add('hidden');
        }

        function closeMysteryModal() {
            document.getElementById('mystery-box-modal').classList.add('hidden');
        }

        function openMysteryBox() {
            document.getElementById('mbox-idle').classList.add('hidden');
            document.getElementById('mbox-spinning').classList.remove('hidden');
            document.getElementById('mbox-open-btn').classList.add('hidden');

            fetch('{{ route("rewards.mystery-box.open") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                setTimeout(() => {
                    document.getElementById('mbox-spinning').classList.add('hidden');
                    if (data.success) {
                        const icon = document.getElementById('mbox-result-icon');
                        const text = document.getElementById('mbox-result-text');
                        if (data.coins > 0 && data.coins >= data.diamonds) {
                            icon.className = 'fa-solid fa-coins text-6xl text-yellow-400 mb-2 drop-shadow-[0_0_20px_rgba(255,200,0,0.8)]';
                            text.innerText = '+' + data.coins + ' Coins!';
                            text.className = 'text-2xl font-black text-yellow-300 font-cinzel';
                        } else {
                            icon.className = 'fa-solid fa-gem text-6xl text-blue-400 mb-2 drop-shadow-[0_0_20px_rgba(59,130,246,0.8)]';
                            text.innerText = '+' + data.diamonds + ' Diamonds!';
                            text.className = 'text-2xl font-black text-blue-300 font-cinzel';
                        }
                        document.getElementById('mbox-result').classList.remove('hidden');
                        document.getElementById('mbox-close-btn').classList.remove('hidden');

                        document.getElementById('topbar-coins').innerText = data.new_coins_balance.toLocaleString();
                        document.getElementById('topbar-diamonds').innerText = data.new_diamonds_balance.toLocaleString();
                    } else {
                        alert(data.message || 'No mystery boxes available.');
                        closeMysteryModal();
                    }
                }, 1200);
            })
            .catch(err => {
                console.error('Mystery box error:', err);
                closeMysteryModal();
            });
        }

        function spawnCoinRain() {
            const container = document.getElementById('coin-rain');
            if (!container) return;
            const emojis = ['🪙', '💎', '✨', '🪙', '🪙'];
            for (let i = 0; i < 25; i++) {
                const el = document.createElement('span');
                el.className = 'coin-particle';
                el.innerText = emojis[Math.floor(Math.random() * emojis.length)];
                el.style.left = Math.random() * 100 + '%';
                el.style.top = Math.random() * 30 + '%';
                el.style.animationDelay = Math.random() * 0.8 + 's';
                container.appendChild(el);
            }
            setTimeout(() => { container.innerHTML = ''; }, 2500);
        }

        // Auto-claim script removed so user can manually claim the daily reward
    </script>
    <audio id="bgMusic" loop>
    <source src="{{ asset('music/theme.mpeg') }}" type="audio/mpeg">
</audio>

<script>
document.addEventListener("click", function () {

    const music = document.getElementById("bgMusic");

    music.volume = 0.3;

    music.play();

}, { once: true });

function toggleMusic(){

    const music = document.getElementById("bgMusic");

    if(music.paused){

        music.play();

    }else{

        music.pause();

    }
}
</script>
    
</body>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.querySelector('.flex-1.overflow-y-auto');

    const savedScroll = localStorage.getItem("sidebarScroll");

    if (savedScroll !== null) {
        sidebar.scrollTop = savedScroll;
    }

    sidebar.addEventListener("scroll", function () {
        localStorage.setItem("sidebarScroll", sidebar.scrollTop);
    });

});
</script>
</html>
