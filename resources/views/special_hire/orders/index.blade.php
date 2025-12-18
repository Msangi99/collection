@extends('special_hire.app')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('page_subtitle', 'Manage hire requests and bookings')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <form action="{{ route('special_hire.orders') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment</label>
                <select name="payment_status" class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500">
                    <option value="">All</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="date" value="{{ request('date') }}"
                       class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                Filter
            </button>
            <a href="{{ route('special_hire.orders') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700 font-medium">
                Clear
            </a>
            <div class="ml-auto">
                <a href="{{ route('special_hire.orders.create') }}" class="btn-primary px-4 py-2 text-white rounded-xl font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    New Order
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @if($orders->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Orders Found</h3>
                <p class="text-gray-500 mb-6">Create your first order to get started.</p>
                <a href="{{ route('special_hire.orders.create') }}" class="btn-primary inline-block px-6 py-3 text-white rounded-xl font-medium">
                    Create First Order
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Order</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Route</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr class="table-row-hover">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-semibold text-teal-600">{{ $order->order_code }}</span>
                                    @if($order->coaster)
                                        <p class="text-xs text-gray-500 mt-1">{{ $order->coaster->name }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">{{ $order->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-800 max-w-xs truncate">{{ $order->pickup_location }}</div>
                                    <div class="text-xs text-gray-400">→ {{ Str::limit($order->dropoff_location, 30) }}</div>
                                    <div class="text-xs text-teal-600 font-medium mt-1">{{ $order->distance_km }} km</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-800">{{ $order->hire_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->hire_time)->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">Tsh {{ number_format($order->total_amount) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-{{ $order->getStatusColor() }}-100 text-{{ $order->getStatusColor() }}-700">
                                        {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-{{ $order->getPaymentStatusColor() }}-100 text-{{ $order->getPaymentStatusColor() }}-700">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('special_hire.orders.show', $order->id) }}" 
                                       class="text-teal-600 hover:text-teal-700 font-medium text-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

