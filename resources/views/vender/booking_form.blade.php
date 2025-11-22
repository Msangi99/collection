@extends('vender.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

<section class="bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h5 class="text-xl font-bold text-gray-800 text-center mb-6">{{ __('vender/busroot.select_your_journey_points') }}</h5>

                <form id="busSearchForm" method="POST" action="{{ route('vender.store') }}">
                    @csrf

                    <!-- Bus Operator -->
                    <div class="mb-4">
                        <label for="busOperator" class="block text-sm text-gray-500 mb-1">
                            <i class="fas fa-building mr-1"></i> {{ __('vender/busroot.bus_operator') }}
                        </label>
                        <input type="text" name="bus_name" value="{{ $car->busname->name }}" readonly
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <input type="hidden" name="bus_id" value="{{ $car->id }}">
                    <input type="hidden" name="route_id" value="{{ $car->route->id }}">

                    <!-- Route Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- From -->
                        <div>
                            <label for="routeFrom" class="block text-sm text-gray-500 mb-1">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ __('vender/busroot.from') }}
                            </label>
                            <input type="text" name="from" value="{{ $car->schedule->from }}" readonly
                                   class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- To -->
                        <div>
                            <label for="routeTo" class="block text-sm text-gray-500 mb-1">
                            <i class="fas fa-map-marker-check mr-1"></i> {{ __('vender/busroot.to') }}
                            </label>
                            <input type="text" name="to" value="{{ $car->schedule->to }}" readonly
                                   class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Points -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Pickup Point -->
                        <div>
                            <label for="pickupPoint" class="block text-sm text-gray-500 mb-1">
                            <i class="fas fa-signpost mr-1"></i> {{ __('vender/busroot.pickup_point') }}
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    id="pickupPoint" name="pickup_point">
                                <option value="">{{ __('vender/busroot.select_pickup_point') }}</option>
                                @if(isset($car->filtered_points))
                                    @foreach($car->filtered_points as $value)
                                        @if($value->point_mode == 1)
                                            <option value="{{ $value->point }}" {{ request('pickup_point_id') == $value->point ? 'selected' : '' }}>
                                                {{ $value->point }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Dropoff Point -->
                        <div>
                            <label for="dropoffPoint" class="block text-sm text-gray-500 mb-1">
                            <i class="fas fa-signpost mr-1"></i> {{ __('vender/busroot.dropoff_point') }}
                            </label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    id="dropoffPoint" name="dropping_point">
                                <option value="">{{ __('vender/busroot.select_dropping_point') }}</option>
                                @if(isset($car->filtered_points))
                                    @foreach($car->filtered_points as $value)
                                        @if($value->point_mode == 2)
                                            <option value="{{ $value->point }}" data-amount="{{ $value->amount }}"
                                                    {{ request('dropping_point_id') == $value->point ? 'selected' : '' }}>
                                                {{ $value->point }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Distance Display -->
                    <div class="mb-4">
                        <label for="routeDistanceDisplay" class="block text-sm text-gray-500 mb-1">
                            <i class="fas fa-ruler mr-1"></i> {{ __('vender/busroot.route_distance') }} (km)
                        </label>
                        <input type="text" id="routeDistanceDisplay" readonly
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-800 focus:outline-none"
                               name="route_distance" placeholder="{{ __('vender/busroot.distance_will_be_calculated') }}">
                    </div>

                    <!-- Map Section -->
                    <div class="mt-4">
                        <div class="mb-3">
                            <label for="start" class="block text-sm text-gray-500 mb-1">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ __('vender/busroot.pickup_location') }}
                            </label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-yellow-50"
                                   id="start" placeholder="{{ __('vender/busroot.search_pickup_location') }}" value="{{ $car->schedule->from }}">
                        </div>
                        <div class="mb-3">
                            <label for="end" class="block text-sm text-gray-500 mb-1">
                            <i class="fas fa-map-marker-check mr-1"></i> {{ __('vender/busroot.dropping_location') }}
                            </label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-yellow-50"
                                   id="end" placeholder="{{ __('vender/busroot.search_dropping_location') }}" value="{{ $car->schedule->to }}">
                        </div>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="font-medium text-gray-600">{{ __('vender/busroot.quick_locations') }}</span>
                            <span class="point-btn px-3 py-1 bg-indigo-500 text-white rounded-md text-xs hover:bg-indigo-600 cursor-pointer transition"
                                  data-point="Nairobi, Kenya">{{ __('vender/busroot.nairobi') }}</span>
                            <span class="point-btn px-3 py-1 bg-indigo-500 text-white rounded-md text-xs hover:bg-indigo-600 cursor-pointer transition"
                                  data-point="Mombasa, Kenya">{{ __('vender/busroot.mombasa') }}</span>
                            <span class="point-btn px-3 py-1 bg-indigo-500 text-white rounded-md text-xs hover:bg-indigo-600 cursor-pointer transition"
                                  data-point="Kisumu, Kenya">{{ __('vender/busroot.kisumu') }}</span>
                            <span class="point-btn px-3 py-1 bg-indigo-500 text-white rounded-md text-xs hover:bg-indigo-600 cursor-pointer transition"
                                  data-point="Nakuru, Kenya">{{ __('vender/busroot.nakuru') }}</span>
                            <span class="point-btn px-3 py-1 bg-indigo-500 text-white rounded-md text-xs hover:bg-indigo-600 cursor-pointer transition"
                                  data-point="Eldoret, Kenya">{{ __('vender/busroot.eldoret') }}</span>
                        </div>
                        <div class="flex gap-3 mb-3">
                            <button type="button" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-md text-sm hover:opacity-90 transition"
                                    id="calculate">{{ __('vender/busroot.calculate_distance') }}</button>
                            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md text-sm hover:bg-gray-600 transition"
                                    id="clear">{{ __('vender/busroot.clear_points') }}</button>
                        </div>
                        <div id="result" class="hidden p-3 bg-green-50 rounded-md text-sm text-gray-800"></div>
                        <div id="map" class="h-72 w-full rounded-md mt-3"></div>
                    </div>

                    <input type="hidden" name="dropping_point_amount" id="droppingPointAmount">
                    <input type="hidden" name="route_distance" id="routeDistance">

                    <button type="submit"
                            class="w-full py-2 mt-4 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-md font-medium hover:opacity-90 transition">
                        <i class="fas fa-search mr-2"></i> {{ __('vender/busroot.search_available_buses') }}
                    </button>
                    <input type="hidden" value="{{ $car->schedule->id }}" name="schedule_id">
                </form>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery-migrate-3.0.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script defer src="{{ asset('js/bootstrap-datepicker.min.js@key=1') }}"></script>
