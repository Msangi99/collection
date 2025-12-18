@extends('special_hire.app')

@section('title', 'Pricing')
@section('page_title', 'Pricing Management')
@section('page_subtitle', 'Set pricing for your coasters')

@section('content')
<div class="space-y-6">
    @if($coasters->isEmpty())
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Coasters Yet</h3>
            <p class="text-gray-500 mb-6">Add a coaster first to set its pricing.</p>
            <a href="{{ route('special_hire.coasters.create') }}" class="btn-primary inline-block px-6 py-3 text-white rounded-xl font-medium">
                Add Coaster
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($coasters as $coaster)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $coaster->name }}</h3>
                            <p class="text-sm text-gray-500 font-mono">{{ $coaster->plate_number }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $coaster->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $coaster->status === 'on_hire' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $coaster->status === 'maintenance' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($coaster->status) }}
                        </span>
                    </div>

                    <form action="{{ route('special_hire.pricing.store') }}" method="POST" class="p-6">
                        @csrf
                        <input type="hidden" name="coaster_id" value="{{ $coaster->id }}">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Base Price (Tsh)</label>
                                <input type="number" name="base_price" 
                                       value="{{ $coaster->pricing->base_price ?? 100000 }}" 
                                       required min="0" step="1000"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price per KM (Tsh)</label>
                                <input type="number" name="price_per_km" 
                                       value="{{ $coaster->pricing->price_per_km ?? 2500 }}" 
                                       required min="0" step="100"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum KM</label>
                                <input type="number" name="min_km" 
                                       value="{{ $coaster->pricing->min_km ?? 10 }}" 
                                       required min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Weekend Surcharge (%)</label>
                                <input type="number" name="weekend_surcharge_percent" 
                                       value="{{ $coaster->pricing->weekend_surcharge_percent ?? 15 }}" 
                                       required min="0" max="100" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Night Surcharge (%) - 6PM to 6AM</label>
                                <input type="number" name="night_surcharge_percent" 
                                       value="{{ $coaster->pricing->night_surcharge_percent ?? 20 }}" 
                                       required min="0" max="100" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            </div>
                        </div>

                        <!-- Example Calculation -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-2">Example: 50 km trip</p>
                            <p class="text-lg font-bold text-teal-600">
                                Tsh {{ number_format(($coaster->pricing->base_price ?? 100000) + (50 * ($coaster->pricing->price_per_km ?? 2500))) }}
                            </p>
                        </div>

                        <button type="submit" class="w-full mt-4 btn-primary py-2.5 text-white rounded-xl font-medium">
                            Update Pricing
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

