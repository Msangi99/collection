@extends('system.app')
@section('content')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- DataTables DateTime CSS -->
    <link href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Requested Transactions Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-400 text-white flex flex-col sm:flex-row justify-between items-center gap-4">
                <h2 class="text-lg font-semibold">Requested Transactions</h2>
                <span class="text-sm font-medium">Total: Tsh <span id="pendingTotal">0</span></span>
            </div>
            <div class="p-4">
                <div class="flex flex-col sm:flex-row gap-4 mb-4">
                    <div class="w-full sm:w-1/2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Filter by:</label>
                        <select id="pendingTimeFilter" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="all">All Time</option>
                            <option value="day">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 hidden" id="pendingDateRangeGroup">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date Range:</label>
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="pendingMinDate" placeholder="Start Date">
                            <span class="text-gray-500 text-sm hidden sm:inline">to</span>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="pendingMaxDate" placeholder="End Date">
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto" id="pendingTransactionsTable">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left font-medium"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Company"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search User"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Payment Method"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Payment Number"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Amount"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Status"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Date"></th>
                                <th class="py-2 px-4 text-left font-medium"></th>
                            </tr>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left font-medium">#</th>
                                <th class="py-2 px-4 text-left font-medium">Company</th>
                                <th class="py-2 px-4 text-left font-medium">User</th>
                                <th class="py-2 px-4 text-left font-medium">Payment Method</th>
                                <th class="py-2 px-4 text-left font-medium">Payment Number</th>
                                <th class="py-2 px-4 text-left font-medium">Amount</th>
                                <th class="py-2 px-4 text-left font-medium">Status</th>
                                <th class="py-2 px-4 text-left font-medium">Date</th>
                                <th class="py-2 px-4 text-left font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-xs">
                            @forelse ($pendingTransactions as $index => $transaction)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition" data-transaction-id="{{ $transaction->id }}" data-campany-id="{{ $transaction->campany ? $transaction->campany->id : 0 }}" data-vender-id="{{ $transaction->vender_id ?? 0 }}">
                                    <td class="py-2 px-4">{{ $index + 1 }}</td>
                                    <td class="py-2 px-4">{{ $transaction->campany ? $transaction->campany->name : 'Vender' }}</td>
                                    <td class="py-2 px-4">{{ $transaction->user ? $transaction->user->name : 'Unknown' }}</td>
                                    <td class="py-2 px-4">{{ $transaction->payment_method ?? 'Unknown' }}</td>
                                    <td class="py-2 px-4">{{ $transaction->payment_number ?? 'Unknown' }}</td>
                                    <td class="py-2 px-4 amount" data-amount="{{ $transaction->amount }}">Tsh {{ number_format($transaction->amount, 2, '.', ',') }}</td>
                                    <td class="py-2 px-4">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $transaction->status === 'Completed' ? 'bg-green-500 text-white' : ($transaction->status === 'Pending' ? 'bg-yellow-500 text-black' : 'bg-red-500 text-white') }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4" data-date="{{ $transaction->created_at->format('Y-m-d') }}">{{ $transaction->created_at->format('d M Y H:i:s') }}</td>
                                    <td class="py-2 px-4">
                                        @if ($transaction->status !== 'Completed' && $transaction->status !== 'Cancelled')
                                            <button class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm flex items-center gap-1" onclick="showTransactionModal('{{ $transaction->id }}', '{{ number_format($transaction->amount, 2, '.', ',') }}', '{{ $transaction->campany ? $transaction->campany->id : 0 }}', '{{ $transaction->vender_id ?? 0 }}')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>
                                        @else
                                            <span class="text-gray-500">No actions available</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-4 px-4 text-center text-gray-500">No pending transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- All Transactions Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-400 text-white flex flex-col sm:flex-row justify-between items-center gap-4">
                <h2 class="text-lg font-semibold">All Transactions</h2>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <span class="text-sm font-medium">Total: Tsh <span id="allTransactionsTotal">0</span></span>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-auto">
                            <label class="block text-xs font-medium text-white mb-1">Filter by:</label>
                            <select id="allTimeFilter" class="w-full sm:w-48 px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="all">All Time</option>
                                <option value="day">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-auto hidden" id="allDateRangeGroup">
                            <label class="block text-xs font-medium text-white mb-1">Date Range:</label>
                            <div class="flex flex-col sm:flex-row items-center gap-2">
                                <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="allMinDate" placeholder="Start Date">
                                <span class="text-gray-500 text-sm hidden sm:inline">to</span>
                                <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" id="allMaxDate" placeholder="End Date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto" id="allTransactionsTable">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left font-medium"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Company"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search User"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Amount"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Reference"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Status"></th>
                                <th class="py-2 px-4 text-left font-medium"><input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search Date"></th>
                                <th class="py-2 px-4 text-left font-medium"></th>
                            </tr>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left font-medium">#</th>
                                <th class="py-2 px-4 text-left font-medium">Company</th>
                                <th class="py-2 px-4 text-left font-medium">User</th>
                                <th class="py-2 px-4 text-left font-medium">Amount</th>
                                <th class="py-2 px-4 text-left font-medium">Reference No</th>
                                <th class="py-2 px-4 text-left font-medium">Status</th>
                                <th class="py-2 px-4 text-left font-medium">Date</th>
                                <th class="py-2 px-4 text-left font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-xs">
                            @forelse ($allTransactions as $index => $transaction)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition" data-transaction-id="{{ $transaction->id }}" data-campany-id="{{ $transaction->campany ? $transaction->campany->id : 0 }}" data-vender-id="{{ $transaction->vender_id ?? 0 }}">
                                    <td class="py-2 px-4">{{ $index + 1 }}</td>
                                    <td class="py-2 px-4">{{ $transaction->campany ? $transaction->campany->name : 'Vender' }}</td>
                                    <td class="py-2 px-4">{{ $transaction->user ? $transaction->user->name : 'Unknown' }}</td>
                                    <td class="py-2 px-4 amount" data-amount="{{ $transaction->amount }}">Tsh {{ number_format($transaction->amount, 2, '.', ',') }}</td>
                                    <td class="py-2 px-4">{{ $transaction->reference_number }}</td>
                                    <td class="py-2 px-4">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $transaction->status === 'Completed' ? 'bg-green-500 text-white' : ($transaction->status === 'Pending' ? 'bg-yellow-500 text-black' : 'bg-red-500 text-white') }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4" data-date="{{ $transaction->created_at->format('Y-m-d') }}">{{ $transaction->created_at->format('d M Y H:i:s') }}</td>
                                    <td class="py-2 px-4">
                                        @if ($transaction->status == 'Completed')
                                            @if ($transaction->vender_id > 0)
                                                <form action="{{ route('print.recipt2') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="data" value="{{ $transaction }}">
                                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                        </svg>
                                                        Print
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('print.recipt') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="data" value="{{ $transaction }}">
                                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                        </svg>
                                                        Print
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-4 px-4 text-center text-gray-500">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Transaction Modal -->
        <div id="transactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 transform transition-all">
                <div class="p-4 flex justify-between items-center border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800" id="transactionModalLabel">Update Transaction Status</h2>
                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeTransactionModal()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4" id="transactionModalBody">
                    <div id="modalLoading" class="hidden text-center">
                        <svg class="animate-spin h-5 w-5 text-blue-500 mx-auto" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm text-gray-600 mt-2">Loading...</p>
                    </div>
                    <div id="modalError" class="hidden text-red-500 text-sm mb-4"></div>
                    <div id="modalContent">
                        <p class="text-sm text-gray-600 mb-4" id="transactionAmount">Update status for transaction of Tsh 0?</p>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <form class="flex-1" id="completeForm" action="" method="POST">
                                @csrf
                                <input required type="text" name="reference_number" class="w-full px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm mb-2" placeholder="Reference Number">
                                <button type="submit" class="w-full px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">Accept</button>
                            </form>
                            <form class="flex-1" id="cancelForm" action="" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="p-4 flex justify-end gap-2 border-t border-gray-200">
                    <button type="button" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm" onclick="closeTransactionModal()">Close</button>
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
        $(document).ready(function() {
            // Create date inputs for Pending Transactions Table
            $.fn.dataTable.ext.errMode = 'none';
            var pendingMinDate = new DateTime($('#pendingMinDate'), {
                format: 'DD MMM YYYY'
            });
            var pendingMaxDate = new DateTime($('#pendingMaxDate'), {
                format: 'DD MMM YYYY'
            });

            // Create date inputs for All Transactions Table
            var allMinDate = new DateTime($('#allMinDate'), {
                format: 'DD MMM YYYY'
            });
            var allMaxDate = new DateTime($('#allMaxDate'), {
                format: 'DD MMM YYYY'
            });

            // Custom date filtering function for both tables
            $.fn.dataTableExt.afnFiltering.push(function(settings, data, dataIndex) {
                let tableId = settings.sTableId;
                let filterValue, minDate, maxDate, dateStr;

                if (tableId === 'pendingTransactionsTable') {
                    filterValue = $('#pendingTimeFilter').val();
                    minDate = pendingMinDate.val();
                    maxDate = pendingMaxDate.val();
                    dateStr = data[7]; // Date column is index 7
                } else {
                    filterValue = $('#allTimeFilter').val();
                    minDate = allMinDate.val();
                    maxDate = allMaxDate.val();
                    dateStr = data[6]; // Date column is index 6
                }

                let date = moment(dateStr, 'DD MMM YYYY HH:mm:ss');
                if (!date.isValid()) {
                    console.warn('Invalid date in table:', dateStr);
                    return true; // Skip invalid dates
                }

                let now = moment();

                if (filterValue === 'custom') {
                    if (minDate && maxDate) {
                        let minDateMoment = moment(minDate, 'DD MMM YYYY');
                        let maxDateMoment = moment(maxDate, 'DD MMM YYYY');
                        if (!minDateMoment.isValid() || !maxDateMoment.isValid()) {
                            console.warn('Invalid custom date range:', minDate, maxDate);
                            return true;
                        }
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

            // Initialize Pending Transactions Table
            const pendingTable = $('#pendingTransactionsTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                language: {
                    emptyTable: "No pending transactions found."
                },
                columnDefs: [
                    { orderable: false, targets: 8 }, // Disable sorting on Action column
                    { searchable: false, targets: 8 } // Disable searching on Action column
                ],
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
                    $('#pendingTotal').text(total.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                }
            });

            // Initialize All Transactions Table
            const allTable = $('#allTransactionsTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                language: {
                    emptyTable: "No transactions found."
                },
                columnDefs: [
                    { orderable: false, targets: 7 }, // Disable sorting on Action column
                    { searchable: false, targets: 7 } // Disable searching on Action column
                ],
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
                    $('#allTransactionsTotal').text(total.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                }
            });

            // Apply search to each column in Pending Transactions Table
            $('#pendingTransactionsTable thead tr:first-child th').each(function(index) {
                if (index !== 0 && index !== 8) { // Skip # and Action columns
                    $(this).find('input').on('keyup change', function() {
                        pendingTable.column(index).search(this.value).draw();
                    });
                }
            });

            // Apply search to each column in All Transactions Table
            $('#allTransactionsTable thead tr:first-child th').each(function(index) {
                if (index !== 0 && index !== 7) { // Skip # and Action columns
                    $(this).find('input').on('keyup change', function() {
                        allTable.column(index).search(this.value).draw();
                    });
                }
            });

            // Apply time filter for Pending Transactions Table
            $('#pendingTimeFilter').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#pendingDateRangeGroup').removeClass('hidden');
                } else {
                    $('#pendingDateRangeGroup').addClass('hidden');
                    pendingTable.draw();
                }
            });

            // Apply time filter for All Transactions Table
            $('#allTimeFilter').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#allDateRangeGroup').removeClass('hidden');
                } else {
                    $('#allDateRangeGroup').addClass('hidden');
                    allTable.draw();
                }
            });

            // Redraw the Pending Transactions Table when the custom date inputs change
            $('#pendingMinDate, #pendingMaxDate').on('change', function() {
                if ($('#pendingTimeFilter').val() === 'custom') {
                    pendingTable.draw();
                }
            });

            // Redraw the All Transactions Table when the custom date inputs change
            $('#allMinDate, #allMaxDate').on('change', function() {
                if ($('#allTimeFilter').val() === 'custom') {
                    allTable.draw();
                }
            });
        });

        // Function to show transaction modal
        function showTransactionModal(transactionId, amount, campanyId, venderId) {
            if (!transactionId || !amount) {
                console.error('Invalid transaction data:', { transactionId, amount, campanyId, venderId });
                alert('Error: Invalid transaction data');
                return;
            }

            // Update the amount display
            document.getElementById('transactionAmount').textContent = `Update status for transaction of Tsh ${amount}?`;

            // Update form action URLs
            const completeForm = document.getElementById('completeForm');
            const cancelForm = document.getElementById('cancelForm');
            completeForm.action = "{{ route('transactions.complete', ['transaction' => ':transaction', 'campany' => ':campany', 'vender' => ':vender']) }}".replace(':transaction', transactionId).replace(':campany', campanyId).replace(':vender', venderId);
            cancelForm.action = "{{ route('transactions.cancel', ['transaction' => ':transaction', 'campany' => ':campany', 'vender' => ':vender']) }}".replace(':transaction', transactionId).replace(':campany', campanyId).replace(':vender', venderId);

            // Clear any previous reference number input
            document.querySelector('#completeForm input[name="reference_number"]').value = '';

            // Show the modal
            document.getElementById('transactionModal').classList.remove('hidden');
        }

        // Function to close transaction modal
        function closeTransactionModal() {
            const modal = document.getElementById('transactionModal');
            const modalContent = document.getElementById('modalContent');
            const modalError = document.getElementById('modalError');
            const modalLoading = document.getElementById('modalLoading');

            modal.classList.add('hidden');
            modalError.classList.add('hidden');
            modalLoading.classList.add('hidden');
            document.getElementById('transactionAmount').textContent = 'Update status for transaction of Tsh 0?';
            document.getElementById('completeForm').action = '';
            document.getElementById('cancelForm').action = '';
        }

        // Close modal when clicking outside
        document.getElementById('transactionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransactionModal();
            }
        });

        // Debug: Log when modal is triggered
        window.addEventListener('click', function(e) {
            if (e.target.closest('button[onclick*="showTransactionModal"]')) {
                console.log('Modal open triggered');
            }
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