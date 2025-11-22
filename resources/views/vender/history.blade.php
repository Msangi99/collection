@extends('vender.app')

@section('content')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <h4 class="text-blue-600 text-center text-lg font-semibold mb-4">{{ __('vender/history.highlink_isgc') }}</h4>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Card Header -->
            <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-400 text-white flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold mb-2">{{ __('vender/history.booking_history') }}</h2>
                    <div class="flex flex-wrap gap-3 text-sm font-medium">
                        <span>{{ __('vender/history.total_payment') }} <span id="totalPayment">0</span></span>
                        <span>{{ __('vender/history.total_discount') }} <span id="totalDiscount">0</span></span>
                        <span>{{ __('vender/history.total_vat') }} <span id="totalVAT">0</span></span>
                        <span>{{ __('vender/history.grand_total') }} <span id="grandTotal">0</span></span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-2">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="text" class="px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm w-full sm:w-48" id="dateRangeFilter" placeholder="{{ __('vender/history.select_date_range') }}">
                        <button class="p-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition" id="clearDateFilter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="relative w-full sm:w-auto">
                        <button class="px-3 py-2 bg-white text-blue-500 rounded-lg hover:bg-blue-50 transition flex items-center gap-1 text-sm w-full sm:w-auto" onclick="toggleDropdown(this)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                            {{ __('vender/history.actions') }}
                        </button>
                        <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                            <form action="{{ route('admin.print.manifest') }}" method="POST" id="manifestForm">
                                @csrf
                                <input type="hidden" name="data" id="manifestData">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('vender/history.print_manifest') }}</button>
                            </form>
                            <form action="{{ route('system.print') }}" method="POST" id="incomeForm">
                                @csrf
                                <input type="hidden" name="data" id="incomeData">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('vender/history.print_income') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table id="busTable" class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left font-medium"></th>
                                @foreach (['search_booking_id', 'search_bus_route', 'search_travel_details', 'search_passenger', 'search_seats_payment', 'search_commission', 'search_total', 'search_action'] as $searchKey)
                                    <th class="py-2 px-4 text-left font-medium">
                                        <input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="{{ __('vender/history.' . $searchKey) }}">
                                    </th>
                                @endforeach
                            </tr>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left font-medium">{{ __('vender/history.sn') }}</th>
                                @foreach (['booking_id', 'bus_route', 'travel_details', 'passenger', 'seats_payment', 'commission', 'total', 'action'] as $header)
                                    <th class="py-2 px-4 text-left font-medium">{{ __('vender/history.' . $header) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-xs">
                            @if (isset($bookings) && $bookings->count())
                                @foreach ($bookings as $index => $booking)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                        <td class="py-2 px-4 text-center">{{ $index + 1 }}</td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="font-medium mb-0">{{ $booking->booking_code ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 mb-0">{{ __('vender/history.confirmed') }}</p>
                                                <p class="text-gray-500 mb-0">{{ __('vender/history.paid_time') }}: {{ $booking->created_at->format('d M Y H:i A') }}</p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <h6 class="font-medium mb-0">{{ $booking->campany->name ?? __('vender/history.na') }}</h6>
                                                <p class="text-gray-500 mb-0">{{ $booking->route_name->from ?? __('vender/history.na') }} {{ __('vender/history.route') }} {{ $booking->route_name->to ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 mb-0">{{ $booking->bus->bus_number ?? __('vender/history.na') }}</p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="font-medium mb-0 view-booking" data-id="{{ $booking->id }}" data-created-at="{{ $booking->created_at->format('Y-m-d') }}">{{ $booking->travel_date ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 mb-0">{{ __('vender/history.seat') }} {{ $booking->seat ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 mb-0">{{ __('vender/history.pickup') }} {{ $booking->pickup_point ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 mb-0">{{ __('vender/history.drop_point') }} {{ $booking->dropping_point ?? __('vender/history.na') }}</p>
                                                
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="font-medium mb-0">{{ $booking->customer_name ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 mb-0">{{ $booking->customer_phone ?? __('vender/history.na') }}</p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="text-gray-500 mb-0 payment-amount" data-amount="{{ $booking->amount ?? '0' }}" data-vat="{{ $booking->vat ?? '0' }}" data-discount="{{ $booking->discount_amount ?? '0' }}" data-fee="{{ $booking->fee ?? '0' }}" data-vender_fee="{{ $booking->vender_fee ?? '0' }}" data-fee_vat="{{ $booking->fee_vat ?? '0' }}">
                                                    {{ $booking->amount + $booking->vat ?? __('vender/history.na') }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="text-gray-500 font-medium mb-0">{{ __('vender/history.system') }} {{ $booking->fee ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 font-medium mb-0">{{ __('vender/history.vendor') }} {{ $booking->vender_fee ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 font-medium mb-0">{{ __('vender/history.discount') }} {{ $booking->discount_amount ?? __('vender/history.na') }}</p>
                                                <p class="text-gray-500 font-medium mb-0">{{ __('vender/history.vat') }} {{ $booking->vat ?? __('vender/history.na') }}</p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="text-gray-500 font-medium mb-0 total-amount" data-total="{{ round($booking->fee + $booking->vender_fee + $booking->amount + $booking->vat + $booking->fee_vat) ?? '0' }}">
                                                    {{ round($booking->fee + $booking->vender_fee + $booking->amount + $booking->vat + $booking->fee_vat) ?? __('vender/history.na') }}
                                                </p>
                                                <p class="hidden text-gray-500 font-medium mb-0">
                                                    {{ round($booking->fee + $booking->vender_fee + $booking->amount + $booking->vat + $booking->service + $booking->vender_service + $booking->fee_vat) ?? __('vender/history.na') }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="relative">
                                                <button class="px-3 py-1 bg-white text-blue-500 rounded-lg hover:bg-blue-50 transition flex items-center gap-1 text-sm" onclick="toggleDropdown(this)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                    </svg>
                                                    {{ __('vender/history.print') }}
                                                </button>
                                                <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                                                    <form action="{{ route('ticket.print') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="data" value="{{ $booking }}">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('vender/history.print_ticket') }}</button>
                                                    </form>
                                                    <form action="{{ route('print.service') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="data" value="{{ $booking }}">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('vender/history.print_service') }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="py-2 px-4 text-center text-gray-500">{{ __('vender/history.no_bookings_found') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Booking Modal -->
    <div id="viewBookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4 transform transition-all">
            <div class="p-4 flex justify-between items-center border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('vender/history.booking_details') }}</h2>
                <button type="button" class="text-gray-500 hover:text-gray-700" onclick="document.getElementById('viewBookingModal').classList.add('hidden')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4" id="modalContent">
                <!-- Dynamic content will be loaded here -->
            </div>
            <div class="p-4 flex justify-end gap-2 border-t border-gray-200">
                <button type="button" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm" onclick="document.getElementById('viewBookingModal').classList.add('hidden')">{{ __('vender/history.close') }}</button>
                <button type="button" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm print-ticket">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                    </svg>
                    {{ __('vender/history.print_ticket') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            DataTable.ext.errMode = 'none';
            const table = $('#busTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                language: {
                    emptyTable: "{{ __('vender/history.no_bookings_found') }}"
                },
                footerCallback: function() {
                    let totalPayment = 0;
                    let totalDiscount = 0;
                    let totalVAT = 0;
                    let grandTotal = 0;

                    this.api()
                        .rows({ page: 'current' })
                        .nodes()
                        .toArray()
                        .forEach(row => {
                            const paymentEl = $(row).find('.payment-amount');
                            const totalEl = $(row).find('.total-amount');
                            const amount = parseFloat(paymentEl.data('amount')) || 0;
                            const vat = parseFloat(paymentEl.data('vat')) || 0;
                            const discount = parseFloat(paymentEl.data('discount')) || 0;
                            const total = parseFloat(totalEl.data('total')) || 0;

                            totalPayment += amount + vat;
                            totalDiscount += discount;
                            totalVAT += vat;
                            grandTotal += total;
                        });

                    $('#totalPayment').text(totalPayment.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                    $('#totalDiscount').text(totalDiscount.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                    $('#totalVAT').text(totalVAT.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                    $('#grandTotal').text(grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2 }));
                }
            });

            // Initialize date range picker
            $('#dateRangeFilter').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: '{{ __('vender/history.close') }}',
                    format: 'YYYY-MM-DD',
                    separator: ' - ',
                    applyLabel: '{{ __('vender/history.apply') }}',
                    cancelLabel: '{{ __('vender/history.cancel') }}',
                    fromLabel: '{{ __('vender/history.from') }}',
                    toLabel: '{{ __('vender/history.to') }}',
                    customRangeLabel: '{{ __('vender/history.custom') }}',
                    daysOfWeek: [
                        '{{ __('vender/history.days.su') }}',
                        '{{ __('vender/history.days.mo') }}',
                        '{{ __('vender/history.days.tu') }}',
                        '{{ __('vender/history.days.we') }}',
                        '{{ __('vender/history.days.th') }}',
                        '{{ __('vender/history.days.fr') }}',
                        '{{ __('vender/history.days.sa') }}'
                    ],
                    monthNames: [
                        '{{ __('vender/history.months.january') }}',
                        '{{ __('vender/history.months.february') }}',
                        '{{ __('vender/history.months.march') }}',
                        '{{ __('vender/history.months.april') }}',
                        '{{ __('vender/history.months.may') }}',
                        '{{ __('vender/history.months.june') }}',
                        '{{ __('vender/history.months.july') }}',
                        '{{ __('vender/history.months.august') }}',
                        '{{ __('vender/history.months.september') }}',
                        '{{ __('vender/history.months.october') }}',
                        '{{ __('vender/history.months.november') }}',
                        '{{ __('vender/history.months.december') }}'
                    ],
                    firstDay: 1
                }
            });

            // Apply date range filter
            $('#dateRangeFilter').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    // Get the row element to access data-created-at attribute
                    const row = table.row(dataIndex).node();
                    const createdDateStr = $(row).find('[data-created-at]').data('created-at');
                    if (!createdDateStr) return false;

                    const createdDate = moment(createdDateStr, 'YYYY-MM-DD');
                    const startDate = picker.startDate;
                    const endDate = picker.endDate;

                    return createdDate.isValid() && createdDate.isBetween(startDate, endDate, null, '[]'); // Inclusive
                });

                table.draw();
                $.fn.dataTable.ext.search.pop(); // Remove filter after applying
            });

            // Clear date filter
            $('#dateRangeFilter').on('cancel.daterangepicker', function() {
                $(this).val('');
                table.draw();
            });

            $('#clearDateFilter').on('click', function() {
                $('#dateRangeFilter').val('');
                table.draw();
            });

            // Column-specific search
            $('#busTable thead tr:first-child th').each(function(index) {
                if (index === 0 || index === 8) return; // Skip SN and Action columns
                $(this).find('input').on('keyup change', function() {
                    table.column(index).search(this.value.trim()).draw();
                });
            });

            // Handle form submissions for filtered data
            $('#manifestForm, #incomeForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let filteredData = [];

                table.rows({ filter: 'applied' }).every(function() {
                    let row = this.data();
                    filteredData.push({
                        booking_code: ($(row[1]).find('p').first().text().trim() || '{{ __('vender/history.na') }}'),
                        company_name: ($(row[2]).find('h6').text().trim() || '{{ __('vender/history.na') }}'),
                        route_from: ($(row[2]).find('p').eq(0).text().split(' {{ __('vender/history.route') }} ')[0]?.trim() || '{{ __('vender/history.na') }}'),
                        route_to: ($(row[2]).find('p').eq(0).text().split(' {{ __('vender/history.route') }} ')[1]?.trim() || '{{ __('vender/history.na') }}'),
                        bus_number: ($(row[2]).find('p').eq(1).text().trim() || '{{ __('vender/history.na') }}'),
                        travel_date: ($(row[3]).find('[data-created-at]').data('created-at') || '{{ __('vender/history.na') }}'),
                        seat: ($(row[3]).find('p').eq(1).text().replace('{{ __('vender/history.seat') }} ', '').trim() || '{{ __('vender/history.na') }}'),
                        pickup_point: ($(row[3]).find('p').eq(2).text().replace('{{ __('vender/history.pickup') }} ', '').trim() || '{{ __('vender/history.na') }}'),
                        customer_name: ($(row[4]).find('p').first().text().trim() || '{{ __('vender/history.na') }}'),
                        customer_phone: ($(row[4]).find('p').eq(1).text().trim() || '{{ __('vender/history.na') }}'),
                        amount: ($(row[5]).find('p').first().text().trim() || '{{ __('vender/history.na') }}'),
                        commision: ($(row[6]).find('p').first().text().replace('{{ __('vender/history.system') }} ', '').trim() || '{{ __('vender/history.na') }}'),
                        service: ($(row[6]).find('p').eq(1).text().replace('{{ __('vender/history.vendor') }} ', '').trim() || '{{ __('vender/history.na') }}'),
                        discount: ($(row[6]).find('p').eq(2).text().replace('{{ __('vender/history.discount') }} ', '').trim() || '{{ __('vender/history.na') }}'),
                        vat: ($(row[6]).find('p').eq(3).text().replace('{{ __('vender/history.vat') }} ', '').trim() || '{{ __('vender/history.na') }}'),
                        total: (function() {
                            // Calculate total from the data attributes
                            let paymentEl = $(row[5]).find('.payment-amount');
                            let totalEl = $(row[7]).find('.total-amount');
                            let amount = parseFloat(paymentEl.data('amount')) || 0;
                            let vat = parseFloat(paymentEl.data('vat')) || 0;
                            let discount = parseFloat(paymentEl.data('discount')) || 0;
                            let fee = parseFloat(paymentEl.data('fee')) || 0;
                            let vender_fee = parseFloat(paymentEl.data('vender_fee')) || 0;
                            let fee_vat = parseFloat(paymentEl.data('fee_vat')) || 0;
                            
                            // Calculate total: amount + vat + fee + vender_fee + fee_vat - discount
                            let calculatedTotal = amount + vat + fee + vender_fee + fee_vat - discount;
                            return calculatedTotal.toFixed(2);
                        })()
                    });
                });

                form.find('input[name="data"]').val(JSON.stringify(filteredData));
                form.off('submit').submit(); // Prevent infinite loop and submit
            });

            // View booking details
            $(document).on('click', '.view-booking', function() {
                const bookingId = $(this).data('id');
                $.ajax({
                    url: '{{ route('history.show', ':id') }}'.replace(':id', bookingId),
                    method: 'GET',
                    success: function(response) {
                        $('#modalContent').html(response.html);
                        document.getElementById('viewBookingModal').classList.remove('hidden');
                    },
                    error: function(xhr) {
                        console.error('Error fetching booking details:', xhr);
                    }
                });
            });

            // Close modal when clicking outside
            document.getElementById('viewBookingModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });

        // Toggle dropdown
        function toggleDropdown(button) {
            const dropdown = button.nextElementSibling;
            dropdown.classList.toggle('hidden');
            document.addEventListener('click', function closeDropdown(e) {
                if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }
    </script>

    <style>
        .search-input {
            width: 100%;
            padding: 4px;
            font-size: 12px;
            border-radius: 4px;
        }
        .daterangepicker {
            z-index: 9999 !important;
        }
        #dateRangeFilter {
            min-width: 150px;
            text-align: center;
        }
        @media (max-width: 640px) {
            #dateRangeFilter {
                min-width: 100%;
            }
        }
    </style>
@endsection