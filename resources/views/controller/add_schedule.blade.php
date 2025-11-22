@extends('admin.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Add New Schedule</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="fetch-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden"></div>

        <form action="{{ route('store_schedule') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <!-- Bus Selection -->
            <div class="mb-4">
                <label for="bus_id" class="block text-gray-700 text-sm font-bold mb-2">Bus</label>
                <select name="bus_id" id="bus_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select Bus</option>
                    @foreach ($buses as $bus)
                        <option value="{{ $bus->id }}" data-routes='@json($bus->route ? [$bus->route] : [])'>
                            {{ $bus->busname->name }} ({{ $bus->bus_number }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Route Selection -->
            <div class="mb-4">
                <label for="route_id" class="block text-gray-700 text-sm font-bold mb-2">Route</label>
                <select name="route_id" id="route_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Select Route</option>
                </select>
            </div>

            <!-- Dynamic Schedule Rows -->
            <div id="schedule_rows">
                <div class="schedule-row mb-4 grid grid-cols-12 gap-4">
                    <div class="col-span-2">
                        <label for="from" class="block text-gray-700 text-sm font-bold mb-2">From</label>
                        <select name="schedules[0][from]" class="from-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Select From</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="to" class="block text-gray-700 text-sm font-bold mb-2">To</label>
                        <select name="schedules[0][to]" class="to-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Select To</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="schedule_date" class="block text-gray-700 text-sm font-bold mb-2">Schedule Date</label>
                        <input type="date" name="schedules[0][schedule_date]" id="first_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="col-span-2">
                        <label for="start" class="block text-gray-700 text-sm font-bold mb-2">Departure Time</label>
                        <input type="time" name="schedules[0][start]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" step="60" required>
                    </div>
                    <div class="col-span-2">
                        <label for="end" class="block text-gray-700 text-sm font-bold mb-2">Arrival Time</label>
                        <input type="time" name="schedules[0][end]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" step="60" required>
                    </div>
                    <div class="col-span-2 flex items-end gap-2">
                        <button type="button" class="edit-date bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Lock</button>
                        <button type="button" class="remove-row bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline hidden">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Add Row Button -->
            <div class="mb-4">
                <button type="button" id="add_row" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Another Schedule</button>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Schedule</button>
        </form>
    </div>

    <script>
        // Define today globally
        const today = new Date().toISOString().split('T')[0];

        document.addEventListener('DOMContentLoaded', function() {
            const firstDate = document.getElementById('first_date');
            firstDate.setAttribute('min', today);

            const timeInputs = document.querySelectorAll('input[type="time"]');
            timeInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const value = input.value;
                    if (value) {
                        const [hours, minutes] = value.split(':');
                        input.value = `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
                    }
                });
            });
        });

        let rowCount = 1;
        let isEditMode = false;

        function incrementDate(dateStr, days) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            date.setDate(date.getDate() + days);
            return date.toISOString().split('T')[0];
        }

        function populateFromToSelects(row, from, to) {
            const fromSelect = row.querySelector('.from-select');
            const toSelect = row.querySelector('.to-select');
            fromSelect.innerHTML =
                `<option value="">Select From</option>${from ? `<option value="${from}">${from}</option>` : ''}${to ? `<option value="${to}">${to}</option>` : ''}`;
            toSelect.innerHTML =
                `<option value="">Select To</option>${from ? `<option value="${from}">${from}</option>` : ''}${to ? `<option value="${to}">${to}</option>` : ''}`;
        }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('edit-date')) {
                isEditMode = !isEditMode;
                e.target.textContent = isEditMode ? 'Unlock' : 'Lock';
                e.target.classList.toggle('bg-blue-500');
                e.target.classList.toggle('bg-blue-700');
                e.target.classList.toggle('bg-gray-500');
                e.target.classList.toggle('bg-gray-700');

                const dateInputs = document.querySelectorAll('input[type="date"]');
                const timeInputs = document.querySelectorAll('input[type="time"]');
                const fromSelects = document.querySelectorAll('.from-select');
                const toSelects = document.querySelectorAll('.to-select');
                
                dateInputs.forEach((input, index) => {
                    if (index > 0) {
                        input.readOnly = isEditMode;
                        if (isEditMode) {
                            const firstDate = document.getElementById('first_date').value;
                            input.value = incrementDate(firstDate, index);
                        }
                    }
                });

                // Lock/unlock time inputs for all rows except the first
                timeInputs.forEach((input, index) => {
                    const rowIndex = Math.floor(index / 2); // Each row has 2 time inputs (start and end)
                    if (rowIndex > 0) {
                        input.readOnly = isEditMode;
                        if (isEditMode) {
                            // Copy times from first row
                            const firstRowTimeInputs = document.querySelectorAll('.schedule-row:first-child input[type="time"]');
                            const timeIndex = index % 2; // 0 for start, 1 for end
                            input.value = firstRowTimeInputs[timeIndex].value;
                        }
                    }
                });

                // Lock/unlock and apply alternating from/to pattern for all rows
                if (isEditMode) {
                    const firstRowFromSelect = document.querySelector('.schedule-row:first-child .from-select');
                    const firstRowToSelect = document.querySelector('.schedule-row:first-child .to-select');
                    
                    if (firstRowFromSelect && firstRowToSelect && firstRowFromSelect.value && firstRowToSelect.value) {
                        const fromValue = firstRowFromSelect.value;
                        const toValue = firstRowToSelect.value;
                        
                        fromSelects.forEach((select, index) => {
                            // Alternating pattern: even index gets fromValue, odd index gets toValue
                            select.value = (index % 2 === 0) ? fromValue : toValue;
                        });

                        toSelects.forEach((select, index) => {
                            // Alternating pattern: even index gets toValue, odd index gets fromValue
                            select.value = (index % 2 === 0) ? toValue : fromValue;
                        });
                    }
                }
            }
        });

        document.getElementById('add_row').addEventListener('click', function() {
            const container = document.getElementById('schedule_rows');
            const newRow = document.createElement('div');
            newRow.className = 'schedule-row mb-4 grid grid-cols-12 gap-4';

            const firstDate = document.getElementById('first_date').value;
            const incrementedDate = isEditMode ? incrementDate(firstDate, rowCount) : '';

            const routeSelect = document.getElementById('route_id');
            const selectedRoute = routeSelect.options[routeSelect.selectedIndex]?.text.split(' to ');
            const from = selectedRoute ? selectedRoute[0] : '';
            const to = selectedRoute ? selectedRoute[1] : '';

            // Get first row values for locked mode
            const firstRowFromSelect = document.querySelector('.schedule-row:first-child .from-select');
            const firstRowToSelect = document.querySelector('.schedule-row:first-child .to-select');
            const firstRowTimeInputs = document.querySelectorAll('.schedule-row:first-child input[type="time"]');
            
            let lockedFrom = '';
            let lockedTo = '';
            
            if (isEditMode && firstRowFromSelect && firstRowToSelect && firstRowFromSelect.value && firstRowToSelect.value) {
                // Alternating pattern based on row count
                const fromValue = firstRowFromSelect.value;
                const toValue = firstRowToSelect.value;
                
                lockedFrom = (rowCount % 2 === 0) ? fromValue : toValue;
                lockedTo = (rowCount % 2 === 0) ? toValue : fromValue;
            }
            
            const lockedStartTime = isEditMode ? (firstRowTimeInputs[0] ? firstRowTimeInputs[0].value : '') : '';
            const lockedEndTime = isEditMode ? (firstRowTimeInputs[1] ? firstRowTimeInputs[1].value : '') : '';

            newRow.innerHTML = `
                <div class="col-span-2">
                    <label for="from" class="block text-gray-700 text-sm font-bold mb-2">From</label>
                    <select name="schedules[${rowCount}][from]" class="from-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Select From</option>
                        ${from ? `<option value="${from}" ${lockedFrom === from ? 'selected' : ''}>${from}</option>` : ''}
                        ${to ? `<option value="${to}" ${lockedFrom === to ? 'selected' : ''}>${to}</option>` : ''}
                    </select>
                </div>
                <div class="col-span-2">
                    <label for="to" class="block text-gray-700 text-sm font-bold mb-2">To</label>
                    <select name="schedules[${rowCount}][to]" class="to-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Select To</option>
                        ${from ? `<option value="${from}" ${lockedTo === from ? 'selected' : ''}>${from}</option>` : ''}
                        ${to ? `<option value="${to}" ${lockedTo === to ? 'selected' : ''}>${to}</option>` : ''}
                    </select>
                </div>
                <div class="col-span-2">
                    <label for="schedule_date" class="block text-gray-700 text-sm font-bold mb-2">Schedule Date</label>
                    <input type="date" name="schedules[${rowCount}][schedule_date]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="${incrementedDate}" ${isEditMode ? 'readonly' : ''} required min="${today}">
                </div>
                <div class="col-span-2">
                    <label for="start" class="block text-gray-700 text-sm font-bold mb-2">Departure Time</label>
                    <input type="time" name="schedules[${rowCount}][start]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="${lockedStartTime}" step="60" required>
                </div>
                <div class="col-span-2">
                    <label for="end" class="block text-gray-700 text-sm font-bold mb-2">Arrival Time</label>
                    <input type="time" name="schedules[${rowCount}][end]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="${lockedEndTime}" step="60" required>
                </div>
                <div class="col-span-2 flex items-end gap-2">
                    <button type="button" class="remove-row bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Remove</button>
                </div>
            `;
            container.appendChild(newRow);

            const newTimeInputs = newRow.querySelectorAll('input[type="time"]');
            newTimeInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const value = input.value;
                    if (value) {
                        const [hours, minutes] = value.split(':');
                        input.value = `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
                    }
                });
            });

            rowCount++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('.schedule-row');
                if (document.querySelectorAll('.schedule-row').length > 1) {
                    row.remove();
                    rowCount--;
                }
            }
        });

        document.getElementById('bus_id').addEventListener('change', function() {
            const busId = this.value;
            const routeSelect = document.getElementById('route_id');
            const selectedOption = this.options[this.selectedIndex];
            const routes = selectedOption ? JSON.parse(selectedOption.getAttribute('data-routes')) : [];

            routeSelect.innerHTML = '<option value="">Select Route</option>';

            routes.forEach(route => {
                const option = new Option(`${route.from} to ${route.to}`, route.id);
                routeSelect.add(option);
            });

            const rows = document.querySelectorAll('.schedule-row');
            rows.forEach(row => {
                populateFromToSelects(row, '', '');
            });

            if (routes.length > 0) {
                routeSelect.value = routes[0].id;
                routeSelect.dispatchEvent(new Event('change'));
            }

            const errorDiv = document.getElementById('fetch-error');
            errorDiv.classList.add('hidden');

            if (busId) {
                console.log(`Fetching schedules for bus_id: ${busId}`);
                fetch(`/bus-company/schedules/unbooked?bus_id=${busId}`)
                    .then(response => {
                        console.log('Fetch response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched schedules:', data.schedules);
                        if (data.error) {
                            errorDiv.textContent = data.error;
                            errorDiv.classList.remove('hidden');
                            return;
                        }
                        const container = document.getElementById('schedule_rows');
                        while (container.children.length > 1) {
                            container.removeChild(container.lastChild);
                            rowCount--;
                        }
                        if (data.schedules.length > 0) {
                            data.schedules.forEach((schedule, index) => {
                                if (index === 0) {
                                    const firstRow = container.querySelector('.schedule-row');
                                    firstRow.querySelector('.from-select').value = schedule.from || '';
                                    firstRow.querySelector('.to-select').value = schedule.to || '';
                                    firstRow.querySelector('input[type="date"]').value = schedule.schedule_date || '';
                                    firstRow.querySelector('input[name="schedules[0][start]"]').value = schedule.start || '';
                                    firstRow.querySelector('input[name="schedules[0][end]"]').value = schedule.end || '';
                                } else {
                                    const newRow = document.createElement('div');
                                    newRow.className = 'schedule-row mb-4 grid grid-cols-12 gap-4';
                                    
                                    // Get first row values for locked mode
                                    const firstRowFromSelect = container.querySelector('.schedule-row:first-child .from-select');
                                    const firstRowToSelect = container.querySelector('.schedule-row:first-child .to-select');
                                    const firstRowTimeInputs = container.querySelectorAll('.schedule-row:first-child input[type="time"]');
                                    
                                    let lockedFrom = schedule.from || '';
                                    let lockedTo = schedule.to || '';
                                    
                                    if (isEditMode && firstRowFromSelect && firstRowToSelect && firstRowFromSelect.value && firstRowToSelect.value) {
                                        // Alternating pattern based on row count
                                        const fromValue = firstRowFromSelect.value;
                                        const toValue = firstRowToSelect.value;
                                        
                                        lockedFrom = (rowCount % 2 === 0) ? fromValue : toValue;
                                        lockedTo = (rowCount % 2 === 0) ? toValue : fromValue;
                                    }
                                    
                                    const lockedStartTime = isEditMode ? (firstRowTimeInputs[0] ? firstRowTimeInputs[0].value : '') : (schedule.start || '');
                                    const lockedEndTime = isEditMode ? (firstRowTimeInputs[1] ? firstRowTimeInputs[1].value : '') : (schedule.end || '');
                                    
                                    newRow.innerHTML = `
                                        <div class="col-span-2">
                                            <label for="from" class="block text-gray-700 text-sm font-bold mb-2">From</label>
                                            <select name="schedules[${rowCount}][from]" class="from-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                                <option value="">Select From</option>
                                                <option value="${schedule.from}" ${lockedFrom === schedule.from ? 'selected' : ''}>${schedule.from}</option>
                                                <option value="${schedule.to}" ${lockedFrom === schedule.to ? 'selected' : ''}>${schedule.to}</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label for="to" class="block text-gray-700 text-sm font-bold mb-2">To</label>
                                            <select name="schedules[${rowCount}][to]" class="to-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                                <option value="">Select To</option>
                                                <option value="${schedule.from}" ${lockedTo === schedule.from ? 'selected' : ''}>${schedule.from}</option>
                                                <option value="${schedule.to}" ${lockedTo === schedule.to ? 'selected' : ''}>${schedule.to}</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label for="schedule_date" class="block text-gray-700 text-sm font-bold mb-2">Schedule Date</label>
                                            <input type="date" name="schedules[${rowCount}][schedule_date]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="${schedule.schedule_date}" ${isEditMode ? 'readonly' : ''} required min="${today}">
                                        </div>
                                        <div class="col-span-2">
                                            <label for="start" class="block text-gray-700 text-sm font-bold mb-2">Departure Time</label>
                                            <input type="time" name="schedules[${rowCount}][start]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="${lockedStartTime}" step="60" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label for="end" class="block text-gray-700 text-sm font-bold mb-2">Arrival Time</label>
                                            <input type="time" name="schedules[${rowCount}][end]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="${lockedEndTime}" step="60" required>
                                        </div>
                                        <div class="col-span-2 flex items-end gap-2">
                                            <button type="button" class="remove-row bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Remove</button>
                                        </div>
                                    `;
                                    container.appendChild(newRow);
                                    rowCount++;
                                }
                            });
                        } else {
                            console.log('No schedules found for this bus.');
                            errorDiv.textContent = 'No unbooked schedules found for this bus.';
                            errorDiv.classList.remove('hidden');
                            const firstRow = container.querySelector('.schedule-row');
                            firstRow.querySelector('.from-select').value = '';
                            firstRow.querySelector('.to-select').value = '';
                            firstRow.querySelector('input[type="date"]').value = '';
                            firstRow.querySelector('input[name="schedules[0][start]"]').value = '';
                            firstRow.querySelector('input[name="schedules[0][end]"]').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching schedules:', error);
                        errorDiv.textContent = `Failed to fetch schedules: ${error.message}`;
                        errorDiv.classList.remove('hidden');
                    });
            }
        });

        document.getElementById('route_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const routeText = selectedOption.text.split(' to ');
            const from = routeText[0] || '';
            const to = routeText[1] || '';

            const rows = document.querySelectorAll('.schedule-row');
            rows.forEach(row => populateFromToSelects(row, from, to));
        });

        // Add event listeners for from/to changes in locked mode
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('from-select') || e.target.classList.contains('to-select')) {
                if (isEditMode) {
                    const row = e.target.closest('.schedule-row');
                    const fromSelect = row.querySelector('.from-select');
                    const toSelect = row.querySelector('.to-select');
                    
                    // Get the first row values to maintain alternating pattern
                    const firstRowFromSelect = document.querySelector('.schedule-row:first-child .from-select');
                    const firstRowToSelect = document.querySelector('.schedule-row:first-child .to-select');
                    
                    if (firstRowFromSelect && firstRowToSelect && firstRowFromSelect.value && firstRowToSelect.value) {
                        const fromValue = firstRowFromSelect.value;
                        const toValue = firstRowToSelect.value;
                        
                        // Find the row index
                        const allRows = document.querySelectorAll('.schedule-row');
                        const rowIndex = Array.from(allRows).indexOf(row);
                        
                        // Apply alternating pattern based on row index
                        if (rowIndex % 2 === 0) {
                            // Even rows: from = fromValue, to = toValue
                            fromSelect.value = fromValue;
                            toSelect.value = toValue;
                        } else {
                            // Odd rows: from = toValue, to = fromValue
                            fromSelect.value = toValue;
                            toSelect.value = fromValue;
                        }
                    }
                }
            }
        });
    </script>
@endsection