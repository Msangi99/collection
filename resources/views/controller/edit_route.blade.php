@extends('admin.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-800">Edit Route</h5>
        </div>
        <div class="p-4 sm:p-6">
            <form id="routeForm" action="{{ route('update.route', $route->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="route_from" class="block text-sm font-medium text-gray-700 mb-1">Route From</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600" id="route_from" name="route_from" value="{{ $route->bus->bus_number }} | {{ $route->from }}-{{ $route->to }}" readonly aria-describedby="route_from_help">
                        <p class="mt-1 text-xs text-gray-500" id="route_from_help">This field is read-only.</p>
                        @error('route_from')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <input type="hidden" name="route_id" value="{{ $route->id }}">
                    <input type="hidden" name="bus_id" value="{{ $route->bus->id }}">
                    <div>
                        <label for="via" class="block text-sm font-medium text-gray-700 mb-1">Via</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('via') border-red-500 @enderror" id="via" name="via" value="{{ old('via', $route->via->name ?? '') }}" placeholder="Enter via location" required aria-describedby="via_help">
                        <p class="mt-1 text-xs text-gray-500" id="via_help">Enter the intermediate location for the route.</p>
                        @error('via')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                        <h6 class="text-base font-semibold text-gray-800">Route Points</h6>
                        <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors" id="addPointBtn" title="Add new route point" aria-label="Add new route point">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Point
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto text-sm text-gray-700" id="routePointsTable">
                            <thead class="bg-gray-100 text-xs uppercase text-gray-500 font-semibold">
                                <tr>
                                    <th class="px-4 py-3 text-left">Point Mode</th>
                                    <th class="px-4 py-3 text-left">Point Name</th>
                                    <th class="px-4 py-3 text-left">Amount (Tsh)</th>
                                    <th class="px-4 py-3 text-left">Is Return?</th>
                                    <th class="px-4 py-3 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    @error('points.*')
                        <div class="mt-2 p-3 bg-red-100 text-red-700 text-sm rounded-md" role="alert">
                            Please ensure all route point fields are correctly filled.
                        </div>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="reset" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors" aria-label="Reset form">Reset</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors" aria-label="Save route">Save Route</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const routePoints = @json($route->points ?? []);
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tableBody = document.querySelector('#routePointsTable tbody');
        const addPointBtn = document.getElementById('addPointBtn');
        let pointCounter = 0;

        const addPointRow = (point = null) => {
            pointCounter++;
            const row = tableBody.insertRow();
            row.dataset.id = point?.id || `new_${pointCounter}`;

            const modeCell = row.insertCell(0);
            modeCell.innerHTML = `
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="points[${pointCounter}][mode]" required aria-label="Point mode">
                    <option value="" disabled ${!point ? 'selected' : ''}>Select</option>
                    <option value="1" ${point?.point_mode == 1 ? 'selected' : ''}>Pickup Point</option>
                    <option value="2" ${point?.point_mode == 2 ? 'selected' : ''}>Dropping Point</option>
                </select>
            `;

            const nameCell = row.insertCell(1);
            const inputValue = (point?.point && Number.isInteger(Number(point.point)) && String(point.point).match(/^-?\d+$/)) ? (point?.city?.name ?? '') : (point?.point ?? '');
            nameCell.innerHTML = `
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="points[${pointCounter}][name]" value="${inputValue.replace(/"/g, '&quot;')}" placeholder="Enter point name" required aria-label="Point name">
            `;

            const amountCell = row.insertCell(2);
            amountCell.innerHTML = `
                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="points[${pointCounter}][amount]" value="${point?.amount ?? ''}" placeholder="0" min="0" step="1" aria-label="Amount in Tsh">
            `;

            const checkCell = row.insertCell(3);
            checkCell.classList.add('text-center');
            checkCell.innerHTML = `
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="points[${pointCounter}][state]" aria-label="Is return point">
                    <option value="no" ${point?.state !== 'yes' ? 'selected' : ''}>No</option>
                    <option value="yes" ${point?.state === 'yes' ? 'selected' : ''}>Yes</option>
                </select>
            `;

            const actionCell = row.insertCell(4);
            actionCell.classList.add('text-center');
            actionCell.innerHTML = `
                <button type="button" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors" title="Remove point" aria-label="Remove point">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;

            if (point?.id) {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `points[${pointCounter}][id]`;
                idInput.value = point.id;
                row.appendChild(idInput);
            }

            actionCell.querySelector('button').addEventListener('click', () => {
                if (confirm('Are you sure you want to remove this route point?')) {
                    tableBody.removeChild(row);
                }
            });
        };

        if (routePoints.length > 0) {
            routePoints.forEach(addPointRow);
        } else {
            addPointRow();
        }

        addPointBtn.addEventListener('click', () => addPointRow());

        document.getElementById('routeForm').addEventListener('submit', (e) => {
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length === 0) {
                e.preventDefault();
                alert('Please add at least one route point.');
            }
        });
    });
</script>
@endsection