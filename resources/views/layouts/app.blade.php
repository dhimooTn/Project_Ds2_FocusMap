<!DOCTYPE html>
<html lang="fr">

<head>
    @include('layouts.head')
    <!-- Dialogflow Messenger CSS for Chatbot -->
    <style>
        /* Hide chatbot by default */
        df-messenger {
            display: none;
            --df-messenger-bot-message: #007bff;
            --df-messenger-user-message: #28a745;
            --df-messenger-titlebar-background: #343a40;
            --df-messenger-titlebar-color: #ffffff;
            z-index: 1000;
        }

        /* Show chatbot when active */
        df-messenger.active {
            display: block;
        }

        /* Floating chat icon */
        .chat-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            transition: background-color 0.3s;
        }

        .chat-icon:hover {
            background-color: #0056b3;
        }

        /* Adjust chatbot position for mobile */
        @media (max-width: 767px) {
            df-messenger {
                --df-messenger-chat-height: 80vh;
            }
        }
    </style>
</head>

<body class="min-vh-100">

    <!-- Desktop Sidebar -->
    <aside class="sidebar position-fixed h-100 d-none d-md-block text-white z-3 shadow-lg">
        <div class="p-4 d-flex align-items-center">
            <h1 class="text-2xl font-pacifico text-white m-0">FocusMap</h1>
        </div>
        <nav class="flex-grow-1 px-3 py-4">
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <a href="{{ route('dashboard') }}"
                        class="d-flex align-items-center p-3 rounded bg-primary bg-opacity-20 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-dashboard-line"></i>
                        </div>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('goals.index') }}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-flag-line"></i>
                        </div>
                        <span>Mes objectifs</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('mindMap')}}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-node-tree"></i>
                        </div>
                        <span>Carte mentale</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('carteGeo') }}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-map-2-line"></i>
                        </div>
                        <span>Carte géographique</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{route('calendar')}}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <span>Calendrier</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('blog.index') }}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-article-line"></i>
                        </div>
                        <span>Blog</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-3 border-top border-white border-opacity-10">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                    <i class="ri-logout-box-r-line"></i>
                </div>
                <span>Déconnexion</span>
            </a>
            <form id="logout-form" action="{{ route('login.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Mobile Header -->
    <div class="d-md-none fixed-top bg-white shadow-sm z-3 p-3 d-flex align-items-center justify-content-between">
        <button class="p-2 rounded border-0 bg-transparent" id="mobile-menu-button">
            <div class="w-6 h-6 d-flex align-items-center justify-content-center">
                <i class="ri-menu-line text-gray-700"></i>
            </div>
        </button>
        <h1 class="text-xl font-pacifico text-primary m-0">FocusMap</h1>
        <button class="p-2 rounded border-0 bg-transparent">
            <div class="w-6 h-6 d-flex align-items-center justify-content-center">
                <i class="ri-user-line text-gray-700"></i>
            </div>
        </button>
    </div>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar-overlay d-md-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 z-4 d-none"
        id="mobile-overlay"></div>
    <div class="sidebar mobile-sidebar position-fixed h-100 bg-dark text-white d-flex flex-column z-4 d-none"
        id="mobile-sidebar">
        <div class="p-4 d-flex align-items-center justify-content-between">
            <h1 class="text-2xl font-pacifico text-white m-0">FocusMap</h1>
            <button class="p-2 rounded border-0 bg-transparent" id="close-sidebar">
                <div class="w-6 h-6 d-flex align-items-center justify-content-center">
                    <i class="ri-close-line text-white"></i>
                </div>
            </button>
        </div>
        <nav class="flex-grow-1 px-3 py-4">
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <a href="{{ route('dashboard') }}"
                        class="d-flex align-items-center p-3 rounded bg-primary bg-opacity-20 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-dashboard-line"></i>
                        </div>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('goals.index') }}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-flag-line"></i>
                        </div>
                        <span>Mes objectifs</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('mindMap') }}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-node-tree"></i>
                        </div>
                        <span>Carte mentale</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('carteGeo') }}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-map-2-line"></i>
                        </div>
                        <span>Carte géographique</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('calendar') }}"
                        class="d-flex align-items-center p-3 roundedochemically-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <span>Calendrier</span>
                    </a>
                </li>
                <li class="mb-2">
                    <a href="{{ route('blog.index') }}"
                        class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                        <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                            <i class="ri-article-line"></i>
                        </div>
                        <span>Blog</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-3 border-top border-white border-opacity-10">
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                class="d-flex align-items-center p-3 rounded hover-bg-white-10 text-white text-decoration-none">
                <div class="w-6 h-6 d-flex align-items-center justify-content-center me-3">
                    <i class="ri-logout-box-r-line"></i>
                </div>
                <span>Déconnexion</span>
            </a>
            <form id="logout-form-mobile" action="{{ route('login.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content min-vh-100 pt-4 pb-10">
        @yield('content')
    </main>

    <!-- Floating Chat Icon -->
    <div class="chat-icon" id="chat-icon">
        <i class="ri-chat-3-line ri-2x"></i>
    </div>

    <!-- Dialogflow Messenger Chatbot -->
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
        intent="WELCOME"
        chat-title="FocusMap Assistant"
        agent-id="your-agent-id"
        language-code="fr"
    ></df-messenger>

    @include('layouts.scripts')

    <script>
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('mobile-overlay');
        const chatIcon = document.getElementById('chat-icon');
        const dfMessenger = document.querySelector('df-messenger');

        // Mobile sidebar toggle
        document.getElementById('mobile-menu-button').addEventListener('click', () => {
            mobileSidebar.classList.remove('d-none');
            overlay.classList.remove('d-none');
        });

        document.getElementById('close-sidebar').addEventListener('click', () => {
            mobileSidebar.classList.add('d-none');
            overlay.classList.add('d-none');
        });

        overlay.addEventListener('click', () => {
            mobileSidebar.classList.add('d-none');
            overlay.classList.add('d-none');
        });

        // Chatbot toggle
        chatIcon.addEventListener('click', () => {
            dfMessenger.classList.toggle('active');
        });
    </script>
</body>

</html>