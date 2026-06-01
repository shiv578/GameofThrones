<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('got_theme') || 'fire', music: localStorage.getItem('got_music_enabled') !== '0' }" :class="'theme-' + theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Conquest of Winter') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
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
            background-position: center;
            background-size: cover;
            transition: all 1s ease-in-out;
        }
        .theme-fire .bg-dragons {
            background-image: url('/images/dragons-bg.jpg');
            filter: sepia(0.15) saturate(1.3) hue-rotate(345deg) contrast(1.1);
            opacity: 0.4;
        }
        .theme-ice .bg-dragons {
            background-image: url('/images/ice-dragon-bg.jpg');
            opacity: 0.8;
        }
        
        .fiery-sword {
            filter: drop-shadow(-4px 0 6px #ff5500) drop-shadow(-8px 0 15px #ff2200);
            animation: fireSwordGlow 1s infinite alternate;
        }

        .theme-ice .fiery-sword {
            filter: drop-shadow(-4px 0 6px #00aaff) drop-shadow(-8px 0 15px #0055ff);
            animation: iceSwordGlow 1s infinite alternate;
        }

        @keyframes fireSwordGlow {
            0% { filter: drop-shadow(-4px 0 6px #ff5500) drop-shadow(-8px 0 12px #ff0000); }
            100% { filter: drop-shadow(-6px 0 10px #ff7700) drop-shadow(-12px 0 25px #ff3300); }
        }

        @keyframes iceSwordGlow {
            0% { filter: drop-shadow(-4px 0 6px #00aaff) drop-shadow(-8px 0 12px #0044ff); }
            100% { filter: drop-shadow(-6px 0 10px #00ccff) drop-shadow(-12px 0 25px #0066ff); }
        }
        
        #particles-canvas {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            pointer-events: none;
        }

        /* Mini Music Toggle Styles */
        .mini-music-toggle {
            display: flex;
            align-items: center;
        }
        .mini-switch {
            position: relative;
            width: 52px;
            height: 28px;
        }
        .mini-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .mini-slider {
            position: absolute;
            inset: 0;
            border-radius: 50px;
            cursor: pointer;
            transition: 0.4s;
            background: rgba(20,20,30,0.75);
            border: 1px solid rgba(255,140,0,0.35);
            backdrop-filter: blur(8px);
            box-shadow: 0 0 10px rgba(255,120,0,0.15), inset 0 0 8px rgba(255,255,255,0.03);
        }
        .music-icon {
            position: absolute;
            top: 6px;
            left: 7px;
            font-size: 13px;
            color: #ffb347;
            transition: 0.4s;
            text-shadow: 0 0 8px rgba(255,140,0,0.8);
        }
        .mini-switch input:checked + .mini-slider {
            background: linear-gradient(90deg,#ff5e00,#ff9900);
            box-shadow: 0 0 15px rgba(255,120,0,0.45);
        }
        .mini-switch input:checked + .mini-slider .music-icon {
            transform: translateX(22px);
            color: white;
        }

        /* Ice Theme specific styles for Music Toggle */
        .theme-ice .mini-slider {
            border-color: rgba(0, 170, 255, 0.35);
            box-shadow: 0 0 10px rgba(0, 170, 255, 0.15), inset 0 0 8px rgba(255,255,255,0.03);
        }
        .theme-ice .music-icon {
            color: #7dd3fc;
            text-shadow: 0 0 8px rgba(0, 170, 255, 0.8);
        }
        .theme-ice .mini-switch input:checked + .mini-slider {
            background: linear-gradient(90deg, #0088ff, #00d4ff);
            box-shadow: 0 0 15px rgba(0, 170, 255, 0.45);
        }
    </style>
    
</head>
<body class="antialiased min-h-screen relative flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Dynamic Backgrounds -->
    <div class="bg-layer bg-dragons"></div>
    
    <!-- Canvas for Particles -->
    <canvas id="particles-canvas"></canvas>

    <!-- Back Button -->
    <div class="fixed top-4 left-4 sm:top-6 sm:left-6 z-50 group">
        <a href="{{ url('/') }}" 
           class="got-btn-outline rounded-full !p-2 sm:!p-3 !flex items-center justify-center w-10 h-10 sm:w-14 sm:h-14 transition-transform duration-300 hover:-translate-x-1 hover:scale-110"
           title="Return to Welcome Page">
            <svg viewBox="0 0 100 100" class="w-6 h-6 sm:w-8 sm:h-8 text-white fiery-sword transition-all duration-300" fill="currentColor">
              <!-- Blade -->
              <path d="M 10 50 L 65 44 L 65 56 Z" />
              <!-- Guard -->
              <rect x="63" y="30" width="6" height="40" rx="2" />
              <!-- Grip -->
              <rect x="69" y="46" width="20" height="8" />
              <!-- Pommel -->
              <circle cx="92" cy="50" r="5" />
            </svg>
        </a>
    </div>

    <!-- Theme Switcher -->
    <div class="fixed top-4 right-4 sm:top-6 sm:right-6 z-50 flex flex-col gap-3">
        <button @click="theme = (theme === 'fire' ? 'ice' : 'fire'); localStorage.setItem('got_theme', theme); initParticles(theme);" 
                class="got-btn-outline rounded-full !p-2 sm:!p-3 !flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12" title="Toggle Theme">
            <i class="fa-solid text-sm sm:text-base" :class="theme === 'fire' ? 'fa-snowflake' : 'fa-fire'"></i>
        </button>

        <!-- Music Switcher -->
        <div class="mini-music-toggle" title="Toggle Music">
            <label class="mini-switch">
                <input 
                    type="checkbox"
                    :checked="music"
                    @change="music = $event.target.checked; MusicSystem.toggle(music);"
                >
                <span class="mini-slider">
                    <i class="fa-solid fa-music music-icon"></i>
                </span>
            </label>
        </div>
    </div>

    <div class="w-full max-w-4xl relative z-10 gs-reveal">
        {{ $slot }}
    </div>

    <script>
        // GSAP Intro Animation
        document.addEventListener('DOMContentLoaded', () => {
            gsap.fromTo('.gs-reveal', 
                { opacity: 0, y: 50 }, 
                { opacity: 1, y: 0, duration: 1.5, ease: 'power3.out' }
            );
            
            // Initialize particles based on localStorage
            const savedTheme = localStorage.getItem('got_theme') || 'fire';
            initParticles(savedTheme);
        });

        // Simple Particle System
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

    <!-- Global Music System -->
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ asset('music/theme.mpeg') }}" type="audio/mpeg">
    </audio>
    <script src="{{ asset('js/music-system.js') }}?v={{ time() }}"></script>
</body>

</html>
