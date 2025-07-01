<?php
require_once 'config.php';

$user = getCurrentUser($pdo);
$isLoggedIn = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BoostMe - Productivité & Bien-être</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                        secondary: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'fade-in': 'fadeIn 0.5s ease-in',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-12px)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .progress-ring__circle {
            transition: stroke-dashoffset 0.5s ease;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
        .focus-mode {
            filter: grayscale(80%) brightness(0.8);
            pointer-events: none;
            user-select: none;
        }
        .breath-circle {
            animation: breath 8s infinite ease-in-out;
        }
        @keyframes breath {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.2); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen font-sans antialiased">
    <?php if ($isLoggedIn): ?>
        <!-- Dashboard -->
        <div class="flex flex-col lg:flex-row min-h-screen">
            <!-- Sidebar -->
            <div class="bg-white w-full lg:w-64 p-4 shadow-lg lg:min-h-screen">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-2">
                        <img src="images/logo.png" alt="Logo BoostMe" class="h-8">
                        <span class="text-xl font-bold text-primary-600">BoostMe</span>
                    </div>
                    <button id="mobileMenuBtn" class="lg:hidden text-gray-500">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <!-- User Profile -->
                <div class="flex items-center gap-3 mb-6 p-2 rounded-lg bg-gray-50">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-medium">
                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700"><?= $user['username'] ?></p>
                        <p class="text-xs text-gray-500">Niveau <?= $user['level'] ?></p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1 mb-6">
                    <a href="#" class="flex items-center gap-3 p-2 rounded-lg bg-primary-100 text-primary-600">
                        <i class="fas fa-home w-5 text-center"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="tasks.php" class="flex items-center gap-3 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-tasks w-5 text-center"></i>
                        <span>Mes tâches</span>
                    </a>
                    <a href="challenges.php" class="flex items-center gap-3 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-trophy w-5 text-center"></i>
                        <span>Mes défis</span>
                    </a>
                    <a href="stats.php" class="flex items-center gap-3 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-chart-line w-5 text-center"></i>
                        <span>Statistiques</span>
                    </a>
                    <a href="settings.php" class="flex items-center gap-3 p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-cog w-5 text-center"></i>
                        <span>Paramètres</span>
                    </a>
                </nav>

                <!-- Quick Actions -->
                <div class="mb-6">
                    <button onclick="startFocusMode()" class="w-full flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium transition-all">
                        <i class="fas fa-bolt"></i>
                        <span>Mode Focus</span>
                    </button>
                </div>

                <!-- XP Progress améliorée -->
                <div class="p-3 bg-gray-50 rounded-lg mb-4 flex items-center gap-4">
                    <div class="flex-1">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Progression</span>
                            <span><span id="xpValue"><?= $user['xp'] ?></span> XP</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-1 overflow-hidden">
                            <div id="xpBar" class="bg-primary-500 h-3 rounded-full transition-all duration-700" style="width: <?= ($user['xp'] % 100) ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Niveau <span id="levelValue"><?= $user['level'] ?></span></span>
                            <span>Niveau <?= $user['level'] + 1 ?></span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="rounded-full bg-primary-100 p-3 shadow text-primary-600 text-2xl font-bold border-4 border-primary-500 animate-bounce">
                            <i class="fas fa-medal"></i>
                        </div>
                        <span class="text-xs mt-1">Niveau <?= $user['level'] ?></span>
                    </div>
                </div>
                <!-- Streak -->
                <div class="bg-white p-3 rounded-lg shadow-sm flex items-center gap-2 mb-4">
                    <i class="fas fa-fire text-red-500"></i>
                    <span>Streak : <b><?= isset($user['streak']) ? $user['streak'] : 1 ?></b> jour(s) consécutif(s)</span>
                </div>
                <!-- Objectifs de la semaine -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-4">
                    <h2 class="text-lg font-semibold mb-2">Objectifs de la semaine</h2>
                    <ul id="weeklyGoals" class="list-disc pl-5 space-y-1 text-gray-700">
                        <li>Compléter 10 tâches</li>
                        <li>Faire 3 sessions focus</li>
                        <li>Terminer 2 défis bien-être</li>
                    </ul>
                </div>
                <!-- Widget météo -->
                <div class="bg-blue-50 p-4 rounded-xl shadow-sm border border-blue-100 mb-4 flex items-center gap-3">
                    <i class="fas fa-cloud-sun text-2xl text-blue-400"></i>
                    <div>
                        <div id="weatherCity" class="font-medium">Paris</div>
                        <div id="weatherTemp" class="text-lg">--°C</div>
                        <div id="weatherDesc" class="text-xs text-gray-500">Chargement...</div>
                    </div>
                    <input id="cityInput" type="text" placeholder="Changer de ville" class="ml-auto border rounded px-2 py-1 text-xs" style="width:100px;">
                </div>
                <!-- Tâches dynamiques -->
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-4">
                    <div class="flex items-center gap-3 mb-2">
                        <input type="text" placeholder="Ajouter une nouvelle tâche..." class="flex-1 border-0 focus:ring-0 focus:outline-none" id="quickTaskInputDyn">
                        <button id="addTaskBtn" class="text-primary-500 hover:text-primary-600"><i class="fas fa-plus"></i></button>
                    </div>
                    <ul id="taskListDyn" class="space-y-2"></ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
                    <div class="flex items-center gap-3">
                        <button class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button onclick="logout()" class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Tâches aujourd'hui</p>
                                <h3 class="text-2xl font-bold">5/8</h3>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 62.5%"></div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Défis complétés</p>
                                <h3 class="text-2xl font-bold">2/3</h3>
                            </div>
                            <div class="p-3 rounded-full bg-secondary-100 text-secondary-600">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-secondary-500 h-2 rounded-full" style="width: 66%"></div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Sessions focus</p>
                                <h3 class="text-2xl font-bold">3</h3>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-bolt"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            <span>Dernière: 25 min</span>
                        </div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Tasks Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Quick Add Task -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                            <div class="flex items-center gap-3">
                                <input 
                                    type="text" 
                                    placeholder="Ajouter une nouvelle tâche..." 
                                    class="flex-1 border-0 focus:ring-0 focus:outline-none"
                                    id="quickTaskInput"
                                >
                                <button class="text-primary-500 hover:text-primary-600">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Today's Tasks -->
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold">Mes tâches du jour</h2>
                                <a href="tasks.php" class="text-sm text-primary-500 hover:underline">Voir tout</a>
                            </div>
                            
                            <div class="space-y-3">
                                <!-- Task items would be loaded here -->
                                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                    <input type="checkbox" class="rounded text-primary-500">
                                    <div class="flex-1">
                                        <p class="font-medium">Préparer la réunion client</p>
                                        <p class="text-xs text-gray-500">10:00 - 11:30</p>
                                    </div>
                                    <button class="text-gray-400 hover:text-red-500">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                    <input type="checkbox" class="rounded text-primary-500">
                                    <div class="flex-1">
                                        <p class="font-medium">Répondre aux emails</p>
                                        <p class="text-xs text-gray-500">15 min</p>
                                    </div>
                                    <button class="text-gray-400 hover:text-red-500">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition">
                                    <input type="checkbox" class="rounded text-primary-500">
                                    <div class="flex-1">
                                        <p class="font-medium">Faire une pause déjeuner</p>
                                    </div>
                                    <button class="text-gray-400 hover:text-red-500">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pomodoro Timer -->
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <h2 class="text-lg font-semibold mb-4">Minuteur de productivité</h2>
                            
                            <div class="flex justify-center gap-4 mb-6">
                                <button class="pomodoro-btn bg-primary-100 text-primary-600 px-4 py-2 rounded-lg font-medium">
                                    25 min
                                </button>
                                <button class="pomodoro-btn bg-gray-100 text-gray-600 px-4 py-2 rounded-lg font-medium">
                                    50 min
                                </button>
                                <button class="pomodoro-btn bg-gray-100 text-gray-600 px-4 py-2 rounded-lg font-medium">
                                    90 min
                                </button>
                            </div>

                            <div class="text-center">
                                <div id="pomodoroTimer" class="text-5xl font-mono mb-4">25:00</div>
                                <button id="startPomodoroBtn" class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-3 rounded-xl text-lg font-medium transition-all w-full">
                                    Commencer
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Daily Challenges -->
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold">Défis du jour</h2>
                                <span class="text-xs bg-secondary-100 text-secondary-800 px-2 py-1 rounded-full">2/3</span>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="p-3 rounded-lg bg-green-50 flex items-start gap-3">
                                    <input type="checkbox" checked class="rounded text-secondary-500 mt-0.5">
                                    <div class="flex-1 line-through text-gray-500">
                                        Boire 1L d'eau
                                        <div class="text-xs mt-1 text-gray-500">15 XP</div>
                                    </div>
                                </div>
                                
                                <div class="p-3 rounded-lg bg-green-50 flex items-start gap-3">
                                    <input type="checkbox" checked class="rounded text-secondary-500 mt-0.5">
                                    <div class="flex-1 line-through text-gray-500">
                                        Faire 10 min de méditation
                                        <div class="text-xs mt-1 text-gray-500">15 XP</div>
                                    </div>
                                </div>
                                
                                <div class="p-3 rounded-lg bg-white border border-gray-200 flex items-start gap-3">
                                    <input type="checkbox" class="rounded text-secondary-500 mt-0.5">
                                    <div class="flex-1 text-gray-800">
                                        Pas de réseaux sociaux pendant 2h
                                        <div class="text-xs mt-1 text-gray-500">25 XP</div>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="mt-4 w-full text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center justify-center gap-1">
                                <i class="fas fa-sync-alt"></i>
                                Nouveau défi
                            </button>
                        </div>

                        <!-- Wellness Reminder -->
                        <div class="bg-blue-50 p-5 rounded-xl shadow-sm border border-blue-100">
                            <div class="text-center mb-4">
                                <img src="images/mascot.png" alt="Mascotte" class="h-20 mascotte mx-auto mb-3">
                                <h4 class="font-medium text-gray-800 mb-1">Bonjour <?= explode(' ', $user['username'])[0] ?> !</h4>
                                <p class="text-sm text-gray-600">Je suis Lumo, ton coach bien-être !</p>
                            </div>

                            <div id="wellnessReminder" class="mb-4 p-3 bg-white rounded-lg shadow-inner">
                                <p class="text-sm">💧 Pensez à boire de l'eau régulièrement</p>
                            </div>

                            <button onclick="startBreathingExercise()" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-spa"></i>
                                Respiration guidée
                            </button>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <h2 class="text-lg font-semibold mb-4">Activité récente</h2>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex items-start gap-2 text-gray-600">
                                    <div class="bg-green-100 p-1 rounded-full mt-0.5">
                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                    </div>
                                    <span>Vous avez complété "Préparer la réunion"</span>
                                </div>
                                
                                <div class="flex items-start gap-2 text-gray-600">
                                    <div class="bg-blue-100 p-1 rounded-full mt-0.5">
                                        <i class="fas fa-bolt text-blue-600 text-xs"></i>
                                    </div>
                                    <span>Session focus de 25 minutes</span>
                                </div>
                                
                                <div class="flex items-start gap-2 text-gray-600">
                                    <div class="bg-secondary-100 p-1 rounded-full mt-0.5">
                                        <i class="fas fa-trophy text-secondary-600 text-xs"></i>
                                    </div>
                                    <span>Défi "Boire 1L d'eau" complété</span>
                                </div>
                                
                                <div class="flex items-start gap-2 text-gray-600">
                                    <div class="bg-purple-100 p-1 rounded-full mt-0.5">
                                        <i class="fas fa-level-up-alt text-purple-600 text-xs"></i>
                                    </div>
                                    <span>Niveau 2 atteint !</span>
                                </div>
                            </div>
                        </div>

                        <!-- Motivational Quote -->
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <h2 class="text-lg font-semibold mb-2">Citation du jour</h2>
                            <div id="motivationalQuote" class="italic text-gray-700 text-center">“Le succès n'est pas la clé du bonheur. Le bonheur est la clé du succès.”</div>
                        </div>

                        <!-- Pause Timer -->
                        <div class="bg-green-50 p-5 rounded-xl shadow-sm border border-green-100">
                            <h2 class="text-lg font-semibold mb-2">Pause café ☕</h2>
                            <div class="flex flex-col items-center">
                                <div id="pauseTimer" class="text-3xl font-mono mb-2">05:00</div>
                                <button id="startPauseBtn" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium">Démarrer la pause</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar & Progress Graph -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <!-- Calendar -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-semibold mb-4">Calendrier</h2>
                        <div id="calendar" class="w-full"></div>
                    </div>
                    <!-- Progress Graph -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-semibold mb-4">Progression</h2>
                        <canvas id="progressChart" height="120"></canvas>
                    </div>
                </div>

                <!-- Badges & Feedback -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <!-- Badges -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-semibold mb-4">Mes badges</h2>
                        <div id="badgesList" class="flex flex-wrap gap-3"></div>
                    </div>
                    <!-- Feedback rapide -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-semibold mb-4">Comment s'est passée ta journée ?</h2>
                        <div class="flex gap-2 justify-center">
                            <button class="feedback-btn text-2xl" data-feedback="1">😞</button>
                            <button class="feedback-btn text-2xl" data-feedback="2">😐</button>
                            <button class="feedback-btn text-2xl" data-feedback="3">😊</button>
                            <button class="feedback-btn text-2xl" data-feedback="4">😃</button>
                        </div>
                        <div id="feedbackMsg" class="text-center text-sm mt-2"></div>
                    </div>
                </div>

                <!-- Chat Assistant -->
                <div class="fixed bottom-6 right-6 z-50">
                    <button id="openChatBtn" class="bg-primary-500 hover:bg-primary-600 text-white p-4 rounded-full shadow-lg flex items-center justify-center">
                        <i class="fas fa-comments text-2xl"></i>
                    </button>
                    <div id="chatBox" class="hidden bg-white rounded-xl shadow-lg p-4 w-80 mt-2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-primary-600">Lumo, assistant</span>
                            <button id="closeChatBtn" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                        </div>
                        <div id="chatMessages" class="h-40 overflow-y-auto mb-2 text-sm"></div>
                        <div class="flex gap-2">
                            <input id="chatInput" type="text" class="flex-1 border rounded-lg px-2 py-1" placeholder="Pose ta question...">
                            <button id="sendChatBtn" class="bg-primary-500 text-white px-3 rounded-lg">Envoyer</button>
                        </div>
                    </div>
                </div>

                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" class="fixed top-6 right-6 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-full shadow z-50 flex items-center gap-2">
                    <i class="fas fa-moon"></i>
                    <span>Mode sombre</span>
                </button>

                <!-- Notifications personnalisées -->
                <div id="customNotification" class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-primary-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 hidden"></div>
            </div>
        </div>

        <!-- Focus Mode Overlay -->
        <div id="focusOverlay" class="fixed inset-0 bg-black bg-opacity-70 z-50 hidden flex-col items-center justify-center">
            <div class="text-white text-center mb-8">
                <h2 class="text-3xl font-bold mb-2">Mode Focus Activé</h2>
                <div id="focusTimer" class="text-4xl font-mono">25:00</div>
                <p class="mt-4">Concentrez-vous sur votre tâche actuelle</p>
            </div>
            <button id="endFocusBtn" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl text-lg font-medium transition-all">
                Terminer la session
            </button>
        </div>

    <?php else: ?>
        <!-- Auth Section -->
        <div class="flex flex-col lg:flex-row items-center justify-center min-h-screen gap-8 p-4">
            <!-- Login Section -->
            <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
                <div class="text-center mb-6">
                    <img src="images/logo.png" alt="Logo BoostMe" class="h-16 mx-auto mb-3">
                    <h2 class="text-2xl font-bold text-primary-600">Connexion à BoostMe</h2>
                    <p class="text-sm text-gray-500 mt-1">Retrouvez votre productivité</p>
                </div>
                
                <form action="login.php" method="POST" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="votre@email.com"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                        >
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                        >
                    </div>
                    
                    <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 rounded-xl font-medium transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </button>
                </form>
                
                <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-600">
                        Pas encore de compte ?
                        <a href="register.php" class="text-primary-600 font-medium hover:underline">Créer un compte</a>
                    </p>
                </div>
            </div>
            
            <!-- Presentation for new visitors -->
            <div class="hidden lg:block max-w-md">
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Boostez votre productivité</h2>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-0.5"></i>
                            <span>Mode focus pour bloquer les distractions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-0.5"></i>
                            <span>Défis quotidiens personnalisés</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-0.5"></i>
                            <span>Suivi de vos progrès et statistiques</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-500 mt-0.5"></i>
                            <span>Rappels de bien-être et pauses</span>
                        </li>
                    </ul>
                    
                    <div class="mt-8 grid grid-cols-3 gap-2">
                        <div class="p-2 bg-purple-50 rounded-lg text-center">
                            <i class="fas fa-bolt text-purple-500 text-xl mb-1"></i>
                            <p class="text-xs text-gray-600">Focus</p>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg text-center">
                            <i class="fas fa-tasks text-blue-500 text-xl mb-1"></i>
                            <p class="text-xs text-gray-600">Tâches</p>
                        </div>
                        <div class="p-2 bg-green-50 rounded-lg text-center">
                            <i class="fas fa-heart text-green-500 text-xl mb-1"></i>
                            <p class="text-xs text-gray-600">Bien-être</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonctions JavaScript
        function logout() {
            window.location.href = 'logout.php';
        }
        
        function startFocusMode() {
            document.getElementById('focusOverlay').classList.remove('hidden');
            document.body.classList.add('focus-mode');
            
            let secondsLeft = 25 * 60;
            updateFocusTimerDisplay(secondsLeft);
            
            const focusInterval = setInterval(() => {
                secondsLeft--;
                updateFocusTimerDisplay(secondsLeft);
                
                if (secondsLeft <= 0) {
                    clearInterval(focusInterval);
                    endFocusMode();
                    
                    // Show notification
                    if (Notification.permission === 'granted') {
                        new Notification('Mode Focus terminé', {
                            body: 'Votre session de concentration est terminée. Prenez une pause !',
                            icon: 'images/logo.png'
                        });
                    }
                }
            }, 1000);
            
            document.getElementById('endFocusBtn').onclick = function() {
                clearInterval(focusInterval);
                endFocusMode();
            };
        }
        
        function endFocusMode() {
            document.getElementById('focusOverlay').classList.add('hidden');
            document.body.classList.remove('focus-mode');
        }
        
        function updateFocusTimerDisplay(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            document.getElementById('focusTimer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
        
        function startBreathingExercise() {
            const reminder = document.getElementById('wellnessReminder');
            reminder.innerHTML = `
                <div class="text-center">
                    <div class="breath-circle w-16 h-16 bg-blue-200 rounded-full mx-auto my-2"></div>
                    <p class="text-sm">Inspirez profondément... puis expirez lentement</p>
                    <p class="text-xs text-gray-500 mt-1">Continuez pendant 1 minute</p>
                </div>
            `;
            
            setTimeout(() => {
                reminder.innerHTML = '<p class="text-sm">👍 Excellent ! Vous devriez vous sentir plus détendu.</p>';
            }, 60000);
        }
        
        // Setup Pomodoro buttons
        document.querySelectorAll('.pomodoro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.pomodoro-btn').forEach(b => {
                    b.classList.remove('bg-primary-100', 'text-primary-600');
                    b.classList.add('bg-gray-100', 'text-gray-600');
                });
                this.classList.remove('bg-gray-100', 'text-gray-600');
                this.classList.add('bg-primary-100', 'text-primary-600');
                const minutes = parseInt(this.textContent);
                updatePomodoroDisplay(minutes * 60);
            });
        });
        
        const startPomodoroBtn = document.getElementById('startPomodoroBtn');
        if (startPomodoroBtn) {
            startPomodoroBtn.addEventListener('click', function() {
                this.textContent = 'En cours...';
                this.disabled = true;
                const minutes = parseInt(document.querySelector('.pomodoro-btn.bg-primary-100').textContent);
                let secondsLeft = minutes * 60;
                const pomodoroInterval = setInterval(() => {
                    secondsLeft--;
                    updatePomodoroDisplay(secondsLeft);
                    if (secondsLeft <= 0) {
                        clearInterval(pomodoroInterval);
                        this.textContent = 'Terminé !';
                        if (Notification.permission === 'granted') {
                            new Notification('Session Pomodoro terminée', {
                                body: `Votre session de ${minutes} minutes est terminée. Prenez une pause !`,
                                icon: 'images/logo.png'
                            });
                        }
                        setTimeout(() => {
                            this.textContent = 'Commencer';
                            this.disabled = false;
                            updatePomodoroDisplay(minutes * 60);
                        }, 3000);
                    }
                }, 1000);
            });
        }
        
        function updatePomodoroDisplay(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            document.getElementById('pomodoroTimer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }
        
        // Request notification permission
        if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }

        // --- Motivational Quote ---
        const quotes = [
            "Le succès n'est pas la clé du bonheur. Le bonheur est la clé du succès.",
            "Chaque jour est une nouvelle chance de réussir.",
            "La persévérance est la clé de la réussite.",
            "Fais de ta vie un rêve, et d'un rêve, une réalité.",
            "N'abandonne jamais, les grandes choses prennent du temps."
        ];
        document.getElementById('motivationalQuote').textContent = quotes[Math.floor(Math.random() * quotes.length)];

        // --- Pause Timer ---
        let pauseInterval;
        const startPauseBtn = document.getElementById('startPauseBtn');
        if (startPauseBtn) {
            startPauseBtn.onclick = function() {
                let seconds = 5 * 60;
                updatePauseDisplay(seconds);
                clearInterval(pauseInterval);
                pauseInterval = setInterval(() => {
                    seconds--;
                    updatePauseDisplay(seconds);
                    if (seconds <= 0) {
                        clearInterval(pauseInterval);
                        showCustomNotification('Pause terminée ! Reprends avec énergie.');
                    }
                }, 1000);
            };
        }
        function updatePauseDisplay(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            document.getElementById('pauseTimer').textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }

        // --- Calendar (simple placeholder) ---
        document.getElementById('calendar').innerHTML = '<div class="text-center text-gray-400">[Calendrier à venir]</div>';

        // --- Progress Graph (Chart.js) ---
        const ctx = document.getElementById('progressChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    label: 'Tâches complétées',
                    data: [3, 5, 4, 6, 2, 7, 5],
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139,92,246,0.1)',
                    tension: 0.4
                }]
            },
            options: {responsive: true, plugins: {legend: {display: false}}}
        });

        // --- Badges (placeholder) ---
        document.getElementById('badgesList').innerHTML = `
            <div class="flex flex-col items-center"><i class="fas fa-star text-yellow-400 text-3xl"></i><span class="text-xs">Débutant</span></div>
            <div class="flex flex-col items-center"><i class="fas fa-fire text-red-500 text-3xl"></i><span class="text-xs">Assidu</span></div>
            <div class="flex flex-col items-center"><i class="fas fa-trophy text-secondary-500 text-3xl"></i><span class="text-xs">Champion</span></div>
        `;

        // --- Feedback rapide ---
        document.querySelectorAll('.feedback-btn').forEach(btn => {
            btn.onclick = function() {
                const val = this.dataset.feedback;
                let msg = '';
                if (val == 1) msg = 'Courage, demain sera meilleur !';
                if (val == 2) msg = 'Merci pour ton retour !';
                if (val == 3) msg = 'Super, continue comme ça !';
                if (val == 4) msg = 'Génial, tu es au top !';
                document.getElementById('feedbackMsg').textContent = msg;
            };
        });

        // --- Chat Assistant (placeholder) ---
        const openChatBtn = document.getElementById('openChatBtn');
        if (openChatBtn) {
            openChatBtn.onclick = function() {
                document.getElementById('chatBox').classList.remove('hidden');
            };
        }
        const closeChatBtn = document.getElementById('closeChatBtn');
        if (closeChatBtn) {
            closeChatBtn.onclick = function() {
                document.getElementById('chatBox').classList.add('hidden');
            };
        }
        const sendChatBtn = document.getElementById('sendChatBtn');
        if (sendChatBtn) {
            sendChatBtn.onclick = function() {
                const input = document.getElementById('chatInput');
                const msg = input.value.trim();
                if (!msg) return;
                const chat = document.getElementById('chatMessages');
                chat.innerHTML += `<div class='mb-1'><b>Moi:</b> ${msg}</div>`;
                chat.innerHTML += `<div class='mb-1'><b>Lumo:</b> ${getAssistantReply(msg)}</div>`;
                chat.scrollTop = chat.scrollHeight;
                input.value = '';
            };
        }
        function getAssistantReply(msg) {
            if (msg.toLowerCase().includes('motivation')) return "N'oublie pas : chaque petit pas compte !";
            if (msg.toLowerCase().includes('focus')) return 'Essaie la technique Pomodoro pour rester concentré.';
            return 'Je suis là pour t\'aider à rester productif !';
        }

        // --- Dark Mode ---
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.onclick = function() {
                document.documentElement.classList.toggle('dark');
                if (document.documentElement.classList.contains('dark')) {
                    this.querySelector('span').textContent = 'Mode clair';
                    this.querySelector('i').classList.remove('fa-moon');
                    this.querySelector('i').classList.add('fa-sun');
                    document.body.classList.add('bg-gray-900', 'text-white');
                } else {
                    this.querySelector('span').textContent = 'Mode sombre';
                    this.querySelector('i').classList.remove('fa-sun');
                    this.querySelector('i').classList.add('fa-moon');
                    document.body.classList.remove('bg-gray-900', 'text-white');
                }
            };
        }

        // --- Custom Notification ---
        function showCustomNotification(msg) {
            const notif = document.getElementById('customNotification');
            notif.textContent = msg;
            notif.classList.remove('hidden');
            setTimeout(() => notif.classList.add('hidden'), 4000);
        }

        // XP Progress animation
        const xpBar = document.getElementById('xpBar');
        if (xpBar) {
            setTimeout(() => {
                xpBar.style.width = (parseInt(document.getElementById('xpValue').textContent) % 100) + '%';
            }, 300);
        }
        // Météo widget (Open-Meteo API, pas besoin de clé)
        function fetchWeather(city) {
            fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(city)}&count=1&language=fr&format=json`)
                .then(r => r.json())
                .then(data => {
                    if (!data.results || !data.results[0]) return;
                    const { latitude, longitude, name } = data.results[0];
                    fetch(`https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true&timezone=auto`)
                        .then(r2 => r2.json())
                        .then(w => {
                            document.getElementById('weatherCity').textContent = name;
                            document.getElementById('weatherTemp').textContent = w.current_weather.temperature + '°C';
                            document.getElementById('weatherDesc').textContent = 'Ciel : ' + (w.current_weather.weathercode < 3 ? 'Clair' : 'Nuageux');
                        });
                });
        }
        const cityInput = document.getElementById('cityInput');
        if (cityInput) {
            cityInput.addEventListener('change', function() {
                fetchWeather(this.value);
            });
        }
        fetchWeather('Paris');
        // Tâches dynamiques (localStorage)
        const taskListDyn = document.getElementById('taskListDyn');
        const quickTaskInputDyn = document.getElementById('quickTaskInputDyn');
        const addTaskBtn = document.getElementById('addTaskBtn');
        if (taskListDyn && quickTaskInputDyn && addTaskBtn) {
            function renderTasks() {
                const tasks = JSON.parse(localStorage.getItem('tasks') || '[]');
                taskListDyn.innerHTML = '';
                tasks.forEach((t, i) => {
                    const li = document.createElement('li');
                    li.className = 'flex items-center gap-2 p-2 bg-gray-50 rounded';
                    li.innerHTML = `<input type='checkbox' class='mr-2'> <span class='flex-1'>${t}</span> <button data-index='${i}' class='delTaskBtn text-red-400 hover:text-red-600'><i class='fas fa-trash'></i></button>`;
                    li.querySelector('.delTaskBtn').onclick = function() {
                        tasks.splice(i, 1);
                        localStorage.setItem('tasks', JSON.stringify(tasks));
                        renderTasks();
                    };
                    li.querySelector('input[type=checkbox]').onchange = function() {
                        li.classList.toggle('line-through', this.checked);
                    };
                    taskListDyn.appendChild(li);
                });
            }
            addTaskBtn.onclick = function() {
                const val = quickTaskInputDyn.value.trim();
                if (!val) return;
                const tasks = JSON.parse(localStorage.getItem('tasks') || '[]');
                tasks.push(val);
                localStorage.setItem('tasks', JSON.stringify(tasks));
                quickTaskInputDyn.value = '';
                renderTasks();
            };
            renderTasks();
        }
        // Thème sombre/clair mémorisé
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('bg-gray-900', 'text-white');
                darkModeToggle.querySelector('span').textContent = 'Mode clair';
                darkModeToggle.querySelector('i').classList.remove('fa-moon');
                darkModeToggle.querySelector('i').classList.add('fa-sun');
            }
            darkModeToggle.onclick = function() {
                document.documentElement.classList.toggle('dark');
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    localStorage.setItem('theme', 'dark');
                    document.body.classList.add('bg-gray-900', 'text-white');
                    this.querySelector('span').textContent = 'Mode clair';
                    this.querySelector('i').classList.remove('fa-moon');
                    this.querySelector('i').classList.add('fa-sun');
                } else {
                    localStorage.setItem('theme', 'light');
                    document.body.classList.remove('bg-gray-900', 'text-white');
                    this.querySelector('span').textContent = 'Mode sombre';
                    this.querySelector('i').classList.remove('fa-sun');
                    this.querySelector('i').classList.add('fa-moon');
                }
            };
        }
    });
    </script>
</body>
</html>