@extends('special_hire.app')

@section('title', 'Order Details')
@section('page_title', 'Order: ' . $order->order_code)
@section('page_subtitle', 'View and manage order details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Order Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $order->order_code }}</h2>
                <p class="text-gray-500">Created {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-4 py-2 text-sm font-semibold rounded-full bg-{{ $order->getStatusColor() }}-100 text-{{ $order->getStatusColor() }}-700">
                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                </span>
                <span class="px-4 py-2 text-sm font-semibold rounded-full bg-{{ $order->getPaymentStatusColor() }}-100 text-{{ $order->getPaymentStatusColor() }}-700">
                    Payment: {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium text-gray-800">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium text-gray-800">{{ $order->customer_phone }}</p>
                    </div>
                    @if($order->customer_email)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium text-gray-800">{{ $order->customer_email }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Route Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Route Details</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pickup</p>
                            <p class="font-medium text-gray-800">{{ $order->pickup_location }}</p>
                        </div>
                    </div>
                    <div class="ml-4 border-l-2 border-dashed border-gray-300 h-8"></div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dropoff</p>
                            <p class="font-medium text-gray-800">{{ $order->dropoff_location }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Distance</p>
                        <p class="font-bold text-teal-600 text-lg">{{ $order->distance_km }} km</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Passengers</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $order->passengers_count }}</p>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Schedule</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Hire Date</p>
                        <p class="font-medium text-gray-800">{{ $order->hire_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pickup Time</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($order->hire_time)->format('h:i A') }}</p>
                    </div>
                    @if($order->return_date)
                        <div>
                            <p class="text-sm text-gray-500">Return Date</p>
                            <p class="font-medium text-gray-800">{{ $order->return_date->format('M d, Y') }}</p>
                        </div>
                    @endif
                    @if($order->return_time)
                        <div>
                            <p class="text-sm text-gray-500">Return Time</p>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($order->return_time)->format('h:i A') }}</p>
                        </div>
                    @endif
                </div>
                @if($order->purpose)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500">Purpose</p>
                        <p class="font-medium text-gray-800">{{ $order->purpose }}</p>
                    </div>
                @endif
                @if($order->notes)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500">Notes</p>
                        <p class="font-medium text-gray-800">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Coaster Info -->
            @if($order->coaster)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Coaster</h3>
                    <div class="text-center">
                        @if($order->coaster->image)
                            <img src="{{ Storage::url($order->coaster->image) }}" alt="{{ $order->coaster->name }}" 
                                 class="w-full h-32 object-cover rounded-xl mb-4">
                        @else
                            <div class="w-full h-32 bg-gray-100 rounded-xl mb-4 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                        @endif
                        <h4 class="font-bold text-gray-800">{{ $order->coaster->name }}</h4>
                        <p class="text-sm text-gray-500 font-mono">{{ $order->coaster->plate_number }}</p>
                        <p class="text-sm text-gray-600 mt-2">{{ $order->coaster->capacity }} seats</p>
                        @if($order->coaster->driver_name)
                            <div class="mt-4 pt-4 border-t border-gray-100 text-sm">
                                <p class="text-gray-500">Driver</p>
                                <p class="font-medium text-gray-800">{{ $order->coaster->driver_name }}</p>
                                @if($order->coaster->driver_contact)
                                    <p class="text-teal-600">{{ $order->coaster->driver_contact }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Price Breakdown -->
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-bold mb-4">Price Breakdown</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-teal-100">Base Price:</span>
                        <span>Tsh {{ number_format($order->base_price) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-teal-100">Distance ({{ $order->distance_km }} km × {{ number_format($order->price_per_km) }}):</span>
                        <span>Tsh {{ number_format($order->km_amount) }}</span>
                    </div>
                    @if($order->surcharge_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-teal-100">Surcharge ({{ $order->surcharge_percent }}%):</span>
                            <span>Tsh {{ number_format($order->surcharge_amount) }}</span>
                        </div>
                    @endif
                    <hr class="border-teal-400 my-2">
                    <div class="flex justify-between text-xl font-bold">
                        <span>Total:</span>
                        <span>Tsh {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Update Status</h3>
                <form action="{{ route('special_hire.orders.update', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                        <select name="order_status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="in_progress" {{ $order->order_status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                        <select name="payment_status" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <select name="payment_method" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500">
                            <option value="">-- Select --</option>
                            <option value="cash" {{ $order->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="mobile" {{ $order->payment_method === 'mobile' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="bank" {{ $order->payment_method === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full btn-primary py-2.5 text-white rounded-xl font-medium">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('special_hire.orders') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
            ← Back to Orders
        </a>
    </div>
</div>
@endsection

