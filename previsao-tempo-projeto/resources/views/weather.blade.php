<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previsão do Tempo</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.svg') }}">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <!-- FontAwesome Ícones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Componentes CSS Reutilizáveis -->
    <style type="text/tailwindcss">
        @layer components {
            .app-nav {
                @apply bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 py-3 shadow-sm transition-colors duration-300;
            }
            .app-card {
                @apply bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-xl transition-colors duration-300;
            }
            .app-input {
                @apply w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition;
            }
            .btn-primary {
                @apply bg-orange-500 hover:bg-orange-400 active:scale-95 text-slate-950 font-bold px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20;
            }
            .metric-card {
                @apply bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center gap-4 shadow-lg transition-colors duration-300;
            }
            .metric-icon {
                @apply p-3 rounded-xl text-xl;
            }
            .metric-label {
                @apply text-slate-400 text-xs font-semibold uppercase block;
            }
            .metric-value {
                @apply text-lg md:text-xl font-bold text-slate-800 dark:text-slate-100;
            }
            .error-banner {
                @apply bg-rose-100 dark:bg-rose-950/60 border border-rose-300 dark:border-rose-800/80 rounded-2xl p-4 mb-6 text-rose-800 dark:text-rose-200 flex items-center gap-3 shadow-lg;
            }
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col font-sans antialiased transition-colors duration-300">

    <!-- Menu Superior -->
    <nav class="app-nav">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="/weather" class="flex items-center gap-2 font-bold text-lg text-sky-600 dark:text-sky-400">
                <i class="fa-solid fa-cloud-sun text-xl"></i>
                <span>TempoCerto</span>
            </a>

            <div class="flex items-center gap-4">
                <button id="theme-toggle" type="button" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition" title="Alternar Tema">
                    <i id="theme-toggle-dark-icon" class="fa-solid fa-moon hidden"></i>
                    <i id="theme-toggle-light-icon" class="fa-solid fa-sun hidden"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="flex-1 max-w-xl w-full mx-auto p-4 flex flex-col justify-center">
        
        <header class="text-center mb-6">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white flex items-center justify-center gap-3">
                Previsão do Tempo
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Consulte dados meteorológicos em tempo real</p>
        </header>

        <!-- Formulário de Busca -->
        <div class="app-card mb-6">
            <form action="/weather" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <input type="text" name="city" value="{{ request('city') }}" placeholder="Digite o nome de uma cidade..." class="app-input" required>
                </div>
                <button type="submit" class="btn-primary">
                    <span>Buscar</span>
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </button>
            </form>
        </div>

        <!-- Alerta de Erro -->
        @if(isset($error) && $error)
            <div class="error-banner">
                <div class="p-2 bg-rose-200 dark:bg-rose-900/50 rounded-xl text-rose-600 dark:text-rose-400 flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <p class="text-sm font-medium">{{ $error }}</p>
            </div>
        @endif

        <!-- Lógica de Ícones e Gradientes Dinâmicos -->
        @php
            $mainIcon = 'fa-sun';
            // Gradiente Laranja / Coral Dourado estilo Pôr do Sol
            $gradientClass = 'from-amber-500 via-orange-500 to-rose-500';

            if (isset($weather) && $weather) {
                $temp = $weather['temperature'] ?? 0;
                $wind = $weather['wind'] ?? 0;

                if ($temp <= 5) {
                    $mainIcon = 'fa-snowflake';
                    $gradientClass = 'from-blue-600 to-indigo-900';
                } elseif ($wind >= 25) {
                    $mainIcon = 'fa-wind';
                    $gradientClass = 'from-teal-600 to-slate-800';
                } elseif ($wind >= 15) {
                    $mainIcon = 'fa-cloud-sun';
                    $gradientClass = 'from-blue-600 to-slate-700';
                }
            }
        @endphp

        <!-- Card de Resultado -->
        @if(isset($weather) && $weather)
            <div class="space-y-4">
                <div class="relative overflow-hidden bg-gradient-to-br {{ $gradientClass }} rounded-3xl p-6 md:p-8 shadow-2xl text-white transition-all duration-500">
                    <div class="absolute -right-6 -bottom-6 opacity-20 text-8xl pointer-events-none">
                        <i class="fa-solid {{ $mainIcon }}"></i>
                    </div>

                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full backdrop-blur-md mb-2">
                                <i class="fa-solid fa-earth-americas"></i>
                                {{ $weather['country'] ?? 'BR' }}
                            </span>
                            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                                {{ $weather['city'] }}
                            </h2>
                        </div>

                        <div class="flex items-center gap-4 sm:flex-col sm:items-end">
                            <div class="text-4xl">
                                <i class="fa-solid {{ $mainIcon }}"></i>
                            </div>
                            <div class="text-5xl md:text-6xl font-black">
                                {{ number_format($weather['temperature'], 1) }}<span class="text-3xl font-light">°C</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Métricas Secundárias -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="metric-card">
                        <div class="metric-icon bg-sky-500/10 text-sky-500">
                            <i class="fa-solid fa-droplet"></i>
                        </div>
                        <div>
                            <span class="metric-label">Umidade</span>
                            <span class="metric-value">{{ $weather['humidity'] }}%</span>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon bg-blue-500/10 text-blue-500">
                            <i class="fa-solid fa-wind"></i>
                        </div>
                        <div>
                            <span class="metric-label">Vento</span>
                            <span class="metric-value">
                                {{ $weather['wind'] }} <span class="text-xs font-normal text-slate-400">km/h</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </main>

    <!-- Rodapé (Footer) -->
    <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md py-6 transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400">
            
            <!-- Créditos dos Dados / API -->
            <div class="flex items-center gap-2 text-center sm:text-left">
                <i class="fa-solid fa-database text-orange-500"></i>
                <span>Dados meteorológicos fornecidos por 
                    <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer" class="font-semibold text-slate-700 dark:text-slate-300 hover:text-orange-500 underline decoration-slate-300 dark:decoration-slate-700 hover:decoration-orange-500 transition">
                        Open-Meteo API
                    </a>
                </span>
            </div>

            <!-- Informações do Projeto -->
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1">
                    <span>Laravel & FastAPI</span>
                </span>
                <span>•</span>
                <span>{{ date('d/M/Y') }}</span>
            </div>

        </div>
    </footer>

    <!-- Script de Tema (Dark / Light) -->
    <script>
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleBtn = document.getElementById('theme-toggle');

        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            themeToggleDarkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    </script>
</body>
</html>