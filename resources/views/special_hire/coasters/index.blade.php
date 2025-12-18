@extends('special_hire.app')

@section('title', 'Coasters')
@section('page_title', 'Coasters')
@section('page_subtitle', 'Manage your coaster fleet')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600">Total: {{ $coasters->total() }} coasters</p>
        </div>
        <a href="{{ route('special_hire.coasters.create') }}" class="btn-primary px-4 py-2.5 text-white rounded-xl font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Coaster
        </a>
    </div>

    <!-- Coasters Grid -->
    @if($coasters->isEmpty())
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Coasters Yet</h3>
            <p class="text-gray-500 mb-6">Add your first coaster to start accepting hire requests.</p>
            <a href="{{ route('special_hire.coasters.create') }}" class="btn-primary inline-block px-6 py-3 text-white rounded-xl font-medium">
                Add Your First Coaster
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($coasters as $coaster)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <!-- Image -->
                    <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 relative">
                        @if($coaster->image)
                            <img src="{{ Storage::url($coaster->image) }}" alt="{{ $coaster->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                        @endif
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $coaster->status === 'available' ? 'bg-green-500 text-white' : '' }}
                                {{ $coaster->status === 'on_hire' ? 'bg-blue-500 text-white' : '' }}
                                {{ $coaster->status === 'maintenance' ? 'bg-red-500 text-white' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $coaster->status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $coaster->name }}</h3>
                                <p class="text-sm text-gray-500 font-mono">{{ $coaster->plate_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-teal-600">{{ $coaster->capacity }}</p>
                                <p class="text-xs text-gray-500">seats</p>
                            </div>
                        </div>

                        @if($coaster->model || $coaster->color)
                            <p class="text-sm text-gray-600 mb-3">
                                {{ $coaster->model }} {{ $coaster->color ? '• ' . $coaster->color : '' }}
                            </p>
                        @endif

                        @if($coaster->driver_name)
                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $coaster->driver_name }}
                                @if($coaster->driver_contact)
                                    <span class="ml-2 text-gray-400">{{ $coaster->driver_contact }}</span>
                                @endif
                            </div>
                        @endif

                        @if($coaster->pricing)
                            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Base Price:</span>
                                    <span class="font-semibold">Tsh {{ number_format($coaster->pricing->base_price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span class="text-gray-500">Per KM:</span>
                                    <span class="font-semibold">Tsh {{ number_format($coaster->pricing->price_per_km) }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('special_hire.coasters.edit', $coaster->id) }}" 
                               class="flex-1 text-center py-2 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                                Edit
                            </a>
                            <form action="{{ route('special_hire.coasters.destroy', $coaster->id) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Are you sure you want to delete this coaster?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 px-4 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $coasters->links() }}
        </div>
    @endif
</div>
@endsection

