@extends('system.app')

@section('content')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables DateTime CSS -->
    <link href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <h3 class="text-center text-blue-600 text-lg font-semibold mb-4">HIGHLINK ISGC</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Service Fees Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-400 text-white text-center">
                    <h2 class="text-lg font-semibold">Commission Fees</h2>
                    <span class="text-sm font-medium">Total: Tsh <span id="serviceTotal">{{ number_format($balances->sum('balance'), 2, '.', ',') }}</span></span>
                </div>
                <div class="p-4">
                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <div class="w-full sm:w-1/2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Filter by:</label>
                            <select id="serviceTimeFilter" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="all">All Time</option>
                                <option value="day">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-1/2 hidden" id="serviceDateRangeGroup">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Date Range:</label>
                            <div class="flex flex-col sm:flex-row items-center gap-2">
                                <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="serviceMinDate" placeholder="Start Date">
                                <span class="text-gray-500 text-sm hidden sm:inline">to</span>
                                <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="serviceMaxDate" placeholder="End Date">
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="serviceTable" class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search No"></th>
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Company"></th>
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Amount"></th>
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Date"></th>
                                </tr>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-2 px-4 text-left font-medium">No</th>
                                    <th class="py-2 px-4 text-left font-medium">Company Name</th>
                                    <th class="py-2 px-4 text-left font-medium">Amount</th>
                                    <th class="py-2 px-4 text-left font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-xs">
                                @php $i = 1; @endphp
                                @if ($balances->count() > 0)
                                    @foreach ($balances as $payment)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                            <td class="py-2 px-4">{{ $i++ }}</td>
                                            <td class="py-2 px-4">{{ $payment->campany->name }}</td>
                                            <td class="py-2 px-4 amount" data-amount="{{ $payment->balance }}">Tsh {{ number_format($payment->balance, 2, '.', ',') }}</td>
                                            <td class="py-2 px-4" data-date="{{ $payment->created_at->format('Y-m-d') }}">{{ $payment->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="4" class="py-2 px-4 text-center text-gray-500">No data found</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Commission Fees Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-400 text-white text-center">
                    <h2 class="text-lg font-semibold">Service Fees</h2>
                    <span class="text-sm font-medium">Total: Tsh <span id="commissionTotal">{{ number_format($pays->sum('amount'), 2, '.', ',') }}</span></span>
                </div>
                <div class="p-4">
                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <div class="w-full sm:w-1/2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Filter by:</label>
                            <select id="commissionTimeFilter" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="all">All Time</option>
                                <option value="day">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-1/2 hidden" id="commissionDateRangeGroup">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Date Range:</label>
                            <div class="flex flex-col sm:flex-row items-center gap-2">
                                <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="commissionMinDate" placeholder="Start Date">
                                <span class="text-gray-500 text-sm hidden sm:inline">to</span>
                                <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="commissionMaxDate" placeholder="End Date">
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="commissionTable" class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search No"></th>
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Company"></th>
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Booking Code"></th>
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Amount"></th>
                                    <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Date"></th>
                                </tr>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-2 px-4 text-left font-medium">No</th>
                                    <th class="py-2 px-4 text-left font-medium">Company Name</th>
                                    <th class="py-2 px-4 text-left font-medium">Booking Code</th>
                                    <th class="py-2 px-4 text-left font-medium">Amount</th>
                                    <th class="py-2 px-4 text-left font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-xs">
                                @php $p = 1; @endphp
                                @if($pays->count() > 0)
                                    @foreach ($pays as $payment)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                            <td class="py-2 px-4">{{ $p++ }}</td>
                                            <td class="py-2 px-4">{{ $payment->campany->name }}</td>
                                            <td class="py-2 px-4">{{ $payment->booking_id }}</td>
                                            <td class="py-2 px-4 amount" data-amount="{{ $payment->amount }}">Tsh {{ number_format($payment->amount, 2, '.', ',') }}</td>
                                            <td class="py-2 px-4" data-date="{{ $payment->created_at->format('Y-m-d') }}">{{ $payment->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5" class="py-2 px-4 text-center text-gray-500">No data found</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Moment.js for date handling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <!-- DataTables DateTime plugin -->
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>

    <script>
        $(document).ready(function () {
            // Create date inputs for Service Fees Table
            var serviceMinDate = new DateTime($('#serviceMinDate'), {
                format: 'DD MMM YYYY'
            });
            var serviceMaxDate = new DateTime($('#serviceMaxDate'), {
                format: 'DD MMM YYYY'
            });

            // Create date inputs for Commission Fees Table
            var commissionMinDate = new DateTime($('#commissionMinDate'), {
                format: 'DD MMM YYYY'
            });
            var commissionMaxDate = new DateTime($('#commissionMaxDate'), {
                format: 'DD MMM YYYY'
            });

            // Custom date filtering function for both tables
            $.fn.dataTableExt.afnFiltering.push(function (settings, data, dataIndex) {
                let tableId = settings.sTableId;
                let filterValue, minDate, maxDate;

                if (tableId === 'serviceTable') {
                    filterValue = $('#serviceTimeFilter').val();
                    minDate = serviceMinDate.val();
                    maxDate = serviceMaxDate.val();
                    dateStr = data[3]; // Date column for serviceTable
                } else {
                    filterValue = $('#commissionTimeFilter').val();
                    minDate = commissionMinDate.val();
                    maxDate = commissionMaxDate.val();
                    dateStr = data[4]; // Date column for commissionTable
                }

                let date = moment(dateStr, 'DD MMM YYYY');
                if (!date.isValid()) return true; // Skip invalid dates

                let now = moment();

                // For custom date range
                if (filterValue === 'custom') {
                    if (minDate && maxDate) {
                        let minDateMoment = moment(minDate, 'DD MMM YYYY');
                        let maxDateMoment = moment(maxDate, 'DD MMM YYYY');
                        return date.isBetween(minDateMoment, maxDateMoment, null, '[]'); // inclusive
                    }
                    return true;
                }

                switch (filterValue) {
                    case 'day':
                        return date.isSame(now, 'day');
                    case 'week':
                        return date.isSame(now, 'week');
                    case 'month':
                        return date.isSame(now, 'month');
                    case 'year':
                        return date.isSame(now, 'year');
                    case 'all':
                    default:
                        return true;
                }
            });

            // Initialize Service Fees Table
            const serviceTable = $('#serviceTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                language: {
                    emptyTable: "No data found"
                },
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();
                    let total = api
                        .rows({ page: 'current' })
                        .nodes()
                        .toArray()
                        .reduce((sum, row) => {
                            let amount = $(row).find('.amount').data('amount') || 0;
                            return sum + parseFloat(amount);
                        }, 0);
                    $('#serviceTotal').text(total.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                }
            });

            // Initialize Commission Fees Table
            const commissionTable = $('#commissionTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                language: {
                    emptyTable: "No data found"
                },
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();
                    let total = api
                        .rows({ page: 'current' })
                        .nodes()
                        .toArray()
                        .reduce((sum, row) => {
                            let amount = $(row).find('.amount').data('amount') || 0;
                            return sum + parseFloat(amount);
                        }, 0);
                    $('#commissionTotal').text(total.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                }
            });

            // Apply search to each column in Service Fees Table
            $('#serviceTable thead tr:first-child th').each(function(index) {
                $(this).find('input').on('keyup change', function() {
                    serviceTable.column(index).search(this.value).draw();
                });
            });

            // Apply search to each column in Commission Fees Table
            $('#commissionTable thead tr:first-child th').each(function(index) {
                $(this).find('input').on('keyup change', function() {
                    commissionTable.column(index).search(this.value).draw();
                });
            });

            // Apply time filter for Service Fees Table
            $('#serviceTimeFilter').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#serviceDateRangeGroup').removeClass('hidden');
                } else {
                    $('#serviceDateRangeGroup').addClass('hidden');
                    serviceTable.draw();
                }
            });

            // Apply time filter for Commission Fees Table
            $('#commissionTimeFilter').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#commissionDateRangeGroup').removeClass('hidden');
                } else {
                    $('#commissionDateRangeGroup').addClass('hidden');
                    commissionTable.draw();
                }
            });

            // Redraw the Service Fees Table when the custom date inputs change
            $('#serviceMinDate, #serviceMaxDate').on('change', function() {
                if ($('#serviceTimeFilter').val() === 'custom') {
                    serviceTable.draw();
                }
            });

            // Redraw the Commission Fees Table when the custom date inputs change
            $('#commissionMinDate, #commissionMaxDate').on('change', function() {
                if ($('#commissionTimeFilter').val() === 'custom') {
                    commissionTable.draw();
                }
            });
        });
    </script>

    <style>
        .search-input {
            width: 100%;
            padding: 4px;
            font-size: 12px;
            border-radius: 4px;
        }
    </style>
@endsection