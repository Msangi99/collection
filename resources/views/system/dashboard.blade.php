@extends('system.app')

@section('title', 'Company Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Dashboard Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
        <div class="text-sm text-gray-500">
            {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
        </div>
    </div>

    @if (auth()->user()->hasAccess(\App\Models\Access::LINKS['CARDS']))
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Insurance Amount Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-100 rounded-xl p-6 transition-all hover:shadow-md hover:border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Insurance Amount</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">Tsh.{{ number_format($bima, 2) }}</h3>
                    </div>
                    <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-blue-500 mt-2">+2.5% from last week</p>
            </div>

            <!-- Total Commission Fees Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-100 rounded-xl p-6 transition-all hover:shadow-md hover:border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Commission Fees</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">Tsh.{{ number_format($service, 2) }}</h3>
                    </div>
                    <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-purple-500 mt-2">+3.1% from last week</p>
            </div>

            <!-- Total Service Fees Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-100 rounded-xl p-6 transition-all hover:shadow-md hover:border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Service Fees</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">Tsh.{{ number_format($fees, 2) }}</h3>
                    </div>
                    <div class="p-3 rounded-lg bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-green-500 mt-2">+4.2% from last week</p>
            </div>

            <!-- Balance Card -->
            <a href="{{ route('system.balance') }}" class="no-underline">
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-100 rounded-xl p-6 transition-all hover:shadow-md hover:border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-600">Available Balance</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1">Tsh.{{ number_format($balance, 2) }}</h3>
                        </div>
                        <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-indigo-500 mt-2">View balance details</p>
                </div>
            </a>
        </div>

        <!-- Total Cancelled Bookings Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-100 rounded-xl p-6 transition-all hover:shadow-md hover:border-red-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Cancellation Fees</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">Tsh.{{ number_format($cancelledAmount, 2) }}</h3>
                    </div>
                    <div class="p-3 rounded-lg bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-red-500 mt-2">Total cancelled amount</p>
            </div>
        </div>
    @endif

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Weekly Amount Chart -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-lg font-semibold text-gray-800">Weekly Booking Amounts</h5>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg">Week</button>
                        <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg">Month</button>
                        <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-lg">Year</button>
                    </div>
                </div>
                <div class="w-full h-64">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity (Placeholder - you can add actual content) -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h5>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="p-2 bg-green-100 rounded-lg text-green-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">New booking confirmed</p>
                            <p class="text-xs text-gray-500">Booking #BK-2023-0567 for Tsh 45,000</p>
                            <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="p-2 bg-blue-100 rounded-lg text-blue-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Payment processed</p>
                            <p class="text-xs text-gray-500">Tsh 12,500 commission received</p>
                            <p class="text-xs text-gray-400 mt-1">5 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="p-2 bg-purple-100 rounded-lg text-purple-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Profile updated</p>
                            <p class="text-xs text-gray-500">Company contact information changed</p>
                            <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-lg font-semibold text-gray-800">Today's Bookings</h5>
                <div class="relative">
                    <input type="text" placeholder="Search bookings..." class="pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            @if ($bookings->isEmpty())
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no bookings for today.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Travel Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->booking_code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->customer_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->campany->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $booking->pickup_point }}-{{ $booking->dropping_point }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($booking->travel_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Tsh {{ number_format($booking->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $booking->payment_status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">{{ $bookings->count() }}</span> results
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </button>
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Weekly Amounts Chart with enhanced styling
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    const weeklyData = @json($weeklyAmounts);

    const labels = weeklyData.map(item => item.date);
    const amounts = weeklyData.map(item => item.amount);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Booking Amount (Tsh)',
                data: amounts,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                borderWidth: 2,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1F2937',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Amount: Tsh ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: '#E5E7EB'
                    },
                    ticks: {
                        color: '#6B7280',
                        callback: function(value) {
                            return 'Tsh ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280'
                    }
                }
            }
        }
    });
</script>
@endsection