<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="hidden md:flex md:flex-col md:w-64 bg-gray-900 text-white transition-all duration-300">
            <div class="flex items-center justify-between h-16 px-4 bg-gray-800">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-400">
                    <i class="fas fa-qrcode mr-2"></i>QRReview
                </a>
            </div>
            
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Dashboard
                </a>
                <a href="{{ route('qr-codes.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                    <i class="fas fa-qrcode w-5 mr-3"></i> QR Codes
                </a>
                <a href="{{ route('reviews.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                    <i class="fas fa-star w-5 mr-3"></i> Reviews
                </a>
                <a href="{{ route('analytics.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                    <i class="fas fa-chart-line w-5 mr-3"></i> Analytics
                </a>
                
                <p class="px-4 pt-4 text-xs font-semibold text-gray-500 uppercase">Campaigns</p>
                <a href="{{ route('whatsapp.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                    <i class="fab fa-whatsapp w-5 mr-3"></i> WhatsApp
                </a>
                <a href="{{ route('sms.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                    <i class="fas fa-sms w-5 mr-3"></i> SMS
                </a>

                <p class="px-4 pt-4 text-xs font-semibold text-gray-500 uppercase">Management</p>
                <a href="{{ route('branches.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                    <i class="fas fa-code-branch w-5 mr-3"></i> Branches
                </a>
                <a href="{{ route('nfc-cards.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">
                    <i class="fas fa-wifi w-5 mr-3"></i> NFC Cards
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="flex items-center justify-between h-16 px-6 bg-white shadow-md">
                <!-- Mobile Menu Button -->
                <button id="menu-btn" class="text-gray-500 focus:outline-none md:hidden">
                    <i class="fas fa-bars text-2xl"></i>
                </button>

                <div class="flex-1"></div>

                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-500 hover:text-blue-600">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </a>

                    <!-- User Dropdown -->
                                        @auth
                    <!-- User Dropdown -->
                    <div class="relative" id="user-menu">
                        <button class="flex items-center space-x-2 text-gray-700 focus:outline-none">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:inline font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- Guest Menu -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Sign Up</a>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-green-700"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded flex justify-between items-center">
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-red-700"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Tailwind JS & Custom Scripts -->
    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const menuBtn = document.getElementById('menu-btn');
        menuBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('absolute');
            sidebar.classList.toggle('z-40');
            sidebar.classList.toggle('h-full');
        });

        // User dropdown toggle
        const userMenu = document.getElementById('user-menu');
        const userDropdown = document.getElementById('user-dropdown');
        userMenu?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', () => userDropdown?.classList.add('hidden'));
    </script>
    @stack('scripts')
</body>
</html>