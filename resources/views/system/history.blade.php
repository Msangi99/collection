@extends('system.app')

@section('content')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <h4 class="text-blue-600 text-center text-lg font-semibold mb-4">HIGHLINK ISGC</h4>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Card Header -->
            <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-400 text-white flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold mb-2">Booking History</h2>
                    <div class="flex flex-wrap gap-3 text-sm font-medium">
                        <span>Total Payment: Tsh <span id="totalPayment">0</span></span>
                        <span>Total Discount: Tsh <span id="totalDiscount">0</span></span>
                        <span>Total VAT: Tsh <span id="totalVAT">0</span></span>
                        <span>Grand Total: Tsh <span id="grandTotal">0</span></span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-2">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="text" class="px-3 py-2 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm w-full sm:w-48" id="dateRangeFilter" placeholder="Select date range">
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
                            Actions
                        </button>
                        <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                            <form action="{{ route('admin.print.manifest') }}" method="POST" id="manifestForm">
                                @csrf
                                <input type="hidden" name="data" id="manifestData">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print Manifest</button>
                            </form>
                            <form action="{{ route('system.print') }}" method="POST" id="incomeForm">
                                @csrf
                                <input type="hidden" name="data" id="incomeData">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print Income</button>
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
                                @foreach (['Booking ID', 'Bus/Route', 'Travel Details', 'Passenger', 'Seats Payment', 'Commision', 'Total', 'Action'] as $placeholder)
                                    <th class="py-2 px-4 text-left font-medium">
                                        <input type="text" class="w-full px-2 py-1 border rounded text-xs search-input" placeholder="Search {{ $placeholder }}">
                                    </th>
                                @endforeach
                            </tr>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                <th class="py-2 px-4 text-left font-medium">SN</th>
                                @foreach (['Booking ID', 'Bus/Route', 'Travel Details', 'Passenger', 'Seats Payment', 'Commision', 'Total', 'Action'] as $header)
                                    <th class="py-2 px-4 text-left font-medium">{{ $header }}</th>
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
                                                <p class="font-medium mb-0">{{ $booking->booking_code ?? 'N/A' }}</p>
                                                <p class="text-gray-500 mb-0">Confirmed</p>
                                                <p class="text-gray-500 mb-0">Pay Time: {{ $booking->created_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <h6 class="font-medium mb-0">{{ $booking->campany->name ?? 'N/A' }}</h6>
                                                <p class="text-gray-500 mb-0">{{ $booking->route_name->from ?? 'N/A' }} to {{ $booking->route_name->to ?? 'N/A' }}</p>
                                                <p class="text-gray-500 mb-0">{{ $booking->bus->bus_number ?? 'N/A' }}</p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="font-medium mb-0 view-booking" data-id="{{ $booking->id }}" data-created-at="{{ $booking->created_at->format('Y-m-d') }}">{{ $booking->travel_date ?? 'N/A' }}</p>
                                                <p class="text-gray-500 mb-0">Seat: {{ $booking->seat ?? 'N/A' }}</p>
                                                <p class="text-gray-500 mb-0">Pickup: {{ $booking->pickup_point ?? 'N/A' }}</p>
                                                <p class="text-gray-500 mb-0">Drop-point: {{ $booking->dropping_point ?? 'N/A' }}</p>
                                               
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="font-medium mb-0">{{ $booking->customer_name ?? 'N/A' }}</p>
                                                <p class="text-gray-500 mb-0">{{ $booking->customer_phone ?? 'N/A' }}</p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="text-gray-500 mb-0 payment-amount" data-amount="{{ $booking->amount ?? '0' }}" data-vat="{{ $booking->vat ?? '0' }}" data-discount="{{ $booking->discount_amount ?? '0' }}" data-fee="{{ $booking->fee ?? '0' }}" data-vender_fee="{{ $booking->vender_fee ?? '0' }}" data-fee_vat="{{ $booking->fee_vat ?? '0' }}">
                                                    {{ $booking->amount + $booking->vat ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="text-gray-500 font-medium mb-0">System: {{ $booking->fee ?? 'N/A' }}</p>
                                                <p class="text-gray-500 font-medium mb-0">Vendor: {{ $booking->vender_fee ?? 'N/A' }}</p>
                                                <p class="text-gray-500 font-medium mb-0">Discount: {{ $booking->discount_amount ?? 'N/A' }}</p>
                                                <p class="text-gray-500 font-medium mb-0">Levies: {{ $booking->vat ?? 'N/A' }}</p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="flex flex-col">
                                                <p class="text-gray-500 font-medium mb-0 total-amount" data-total="{{ round($booking->fee + $booking->vender_fee + $booking->amount + $booking->vat + $booking->fee_vat) ?? '0' }}">
                                                    {{ round($booking->fee + $booking->vender_fee + $booking->amount + $booking->vat + $booking->fee_vat) ?? 'N/A' }}
                                                </p>
                                                <p class="hidden text-gray-500 font-medium mb-0">
                                                    {{ round($booking->fee + $booking->vender_fee + $booking->amount + $booking->vat + $booking->service + $booking->vender_service + $booking->fee_vat) ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4">
                                            <div class="relative">
                                                <button class="px-3 py-1 bg-white text-blue-500 rounded-lg hover:bg-blue-50 transition flex items-center gap-1 text-sm" onclick="toggleDropdown(this)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                    </svg>
                                                    Print
                                                </button>
                                                <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                                                    <form action="{{ route('ticket.print') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="data" value="{{ $booking }}">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print Ticket</button>
                                                    </form>
                                                    <form action="{{ route('print.service') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="data" value="{{ $booking }}">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print Service</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="py-2 px-4 text-center text-gray-500">No bookings found.</td>
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
                <h2 class="text-lg font-semibold text-gray-800">Booking Details</h2>
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
                <button type="button" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm" onclick="document.getElementById('viewBookingModal').classList.add('hidden')">Close</button>
                <button type="button" class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm print-ticket">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                    </svg>
                    Print Ticket
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
                    emptyTable: "No bookings found."
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
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD',
                    separator: ' - ',
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
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
                        booking_code: ($(row[1]).find('p').first().text().trim() || 'N/A'),
                        company_name: ($(row[2]).find('h6').text().trim() || 'N/A'),
                        route_from: ($(row[2]).find('p').eq(0).text().split(' to ')[0]?.trim() || 'N/A'),
                        route_to: ($(row[2]).find('p').eq(0).text().split(' to ')[1]?.trim() || 'N/A'),
                        bus_number: ($(row[2]).find('p').eq(1).text().trim() || 'N/A'),
                        travel_date: ($(row[3]).find('[data-created-at]').data('created-at') || 'N/A'),
                        seat: ($(row[3]).find('p').eq(1).text().replace('Seat: ', '').trim() || 'N/A'),
                        pickup_point: ($(row[3]).find('p').eq(2).text().replace('Pickup: ', '').trim() || 'N/A'),
                        customer_name: ($(row[4]).find('p').first().text().trim() || 'N/A'),
                        customer_phone: ($(row[4]).find('p').eq(1).text().trim() || 'N/A'),
                        amount: ($(row[5]).find('p').first().text().trim() || 'N/A'),
                        commision: ($(row[6]).find('p').first().text().replace('System: ', '').trim() || 'N/A'),
                        service: ($(row[6]).find('p').eq(1).text().replace('Vendor: ', '').trim() || 'N/A'),
                        discount: ($(row[6]).find('p').eq(2).text().replace('Discount: ', '').trim() || 'N/A'),
                        vat: ($(row[6]).find('p').eq(3).text().replace('VAT: ', '').trim() || 'N/A'),
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