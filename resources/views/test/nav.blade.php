<style>
    @media (max-width: 767px) {
        #mobile-menu {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-12px);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        #mobile-menu.open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }
    }
</style>

<nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md shadow-sm">
    <div class="container mx-auto px-3 sm:px-4 py-2.5 sm:py-3 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center space-x-1.5 sm:space-x-2 flex-shrink-0">
            <img src="{{ asset('ChatGPT Image Jul 7, 2025, 12_18_13 PM.png') }}" alt="Bus icon"
                class="h-8 w-8 sm:h-10 sm:w-10 rounded-2xl" loading="lazy">
            <a href="{{ route('home') }}" wire:navigate
                class="text-base sm:text-xl font-bold bg-gradient-to-r from-indigo-600 to-pink-600 bg-clip-text text-transparent whitespace-nowrap">Highlink ISGC</a>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <a wire:navigate href="{{ route('home') }}"
                class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">Home</a>
            <a wire:navigate href="{{ route('about') }}"
                class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">About Us</a>
            <a wire:navigate href="{{ route('contact') }}"
                class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">Contact Us</a>
            <a wire:navigate href="{{ route('info') }}"
                class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">Booking Info</a>
            <a href="tel:+255755879793"
                class="flex items-center text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                <img src="{{ asset('images/phone-call.png') }}" alt="Phone icon" class="mr-2 h-5 w-5" loading="lazy">
                <span>+255 755 879 793</span>
            </a>
        </div>

        <!-- Auth Actions -->
        <div class="flex items-center space-x-2 md:space-x-4">
            @guest
                <a wire:navigate href="{{ route('login') }}"
                    class="hidden md:block px-4 py-2 text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 transition-colors duration-200">
                    Sign In
                </a>
                <a wire:navigate href="{{ route('register') }}"
                    class="hidden md:block px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 shadow-sm">
                    Register
                </a>
            @endguest
            @auth
                @if (auth()->user()->isAdmin())
                    <a wire:navigate href="{{ route('system.index') }}"
                        class="hidden md:block px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 shadow-sm">
                        Dashboard
                    </a>
                @elseif (auth()->user()->isBuscampany())
                    <a wire:navigate href="{{ route('index') }}"
                        class="hidden md:block px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 shadow-sm">
                        Dashboard
                    </a>
                @elseif (auth()->user()->isVender())
                    <a wire:navigate href="{{ route('vender.index') }}"
                        class="hidden md:block px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 shadow-sm">
                        Dashboard
                    </a>
                @elseif (auth()->user()->role == 'customer')
                    <a wire:navigate href="{{ route('customer.index') }}"
                        class="hidden md:block px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 shadow-sm">
                        Dashboard
                    </a>
                @endif
            @endauth
            
            <!-- Mobile Menu Button (Visible for all users) -->
            <button id="mobile-menu-btn"
                class="md:hidden text-gray-700 focus:outline-none p-2 hover:bg-gray-100 rounded-md transition-colors duration-200"
                aria-label="Toggle mobile menu" aria-expanded="false">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="mobile-menu md:hidden absolute top-full left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200 rounded-b-2xl shadow-lg">
        <div class="flex flex-col space-y-1 p-4">
            <a wire:navigate href="{{ route('home') }}"
                class="px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors duration-200 font-medium">Home</a>
            <a wire:navigate href="{{ route('about') }}"
                class="px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors duration-200 font-medium">About
                Us</a>
            <a wire:navigate href="{{ route('contact') }}"
                class="px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors duration-200 font-medium">Contact
                Us</a>
            <a wire:navigate href="{{ route('info') }}"
                class="px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors duration-200 font-medium">Booking
                Info</a>
            <a href="tel:+255755879793"
                class="px-4 py-3 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors duration-200 flex items-center font-medium">
                <img src="{{ asset('images/phone-call.png') }}" alt="Phone icon" class="mr-2 h-5 w-5" loading="lazy">
                <span>+255 755 879 793</span>
            </a>
            @guest
                <div class="pt-2 mt-2 border-t border-gray-200 space-y-2">
                    <a wire:navigate href="{{ route('login') }}"
                        class="block w-full px-4 py-2.5 text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 transition-colors duration-200 text-center font-medium">
                        Sign In
                    </a>
                    <a wire:navigate href="{{ route('register') }}"
                        class="block w-full px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 text-center font-medium">
                        Register
                    </a>
                </div>
            @endguest
            @auth
                <div class="pt-2 mt-2 border-t border-gray-200">
                    @if (auth()->user()->isAdmin())
                        <a wire:navigate href="{{ route('system.index') }}"
                            class="block w-full px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 text-center font-medium">
                            Dashboard
                        </a>
                    @elseif (auth()->user()->isBuscampany())
                        <a wire:navigate href="{{ route('index') }}"
                            class="block w-full px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 text-center font-medium">
                            Dashboard
                        </a>
                    @elseif (auth()->user()->isVender())
                        <a wire:navigate href="{{ route('vender.index') }}"
                            class="block w-full px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 text-center font-medium">
                            Dashboard
                        </a>
                    @elseif (auth()->user()->role == 'customer')
                        <a wire:navigate href="{{ route('customer.index') }}"
                            class="block w-full px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-md hover:bg-opacity-90 transition-colors duration-200 text-center font-medium">
                            Dashboard
                        </a>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (!mobileMenuBtn || !mobileMenu) {
            return;
        }

        const setMenuState = (isOpen) => {
            mobileMenu.classList.toggle('open', isOpen);
            mobileMenuBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            mobileMenuBtn.innerHTML = isOpen
                ? '<i class="fas fa-times text-xl"></i>'
                : '<i class="fas fa-bars text-xl"></i>';
        };

        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('open');
            setMenuState(!isOpen);
        });

        mobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => setMenuState(false));
        });

        document.addEventListener('click', (event) => {
            if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                setMenuState(false);
            }
        });
    });
</script>
