@extends('special_hire.app')

@section('title', 'Earnings')
@section('page_title', 'Earnings Report')
@section('page_subtitle', 'Track your revenue and performance')

@section('content')
<div class="space-y-6">
    <!-- Period Filter -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-wrap items-center gap-4">
            <span class="text-gray-600 font-medium">Period:</span>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('special_hire.earnings', ['period' => 'today']) }}" 
                   class="px-4 py-2 rounded-xl font-medium transition-colors {{ $period === 'today' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Today
                </a>
                <a href="{{ route('special_hire.earnings', ['period' => 'week']) }}" 
                   class="px-4 py-2 rounded-xl font-medium transition-colors {{ $period === 'week' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    This Week
                </a>
                <a href="{{ route('special_hire.earnings', ['period' => 'month']) }}" 
                   class="px-4 py-2 rounded-xl font-medium transition-colors {{ $period === 'month' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    This Month
                </a>
                <a href="{{ route('special_hire.earnings', ['period' => 'year']) }}" 
                   class="px-4 py-2 rounded-xl font-medium transition-colors {{ $period === 'year' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    This Year
                </a>
            </div>
            <div class="ml-auto text-sm text-gray-500">
                {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-sm">Total Earnings</p>
                    <h3 class="text-3xl font-bold mt-1">Tsh {{ number_format($totalEarnings) }}</h3>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Completed Orders</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalOrders }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Distance</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalDistance) }} km</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings by Coaster -->
    @if($earningsByCoaster->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Earnings by Coaster</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($earningsByCoaster as $earning)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $earning->coaster->name ?? 'Unknown' }}</h4>
                                    <p class="text-sm text-gray-500">{{ $earning->trips }} trips</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-teal-600 text-lg">Tsh {{ number_format($earning->total) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Paid Orders -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Paid Orders</h3>
        </div>
        @if($orders->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500">No paid orders for this period</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Coaster</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Distance</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr class="table-row-hover">
                                <td class="px-6 py-4">
                                    <a href="{{ route('special_hire.orders.show', $order->id) }}" class="font-mono font-semibold text-teal-600 hover:text-teal-700">
                                        {{ $order->order_code }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-800">{{ $order->customer_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $order->coaster->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $order->distance_km }} km</td>
                                <td class="px-6 py-4 text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">Tsh {{ number_format($order->total_amount) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

