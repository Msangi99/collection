 <!DOCTYPE html>
 <html lang="{{ app()->getLocale() }}">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>{{ __('all.highlink_isgc') }} - @yield('title')</title>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
     <!-- Tailwind CSS CDN -->
     <script src="https://cdn.tailwindcss.com"></script>
     <style>
         /* Custom scrollbar for sidebar */
         .sidebar::-webkit-scrollbar {
             width: 6px;
         }

         .sidebar::-webkit-scrollbar-track {
             background: transparent;
         }

         .sidebar::-webkit-scrollbar-thumb {
             background: rgba(255, 255, 255, 0.3);
             border-radius: 3px;
         }

         .sidebar::-webkit-scrollbar-thumb:hover {
             background: rgba(255, 255, 255, 0.5);
         }
     </style>
 </head>

 <body class="bg-gray-100 font-sans">
     <!-- Sidebar Overlay (for mobile) -->
     <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-[999] hidden"></div>

     <div class="flex min-h-screen">
         <!-- Sidebar -->
         <nav id="sidebar"
             class="sidebar fixed top-0 bottom-0 left-0 w-64 bg-gradient-to-b from-blue-900 to-blue-400 backdrop-blur-lg text-white p-4 overflow-y-auto transition-all duration-300 md:w-64 -translate-x-full md:translate-x-0 z-[1000]">
             <button id="sidebar-close-btn" class="absolute top-4 right-4 text-white hover:text-gray-300 md:hidden">
                 <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                     <path d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>
             @include('customer.sidebar')
         </nav>

         <!-- Main Content -->
         <div class="main-content flex-1 ml-0 md:ml-64 transition-all duration-300">
             <nav class="bg-white shadow-md sticky top-0 z-[999]">
                 <div class="container-fluid px-4 py-3">
                     <div class="flex items-center justify-between">
                         <button id="sidebar-toggle"
                             class="navbar-toggler text-teal-600 hover:text-teal-700 md:hidden focus:outline-none">
                             <svg id="hamburger-icon" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                 <path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z" />
                             </svg>
                             <svg id="close-icon" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 24 24">
                                 <path d="M6 18L18 6M6 6l12 12" />
                             </svg>
                         </button>
                         <h1 class="text-lg font-semibold text-gray-900">{{ __('all.highlink_isgc') }}</h1>
                         <div class="relative flex">

                             <select
                                 class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                 onchange="window.location.href = '{{ route('set.currency', ['currency' => ':currency']) }}'.replace(':currency', this.value)">
                                 <option value="Tsh" {{ session('currency') == 'Tsh' ? 'selected' : '' }}>Tsh
                                 </option>
                                 <option value="Usd" {{ session('currency') == 'Usd' ? 'selected' : '' }}>Usd
                                 </option>
                             </select>
                             <div
                                 class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                 <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                     <path
                                         d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                 </svg>
                             </div>

                             <select
                                 class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                 onchange="window.location.href = '{{ route('set.locale', ['lang' => '']) }}' + this.value">
                                  <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>{{ __('customer/busroot.english') }}
                                 </option>
                                 <option value="sw" {{ app()->getLocale() == 'sw' ? 'selected' : '' }}>{{ __('customer/busroot.kiswahili') }}
                                 </option>
                             </select>
                             <div
                                 class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                 <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                     <path
                                         d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                 </svg>
                             </div>
                         </div>
                     </div>
                 </div>
             </nav>

             <main class="container-fluid py-4">
                 @yield('content')
             </main>

             @include('customer.footer')
         </div>
     </div>

     <!-- Custom JS for sidebar toggle -->
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const sidebar = document.getElementById('sidebar');
             const sidebarToggle = document.getElementById('sidebar-toggle');
             const hamburgerIcon = document.getElementById('hamburger-icon');
             const closeIcon = document.getElementById('close-icon');
             const sidebarOverlay = document.getElementById('sidebar-overlay');

             // Toggle sidebar when navbar toggler is clicked
             sidebarToggle.addEventListener('click', function() {
                 sidebar.classList.toggle('-translate-x-full');
                 sidebarOverlay.classList.toggle('hidden');
                 hamburgerIcon.classList.toggle('hidden');
                 closeIcon.classList.toggle('hidden');
             });

             // Close sidebar when close button or overlay is clicked
             const closeSidebar = function() {
                 sidebar.classList.add('-translate-x-full');
                 sidebarOverlay.classList.add('hidden');
                 hamburgerIcon.classList.remove('hidden');
                 closeIcon.classList.add('hidden');
             };

             document.getElementById('sidebar-close-btn').addEventListener('click', closeSidebar);
             sidebarOverlay.addEventListener('click', closeSidebar);

             // Close sidebar when clicking outside on mobile
             document.addEventListener('click', function(event) {
                 const isClickInsideSidebar = sidebar.contains(event.target);
                 const isClickOnToggler = sidebarToggle.contains(event.target);

                 if (!isClickInsideSidebar && !isClickOnToggler && window.innerWidth < 768) {
                     closeSidebar();
                 }
             });
         });
     </script>
 </body>

 </html>
