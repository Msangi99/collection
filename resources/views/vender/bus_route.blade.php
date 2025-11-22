@extends('vender.app')

@section('title', __('assistance/schedule.bus_routes'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 flex justify-between items-center">
                    <h4 class="text-xl font-semibold text-white">{{ __('assistance/schedule.bus_routes_schedule') }}</h4>
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="{{ __('assistance/schedule.search_routes') }}" 
                            class="pl-10 pr-4 py-2 rounded-lg bg-white/10 text-white placeholder-white/70 border border-white/20 focus:outline-none focus:ring-2 focus:ring-white/30">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table id="busesTable" class="w-full">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                    <th class="sortable px-4 py-3 text-left font-medium" data-sort="number">{{ __('vender/busroot.number') }}</th>
                                    <th class="sortable px-4 py-3 text-left font-medium" data-sort="text">{{ __('vender/busroot.company') }}</th>
                                    <th class="sortable px-4 py-3 text-left font-medium" data-sort="text">{{ __('vender/busroot.bus_number') }}</th>
                                    <th class="sortable px-4 py-3 text-left font-medium" data-sort="text">{{ __('vender/busroot.main_route') }}</th>
                                    <th class="sortable px-4 py-3 text-left font-medium" data-sort="text">{{ __('vender/busroot.next_route') }}</th>
                                    <th class="sortable px-4 py-3 text-left font-medium" data-sort="text">{{ __('vender/busroot.bus_fee') }}</th>
                                    <th class="sortable px-4 py-3 text-left font-medium" data-sort="text">{{ __('vender/busroot.time_24hrs') }}</th>
                                    <th class="sortable px-4 py-3 text-left font-medium" data-sort="date">{{ __('vender/busroot.date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($cars as $car)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">{{ $car->campany->name }}</td>
                                        <td class="px-4 py-3">{{ $car->bus_number }}</td>
                                        <td class="px-4 py-3">{{ $car->route->from }} To {{ $car->route->to }}</td>
                                        <td class="px-4 py-3">{{ $car->schedule->from }} To {{ $car->schedule->to }}</td>
                                        <td class="px-4 py-3">Tsh. {{ $car->route->price }}</td>
                                        <td class="px-4 py-3">{{ $car->schedule->start }} -> {{ $car->schedule->end }}</td>
                                        <td class="px-4 py-3" data-sort-value="{{ $car->schedule->schedule_date ?? '' }}">
                                            {{ $car->schedule->schedule_date ? \Carbon\Carbon::parse($car->schedule->schedule_date)->format('d M Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-3 text-center text-gray-500">{{ __('assistance/schedule.no_bus_routes_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-between items-center mt-6">
                        <div class="text-sm text-gray-600">
                            {{ __('vender/busroot.showing') }} <span id="startItem">1</span> {{ __('vender/busroot.to') }} <span id="endItem">10</span> {{ __('vender/busroot.of') }} <span id="totalItems">{{ count($cars) }}</span> {{ __('vender/busroot.entries') }}
                        </div>
                        <nav>
                            <ul class="flex space-x-2" id="pagination">
                                <!-- Pagination will be inserted here by JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .sortable {
            cursor: pointer;
            position: relative;
        }

        .sortable::after {
            content: "↑↓";
            font-size: 0.75rem;
            margin-left: 0.5rem;
            opacity: 0.4;
            display: inline-block;
            transition: opacity 0.2s;
        }

        .sortable.asc::after {
            content: "↑";
            opacity: 1;
        }

        .sortable.desc::after {
            content: "↓";
            opacity: 1;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('busesTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const searchInput = document.getElementById('searchInput');
            const sortableHeaders = table.querySelectorassistance/schedule('.sortable');
            const itemsPerPage = 10;
            let currentPage = 1;
            let currentSort = {
                column: 7, // Updated to match Date column index
                direction: 'desc'
            };

            function initTable() {
                updateTable();
                setupPagination();
                updatePagination();
            }

            function updateTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const filteredRows = rows.filter(row => {
                    const cells = row.querySelectorassistance/schedule('td');
                    return Array.from(cells).some(cell =>
                        cell.textContent.toLowerCase().includes(searchTerm)
                    );
                });

                filteredRows.sort((a, b) => {
                    const aValue = a.querySelectorassistance/schedule('td')[currentSort.column].getAttribute('data-sort-value') ||
                        a.querySelectorassistance/schedule('td')[currentSort.column].textContent;
                    const bValue = b.querySelectorassistance/schedule('td')[currentSort.column].getAttribute('data-sort-value') ||
                        b.querySelectorassistance/schedule('td')[currentSort.column].textContent;

                    if (currentSort.column === 7) { // Date column
                        const aDate = aValue ? new Date(aValue) : null;
                        const bDate = bValue ? new Date(bValue) : null;

                        if (!aDate && !bDate) return 0;
                        if (!aDate) return 1;
                        if (!bDate) return -1;

                        return currentSort.direction === 'asc' ?
                            aDate - bDate :
                            bDate - aDate;
                    } else {
                        return currentSort.direction === 'asc' ?
                            aValue.localeCompare(bValue) :
                            bValue.localeCompare(bValue);
                    }
                });

                while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                }

                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const paginatedRows = filteredRows.slice(startIndex, endIndex);

                paginatedRows.forEach(row => tbody.appendChild(row));

                document.getElementById('startItem').textContent = filteredRows.length > 0 ? startIndex + 1 : 0;
                document.getElementById('endItem').textContent = Math.min(endIndex, filteredRows.length);
                document.getElementById('totalItems').textContent = filteredRows.length;
            }

            function setupPagination() {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                const prevButton = document.createElement('li');
                prevButton.className = `px-3 py-1.5 rounded-md border border-gray-300 text-gray-600 hover:bg-blue-50 hover:border-blue-300 transition-colors ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}`;
                prevButton.innerHTML = '«';
                prevButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage > 1) {
                        currentPage--;
                        updateTable();
                        updatePagination();
                    }
                });
                pagination.appendChild(prevButton);

                const totalPages = Math.ceil(rows.length / itemsPerPage);
                for (let i = 1; i <= totalPages; i++) {
                    const pageItem = document.createElement('li');
                    pageItem.className = `px-3 py-1.5 rounded-md border border-gray-300 ${i === currentPage ? 'bg-blue-500 text-white border-blue-500' : 'text-gray-600 hover:bg-blue-50 hover:border-blue-300'} transition-colors`;
                    pageItem.innerHTML = `${i}`;
                    pageItem.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = i;
                        updateTable();
                        updatePagination();
                    });
                    pagination.appendChild(pageItem);
                }

                const nextButton = document.createElement('li');
                nextButton.className = `px-3 py-1.5 rounded-md border border-gray-300 text-gray-600 hover:bg-blue-50 hover:border-blue-300 transition-colors ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}`;
                nextButton.innerHTML = '»';
                nextButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    const totalPages = Math.ceil(rows.length / itemsPerPage);
                    if (currentPage < totalPages) {
                        currentPage++;
                        updateTable();
                        updatePagination();
                    }
                });
                pagination.appendChild(nextButton);
            }

            function updatePagination() {
                const pageItems = document.querySelectorAll('#pagination li:not(:first-child):not(:last-child)');
                pageItems.forEach((item, index) => {
                    if (index + 1 === currentPage) {
                        item.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
                        item.classList.remove('text-gray-600', 'hover:bg-blue-50', 'hover:border-blue-300');
                    } else {
                        item.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
                        item.classList.add('text-gray-600', 'hover:bg-blue-50', 'hover:border-blue-300');
                    }
                });
            }

            searchInput.addEventListener('input', () => {
                currentPage = 1;
                updateTable();
                setupPagination();
            });

            sortableHeaders.forEach((header, index) => {
                header.addEventListener('click', () => {
                    if (currentSort.column === index) {
                        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        currentSort.column = index;
                        currentSort.direction = 'asc';
                    }

                    sortableHeaders.forEach(h => {
                        h.classList.remove('asc', 'desc');
                    });
                    header.classList.add(currentSort.direction);

                    updateTable();
                });
            });

            sortableHeaders[currentSort.column].classList.add(currentSort.direction);

            initTable();
        });
    </script>
@endsection
