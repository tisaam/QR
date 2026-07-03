<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Admin Sidebar (Purple Theme) -->
        <aside id="sidebar" class="hidden md:flex md:flex-col md:w-64 bg-indigo-900 text-white">
            <div class="flex items-center h-16 px-4 bg-indigo-800">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-purple-300">
                    <i class="fas fa-shield-halved mr-2"></i>Admin Panel
                </a>
            </div>
            
            <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i> Dashboard
                </a>
                <a href="{{ route('admin.businesses.index') }}" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded">
                    <i class="fas fa-building w-5 mr-3"></i> Businesses
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded">
                    <i class="fas fa-users w-5 mr-3"></i> Users
                </a>
                
                <p class="px-4 pt-4 text-xs font-semibold text-indigo-400 uppercase">Finance</p>
                <a href="{{ route('admin.plans.index') }}" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded">
                    <i class="fas fa-tags w-5 mr-3"></i> Plans
                </a>
                <a href="{{ route('admin.payments.index') }}" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded">
                    <i class="fas fa-credit-card w-5 mr-3"></i> Payments
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded">
                    <i class="fas fa-percent w-5 mr-3"></i> Coupons
                </a>

                <p class="px-4 pt-4 text-xs font-semibold text-indigo-400 uppercase">Support</p>
                <a href="{{ route('admin.tickets.index') }}" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded">
                    <i class="fas fa-headset w-5 mr-3"></i> Tickets
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-indigo-200 hover:bg-indigo-700 rounded">
                    <i class="fas fa-cog w-5 mr-3"></i> Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between h-16 px-6 bg-white shadow-md">
                <button id="menu-btn" class="text-gray-500 focus:outline-none md:hidden">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-semibold text-indigo-600">Super Admin</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-gray-500 hover:text-red-600">Logout <i class="fas fa-sign-out-alt"></i></button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        document.getElementById('menu-btn')?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('absolute');
            sidebar.classList.toggle('z-40');
            sidebar.classList.toggle('h-full');
        });
    </script>
    @stack('scripts')
</body>
</html>