@extends('admin.app')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Success message -->
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg flex justify-between items-center shadow-sm text-sm">
                {{ session('success') }}
                <button type="button" class="text-green-800 hover:text-green-900" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-teal-500 to-teal-400 text-white flex justify-between items-center">
                <h2 class="text-lg font-semibold">{{ __('vender/cities.cities') }}</h2>
                <button class="bg-white text-blue-500 px-3 py-1 rounded-lg hover:bg-blue-50 transition flex items-center gap-1 text-sm" onclick="document.getElementById('addCityModal').classList.remove('hidden')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('vender/cities.add') }}
                </button>
            </div>

            <div class="p-4">
                <div class="overflow-x-auto">
                    <table id="citiesTable" class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left font-medium">{{ __('vender/cities.index') }}</th>
                                <th class="py-2 px-4 text-left font-medium">{{ __('vender/cities.name') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-xs">
                            @forelse ($cities as $city)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                    <td class="py-2 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-4">{{ $city->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-2 px-4 text-center text-gray-500">{{ __('vender/cities.no_cities_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add City Modal -->
        <div id="addCityModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-all">
                <div class="p-4 flex justify-between items-center border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">{{ __('vender/cities.add_new_city') }}</h2>
                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="document.getElementById('addCityModal').classList.add('hidden')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('city.store') }}" method="POST">
                    @csrf
                    <div class="p-4 space-y-3">
                        <div>
                            <label for="cityName" class="block text-xs font-medium text-gray-700 mb-1">{{ __('vender/cities.city_name') }}</label>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="cityName" name="name" required>
                            @error('name')
                                <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="p-4 flex justify-end gap-2 border-t border-gray-200">
                        <button type="button" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm" onclick="document.getElementById('addCityModal').classList.add('hidden')">{{ __('vender/cities.close') }}</button>
                        <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">{{ __('vender/cities.save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Include Tailwind CSS -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <!-- Include DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <!-- Include jQuery and DataTables JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                // Define translation strings for JavaScript
                const translations = {
                    empty_table: "{{ __('vender/cities.no_cities_found') }}",
                    paginate_first: "{{ __('vender/cities.first') }}",
                    paginate_last: "{{ __('vender/cities.last') }}",
                    paginate_next: "{{ __('vender/cities.next') }}",
                    paginate_previous: "{{ __('vender/cities.previous') }}"
                };

                // Initialize DataTable
                $('#citiesTable').DataTable({
                    responsive: true,
                    order: [[1, 'asc']],
                    columnDefs: [{ orderable: false, targets: 0 }],
                    paging: true,
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    searching: true,
                    ordering: true,
                    language: {
                        emptyTable: translations.empty_table,
                        paginate: {
                            first: translations.paginate_first,
                            last: translations.paginate_last,
                            next: translations.paginate_next,
                            previous: translations.paginate_previous
                        }
                    }
                });

                // Close modal when clicking outside
                document.getElementById('addCityModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                    }
                });

                // Reset form on modal close
                document.getElementById('addCityModal').addEventListener('transitionend', function() {
                    if (this.classList.contains('hidden')) {
                        const form = this.querySelector('form');
                        if (form) form.reset();
                    }
                });
            });
        </script>
    </div>
@endsection 