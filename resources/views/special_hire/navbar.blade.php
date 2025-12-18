<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Page Title -->
            <div>
                <h1 class="text-xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
                <p class="text-sm text-gray-500">@yield('page_subtitle', 'Welcome back!')</p>
            </div>
            
            <!-- Right Section -->
            <div class="flex items-center space-x-4">
                <!-- Quick Stats -->
                <div class="hidden md:flex items-center space-x-6 mr-6">
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Today's Orders</p>
                        <p class="text-lg font-bold text-teal-600">{{ \App\Models\SpecialHireOrder::byUser(auth()->id())->today()->count() }}</p>
                    </div>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Active Coasters</p>
                        <p class="text-lg font-bold text-teal-600">{{ \App\Models\Coaster::byUser(auth()->id())->available()->count() }}</p>
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Special Hire</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50 border border-gray-100">
                        <a href="{{ route('special_hire.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>
                        <a href="{{ route('special_hire.earnings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Earnings
                        </a>
                        <hr class="my-2 border-gray-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

