<?php
/**
 * @var \Illuminate\Support\Collection $routes
 */
?>

<div class="customer-sidebar px-4">
    <div class="text-center mb-6 pt-4">
        <h4 class="text-white text-xl font-semibold tracking-tight">Customer Dashboard</h4>
        <hr class="border-gray-300/50 mt-2">
    </div>

    <ul class="space-y-2">
        <li>
            <a class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-teal-700 transition-all duration-200"
                href="#">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 0C5.372 0 0 5.372 0 12c0 6.628 5.372 12 12 12s12-5.372 12-12C24 5.372 18.628 0 12 0zm0 22c-5.52 0-10-4.48-10-10S6.48 2 12 2s10 4.48 10 10-4.48 10-10 10zm-1-15h-2v2h2v-2zm0 4h-2v2h2v-2zm0 4h-2v2h2v-2zm4-8h-2v2h2v-2zm0 4h-2v2h2v-2zm0 4h-2v2h2v-2z" />
                </svg>
                Wallet: {{ convert_money(auth()->user()->temp_wallets->amount ?? '0') }} {{ $currency }}
            </a>
        </li>
        <li>
            <a class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-teal-700 transition-all duration-200 {{ request()->routeIs('customer.index') ? 'bg-teal-600' : '' }}"
                href="{{ route('customer.index') }}">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
                {{ __('customer_sidebar.Dashboard') }}
            </a>
        </li>
        <li>
            <a class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-teal-700 transition-all duration-200 {{ request()->routeIs('customer.mybooking') ? 'bg-teal-600' : '' }}"
                href="{{ route('customer.mybooking') }}">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM4 8h16v2H4V8zm0 4h16v6H4v-6z" />
                </svg>
                {{ __('customer_sidebar.My Tickets') }}
            </a>
        </li>
        <li>
            <a class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-teal-700 transition-all duration-200 {{ request()->routeIs('customer.mybooking.search') ? 'bg-teal-600' : '' }}"
                href="{{ route('customer.mybooking.search') }}">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                </svg>
                {{ __('customer_sidebar.Bus Route') }}
            </a>
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
            <a class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-teal-700 transition-all duration-200 {{ request()->routeIs('customer.profile') ? 'bg-teal-600' : '' }}"
                href="{{ route('customer.profile') }}">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                {{ __('customer_sidebar.Profile') }}
            </a>
        </li>
        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center px-3 py-2 text-white rounded-lg hover:bg-teal-700 transition-all duration-200 w-full text-left {{ request()->routeIs('logout') ? 'bg-teal-600' : '' }}">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z" />
                    </svg>
                    {{ __('customer_sidebar.Logout') }}
                </button>
            </form>
        </li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Custom namespace for sidebar collapse to avoid Bootstrap conflicts
        const sidebarToggle = document.querySelector('button[aria-controls="booking-history-collapse"]');
        const sidebarCollapse = document.getElementById('booking-history-collapse');
        const sidebarChevron = document.querySelector('.booking-history-chevron');

        if (sidebarToggle && sidebarCollapse && sidebarChevron) {
            window.customerSidebarToggleCollapse = function() {
                const isExpanded = sidebarToggle.getAttribute('aria-expanded') === 'true';
                sidebarToggle.setAttribute('aria-expanded', !isExpanded);
                sidebarCollapse.classList.toggle('hidden');
                sidebarChevron.classList.toggle('rotate-180');
            };

            // Initialize collapse state based on active route
            if ({{ request()->routeIs('history') ? 'true' : 'false' }}) {
                sidebarToggle.setAttribute('aria-expanded', 'true');
                sidebarCollapse.classList.remove('hidden');
                sidebarChevron.classList.add('rotate-180');
            }
        }
    });
</script>
<style>
    .customer-sidebar .px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}
</style>
