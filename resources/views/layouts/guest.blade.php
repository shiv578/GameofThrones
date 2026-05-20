<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ theme: localStorage.getItem('got_theme') || 'fire' }" :class="'theme-' + theme">
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
<body class="antialiased min-h-screen relative flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Dynamic Backgrounds -->
    <div class="bg-layer bg-dragons"></div>
    
    <!-- Canvas for Particles -->
    <canvas id="particles-canvas"></canvas>

    <!-- Theme Switcher -->
    <div class="fixed top-6 right-6 z-50">
        <button @click="theme = (theme === 'fire' ? 'ice' : 'fire'); localStorage.setItem('got_theme', theme); initParticles(theme);" 
                class="got-btn-outline rounded-full !p-3 !flex items-center justify-center w-12 h-12">
            <i class="fa-solid" :class="theme === 'fire' ? 'fa-snowflake' : 'fa-fire'"></i>
        </button>
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
</body>
</html>
