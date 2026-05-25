<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between" data-aos="fade-down">
        <div>
            <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
                <i class="fa-solid fa-chart-line mr-2 text-[var(--text-accent)]"></i> Intelligence Analytics
            </h1>
            <p class="text-[var(--text-secondary)]">Track your cognitive growth across the Realm</p>
        </div>
        
        <div class="mt-4 sm:mt-0 flex space-x-2">
            <button class="got-btn-outline !py-2 !px-4 text-xs rounded">7 Days</button>
            <button class="got-btn-outline !py-2 !px-4 text-xs rounded opacity-50">30 Days</button>
            <button class="got-btn-outline !py-2 !px-4 text-xs rounded opacity-50">All Time</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Quick Stat 1 -->
        <div class="got-panel p-6 rounded-xl" data-aos="fade-up">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Total Intellect Score</div>
                    <div class="text-3xl font-cinzel font-bold text-white mt-1">{{ number_format(auth()->user()->xp) }}</div>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-900/50 flex items-center justify-center border border-blue-500">
                    <i class="fa-solid fa-brain text-blue-400"></i>
                </div>
            </div>
            <div class="text-xs text-green-400 font-bold"><i class="fa-solid fa-arrow-trend-up mr-1"></i> +15% from last week</div>
        </div>

        <!-- Quick Stat 2 -->
        <div class="got-panel p-6 rounded-xl" data-aos="fade-up" data-aos-delay="100">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Games Played</div>
                    <div class="text-3xl font-cinzel font-bold text-white mt-1">{{ number_format($totalGamesPlayed) }}</div>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-900/50 flex items-center justify-center border border-green-500">
                    <i class="fa-solid fa-gamepad text-green-400"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stat 3 -->
        <div class="got-panel p-6 rounded-xl" data-aos="fade-up" data-aos-delay="200">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider font-bold">Global Win Rate</div>
                    <div class="text-3xl font-cinzel font-bold text-white mt-1">68%</div>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-900/50 flex items-center justify-center border border-yellow-500">
                    <i class="fa-solid fa-chess-knight text-yellow-400"></i>
                </div>
            </div>
            <div class="text-xs text-red-400 font-bold"><i class="fa-solid fa-arrow-trend-down mr-1"></i> -2% from last week</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- XP Growth Chart -->
        <div class="got-panel p-6 rounded-xl" data-aos="fade-right">
            <h3 class="font-cinzel font-bold text-lg mb-4 border-b border-[var(--panel-border)] pb-2">Experience Growth (7 Days)</h3>
            <div id="xp-chart" class="w-full h-80"></div>
        </div>
        
        <!-- Performance by Category -->
        <div class="got-panel p-6 rounded-xl" data-aos="fade-left">
            <h3 class="font-cinzel font-bold text-lg mb-4 border-b border-[var(--panel-border)] pb-2">Mastery by Category</h3>
            <div id="category-chart" class="w-full h-80 flex justify-center"></div>
        </div>

    </div>

    <!-- Games Played by Category -->
    <div class="mt-8 mb-8">
        <h3 class="font-cinzel font-bold text-2xl mb-6 text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-300" data-aos="fade-up">
            <i class="fa-solid fa-layer-group mr-2 text-orange-400"></i> Games Played by Section
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($gamesPlayedByCategory as $stat)
            <div class="got-panel p-5 rounded-lg flex flex-col items-center justify-center text-center group hover:-translate-y-1 transition duration-300 border border-[var(--panel-border)] hover:border-orange-500 shadow-lg" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="text-xs text-[var(--text-secondary)] uppercase tracking-widest font-bold mb-2 group-hover:text-orange-300 transition">{{ ucfirst($stat->category) }}</div>
                <div class="text-4xl font-cinzel font-black text-white group-hover:text-yellow-400 drop-shadow-[0_2px_5px_rgba(0,0,0,0.8)]">
                    {{ number_format($stat->games_count) }}
                </div>
                <div class="text-[10px] text-gray-500 mt-1 uppercase">Matches</div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-8 italic bg-black/40 rounded-xl border border-gray-800">
                You haven't played any games yet. Head to the Games Arena!
            </div>
            @endforelse
        </div>
    </div>

    <!-- Script to render ApexCharts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Shared Chart Theme settings
            const isFire = '{{ auth()->user()->theme_preference ?? "fire" }}' === 'fire';
            const primaryColor = isFire ? '#ea580c' : '#0ea5e9'; // Orange vs Sky Blue
            const textColor = '#9ca3af'; // Gray-400

            // Line Chart: XP Growth
            var xpOptions = {
                series: [{
                    name: 'XP Earned',
                    data: {!! json_encode($lineData) !!}
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: { show: false },
                    background: 'transparent',
                    fontFamily: 'Rajdhani, sans-serif'
                },
                colors: [primaryColor],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: {!! json_encode($lineLabels) !!},
                    labels: { style: { colors: textColor } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { style: { colors: textColor } }
                },
                grid: {
                    borderColor: 'rgba(255,255,255,0.05)',
                    strokeDashArray: 4,
                },
                theme: { mode: 'dark' }
            };

            var xpChart = new ApexCharts(document.querySelector("#xp-chart"), xpOptions);
            xpChart.render();

            // Radar Chart: Mastery by Category
            var catOptions = {
                series: [{
                    name: 'Total Score',
                    data: {!! json_encode($pieData) !!}
                }],
                chart: {
                    type: 'radar',
                    height: 350,
                    toolbar: { show: false },
                    background: 'transparent',
                    fontFamily: 'Cinzel, serif'
                },
                labels: {!! json_encode($pieLabels) !!},
                stroke: {
                    width: 2,
                    colors: [primaryColor]
                },
                fill: {
                    opacity: 0.4,
                    colors: [primaryColor]
                },
                markers: { size: 4, colors: ['#fff'], strokeColors: primaryColor, strokeWidth: 2 },
                yaxis: { show: false },
                xaxis: {
                    labels: {
                        style: {
                            colors: [textColor, textColor, textColor, textColor, textColor],
                            fontSize: '12px',
                            fontFamily: 'Cinzel, serif'
                        }
                    }
                },
                plotOptions: {
                    radar: {
                        polygons: {
                            strokeColors: 'rgba(255,255,255,0.1)',
                            strokeWidth: 1,
                            connectorColors: 'rgba(255,255,255,0.1)'
                        }
                    }
                },
                theme: { mode: 'dark' }
            };

            // If empty data, show placeholder
            @if(empty($pieData))
                document.querySelector("#category-chart").innerHTML = '<div class="flex items-center justify-center h-full text-gray-500 italic">No games played yet.</div>';
            @else
                var catChart = new ApexCharts(document.querySelector("#category-chart"), catOptions);
                catChart.render();
            @endif
        });
    </script>
</x-app-layout>
