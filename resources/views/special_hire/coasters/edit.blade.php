@extends('special_hire.app')

@section('title', 'Edit Coaster')
@section('page_title', 'Edit Coaster')
@section('page_subtitle', 'Update coaster details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Edit: {{ $coaster->name }}</h3>
        </div>

        <form action="{{ route('special_hire.coasters.update', $coaster->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Vehicle Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coaster Name *</label>
                    <input type="text" name="name" value="{{ old('name', $coaster->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Plate Number *</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number', $coaster->plate_number) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('plate_number')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity (Seats) *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $coaster->capacity) }}" required min="1" max="100"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('capacity')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="available" {{ old('status', $coaster->status) === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="on_hire" {{ old('status', $coaster->status) === 'on_hire' ? 'selected' : '' }}>On Hire</option>
                        <option value="maintenance" {{ old('status', $coaster->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                    <input type="text" name="model" value="{{ old('model', $coaster->model) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('model')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <input type="text" name="color" value="{{ old('color', $coaster->color) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('color')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                    @if($coaster->image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($coaster->image) }}" alt="{{ $coaster->name }}" class="w-32 h-24 object-cover rounded-lg">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                    @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Driver Info -->
            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Driver Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Driver Name</label>
                        <input type="text" name="driver_name" value="{{ old('driver_name', $coaster->driver_name) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('driver_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Driver Contact</label>
                        <input type="text" name="driver_contact" value="{{ old('driver_contact', $coaster->driver_contact) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('driver_contact')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="border-t border-gray-100 pt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Features & Amenities</label>
                <textarea name="features" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('features', $coaster->features) }}</textarea>
                @error('features')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Pricing -->
            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Pricing (Per KM)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Base Price (Tsh) *</label>
                        <input type="number" name="base_price" value="{{ old('base_price', $coaster->pricing->base_price ?? 100000) }}" required min="0" step="1000"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('base_price')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price per KM (Tsh) *</label>
                        <input type="number" name="price_per_km" value="{{ old('price_per_km', $coaster->pricing->price_per_km ?? 2500) }}" required min="0" step="100"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('price_per_km')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum KM *</label>
                        <input type="number" name="min_km" value="{{ old('min_km', $coaster->pricing->min_km ?? 10) }}" required min="1"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('min_km')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Weekend Surcharge (%)</label>
                        <input type="number" name="weekend_surcharge_percent" value="{{ old('weekend_surcharge_percent', $coaster->pricing->weekend_surcharge_percent ?? 15) }}" min="0" max="100" step="0.01"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('weekend_surcharge_percent')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Night Surcharge (%)</label>
                        <input type="number" name="night_surcharge_percent" value="{{ old('night_surcharge_percent', $coaster->pricing->night_surcharge_percent ?? 20) }}" min="0" max="100" step="0.01"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('night_surcharge_percent')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="border-t border-gray-100 pt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('special_hire.coasters') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="btn-primary px-6 py-2.5 text-white rounded-xl font-medium">
                    Update Coaster
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

