<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Leave a Review')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; min-height: 100%; }
        body { background: #f8fafc; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: #111827; }
        button, input, select, textarea { font: inherit; }
        img { max-width: 100%; display: block; }
        a { color: inherit; text-decoration: none; }
        .bg-gray-50 { background: #f8fafc; }
        .bg-white { background: #ffffff; }
        .min-h-screen { min-height: 100vh; }
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .items-start { align-items: flex-start; }
        .justify-center { justify-content: center; }
        .text-center { text-align: center; }
        .p-4 { padding: 1rem; }
        .p-6 { padding: 1.5rem; }
        .pt-8 { padding-top: 2rem; }
        .rounded-2xl { border-radius: 1.5rem; }
        .rounded-full { border-radius: 9999px; }
        .shadow-lg { box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08); }
        .shadow-sm { box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08); }
        .w-full { width: 100%; }
        .max-w-md { max-width: 28rem; }
        .max-w-lg { max-width: 32rem; }
        .text-lg { font-size: 1.125rem; }
        .text-sm { font-size: 0.875rem; }
        .text-xs { font-size: 0.75rem; }
        .text-gray-400 { color: #9ca3af; }
        .text-gray-500 { color: #6b7280; }
        .text-gray-800 { color: #1f2937; }
        .font-bold { font-weight: 700; }
        .font-medium { font-weight: 500; }
        .rounded-lg { border-radius: 1rem; }
        .rounded-full { border-radius: 9999px; }
        .shadow-lg { box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08); }
        .mt-2 { margin-top: 0.5rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .text-xs { font-size: 0.75rem; }
        .bg-white { background: #ffffff; }
        .rounded-full { border-radius: 9999px; }
        .shadow-sm { box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08); }
        .w-12 { width: 3rem; }
        .h-12 { height: 3rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-8 { margin-bottom: 2rem; }
        .p-4 { padding: 1rem; }
        .p-6 { padding: 1.5rem; }
        .p-8 { padding: 2rem; }
    
        .object-cover { object-fit: cover; }
        .flex-1 { flex: 1 1 0%; }
        .justify-start { justify-content: flex-start; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

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

    <main class="flex-1 flex items-start justify-center p-4 pt-8">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6">
            @yield('content')
        </div>
    </main>

    <footer class="p-4 text-center text-xs text-gray-400">
        Powered by Kesharinandan
    </footer>

    @stack('scripts')
</body>
</html>
