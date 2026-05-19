<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('got_theme') || 'fire' }" :class="'theme-' + theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<title>Conquest of Winter</title>
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        .bg-layer {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-size: cover;
            background-position: center;
            z-index: -2;
            transition: all 1s ease-in-out;
        }
        .bg-dragons {
            background-image: url('/images/dragons-bg.jpg');
            opacity: 0.3;
        }
        .theme-fire .bg-dragons {
            filter: sepia(0.15) saturate(1.3) hue-rotate(345deg) contrast(1.1);
            opacity: 0.4;
        }
        .theme-ice .bg-dragons {
            filter: saturate(1.3) hue-rotate(195deg) contrast(1.1);
            opacity: 0.45;
        }
        
        #particles-canvas {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            pointer-events: none;
        }
    </style> 
</head>
<body class="antialiased min-h-screen relative flex flex-col justify-between py-6 px-4 sm:px-6 lg:px-8 text-[var(--text-primary)]">
    
    <!-- Dynamic Backgrounds -->
    <div class="bg-layer bg-dragons"></div>
    <canvas id="particles-canvas"></canvas>

    <!-- Header Navigation -->
    <header class="w-full max-w-7xl mx-auto flex justify-between items-center z-10 py-4">
        <div class="flex items-center space-x-3">
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button @click="theme = (theme === 'fire' ? 'ice' : 'fire'); localStorage.setItem('got_theme', theme); initParticles(theme);" 
                    class="got-btn-outline rounded-full !p-2.5 !flex items-center justify-center w-10 h-10">
                <i class="fa-solid" :class="theme === 'fire' ? 'fa-snowflake' : 'fa-fire'"></i>
            </button>

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="got-btn rounded-lg text-sm !px-5 !py-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-cinzel tracking-wider text-[var(--text-secondary)] hover:text-white transition-colors mr-2">Log In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="got-btn rounded-lg text-sm !px-5 !py-2">Forge Legacy</a>
                    @endif
                @endauth
            @endif
        </div>
    </header>

    <!-- Main Hero Content -->
    <main class="w-full max-w-4xl mx-auto text-center my-auto z-10 gs-reveal">
        <h2 class="text-xs uppercase tracking-[0.4em] text-[var(--text-accent)] font-bold mb-4">
            A TOYS AND GAMES PLATFORM
        </h2>
        
        <h1 class="text-5xl sm:text-7xl md:text-8xl font-cinzel font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-400 mb-6 drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)]">
            CONQUEST<br class="sm:hidden"> OF WINTER
        </h1>
        
        <p class="text-base sm:text-lg md:text-xl text-[var(--text-secondary)] font-cinzel tracking-wide italic max-w-2xl mx-auto mb-10">
            "When the cold winds rise and the dead walk, intellect is the only shield that guards the realms of men."
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-6">
            @auth
                <a href="{{ url('/dashboard') }}" class="got-btn rounded-xl text-lg w-full sm:w-64 py-4 shadow-[0_0_30px_var(--accent-glow)]">
                    <i class="fa-solid fa-play mr-2"></i> Enter Arena
                </a>
            @else
                <a href="{{ route('register') }}" class="got-btn rounded-xl text-lg w-full sm:w-64 py-4 shadow-[0_0_30px_var(--accent-glow)]">
                    Forge Your Legacy
                </a>
                <a href="{{ route('login') }}" class="got-btn-outline rounded-xl text-lg w-full sm:w-64 py-4">
                    Claim Your Throne
                </a>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-7xl mx-auto text-center z-10 py-4 border-t border-[var(--panel-border)]/20 mt-8">
        <p class="text-xs text-[var(--text-secondary)]">
            &copy; 2026 Conquest of Winter. Built for the 323RK Toys & Games Challenge. All Rights Reserved.
        </p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.fromTo('.gs-reveal', 
                { opacity: 0, y: 50 }, 
                { opacity: 1, y: 0, duration: 1.5, ease: 'power3.out' }
            );
            
            const savedTheme = localStorage.getItem('got_theme') || 'fire';
            initParticles(savedTheme);
        });

        let particleInterval;
        function initParticles(theme) {
            const canvas = document.getElementById('particles-canvas');
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
                    p.radius = Math.random() * 2.5 + 0.8;
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
