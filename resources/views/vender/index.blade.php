@extends('vender.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('assistance/dashboard.dashboard_overview') }}</h1>
            <a href="{{ route('vender.bus_route') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-assistance/dashboard">
                <i class="fas fa-bus mr-2"></i> {{ __('assistance/dashboard.view_bus_routes') }}
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg transition-transform hover:-translate-y-1">
                <div class="bg-blue-600 text-white rounded-t-xl font-semibold p-4 flex justify-between items-center">
                    <span>{{ __('assistance/dashboard.todays_bookings') }}</span>
                    <div class="text-3xl opacity-30"><i class="fas fa-calendar-day"></i></div>
                </div>
                <div class="p-6">
                    <div class="text-2xl font-bold text-gray-800">{{ $TodayBookings->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ __('assistance/dashboard.total_bookings_today') }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg transition-transform hover:-translate-y-1">
                <div class="bg-blue-600 text-white rounded-t-xl font-semibold p-4 flex justify-between items-center">
                    <span>{{ __('assistance/dashboard.weekly_bookings') }}</span>
                    <div class="text-3xl opacity-30"><i class="fas fa-calendar-week"></i></div>
                </div>
                <div class="p-6">
                    <div class="text-2xl font-bold text-gray-800">{{ $WeekBookings->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ __('assistance/dashboard.total_bookings_week') }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg transition-transform hover:-translate-y-1">
                <div class="bg-blue-600 text-white rounded-t-xl font-semibold p-4 flex justify-between items-center">
                    <span>{{ __('assistance/dashboard.available_balance') }}</span>
                    <div class="text-3xl opacity-30"><i class="fas fa-wassistance/dashboardet"></i></div>
                </div>
                <div class="p-6">
                    <div class="text-2xl font-bold text-gray-800">Tsh. {{ number_format(auth()->user()->VenderBalances->amount, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">{{ __('assistance/dashboard.current_available_balance') }}</div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h5 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-3">{{ __('assistance/dashboard.booking_analytics') }}</h5>
                    <form method="GET" class="flex items-center gap-2">
                        <select name="filter" class="border rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>{{ __('assistance/dashboard.today') }}</option>
                            <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>{{ __('assistance/dashboard.this_week') }}</option>
                            <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>{{ __('assistance/dashboard.this_month') }}</option>
                            <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>{{ __('assistance/dashboard.this_year') }}</option>
                        </select>
                    </form>
                </div>
                <div class="h-96">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h5 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-3">{{ __('assistance/dashboard.recent_bookings') }}</h5>

                <!-- Table Controls -->
                <div class="flex justify-between items-center mb-4">
                    <div class="w-64">
                        <input type="text" id="bookingSearch" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="{{ __('assistance/dashboard.search_bookings') }}">
                    </div>
                    <div>
                        <select id="rowsPerPage" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="5">{{ __('assistance/dashboard.rows_5') }}</option>
                            <option value="10" selected>{{ __('assistance/dashboard.rows_10') }}</option>
                            <option value="20">{{ __('assistance/dashboard.rows_20') }}</option>
                            <option value="50">{{ __('assistance/dashboard.rows_50') }}</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto" id="bookingsTable">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">#</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 sortable" data-sort="id">{{ __('assistance/dashboard.booking_id') }} <i class="fas fa-sort"></i></th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 sortable" data-sort="customer">{{ __('assistance/dashboard.customer') }} <i class="fas fa-sort"></i></th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 sortable" data-sort="bus">{{ __('assistance/dashboard.bus') }} <i class="fas fa-sort"></i></th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 sortable" data-sort="date">{{ __('assistance/dashboard.date') }} <i class="fas fa-sort"></i></th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 sortable" data-sort="seats">{{ __('assistance/dashboard.seats') }} <i class="fas fa-sort"></i></th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 sortable" data-sort="amount">{{ __('assistance/dashboard.amount') }} <i class="fas fa-sort"></i></th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 sortable" data-sort="status">{{ __('assistance/dashboard.status') }} <i class="fas fa-sort"></i></th>
                            </tr>
                        </thead>
                        <tbody id="bookingsTableBody">
                            @foreach ($bookings as $index => $booking)
                                <tr class="booking-row hover:bg-gray-50" data-id="{{ $booking->id }}"
                                    data-customer="{{ $booking->customer_name }}"
                                    data-bus="{{ $booking->bus->busname->name ?? 'N/A' }}"
                                    data-date="{{ $booking->created_at->timestamp }}"
                                    data-seats="{{ $booking->seat }}"
                                    data-amount="{{ $booking->amount }}"
                                    data-status="{{ strtolower($booking->payment_status) }}">
                                    <td class="px-4 py-2 row-index">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $booking->booking_code }}</td>
                                    <td class="px-4 py-2">{{ $booking->customer_name }}</td>
                                    <td class="px-4 py-2">{{ $booking->bus->busname->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $booking->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-2">{{ $booking->seat }}</td>
                                    <td class="px-4 py-2 font-semibold text-green-600">Tsh. {{ number_format($booking->amount, 2) }}</td>
                                    <td class="px-4 py-2">
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-600">
                        {{ __('assistance/dashboard.showing') }} <span id="showingStart">1</span> {{ __('assistance/dashboard.to') }} <span id="showingEnd">10</span> {{ __('assistance/dashboard.of') }} <span id="totalEntries">{{ $bookings->count() }}</span> {{ __('assistance/dashboard.entries') }}
                    </div>
                    <nav aria-label="Bookings pagination">
                        <ul class="flex space-x-1" id="bookingsPagination">
                            <!-- Pagination will be inserted here by JavaScript -->
                        </ul>
                    </nav>
                    <a href="{{ route('vender.history') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        {{ __('assistance/dashboard.view_assistance/dashboard_bookings') }} <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <style>
            .sortable {
                cursor: pointer;
                position: relative;
            }

            .sortable:hover {
                background-color: #f8f9fa;
            }

            .sortable .fa-sort {
                opacity: 0.3;
            }

            .sortable.sort-asc .fa-sort {
                opacity: 1;
                transform: rotate(180deg);
            }

            .sortable.sort-desc .fa-sort {
                opacity: 1;
            }

            .highlight {
                background-color: #fef08a;
                padding: 0 2px;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Table elements
                const table = document.getElementById('bookingsTable');
                const tableBody = document.getElementById('bookingsTableBody');
                const rows = Array.from(document.querySelectorassistance/dashboard('.booking-row'));
                const searchInput = document.getElementById('bookingSearch');
                const rowsPerPageSelect = document.getElementById('rowsPerPage');
                const paginationContainer = document.getElementById('bookingsPagination');
                const showingStart = document.getElementById('showingStart');
                const showingEnd = document.getElementById('showingEnd');
                const totalEntries = document.getElementById('totalEntries');

                // Table state
                let currentPage = 1;
                let rowsPerPage = parseInt(rowsPerPageSelect.value);
                let filteredRows = rows;
                let sortColumn = 'date';
                let sortDirection = 'desc';

                // Initialize the table
                function initTable() {
                    updateRowIndices();
                    sortTable();
                    renderTable();
                    renderPagination();
                    updateShowingEntries();
                }

                // Update row indices (1, 2, 3, etc.)
                function updateRowIndices() {
                    const indexCells = document.querySelectorassistance/dashboard('.row-index');
                    indexCells.forEach((cell, index) => {
                        cell.textContent = index + 1;
                    });
                }

                // Sort the table
                function sortTable() {
                    filteredRows.sort((a, b) => {
                        let aValue, bValue;

                        switch (sortColumn) {
                            case 'id':
                                aValue = parseInt(a.dataset.id);
                                bValue = parseInt(b.dataset.id);
                                break;
                            case 'customer':
                                aValue = a.dataset.customer.toLowerCase();
                                bValue = b.dataset.customer.toLowerCase();
                                break;
                            case 'bus':
                                aValue = a.dataset.bus.toLowerCase();
                                bValue = b.dataset.bus.toLowerCase();
                                break;
                            case 'date':
                                aValue = parseInt(a.dataset.date);
                                bValue = parseInt(b.dataset.date);
                                break;
                            case 'seats':
                                aValue = parseInt(a.dataset.seats);
                                bValue = parseInt(a.dataset.seats);
                                break;
                            case 'amount':
                                aValue = parseFloat(a.dataset.amount);
                                bValue = parseFloat(b.dataset.amount);
                                break;
                            case 'status':
                                aValue = a.dataset.status;
                                bValue = b.dataset.status;
                                break;
                            default:
                                aValue = a.dataset[sortColumn];
                                bValue = b.dataset[sortColumn];
                        }

                        if (sortDirection === 'asc') {
                            return aValue > bValue ? 1 : -1;
                        } else {
                            return aValue < bValue ? 1 : -1;
                        }
                    });
                }

                // Render the current page of rows
                function renderTable() {
                    // Hide assistance/dashboard rows
                    rows.forEach(row => {
                        row.style.display = 'none';
                    });

                    // Calculate start and end index
                    const startIndex = (currentPage - 1) * rowsPerPage;
                    const endIndex = startIndex + rowsPerPage;

                    // Show only the rows for the current page
                    filteredRows.slice(startIndex, endIndex).forEach(row => {
                        row.style.display = '';
                    });
                }

                // Render pagination controls
                function renderPagination() {
                    paginationContainer.innerHTML = '';

                    const pageCount = Math.ceil(filteredRows.length / rowsPerPage);

                    // Previous button
                    const prevLi = document.createElement('li');
                    prevLi.className = `px-2 py-1 rounded ${currentPage === 1 ? 'text-gray-400 cursor-not-assistance/dashboardowed' : 'text-blue-600 hover:bg-blue-100'}`;
                    prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true">«</span>
        </a>`;
                    prevLi.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (currentPage > 1) {
                            currentPage--;
                            updateTable();
                        }
                    });
                    paginationContainer.appendChild(prevLi);

                    // Page numbers
                    const maxVisiblePages = 5;
                    let startPage, endPage;

                    if (pageCount <= maxVisiblePages) {
                        startPage = 1;
                        endPage = pageCount;
                    } else {
                        const maxPagesBeforeCurrent = Math.floor(maxVisiblePages / 2);
                        const maxPagesAfterCurrent = Math.ceil(maxVisiblePages / 2) - 1;

                        if (currentPage <= maxPagesBeforeCurrent) {
                            startPage = 1;
                            endPage = maxVisiblePages;
                        } else if (currentPage + maxPagesAfterCurrent >= pageCount) {
                            startPage = pageCount - maxVisiblePages + 1;
                            endPage = pageCount;
                        } else {
                            startPage = currentPage - maxPagesBeforeCurrent;
                            endPage = currentPage + maxPagesAfterCurrent;
                        }
                    }

                    // Add first page and ellipsis if needed
                    if (startPage > 1) {
                        const firstLi = document.createElement('li');
                        firstLi.className = 'px-2 py-1 text-blue-600 hover:bg-blue-100 rounded';
                        firstLi.innerHTML = `<a class="page-link" href="#">1</a>`;
                        firstLi.addEventListener('click', (e) => {
                            e.preventDefault();
                            currentPage = 1;
                            updateTable();
                        });
                        paginationContainer.appendChild(firstLi);

                        if (startPage > 2) {
                            const ellipsisLi = document.createElement('li');
                            ellipsisLi.className = 'px-2 py-1 text-gray-400';
                            ellipsisLi.innerHTML = `<a class="page-link" href="#">...</a>`;
                            paginationContainer.appendChild(ellipsisLi);
                        }
                    }

                    // Add page numbers
                    for (let i = startPage; i <= endPage; i++) {
                        const pageLi = document.createElement('li');
                        pageLi.className = `px-2 py-1 rounded ${i === currentPage ? 'bg-blue-600 text-white' : 'text-blue-600 hover:bg-blue-100'}`;
                        pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                        pageLi.addEventListener('click', (e) => {
                            e.preventDefault();
                            currentPage = i;
                            updateTable();
                        });
                        paginationContainer.appendChild(pageLi);
                    }

                    // Add last page and ellipsis if needed
                    if (endPage < pageCount) {
                        if (endPage < pageCount - 1) {
                            const ellipsisLi = document.createElement('li');
                            ellipsisLi.className = 'px-2 py-1 text-gray-400';
                            ellipsisLi.innerHTML = `<a class="page-link" href="#">...</a>`;
                            paginationContainer.appendChild(ellipsisLi);
                        }

                        const lastLi = document.createElement('li');
                        lastLi.className = 'px-2 py-1 text-blue-600 hover:bg-blue-100 rounded';
                        lastLi.innerHTML = `<a class="page-link" href="#">${pageCount}</a>`;
                        lastLi.addEventListener('click', (e) => {
                            e.preventDefault();
                            currentPage = pageCount;
                            updateTable();
                        });
                        paginationContainer.appendChild(lastLi);
                    }

                    // Next button
                    const nextLi = document.createElement('li');
                    nextLi.className = `px-2 py-1 rounded ${currentPage === pageCount ? 'text-gray-400 cursor-not-assistance/dashboardowed' : 'text-blue-600 hover:bg-blue-100'}`;
                    nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next">
            <span aria-hidden="true">»</span>
        </a>`;
                    nextLi.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (currentPage < pageCount) {
                            currentPage++;
                            updateTable();
                        }
                    });
                    paginationContainer.appendChild(nextLi);
                }

                // Update showing entries text
                function updateShowingEntries() {
                    const start = (currentPage - 1) * rowsPerPage + 1;
                    const end = Math.min(currentPage * rowsPerPage, filteredRows.length);

                    showingStart.textContent = start;
                    showingEnd.textContent = end;
                    totalEntries.textContent = filteredRows.length;
                }

                // Update assistance/dashboard table components
                function updateTable() {
                    renderTable();
                    renderPagination();
                    updateShowingEntries();
                }

                // Search functionality
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    filteredRows = rows.filter(row =>  {
                        const cells = row.querySelectorassistance/dashboard('td:not(.row-index)');
                        let rowMatches = false;

                        for (let j = 0; j < cells.length; j++) {
                            const cellText = cells[j].textContent.toLowerCase();

                            // Remove previous highlights
                            cells[j].innerHTML = cells[j].textContent;

                            if (cellText.includes(searchTerm)) {
                                rowMatches = true;

                                // Highlight matching text
                                if (searchTerm) {
                                    const regex = new RegExp(searchTerm, 'gi');
                                    cells[j].innerHTML = cells[j].textContent.replace(regex,
                                        match => `<span class="highlight">${match}</span>`);
                                }
                            }
                        }

                        return rowMatches;
                    });

                    currentPage = 1;
                   sortTable();
                    updateTable();
                });

                // Rows per page change
                rowsPerPageSelect.addEventListener('change', function() {
                    rowsPerPage = parseInt(this.value);
                    currentPage = 1;
                    updateTable();
                });

                // Sortable columns
                document.querySelectorassistance/dashboard('.sortable').forEach(header => {
                    header.addEventListener('click', function() {
                        const column = this.dataset.sort;

                        // Update sort direction
                        if (sortColumn === column) {
                            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            sortColumn = column;
                            sortDirection = 'asc';
                        }

                        // Update sort indicators
                        document.querySelectorassistance/dashboard('.sortable').forEach(h => {
                            h.classList.remove('sort-asc', 'sort-desc');
                        });

                        this.classList.add(`sort-${sortDirection}`);

                        // Sort and render table
                        sortTable();
                        updateTable();
                    });
                });

                // Initialize the table
                initTable();
            });
        </script>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('incomeChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{
                        label: '{{ __('assistance/dashboard.bookings') }}',
                        data: @json($monthlyData),
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: '#fff',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            cassistance/dashboardbacks: {
                                label: context => `{{ __('assistance/dashboard.bookings') }}: ${context.parsed.y}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection