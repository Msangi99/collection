@extends('admin.app')

@section('content')
<div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h5 class="text-lg font-semibold text-gray-800">{{ __('vender/route.manage_route') }}</h5>
                <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors" aria-label="{{ __('vender/route.go_back') }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __('vender/route.back') }}
                </button>
            </div>
        </div>
        <div class="p-4 sm:p-6">
            <form id="routeForm" action="{{ route('route.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="route_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('vender/route.route_from') }}</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('route_from') border-red-500 @enderror" id="route_from" name="route_from" required aria-label="{{ __('vender/route.select_bus_and_route') }}">
                            <option value="">{{ __('vender/route.select_bus') }}</option>
                            @if(isset($buses))
                                @foreach($buses as $bus)
                                    @foreach($bus->routes as $route)
                                        <option data-bus="{{ $bus->id }}" data-route="{{ $route->id }}" value="{{ $route->id }}" {{ old('route_from') == $route->id ? 'selected' : '' }}>{{ $bus->bus_number }} | {{ $route->from }} {{ __('vender/route.to') }} {{ $route->to }}</option>
                                    @endforeach
                                @endforeach
                            @endif
                        </select>
                        @error('route_from')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="via" class="block text-sm font-medium text-gray-700 mb-1">{{ __('vender/route.via') }}</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('via') border-red-500 @enderror" id="via" name="via" value="{{ old('via') }}" placeholder="{{ __('vender/route.enter_via_location') }}" required aria-label="{{ __('vender/route.via_location') }}">
                        @error('via')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="return" class="block text-sm font-medium text-gray-700 mb-2">{{ __('vender/route.is_returning_route') }}</label>
                    <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" id="return" name="return" value="yes" {{ old('return') ? 'checked' : '' }} aria-label="{{ __('vender/route.is_returning_route_aria') }}">
                </div>
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                        <h6 class="text-base font-semibold text-gray-800">{{ __('vender/route.route_points') }}</h6>
                        <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors" id="addPointBtn" aria-label="{{ __('vender/route.add_new_route_point') }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('vender/route.add_point') }}
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto text-sm text-gray-700" id="routePointsTable">
                            <thead class="bg-gray-100 text-xs uppercase text-gray-500 font-semibold">
                                <tr>
                                    <th class="px-4 py-3 text-left">{{ __('vender/route.point_mode') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('vender/route.point_name') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('vender/route.amount_tsh') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('vender/route.action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    @error('points.*')
                        <div class="mt-2 p-3 bg-red-100 text-red-700 text-sm rounded-md" role="alert">
                            {{ __('vender/route.route_points_error') }}
                        </div>
                    @enderror
                </div>
                <input type="hidden" name="route_id" id="route_id">
                <input type="hidden" name="bus_id" id="bus_id">
                <div class="flex justify-end gap-2">
                    <button type="reset" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors" aria-label="{{ __('vender/route.reset_form_aria') }}">{{ __('vender/route.reset') }}</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors" aria-label="{{ __('vender/route.save_route_aria') }}">{{ __('vender/route.save_route') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('route_from').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('bus_id').value = selectedOption.getAttribute('data-bus') || '';
        document.getElementById('route_id').value = selectedOption.getAttribute('data-route') || '';
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define translation strings for JavaScript
        const translations = {
            confirm_remove_point: "{{ __('vender/route.confirm_remove_point') }}",
            no_route_points: "{{ __('vender/route.no_route_points') }}"
        };

        const tableBody = document.querySelector('#routePointsTable tbody');
        const addPointBtn = document.getElementById('addPointBtn');
        let pointCounter = 0;

        function addPointRow() {
            pointCounter++;
            const row = tableBody.insertRow();

            const modeCell = row.insertCell(0);
            modeCell.innerHTML = `
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="points[${pointCounter}][mode]" required aria-label="{{ __('vender/route.point_mode_aria') }}">
                    <option value="" disabled selected>{{ __('vender/route.select') }}</option>
                    <option value="1">{{ __('vender/route.pickup_point') }}</option>
                    <option value="2">{{ __('vender/route.dropping_point') }}</option>
                </select>
            `;

            const nameCell = row.insertCell(1);
            nameCell.innerHTML = `
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="points[${pointCounter}][name]" placeholder="{{ __('vender/route.enter_point_name') }}" required aria-label="{{ __('vender/route.point_name_aria') }}">
            `;

            const amountCell = row.insertCell(2);
            amountCell.innerHTML = `
                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="points[${pointCounter}][amount]" placeholder="0" min="0" step="1" aria-label="{{ __('vender/route.amount_tsh_aria') }}">
            `;

            const actionCell = row.insertCell(3);
            actionCell.classList.add('text-center');
            actionCell.innerHTML = `
                <button type="button" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors" aria-label="{{ __('vender/route.remove_point_aria') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;

            actionCell.querySelector('button').addEventListener('click', () => {
                if (confirm(translations.confirm_remove_point)) {
                    tableBody.removeChild(row);
                }
            });
        }

        addPointRow();
        addPointBtn.addEventListener('click', addPointRow);

        document.getElementById('routeForm').addEventListener('submit', (e) => {
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length === 0) {
                e.preventDefault();
                alert(translations.no_route_points);
            }
        });
    });
</script>
@endsection