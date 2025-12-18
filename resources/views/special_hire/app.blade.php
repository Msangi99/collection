<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Special Hire') - {{ config('app.name') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts - Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }
        
        :root {
            --primary: #0f766e;
            --primary-dark: #0d5f59;
            --primary-light: #14b8a6;
            --accent: #f97316;
            --bg-dark: #111827;
            --bg-card: #1f2937;
        }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f766e 0%, #064e3b 100%);
        }
        
        .card-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 118, 110, 0.3);
        }
        
        .table-row-hover:hover {
            background: rgba(15, 118, 110, 0.05);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #0f766e;
            border-radius: 3px;
        }
        
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(4px);
        }
        
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 3px solid #f97316;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar-gradient w-64 min-h-screen fixed left-0 top-0 z-40 shadow-2xl">
            @include('special_hire.sidebar')
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top Navbar -->
            @include('special_hire.navbar')
            
            <!-- Page Content -->
            <div class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Leaflet JS for Maps -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>

