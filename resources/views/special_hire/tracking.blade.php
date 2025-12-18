@extends('special_hire.app')

@section('title', 'Live Tracking')
@section('page_title', 'Live Tracking')
@section('page_subtitle', 'Track your coasters in real-time')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Coasters List -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Coasters</h3>
                </div>
                <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                    @forelse($coasters as $coaster)
                        <div class="p-4 hover:bg-gray-50 cursor-pointer coaster-item" data-id="{{ $coaster->id }}" data-lat="{{ $coaster->latitude }}" data-lng="{{ $coaster->longitude }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $coaster->name }}</h4>
                                    <p class="text-sm text-gray-500 font-mono">{{ $coaster->plate_number }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $coaster->status === 'available' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $coaster->status === 'on_hire' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $coaster->status === 'maintenance' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($coaster->status) }}
                                </span>
                            </div>
                            @if($coaster->driver_name)
                                <p class="text-xs text-gray-500 mt-2">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $coaster->driver_name }}
                                </p>
                            @endif
                            @if($coaster->last_location_update)
                                <p class="text-xs text-gray-400 mt-1">
                                    Updated {{ $coaster->last_location_update->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="p-6 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <p class="text-gray-500 text-sm">No location data</p>
                            <p class="text-gray-400 text-xs mt-1">Location will appear when coasters report their GPS position</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Map View</h3>
                    <button onclick="refreshLocations()" class="px-3 py-1.5 text-sm bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>
                <div id="tracking-map" class="h-[600px]"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let map;
let markers = {};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Tanzania
    map = L.map('tracking-map').setView([-6.7924, 39.2083], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add markers for each coaster
    const coasters = @json($coasters);
    const bounds = [];

    coasters.forEach(coaster => {
        if (coaster.latitude && coaster.longitude) {
            addMarker(coaster);
            bounds.push([coaster.latitude, coaster.longitude]);
        }
    });

    // Fit map to markers
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50] });
    }

    // Click handlers for coaster list
    document.querySelectorAll('.coaster-item').forEach(item => {
        item.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            const id = this.dataset.id;
            
            if (lat && lng) {
                map.setView([lat, lng], 15);
                if (markers[id]) {
                    markers[id].openPopup();
                }
            }
        });
    });
});

function addMarker(coaster) {
    const statusColors = {
        'available': '#10b981',
        'on_hire': '#3b82f6',
        'maintenance': '#ef4444'
    };

    const color = statusColors[coaster.status] || '#6b7280';

    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="background-color: ${color}; width: 32px; height: 32px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                   <svg style="transform: rotate(45deg); margin: 4px;" width="20" height="20" fill="white" viewBox="0 0 24 24">
                       <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                   </svg>
               </div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });

    const marker = L.marker([coaster.latitude, coaster.longitude], { icon: customIcon })
        .addTo(map)
        .bindPopup(`
            <div class="p-2 min-w-[200px]">
                <h4 class="font-bold text-gray-800">${coaster.name}</h4>
                <p class="text-sm text-gray-500 font-mono">${coaster.plate_number}</p>
                <div class="mt-2">
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" 
                          style="background-color: ${color}20; color: ${color}">
                        ${coaster.status.charAt(0).toUpperCase() + coaster.status.slice(1)}
                    </span>
                </div>
                ${coaster.driver_name ? `
                    <div class="mt-2 text-sm text-gray-600">
                        <strong>Driver:</strong> ${coaster.driver_name}
                        ${coaster.driver_contact ? `<br><a href="tel:${coaster.driver_contact}" class="text-teal-600">${coaster.driver_contact}</a>` : ''}
                    </div>
                ` : ''}
                <div class="mt-2 text-xs text-gray-400">
                    Lat: ${coaster.latitude}<br>
                    Lng: ${coaster.longitude}
                </div>
            </div>
        `);

    markers[coaster.id] = marker;
}

function refreshLocations() {
    // In a real app, this would fetch new locations via AJAX
    location.reload();
}
</script>

<style>
.custom-marker {
    background: transparent;
    border: none;
}
</style>
@endpush
@endsection

