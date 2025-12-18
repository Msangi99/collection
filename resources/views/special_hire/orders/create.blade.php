@extends('special_hire.app')

@section('title', 'New Order')
@section('page_title', 'Create New Order')
@section('page_subtitle', 'Book a coaster for a customer')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Order Details</h3>
        </div>

        <form action="{{ route('special_hire.orders.store') }}" method="POST" class="p-6 space-y-6" id="orderForm">
            @csrf

            <!-- Select Coaster -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Coaster *</label>
                @if($coasters->isEmpty())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-yellow-700">
                        <p class="font-medium">No available coasters</p>
                        <p class="text-sm mt-1">All coasters are either on hire or under maintenance.</p>
                        <a href="{{ route('special_hire.coasters.create') }}" class="text-sm text-yellow-800 underline mt-2 inline-block">Add a new coaster</a>
                    </div>
                @else
                    <select name="coaster_id" id="coaster_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">-- Select Coaster --</option>
                        @foreach($coasters as $coaster)
                            <option value="{{ $coaster->id }}" 
                                    data-base-price="{{ $coaster->pricing->base_price ?? 0 }}"
                                    data-price-per-km="{{ $coaster->pricing->price_per_km ?? 0 }}"
                                    data-min-km="{{ $coaster->pricing->min_km ?? 10 }}"
                                    {{ old('coaster_id') == $coaster->id ? 'selected' : '' }}>
                                {{ $coaster->name }} ({{ $coaster->plate_number }}) - {{ $coaster->capacity }} seats
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('coaster_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Customer Info -->
            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Customer Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="Customer full name">
                        @error('customer_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="+255...">
                        @error('customer_phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="customer@email.com">
                        @error('customer_email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Route Info -->
            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Route Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Location *</label>
                        <input type="text" name="pickup_location" value="{{ old('pickup_location') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="e.g., Dar es Salaam, Posta">
                        @error('pickup_location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dropoff Location *</label>
                        <input type="text" name="dropoff_location" value="{{ old('dropoff_location') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="e.g., Bagamoyo Beach">
                        @error('dropoff_location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Distance (KM) *</label>
                        <input type="number" name="distance_km" id="distance_km" value="{{ old('distance_km', 10) }}" required min="1" step="0.1"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="Enter distance in KM">
                        @error('distance_km')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Passengers Count *</label>
                        <input type="number" name="passengers_count" value="{{ old('passengers_count', 1) }}" required min="1" max="100"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('passengers_count')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Schedule</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hire Date *</label>
                        <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('hire_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Time *</label>
                        <input type="time" name="hire_time" id="hire_time" value="{{ old('hire_time', '08:00') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('hire_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Return Date</label>
                        <input type="date" name="return_date" value="{{ old('return_date') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('return_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Return Time</label>
                        <input type="time" name="return_time" value="{{ old('return_time') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('return_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="border-t border-gray-100 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
                        <input type="text" name="purpose" value="{{ old('purpose') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="e.g., Wedding, Corporate Event">
                        @error('purpose')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <input type="text" name="notes" value="{{ old('notes') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="Special requests...">
                        @error('notes')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Price Preview -->
            <div class="border-t border-gray-100 pt-6">
                <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl p-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Price Estimate</h4>
                    <div class="space-y-2 text-sm" id="priceBreakdown">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Base Price:</span>
                            <span class="font-medium" id="basePriceDisplay">Tsh 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Distance Charge (<span id="distanceDisplay">0</span> km × <span id="rateDisplay">0</span>):</span>
                            <span class="font-medium" id="kmAmountDisplay">Tsh 0</span>
                        </div>
                        <div class="flex justify-between" id="surchargeRow" style="display: none;">
                            <span class="text-gray-600">Surcharge (<span id="surchargeLabel"></span>):</span>
                            <span class="font-medium" id="surchargeDisplay">Tsh 0</span>
                        </div>
                        <hr class="border-teal-200 my-2">
                        <div class="flex justify-between text-lg font-bold text-teal-700">
                            <span>Total:</span>
                            <span id="totalDisplay">Tsh 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="border-t border-gray-100 pt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('special_hire.orders') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 text-white rounded-xl font-medium" {{ $coasters->isEmpty() ? 'disabled' : '' }}>
                    Create Order
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const coasterSelect = document.getElementById('coaster_id');
    const distanceInput = document.getElementById('distance_km');
    const hireDateInput = document.getElementById('hire_date');
    const hireTimeInput = document.getElementById('hire_time');

    function calculatePrice() {
        const selectedOption = coasterSelect.options[coasterSelect.selectedIndex];
        if (!selectedOption.value) {
            resetPriceDisplay();
            return;
        }

        const basePrice = parseFloat(selectedOption.dataset.basePrice) || 0;
        const pricePerKm = parseFloat(selectedOption.dataset.pricePerKm) || 0;
        const minKm = parseFloat(selectedOption.dataset.minKm) || 10;
        const distance = Math.max(parseFloat(distanceInput.value) || 0, minKm);
        
        // Calculate KM amount
        const kmAmount = distance * pricePerKm;
        
        // Check for surcharges
        let surchargePercent = 0;
        let surchargeLabels = [];
        
        const hireDate = new Date(hireDateInput.value);
        const dayOfWeek = hireDate.getDay();
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            surchargePercent += 15;
            surchargeLabels.push('Weekend +15%');
        }
        
        const hireHour = parseInt(hireTimeInput.value.split(':')[0]);
        if (hireHour >= 18 || hireHour < 6) {
            surchargePercent += 20;
            surchargeLabels.push('Night +20%');
        }
        
        const subtotal = basePrice + kmAmount;
        const surchargeAmount = (subtotal * surchargePercent) / 100;
        const total = subtotal + surchargeAmount;

        // Update display
        document.getElementById('basePriceDisplay').textContent = 'Tsh ' + numberFormat(basePrice);
        document.getElementById('distanceDisplay').textContent = distance.toFixed(1);
        document.getElementById('rateDisplay').textContent = 'Tsh ' + numberFormat(pricePerKm);
        document.getElementById('kmAmountDisplay').textContent = 'Tsh ' + numberFormat(kmAmount);
        
        if (surchargePercent > 0) {
            document.getElementById('surchargeRow').style.display = 'flex';
            document.getElementById('surchargeLabel').textContent = surchargeLabels.join(', ');
            document.getElementById('surchargeDisplay').textContent = 'Tsh ' + numberFormat(surchargeAmount);
        } else {
            document.getElementById('surchargeRow').style.display = 'none';
        }
        
        document.getElementById('totalDisplay').textContent = 'Tsh ' + numberFormat(total);
    }

    function resetPriceDisplay() {
        document.getElementById('basePriceDisplay').textContent = 'Tsh 0';
        document.getElementById('distanceDisplay').textContent = '0';
        document.getElementById('rateDisplay').textContent = '0';
        document.getElementById('kmAmountDisplay').textContent = 'Tsh 0';
        document.getElementById('surchargeRow').style.display = 'none';
        document.getElementById('totalDisplay').textContent = 'Tsh 0';
    }

    function numberFormat(num) {
        return num.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    coasterSelect.addEventListener('change', calculatePrice);
    distanceInput.addEventListener('input', calculatePrice);
    hireDateInput.addEventListener('change', calculatePrice);
    hireTimeInput.addEventListener('change', calculatePrice);

    // Initial calculation
    calculatePrice();
});
</script>
@endpush
@endsection

