@extends('special_hire.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview of your special hire business')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Revenue -->
        <div class="stat-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">Tsh {{ number_format($todayRevenue) }}</h3>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-500">Month: Tsh {{ number_format($monthRevenue) }}</span>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="stat-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $todayOrders }}</h3>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-500">Total: {{ $totalOrders }} orders</span>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="stat-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingOrders }}</h3>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-yellow-600 font-medium">Needs attention</span>
            </div>
        </div>

        <!-- Active Coasters -->
        <div class="stat-card rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Coasters</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $availableCoasters }}/{{ $totalCoasters }}</h3>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-teal-600 font-medium">Available</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Recent Orders</h3>
                <a href="{{ route('special_hire.orders') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">View All →</a>
            </div>
            <div class="p-6">
                @if($recentOrders->isEmpty())
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500">No orders yet</p>
                        <a href="{{ route('special_hire.orders.create') }}" class="btn-primary inline-block mt-4 px-4 py-2 text-white text-sm rounded-lg">
                            Create First Order
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <a href="{{ route('special_hire.orders.show', $order->id) }}" class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-800">{{ $order->customer_name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $order->pickup_location }} → {{ $order->dropoff_location }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900">Tsh {{ number_format($order->total_amount) }}</p>
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-{{ $order->getStatusColor() }}-100 text-{{ $order->getStatusColor() }}-700">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Orders -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Upcoming Hires</h3>
                <a href="{{ route('special_hire.orders.create') }}" class="text-sm bg-teal-600 text-white px-3 py-1.5 rounded-lg hover:bg-teal-700 transition-colors">
                    + New Order
                </a>
            </div>
            <div class="p-6">
                @if($upcomingOrders->isEmpty())
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500">No upcoming hires</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-gray-500 uppercase">
                                    <th class="pb-3">Code</th>
                                    <th class="pb-3">Customer</th>
                                    <th class="pb-3">Date</th>
                                    <th class="pb-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($upcomingOrders as $order)
                                    <tr class="table-row-hover">
                                        <td class="py-3">
                                            <span class="text-sm font-mono font-semibold text-teal-600">{{ $order->order_code }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-sm text-gray-800">{{ $order->customer_name }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-sm text-gray-600">{{ $order->hire_date->format('M d, Y') }}</span>
                                        </td>
                                        <td class="py-3">
                                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-{{ $order->getStatusColor() }}-100 text-{{ $order->getStatusColor() }}-700">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Map Section -->
    @if($coastersWithLocation->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Coaster Locations</h3>
                <a href="{{ route('special_hire.tracking') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">Full Map →</a>
            </div>
            <div id="dashboard-map" class="h-80"></div>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold">Total Revenue</h3>
                <p class="text-3xl font-bold mt-2">Tsh {{ number_format($totalRevenue) }}</p>
                <p class="text-teal-200 text-sm mt-1">All time earnings</p>
            </div>
            <div class="hidden md:block">
                <svg class="w-24 h-24 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

@if($coastersWithLocation->count() > 0)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('dashboard-map').setView([-6.7924, 39.2083], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers for coasters
    const coasters = @json($coastersWithLocation);
    const bounds = [];
    
    coasters.forEach(coaster => {
        if (coaster.latitude && coaster.longitude) {
            const marker = L.marker([coaster.latitude, coaster.longitude])
                .addTo(map)
                .bindPopup(`
                    <div class="p-2">
                        <h4 class="font-bold">${coaster.name}</h4>
                        <p class="text-sm text-gray-600">${coaster.plate_number}</p>
                        <p class="text-xs mt-1">
                            <span class="inline-block px-2 py-0.5 rounded-full ${coaster.status === 'available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">
                                ${coaster.status}
                            </span>
                        </p>
                    </div>
                `);
            bounds.push([coaster.latitude, coaster.longitude]);
        }
    });
    
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50] });
    }
});
</script>
@endpush
@endif
@endsection

