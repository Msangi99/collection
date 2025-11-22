<!-- Include jQuery and Select2 CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="glass-card p-6 md:p-8 max-w-5xl mx-auto fade-in delay-200">
    <div class="flex space-x-2 mb-6 bg-white/10 rounded-xl p-1">
        <button class="search-tab active flex-1 px-4 py-3 rounded-lg font-medium text-white text-sm uppercase tracking-wide" data-tab="one-way">One Way</button>
        <button class="search-tab flex-1 px-4 py-3 rounded-lg font-medium text-white/80 text-sm uppercase tracking-wide" data-tab="round-trip">Round Trip</button>
        <!--<button class="search-tab flex-1 px-4 py-3 rounded-lg font-medium text-white/80 text-sm uppercase tracking-wide" data-tab="multi-city">Multi-City</button>-->
        <button class="search-tab flex-1 px-4 py-3 rounded-lg font-medium text-white/80 text-sm uppercase tracking-wide" data-tab="bus-name">Bus Name</button>
    </div>

    <!-- One Way Form -->
    <form action="{{ route('by_route_search') }}" method="POST" class="search-form" id="one-way-form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="relative">
                <label class="block text-white/80 text-sm font-medium mb-1">From</label>
                <div class="relative">
                    <select name="departure_city" id="departure_city"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white [color-scheme:dark]">
                        <option value="">Select Departure City</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ old('departure_city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-map-marker-alt absolute right-3 top-3 text-white/60 pointer-events-none"></i>
                </div>
            </div>

            <div class="relative">
                <label class="block text-white/80 text-sm font-medium mb-1">To</label>
                <div class="relative">
                    <select name="arrival_city" id="arrival_city"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white [color-scheme:dark]">
                        <option value="">Select Arrival City</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ old('arrival_city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-map-marker-alt absolute right-3 top-3 text-white/60 pointer-events-none"></i>
                </div>
            </div>

            <div class="relative">
                <label class="block text-white/80 text-sm font-medium mb-1">Date</label>
                <div class="relative">
                    <input type="date" name="departure_date" id="departure_date"
                        value="{{ old('departure_date') }}"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white [color-scheme:dark]">
                    <i class="fas fa-calendar-day absolute right-3 top-3 text-white/60"></i>
                </div>
            </div>

            <div class="relative">
                <label class="block text-white/80 text-sm font-medium mb-1">Bus Class</label>
                <div class="relative">
                    <select name="bus_class" id="bus_class"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white [color-scheme:dark]">
                        <option value="any">Any</option>
                        <option value="10" {{ old('bus_class') == '10' ? 'selected' : '' }}>Luxury</option>
                        <option value="20" {{ old('bus_class') == '20' ? 'selected' : '' }}>Upper Semi-Luxury</option>
                        <option value="30" {{ old('bus_class') == '30' ? 'selected' : '' }}>Lower Semi-Luxury</option>
                        <option value="40" {{ old('bus_class') == '40' ? 'selected' : '' }}>Ordinary</option>
                    </select>
                    <i class="fas fa-bus absolute right-3 top-3 text-white/60 pointer-events-none"></i>
                </div>
            </div>

            <div class="relative flex items-end">
                <button
                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white py-3 rounded-lg font-medium text-base transition-all btn-glow">
                    <i class="fas fa-search mr-2"></i> Find Buses
                </button>
            </div>
        </div>
    </form>

    <!-- Round Trip Form -->
    <div class="search-form hidden text-center py-12" id="round-trip-form">
        <div class="flex flex-col items-center justify-center">
            <i class="fas fa-road text-4xl text-indigo-400 mb-4 animate-pulse"></i>
            <h2 class="text-2xl font-bold text-white mb-2">Round Trip Booking</h2>
            <p class="text-white/80 text-sm max-w-md mb-6">Book your round trip journey with ease and convenience!</p>
            <a href="{{ route('round.trip') }}" 
               class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-lg font-medium text-base transition-all btn-glow inline-flex items-center">
                <i class="fas fa-route mr-2"></i> Book Round Trip
            </a>
        </div>
    </div>

    <!-- Multi-City Coming Soon -->
    <div class="search-form hidden text-center py-12" id="multi-city-form">
        <div class="flex flex-col items-center justify-center">
            <i class="fas fa-route text-4xl text-indigo-400 mb-4 animate-pulse"></i>
            <h2 class="text-2xl font-bold text-white mb-2">Multi-City Coming Soon!</h2>
            <p class="text-white/80 text-sm max-w-md">Explore multiple destinations with ease. Multi-city booking is on its way!</p>
        </div>
    </div>

    <!-- Bus Name Search Form -->
    <form action="{{ route('busname') }}" method="get" class="search-form hidden" id="bus-name-form">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <label class="block text-white/80 text-sm font-medium mb-1">Bus Name</label>
                <div class="relative">
                    <select name="bus_id" id="bus_departure_date"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white [color-scheme:dark]">
                        <option value="">Select Bus Name</option>
                        @forelse (App\Models\campany::all() as $bus)
                        <option value="{{ $bus->id }}" {{ request('bus_name') == $bus->id ? 'selected' : '' }}>{{ $bus->name ?? 'N/A' }}</option>
                        @empty
                            <option value="">No Bus Companies Available</option>
                        @endforelse
                    </select>
                    <i class="fas fa-bus absolute right-3 top-3 text-white/60"></i>
                </div>
            </div>

            <div class="relative flex items-end">
                <button
                    class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white py-3 rounded-lg font-medium text-base transition-all btn-glow">
                    <i class="fas fa-search mr-2"></i> Find Buses
                </button>
            </div>
        </div>
    </form>
</div>

<style>
/* Custom Select2 styles to match dark theme without blur */
.select2-container--default .select2-selection--single {
    background-color: #1f2937; /* Solid dark background (Tailwind: gray-800) */
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 0.5rem;
    height: 44px;
    color: white;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: white;
    line-height: 44px;
    padding-left: 1rem;
    padding-right: 2rem;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px;
    right: 10px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: rgba(255, 255, 255, 0.6);
}

.select2-dropdown {
    background-color: #1f2937; /* Solid dark background (no transparency or blur) */
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 0.5rem;
    color: white;
}

.select2-container--default .select2-results__option {
    color: white;
    background-color: #1f2937; /* Solid background for options */
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #4f46e5; /* Indigo-600 for highlight */
    color: white;
}

.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #312e81; /* Indigo-900 for selected option */
}

/* Ensure the font-awesome icon is above the Select2 dropdown arrow */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    z-index: 1;
}
.fas.fa-map-marker-alt, .fas.fa-bus, .fas.fa-calendar-day, .fas.fa-road, .fas.fa-route {
    z-index: 2;
}

