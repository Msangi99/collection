<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Booking - @yield('title')</title>
    
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Custom styles for specific overrides -->
    <style>
        .form-control:focus {
            @apply ring-2 ring-blue-500 ring-offset-2 border-blue-500;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="container py-8 max-w-3xl">
        <div class="text-center mb-8">
            <svg class="w-12 h-12 mx-auto text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 10h-1V8c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2v2H3c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h18c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-3-2v2H6V8h12zm-3 8H9v-2h6v2zm4-4H5v-2h14v2z"/>
            </svg>
            <h3 class="mt-2 text-2xl font-semibold text-gray-800">HIGHLINK ISGC</h3>
        </div>
        
        @yield('content')
    </div>

    <!-- No Bootstrap JS required; keeping any custom JS --> 
</body>
</html>