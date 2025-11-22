<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        /* Simplified Select2 dark theme */
        .select2-container--default .select2-selection--single {
            background-color: #1f2937;
            border: 1px solid #4b5563;
            border-radius: 0.375rem;
            height: 40px;
            color: white;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white;
            line-height: 40px;
            padding-left: 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
        }

        .select2-dropdown {
            background-color: #1f2937;
            border: 1px solid #4b5563;
            border-radius: 0.375rem;
            color: white;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4f46e5;
            color: white;
        }

        /* Ensure icons don’t block input */
        .fa-map-marker-alt,
        .fa-bus,
        .fa-calendar-day {
            pointer-events: none;
            z-index: 10;
        }

        /* Ensure date input is clickable */
        input[type="date"] {
            position: relative;
            z-index: 1;
            background: #1f2937;
            color: white;
            appearance: auto;
            /* Ensure native date picker is used */
        }

        /* Style for date input placeholder */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.7);
            /* Make calendar icon visible in dark theme */
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-900 text-white">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Tab Navigation -->
        <div class="flex space-x-2 mb-4 bg-gray-800 rounded-lg p-1">
            <button
                class="search-tab flex-1 py-2 px-4 rounded-lg bg-indigo-600 text-white font-medium text-sm uppercase"
                data-tab="one-way">One Way</button>
            <button class="search-tab flex-1 py-2 px-4 rounded-lg text-gray-400 font-medium text-sm uppercase"
                data-tab="bus-name">Bus Name</button>
        </div>

        <!-- One Way Form -->
        <form action="/search" method="POST" class="search-form" id="one-way-form">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    @csrf
                    <label class="block text-gray-300 text-sm mb-1">From</label>
                    <div class="relative">
                        <select name="departure_city" id="departure_city"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white">
                            <option value="">Select Departure City</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ old('departure_city') == $city->id ? 'selected' : '' }}>{{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-map-marker-alt absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm mb-1">To</label>
                    <div class="relative">
                        <select name="arrival_city" id="arrival_city"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white">
                            <option value="">Select Arrival City</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ old('departure_city') == $city->id ? 'selected' : '' }}>{{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-map-marker-alt absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm mb-1">Date</label>
                    <div class="relative">
                        <input type="date" name="departure_date" id="departure_date"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white">
                        <i class="fas fa-calendar-day absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
            <button class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-medium">
                <i class="fas fa-search mr-2"></i> Find Buses
            </button>
        </form>

        <!-- Bus Name Form -->
        <form action="{{ route('customer.mybooking.search') }}" method="get" class="search-form hidden" id="bus-name-form">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    @csrf
                    <label class="block text-gray-300 text-sm mb-1">Bus Name</label>
                    <div class="relative">
                        <select name="bus_name" id="bus_name"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white">
                            <option value="">Select Bus Name</option>
                            @forelse (App\Models\Campany::all() as $bus)
                                <option value="{{ $bus->id }}"
                                    {{ request('bus_name') == $bus->name ? 'selected' : '' }}>
                                    {{ $bus->name ?? 'N/A' }}</option>
                            @empty
                                <option>No companies found.</option>
                            @endforelse
                        </select>
                        <i class="fas fa-bus absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
            <button class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-medium">
                <i class="fas fa-search mr-2"></i> Find Buses
            </button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#departure_city, #arrival_city, #bus_name').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: '100%'
            });

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            $('#departure_date').attr('min', today);

            // Tab switching
            $('.search-tab').click(function() {
                $('.search-tab').removeClass('bg-indigo-600 text-white').addClass('text-gray-400');
                $(this).addClass('bg-indigo-600 text-white').removeClass('text-gray-400');
                $('.search-form').addClass('hidden');
                $(`#${$(this).data('tab')}-form`).removeClass('hidden');
            });

            // Ensure date input is clickable
            $('#departure_date').on('click', function() {
                $(this).focus(); // Force focus on click
            });
        });
    </script>
</body>

</html>