/* Animation for Coming Soon icons */
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}
.animate-pulse {
    animation: pulse 2s infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for all selects
    $('#departure_city, #arrival_city, #bus_class, #bus_departure_date').select2({
        placeholder: "Select an option",
        allowClear: true,
        theme: "default",
        dropdownCssClass: "select2-dropdown--dark",
        width: '100%'
    });

    // Set today's date as the minimum for departure date inputs
    const today = new Date().toISOString().split('T')[0];
    const departureDateInput = document.getElementById('departure_date');
    const busDepartureDateInput = document.getElementById('bus_departure_date');
    departureDateInput.setAttribute('min', today);
    busDepartureDateInput.setAttribute('min', today);

    // Tab switching logic
    const tabs = document.querySelectorAll('.search-tab');
    const forms = document.querySelectorAll('.search-form');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active', 'text-white'));
            tabs.forEach(t => t.classList.add('text-white/80'));

            // Add active class to clicked tab
            this.classList.add('active', 'text-white');
            this.classList.remove('text-white/80');

            // Hide all forms
            forms.forEach(form => form.classList.add('hidden'));

            // Show the corresponding form
            const targetForm = document.getElementById(`${this.dataset.tab}-form`);
            if (targetForm) {
                targetForm.classList.remove('hidden');
            }
        });
    });
});
</script>