<style>
    .nav-link.active {
        background-color: #3b3b70 !important; /* Matches the bg-blue-500 color from the original */
    }
</style>

<div class="px-4 py-6 h-screen overflow-y-auto bg-gray-800">
    <div class="text-center mb-6">
        <h4 class="text-xl font-semibold text-white">{{ __('assistance/sidebar.vendor_panel') }}</h4>
        <hr class="border-gray-600 mt-2">
    </div>

    <ul class="space-y-2">
        <li>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mt-4 mb-2">{{ __('assistance/sidebar.vendor_management') }}</p>
        </li>

        <li>
            <a wire:navigate class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('vender.index') ? 'bg-blue-500' : '' }} nav-link" href="{{ route('vender.index') }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                {{ __('assistance/sidebar.dashboard') }}
            </a>
        </li>

        <li>
            <a wire:navigate class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('vender.bus_route') ? 'bg-blue-500' : '' }} nav-link" href="{{ route('vender.bus_route') }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2H6zm2 2h8v2H8V7zm0 4h8v2H8v-2zm0 4h8v2H8v-2z"></path></svg>
                {{ __('assistance/sidebar.bus_schedule') }}
            </a>
        </li>

        @if (auth()->user()->status == 'accept')
        <li>
            <a wire:navigate class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('vender.route') ? 'bg-blue-500' : '' }} nav-link" href="{{ route('vender.route') }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                {{ __('assistance/sidebar.book_ticket') }}
            </a>
        </li>
        @endif

        <li>
            <div class="relative">
                <button 
                    type="button"
                    class="bus-system-dropdown-toggle flex items-center px-3 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors w-full text-left {{ request()->routeIs('vender.history') ? 'bg-blue-500' : '' }} nav-link"
                    aria-expanded="false"
                    aria-controls="vendor-booking-history"
                    data-bus-system-toggle="vendor-booking-history"
                    aria-label="{{ __('assistance/sidebar.booking_history_menu') }}"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    {{ __('assistance/sidebar.booking_history') }}
                    <svg class="w-4 h-4 ml-auto transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <ul 
                    id="vendor-booking-history"
                    class="bus-system-dropdown-menu hidden absolute left-0 mt-1 w-full bg-gray-700 text-white rounded-lg shadow-lg z-10"
                    role="menu"
                >
                    <li role="menuitem"><a wire:navigate class="block px-4 py-2 hover:bg-gray-600 rounded-t-lg" href="{{ route('vender.history') }}?period=today">{{ __('assistance/sidebar.today') }}</a></li>
                    <li role="menuitem"><a wire:navigate class="block px-4 py-2 hover:bg-gray-600" href="{{ route('vender.history') }}?period=week">{{ __('assistance/sidebar.week') }}</a></li>
                    <li role="menuitem"><a wire:navigate class="block px-4 py-2 hover:bg-gray-600" href="{{ route('vender.history') }}?period=month">{{ __('assistance/sidebar.month') }}</a></li>
                    <li role="menuitem"><a wire:navigate class="block px-4 py-2 hover:bg-gray-600 rounded-b-lg" href="{{ route('vender.history') }}?period=year">{{ __('assistance/sidebar.year') }}</a></li>
                </ul>
            </div>
        </li>

        <li>
            <a class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-teal-700 transition-all duration-200 {{ request()->routeIs('round.trip') ? 'bg-teal-600' : '' }}"
                href="{{ route('round.trip') }}">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
                Round Trip
            </a>
        </li>

        <li>
            <a wire:navigate class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('vender.transaction') ? 'bg-blue-500' : '' }} nav-link" href="{{ route('vender.transaction') }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                {{ __('assistance/sidebar.transactions') }}
            </a>
        </li>

        <li>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mt-4 mb-2">{{ __('assistance/sidebar.account') }}</p>
        </li> 
        <li>
            <a wire:navigate class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('vender.profile') ? 'bg-blue-500' : '' }} nav-link" href="{{ route('vender.profile') }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ __('assistance/sidebar.profile') }}
            </a>
        </li>
        
        <li>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-gray-700 transition-colors w-full text-left {{ request()->routeIs('logout') ? 'bg-blue-500' : '' }} nav-link">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1"></path></svg>
                    {{ __('assistance/sidebar.logout') }}
                </button>
            </form>
        </li>
    </ul>

    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-gray-900">
        <div class="text-center text-gray-400 small">
            <div>{{ __('assistance/sidebar.highlink_isgc') }}</div>
            <div class="mt-1">{{ __('assistance/sidebar.version') }}</div>
        </div>
    </div>
</div>

<script>
(function () {
    // Dropdown handler for Booking History
    const toggleButton = document.querySelector('[data-bus-system-toggle="vendor-booking-history"]');
    const dropdownMenu = document.getElementById('vendor-booking-history');

    if (toggleButton && dropdownMenu) {
        // Toggle dropdown visibility and ARIA state
        toggleButton.addEventListener('click', function (event) {
            event.preventDefault();
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
            toggleButton.setAttribute('aria-expanded', !isExpanded);
            dropdownMenu.classList.toggle('hidden');
            // Rotate chevron icon
            const chevron = toggleButton.querySelector('svg:last-child');
            chevron.classList.toggle('rotate-180');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (!toggleButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                toggleButton.setAttribute('aria-expanded', 'false');
                dropdownMenu.classList.add('hidden');
                const chevron = toggleButton.querySelector('svg:last-child');
                chevron.classList.remove('rotate-180');
            }
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && toggleButton.getAttribute('aria-expanded') === 'true') {
                toggleButton.setAttribute('aria-expanded', 'false');
                dropdownMenu.classList.add('hidden');
                const chevron = toggleButton.querySelector('svg:last-child');
                chevron.classList.remove('rotate-180');
                toggleButton.focus();
            }
        });
    }
})();
</script> 