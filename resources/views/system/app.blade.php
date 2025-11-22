<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'System Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-gray-800 text-white shadow-lg transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out fixed h-screen z-20">
            @include('system.sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-0 md:ml-64">
            <!-- Navbar -->
            <div class="bg-white shadow flex items-center justify-between px-4 py-3 fixed top-0 left-0 md:left-64 right-0 z-10">
                <button id="toggleSidebar" class="md:hidden p-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                @include('system.navbar')
            </div>

            <!-- Content -->
            <main class="flex-1 mt-16 overflow-y-auto p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white shadow-inner p-4">
                @include('system.footer')
            </footer>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-10"></div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleSidebarButton = document.getElementById('toggleSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        toggleSidebarButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    </script>
</body>
</html>