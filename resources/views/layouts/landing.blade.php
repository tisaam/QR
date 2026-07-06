<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Leave a Review')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Star Rating CSS -->
    <style>
        .star-rating { 
            display: flex; 
            flex-direction: row-reverse; 
            justify-content: center; 
            gap: 10px; 
        }
        .star-rating input { 
            display: none; 
        }
        .star-rating label { 
            font-size: 3rem; 
            color: #d1d5db; 
            cursor: pointer; 
            transition: color 0.2s; 
        }
        .star-rating input:checked ~ label, 
        .star-rating label:hover, 
        .star-rating label:hover ~ label { 
            color: #facc15; 
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Top Bar -->
    <div class="bg-white shadow-sm p-4 text-center">
        @isset($business)
            @if($business->logo)
                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" class="w-12 h-12 rounded-full mx-auto mb-2 object-cover">
            @else
                <div class="w-12 h-12 bg-blue-100 rounded-full mx-auto mb-2 flex items-center justify-center">
                    <i class="fas fa-store text-blue-600 text-xl"></i>
                </div>
            @endif
            <h1 class="text-lg font-bold text-gray-800">{{ $business->name }}</h1>
            <p class="text-sm text-gray-500">
                {{ $business->city ?? '' }}{{ ($business->city && $business->state) ? ', ' : '' }}{{ $business->state ?? '' }}
            </p>
        @else
            <div class="w-12 h-12 bg-gray-100 rounded-full mx-auto mb-2 flex items-center justify-center">
                <i class="fas fa-qrcode text-gray-400 text-xl"></i>
            </div>
            <h1 class="text-lg font-bold text-gray-800">QR Review</h1>
        @endisset
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 flex items-start justify-center p-4 pt-8">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="p-4 text-center text-xs text-gray-400">
        Powered by Kesharinandan
    </footer>

    @stack('scripts')
</body>
</html>