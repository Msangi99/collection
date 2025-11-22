@extends('vender.app')

@section('content')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- DataTables DateTime CSS -->
    <link href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">{{ __('assistance/transaction.transactions') }}</h1>

        <!-- Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all hover:shadow-xl">
                <h3 class="text-lg font-semibold text-gray-700 text-center">{{ __('assistance/transaction.balance') }}</h3>
                <p class="text-2xl font-bold text-gray-900 text-center mt-2">Tsh. {{ number_format(auth()->user()->VenderBalances->amount ?? 0, 2) }}</p>
                <div class="mt-4 text-center">
                    <a href="{{ route('vender.wallet.deposit') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        deposit
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all hover:shadow-xl">
                <h3 class="text-lg font-semibold text-gray-700 text-center">{{ __('assistance/transaction.pending') }}</h3>
                <p class="text-2xl font-bold text-gray-900 text-center mt-2">Tsh. {{ number_format($pending) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all hover:shadow-xl">
                <h3 class="text-lg font-semibold text-gray-700 text-center">{{ __('assistance/transaction.withdrawn') }}</h3>
                <p class="text-2xl font-bold text-gray-900 text-center mt-2">Tsh. {{ number_format($accept) }}</p>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 text-center mb-4">{{ __('assistance/transaction.transaction_history') }}</h3>
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button id="openTransactionModal" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center">
                        <i class="bi bi-plus-circle mr-2"></i> {{ __('assistance/transaction.request_transaction') }}
                    </button>
                    <div class="flex items-center gap-2">
                        <select id="timeFilter" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                            <option value="all">{{ __('assistance/transaction.all_time') }}</option>
                            <option value="day">{{ __('assistance/transaction.today') }}</option>
                            <option value="week">{{ __('assistance/transaction.this_week') }}</option>
                            <option value="month">{{ __('assistance/transaction.this_month') }}</option>
                            <option value="year">{{ __('assistance/transaction.this_year') }}</option>
                            <option value="custom">{{ __('assistance/transaction.custom_range') }}</option>
                        </select>
                        <div id="dateRangeGroup" class="hidden flex items-center gap-2">
                            <input type="text" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" id="minDate" placeholder="{{ __('assistance/transaction.search_date') }}">
                            <span class="text-gray-500">{{ __('assistance/transaction.to') }}</span>
                            <input type="text" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" id="maxDate" placeholder="{{ __('assistance/transaction.search_date') }}">
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-64">
                    <input type="text" id="tableSearch" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" placeholder="{{ __('assistance/transaction.search_all_columns') }}">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600">{{ __('assistance/transaction.rows') }}</span>
                    <select id="rowsPerPage" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-auto text-left text-gray-700" id="transactionsTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 font-semibold">{{ __('assistance/transaction.number') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('assistance/transaction.vender') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('assistance/transaction.payment_method') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('assistance/transaction.date') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('assistance/transaction.amount') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('assistance/transaction.reference_no') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('assistance/transaction.status') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('assistance/transaction.action') }}</th>
                        </tr>
                        <tr class="bg-gray-50">
                            <th></th>
                            <th class="px-4 py-2"><input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" data-column="1" placeholder="{{ __('assistance/transaction.search_name') }}"></th>
                            <th class="px-4 py-2"><input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" data-column="2" placeholder="{{ __('assistance/transaction.search_method') }}"></th>
                            <th class="px-4 py-2"><input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" data-column="3" placeholder="{{ __('assistance/transaction.search_date') }}"></th>
                            <th class="px-4 py-2"><input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" data-column="4" placeholder="{{ __('assistance/transaction.search_amount') }}"></th>
                            <th class="px-4 py-2"><input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" data-column="5" placeholder="{{ __('assistance/transaction.search_transaction_id') }}"></th>
                            <th class="px-4 py-2"><input type="text" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" data-column="6" placeholder="{{ __('assistance/transaction.search_status') }}"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @if ($coll->count() > 0)
                            @foreach ($coll as $data)
                                <tr class="data-row hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 row-index"></td>
                                    <td class="px-4 py-3">{{ $data->user->name }}</td>
                                    <td class="px-4 py-3">{{ $data->payment_method }}</td>
                                    <td class="px-4 py-3" data-date="{{ $data->created_at->format('Y-m-d') }}">{{ $data->created_at }}</td>
                                    <td class="px-4 py-3">{{ $data->amount }}</td>
                                    <td class="px-4 py-3">{{ $data->reference_number ?? __('assistance/transaction.na') }}</td>
                                    <td class="px-4 py-3">{{ $data->status }}</td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('print.recipt2') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="data" value="{{ $data }}">
                                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition-colors flex items-center">
                                                <i class="bi bi-receipt mr-2"></i> {{ __('assistance/transaction.print') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center py-4 text-gray-500">{{ __('assistance/transaction.no_transactions_found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-center">
                <ul class="data-table-pagination flex gap-2" id="paginationControls"></ul>
            </div>
        </div>
    </div>

    <!-- Request Transaction Modal -->
    <div id="requestTransactionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h5 class="text-lg font-semibold text-gray-800">{{ __('assistance/transaction.request_transaction_title') }}</h5>
                <button id="closeTransactionModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <form id="requestTransactionForm" action="{{ route('vender.transaction.request') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700">{{ __('assistance/transaction.amount_tsh') }}</label>
                        <input type="number" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" 
                               placeholder="{{ __('assistance/transaction.max_amount', ['amount' => number_format(auth()->user()->VenderBalances->amount ?? 0, 2, '.', ',')]) }}" 
                               id="amount" name="amount" step="0.01" min="1" max="{{ auth()->user()->VenderBalances->amount ?? 0 }}" required>
                        @error('amount')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">{{ __('assistance/transaction.payment_method') }}</label>
                        <select class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" id="payment_method" name="payment_method" required>
                            <option value="" disabled selected>{{ __('assistance/transaction.select_payment_method') }}</option>
                            <option value="MPesa">MPesa</option>
                            <option value="AirtelMoney">Airtel-money</option>
                            <option value="MixxBYYass">Mixx BY Yass</option>
                            <option value="Halopesa">Halopesa</option>
                            <option value="bank">Bank</option>
                        </select>
                        @error('payment_method')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="payment_number" class="block text-sm font-medium text-gray-700">{{ __('assistance/transaction.payment_number') }}</label>
                        <input type="text" name="payment_number" class="mt-1 w-full border border-gray-300 rounded-lg p-2 bg-gray-100" 
                               readonly value="{{ auth()->user()->VenderBalances->payment_number ?? __('assistance/transaction.na') }}" required>
                        @error('payment_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end gap-4">
                <button id="closeTransactionModalFooter" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">{{ __('assistance/transaction.close') }}</button>
                <button type="submit" form="requestTransactionForm" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">{{ __('assistance/transaction.submit_request') }}</button>
            </div>
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Moment.js for date handling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <!-- DataTables DateTime plugin -->
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>

    <script>
        $(document).ready(function() {
            // Modal toggle functionality
            const modal = document.getElementById('requestTransactionModal');
            const openModalBtn = document.getElementById('openTransactionModal');
            const closeModalBtn = document.getElementById('closeTransactionModal');
            const closeModalFooterBtn = document.getElementById('closeTransactionModalFooter');

            // Open modal
            openModalBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.querySelector('input#amount').focus();
            });

            // Close modal
            function closeModal() {
                modal.classList.add('hidden');
                document.getElementById('requestTransactionForm').reset();
            }

            closeModalBtn.addEventListener('click', closeModal);
            closeModalFooterBtn.addEventListener('click', closeModal);

            // Close modal on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Form submission handling with AJAX
            $('#requestTransactionForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const submitButton = form.find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Submitting...');

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        alert('Transaction request submitted successfully!');
                        closeModal();
                        window.location.reload();
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        alert(errorMessage);
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Submit Request');
                    }
                });
            });

            // Form validation (client-side)
            $('#amount').on('input', function() {
                const maxAmount = parseFloat($(this).attr('max'));
                const value = parseFloat($(this).val());
                if (value > maxAmount) {
                    $(this).val(maxAmount);
                    alert('Amount cannot exceed available balance.');
                }
            });

            // Create date inputs
            var minDate = new DateTime($('#minDate'), {
                format: 'DD MMM YYYY'
            });
            var maxDate = new DateTime($('#maxDate'), {
                format: 'DD MMM YYYY'
            });

            // Initialize variables
            let currentPage = 1;
            let rowsPerPage = parseInt($('#rowsPerPage').val());
            let allRows = $('.data-row');
            let filteredRows = allRows;

            // Custom date filtering function
            function dateFilter(row) {
                let filterValue = $('#timeFilter').val();
                let dateStr = $(row).find('td[data-date]').data('date');
                let date = moment(dateStr, 'YYYY-MM-DD');

                if (!date.isValid()) return true; // Skip invalid dates

                let now = moment();

                if (filterValue === 'custom') {
                    let min = minDate.val();
                    let max = maxDate.val();

                    if (min && max) {
                        let minDateMoment = moment(min, 'DD MMM YYYY');
                        let maxDateMoment = moment(max, 'DD MMM YYYY');
                        return date.isBetween(minDateMoment, maxDateMoment, null, '[]');
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
            }

            // Initialize the table
            function initTable() {
                updateRowIndices();
                renderTable();
                renderPagination();
            }

            // Update row indices (1, 2, 3, etc.)
            function updateRowIndices() {
                $('.row-index').each(function(index) {
                    $(this).text(index + 1);
                });
            }

            // Render the current page of rows
            function renderTable() {
                allRows.hide();
                let dateFilteredRows = allRows.filter(function() {
                    return dateFilter(this);
                });

                filteredRows = dateFilteredRows.filter(function() {
                    const row = $(this);
                    let rowMatches = true;

                    $('.column-search-input').each(function() {
                        const columnIndex = $(this).data('column');
                        const searchTerm = $(this).val().toLowerCase();

                        if (searchTerm) {
                            const cellText = row.find('td').eq(columnIndex).text().toLowerCase();
                            if (!cellText.includes(searchTerm)) {
                                rowMatches = false;
                                return false;
                            }
                        }
                    });

                    const globalSearchTerm = $('#tableSearch').val().toLowerCase();
                    if (globalSearchTerm) {
                        let found = false;
                        row.find('td').each(function() {
                            const cellText = $(this).text().toLowerCase();
                            if (cellText.includes(globalSearchTerm)) {
                                found = true;
                                return false;
                            }
                        });
                        if (!found) rowMatches = false;
                    }

                    return rowMatches;
                });

                const startIndex = (currentPage - 1) * rowsPerPage;
                const endIndex = startIndex + rowsPerPage;
                filteredRows.slice(startIndex, endIndex).show();
                updateRowIndices();
                highlightSearchTerms();
            }

            // Highlight search terms in the table
            function highlightSearchTerms() {
                $('span.highlight').each(function() {
                    $(this).replaceWith($(this).text());
                });

                const globalSearchTerm = $('#tableSearch').val().toLowerCase();
                if (globalSearchTerm) {
                    filteredRows.find('td').each(function() {
                        const cellText = $(this).text().toLowerCase();
                        if (cellText.includes(globalSearchTerm)) {
                            const regex = new RegExp(globalSearchTerm, 'gi');
                            $(this).html($(this).text().replace(regex,
                                match => `<span class="bg-yellow-200">${match}</span>`));
                        }
                    });
                }

                $('.column-search-input').each(function() {
                    const columnIndex = $(this).data('column');
                    const searchTerm = $(this).val().toLowerCase();

                    if (searchTerm) {
                        filteredRows.find('td').filter(function() {
                            return $(this).index() === columnIndex;
                        }).each(function() {
                            const cellText = $(this).text().toLowerCase();
                            if (cellText.includes(searchTerm)) {
                                const regex = new RegExp(searchTerm, 'gi');
                                $(this).html($(this).text().replace(regex,
                                    match => `<span class="bg-yellow-200">${match}</span>`));
                            }
                        });
                    }
                });
            }

            // Render pagination controls
            function renderPagination() {
                $('#paginationControls').empty();
                const pageCount = Math.ceil(filteredRows.length / rowsPerPage);

                if (pageCount <= 1) return;

                const prevLi = $('<li>').addClass(currentPage === 1 ? 'opacity-50 cursor-not-allowed' : '')
                    .html('<a class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100">«</a>')
                    .click(function() {
                        if (currentPage > 1) {
                            currentPage--;
                            renderTable();
                            renderPagination();
                        }
                    });
                $('#paginationControls').append(prevLi);

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

                if (startPage > 1) {
                    const firstLi = $('<li>').html('<a class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100">1</a>')
                        .click(function() {
                            currentPage = 1;
                            renderTable();
                            renderPagination();
                        });
                    $('#paginationControls').append(firstLi);

                    if (startPage > 2) {
                        const ellipsisLi = $('<li>').addClass('opacity-50 cursor-not-allowed').html('<a class="px-3 py-1">...</a>');
                        $('#paginationControls').append(ellipsisLi);
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageLi = $('<li>').addClass(i === currentPage ? 'bg-blue-500 text-white' : '')
                        .html(`<a class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100">${i}</a>`)
                        .click(function() {
                            currentPage = i;
                            renderTable();
                            renderPagination();
                        });
                    $('#paginationControls').append(pageLi);
                }

                if (endPage < pageCount) {
                    if (endPage < pageCount - 1) {
                        const ellipsisLi = $('<li>').addClass('opacity-50 cursor-not-allowed').html('<a class="px-3 py-1">...</a>');
                        $('#paginationControls').append(ellipsisLi);
                    }

                    const lastLi = $('<li>').html('<a class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100">${pageCount}</a>')
                        .click(function() {
                            currentPage = pageCount;
                            renderTable();
                            renderPagination();
                        });
                    $('#paginationControls').append(lastLi);
                }

                const nextLi = $('<li>').addClass(currentPage === pageCount ? 'opacity-50 cursor-not-allowed' : '')
                    .html('<a class="px-3 py-1 border border-gray-300 rounded-lg hover:bg-gray-100">»</a>')
                    .click(function() {
                        if (currentPage < pageCount) {
                            currentPage++;
                            renderTable();
                            renderPagination();
                        }
                    });
                $('#paginationControls').append(nextLi);
            }

            // Global search functionality
            $('#tableSearch').on('keyup', function() {
                currentPage = 1;
                renderTable();
                renderPagination();
            });

            // Column-specific search functionality
            $('.column-search-input').on('keyup', function() {
                currentPage = 1;
                renderTable();
                renderPagination();
            });

            // Rows per page change
            $('#rowsPerPage').on('change', function() {
                rowsPerPage = parseInt($(this).val());
                currentPage = 1;
                renderTable();
                renderPagination();
            });

            // Time filter change
            $('#timeFilter').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#dateRangeGroup').show();
                } else {
                    $('#dateRangeGroup').hide();
                    currentPage = 1;
                    renderTable();
                    renderPagination();
                }
            });

            // Date range change
            $('#minDate, #maxDate').on('change', function() {
                if ($('#timeFilter').val() === 'custom') {
                    currentPage = 1;
                    renderTable();
                    renderPagination();
                }
            });

            // Initialize the table
            initTable();
        });
    </script>
@endsection