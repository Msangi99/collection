<div class="px-4 py-6 h-screen overflow-y-auto">
    <!-- Logo/Brand -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-white/20 rounded-2xl mx-auto mb-3 flex items-center justify-center">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
        </div>
        <h4 class="text-xl font-bold text-white tracking-tight">Special Hire</h4>
        <p class="text-white/60 text-sm">Coaster Management</p>
    </div>

    <nav class="space-y-1">
        <!-- Main Section -->
        <p class="text-xs font-semibold text-white/50 uppercase tracking-wider px-3 mb-2">Main</p>
        
        <a href="{{ route('special_hire.index') }}" 
           class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg {{ request()->routeIs('special_hire.index') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <!-- Fleet Management -->
        <p class="text-xs font-semibold text-white/50 uppercase tracking-wider px-3 mt-6 mb-2">Fleet</p>
        
        <a href="{{ route('special_hire.coasters') }}" 
           class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg {{ request()->routeIs('special_hire.coasters*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Coasters
        </a>
        
        <a href="{{ route('special_hire.tracking') }}" 
           class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg {{ request()->routeIs('special_hire.tracking') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Live Tracking
        </a>

        <!-- Bookings -->
        <p class="text-xs font-semibold text-white/50 uppercase tracking-wider px-3 mt-6 mb-2">Bookings</p>
        
        <a href="{{ route('special_hire.orders') }}" 
           class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg {{ request()->routeIs('special_hire.orders') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Orders
        </a>
        
        <a href="{{ route('special_hire.orders.create') }}" 
           class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg {{ request()->routeIs('special_hire.orders.create') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            New Order
        </a>

        <!-- Finance -->
        <p class="text-xs font-semibold text-white/50 uppercase tracking-wider px-3 mt-6 mb-2">Finance</p>
        
        <a href="{{ route('special_hire.pricing') }}" 
           class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg {{ request()->routeIs('special_hire.pricing') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Pricing
        </a>
        
        <a href="{{ route('special_hire.earnings') }}" 
           class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg {{ request()->routeIs('special_hire.earnings') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Earnings
        </a>

        <!-- Account -->
        <p class="text-xs font-semibold text-white/50 uppercase tracking-wider px-3 mt-6 mb-2">Account</p>
        
        <a href="{{ route('special_hire.profile') }}" 
           class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg {{ request()->routeIs('special_hire.profile') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
        
        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="sidebar-link flex items-center px-3 py-2.5 text-white rounded-lg w-full text-left hover:bg-red-500/20">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </nav>
</div>