<script defer src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.easing.1.3.js') }}"></script>
<script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('js/jquery.stellar.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/aos.js') }}"></script>
<script src="{{ asset('js/jquery.animateNumber.min.js') }}"></script>
<script src="{{ asset('js/scrollax.min.js') }}"></script>
<script src="{{ asset('js/main.js@key=1') }}"></script>
<script src="{{ asset('js/hashes.min.js') }}"></script>
<script defer src="{{ asset('js/common.js@3') }}"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<script>
    // Dropoff point handling
    document.getElementById('dropoffPoint').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const amount = selectedOption.getAttribute('data-amount');
        document.getElementById('droppingPointAmount').value = amount;

        let hiddenInput = document.getElementById('hiddenDropoffPoint');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'hiddenDropoffPoint';
            hiddenInput.name = 'hidden_dropping_point';
            document.getElementById('busSearchForm').appendChild(hiddenInput);
        }
        hiddenInput.value = this.value;

        const endInput = document.getElementById('end');
        if (this.value) {
            endInput.value = this.value;
            geocodePlace(this.value, 'end');
        }
    });

    document.getElementById('pickupPoint').addEventListener('change', function () {
        const startInput = document.getElementById('start');
        if (this.value) {
            startInput.value = this.value;
            geocodePlace(this.value, 'start');
        }
    });

    // Map initialization
    let map, startMarker, endMarker, routingControl, activeInput;
    map = L.map('map').setView([-1.286389, 36.817223], 6); // Centered on Nairobi
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '{{ __('vender/busroot.map_attribution') }}'
    }).addTo(map);

    function createMarkerIcon(color) {
        return L.divIcon({
            className: 'custom-icon',
            html: `<div style="background-color:${color}; width:20px; height:20px; border-radius:50%; border:2px solid white;"></div>`,
            iconSize: [24, 24]
        });
    }

    function updateMarker(marker, latlng, inputId) {
        if (marker) {
            marker.setLatLng(latlng);
        } else {
            const color = inputId === 'start' ? 'green' : 'red';
            marker = L.marker(latlng, {
                icon: createMarkerIcon(color),
                draggable: true
            }).addTo(map).on('dragend', function (e) {
                const position = marker.getLatLng();
                document.getElementById(inputId).value = `${position.lat.toFixed(6)}, ${position.lng.toFixed(6)}`;
                if ((inputId === 'start' && endMarker) || (inputId === 'end' && startMarker)) {
                    calculateDistance();
                }
            });
            if (inputId === 'start') startMarker = marker;
            else endMarker = marker;
        }
        return marker;
    }

    function calculateDistance() {
        if (!startMarker || !endMarker) return;
        const startLatLng = startMarker.getLatLng();
        const endLatLng = endMarker.getLatLng();

        if (routingControl) map.removeControl(routingControl);

        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(startLatLng.lat, startLatLng.lng),
                L.latLng(endLatLng.lat, endLatLng.lng)
            ],
            routeWhileDragging: true,
            showAlternatives: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            lineOptions: {
                styles: [{ color: 'blue', opacity: 0.7, weight: 5 }]
            },
            createMarker: function () { return null; }
        }).addTo(map);

        routingControl.on('routesfound', function (e) {
            const routes = e.routes;
            const distance = routes[0].summary.totalDistance; // in meters
            const duration = routes[0].summary.totalTime; // in seconds
            const calculatedDistance = (distance / 1000).toFixed(2); // Convert to km

            const resultDiv = document.getElementById('result');
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = `
                <strong>{{ __('vender/busroot.distance') }}</strong> ${calculatedDistance} km (${distance.toFixed(0)} meters)<br>
                <strong>{{ __('vender/busroot.duration') }}</strong> ${Math.floor(duration / 60)} min ${duration % 60} sec<br>
                <strong>{{ __('vender/busroot.start') }}</strong> ${startLatLng.lat.toFixed(6)}, ${startLatLng.lng.toFixed(6)}<br>
                <strong>{{ __('vender/busroot.end') }}</strong> ${endLatLng.lat.toFixed(6)}, ${endLatLng.lng.toFixed(6)}
            `;
            document.getElementById('routeDistance').value = calculatedDistance;
            document.getElementById('routeDistanceDisplay').value = calculatedDistance;
        });

        map.fitBounds(L.latLngBounds(startLatLng, endLatLng));
    }

    function geocodePlace(place, inputId) {
        if (!place) return;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(place)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    const latlng = L.latLng(lat, lon);
                    document.getElementById(inputId).value = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;

                    if (inputId === 'start') {
                        startMarker = updateMarker(startMarker, latlng, 'start');
                    } else {
                        endMarker = updateMarker(endMarker, latlng, 'end');
                    }

                    if (startMarker && endMarker) {
                        calculateDistance();
                    } else {
                        map.setView(latlng, 12);
                    }
                } else {
                    alert(`{{ __('vender/busroot.no_results_found', ['place' => '${place}']) }}`);
                    document.getElementById(inputId).value = '';
                }
            })
            .catch(error => {
                console.error('Geocoding error:', error);
                alert('{{ __('vender/busroot.geocoding_error') }}');
                document.getElementById(inputId).value = '';
            });
    }

    function handleInputChange(inputId) {
        const input = document.getElementById(inputId);
        input.addEventListener('change', function () {
            const value = this.value.trim();
            if (!value.match(/^-?\d+\.\d+,\s*-?\d+\.\d+$/)) {
                geocodePlace(value, inputId);
            }
        });
        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const value = this.value.trim();
                if (!value.match(/^-?\d+\.\d+,\s*-?\d+\.\d+$/)) {
                    geocodePlace(value, inputId);
                }
            }
        });
    }

    // Handle input focus
    document.getElementById('start').addEventListener('focus', function () {
        activeInput = 'start';
        this.classList.add('bg-yellow-50');
        document.getElementById('end').classList.remove('bg-yellow-50');
    });

    document.getElementById('end').addEventListener('focus', function () {
        activeInput = 'end';
        this.classList.add('bg-yellow-50');
        document.getElementById('start').classList.remove('bg-yellow-50');
    });

    // Handle map clicks
    map.on('click', function (e) {
        if (activeInput) {
            const latlng = e.latlng;
            document.getElementById(activeInput).value = `${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
            if (activeInput === 'start') {
                startMarker = updateMarker(startMarker, latlng, 'start');
            } else {
                endMarker = updateMarker(endMarker, latlng, 'end');
            }
            if (startMarker && endMarker) {
                calculateDistance();
            }
        } else {
            alert('{{ __('vender/busroot.select_input_first') }}');
        }
    });

    // Handle calculate button
    document.getElementById('calculate').addEventListener('click', function () {
        const startValue = document.getElementById('start').value.trim();
        const endValue = document.getElementById('end').value.trim();
        const coordRegex = /^-?\d+\.\d+,\s*-?\d+\.\d+$/;

        if (startValue && !startValue.match(coordRegex)) {
            geocodePlace(startValue, 'start');
        } else if (startValue) {
            try {
                const startCoords = startValue.split(',').map(coord => parseFloat(coord.trim()));
                startMarker = updateMarker(startMarker, L.latLng(startCoords[0], startCoords[1]), 'start');
                if (startMarker && endMarker) calculateDistance();
            } catch (e) {
                alert('{{ __('vender/busroot.invalid_start_coords') }}');
            }
        }

        if (endValue && !endValue.match(coordRegex)) {
            geocodePlace(endValue, 'end');
        } else if (endValue) {
            try {
                const endCoords = endValue.split(',').map(coord => parseFloat(coord.trim()));
                endMarker = updateMarker(endMarker, L.latLng(endCoords[0], endCoords[1]), 'end');
                if (startMarker && endMarker) calculateDistance();
            } catch (e) {
                alert('{{ __('vender/busroot.invalid_end_coords') }}');
            }
        }
    });

    // Handle clear button
    document.getElementById('clear').addEventListener('click', function () {
        if (startMarker) {
            map.removeLayer(startMarker);
            startMarker = null;
        }
        if (endMarker) {
            map.removeLayer(endMarker);
            endMarker = null;
        }
        if (routingControl) {
            map.removeControl(routingControl);
            routingControl = null;
        }
        document.getElementById('start').value = document.getElementById('routeFrom').value;
        document.getElementById('end').value = document.getElementById('routeTo').value;
        document.getElementById('result').classList.add('hidden');
        document.getElementById('start').classList.remove('bg-yellow-50');
        document.getElementById('end').classList.remove('bg-yellow-50');
        activeInput = null;
        document.getElementById('routeDistance').value = '';
        document.getElementById('routeDistanceDisplay').value = '';
        const fromValue = document.getElementById('routeFrom').value;
        const toValue = document.getElementById('routeTo').value;
        if (fromValue) geocodePlace(fromValue, 'start');
        if (toValue) geocodePlace(toValue, 'end');
    });

    // Handle default point buttons
    document.querySelectorAll('.point-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!activeInput) {
                alert('{{ __('vender/busroot.select_input_first') }}');
                return;
            }
            const pointName = this.getAttribute('data-point');
            geocodePlace(pointName, activeInput);
        });
    });

    // Initialize input handlers
    handleInputChange('start');
    handleInputChange('end');

    // Auto-geocode default points on load
    window.addEventListener('load', function () {
        const fromValue = document.getElementById('routeFrom').value;
        const toValue = document.getElementById('routeTo').value;
        if (fromValue) {
            document.getElementById('start').value = fromValue;
            geocodePlace(fromValue, 'start');
        }
        if (toValue) {
            document.getElementById('end').value = toValue;
            geocodePlace(toValue, 'end');
        }
    });
</script>

<style>
    #map {
        height: 18rem; /* h-72 = 288px */
        width: 100%;
        border-radius: 0.375rem; /* rounded-md */
    }
    .toggle-password {
        float: right;
        cursor: pointer;
        margin-right: 0.625rem; /* 10px */
        margin-top: -1.5625rem; /* -25px */
    }
    .resend-color {
        color: #183C64 !important;
        cursor: pointer;
    }
</style>
@endsection
