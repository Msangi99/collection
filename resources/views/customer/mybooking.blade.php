@extends('customer.app')

@section('content')
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-xl font-bold text-gray-800">{{ __('customer/myticket.my_ticket') }}</h1>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 text-sm rounded-md" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded-md" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table id="busTable" class="w-full table-auto text-sm text-gray-700">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-500 font-semibold">
                            <tr>
                                <th>{{ __('customer/myticket.no_booking_found') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('customer/myticket.booking_id') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('customer/busroot.price') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('customer/myticket.bus_name') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('customer/myticket.departure_date') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('customer/busroot.created_at') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('customer/myticket.status') }}</th>
                                <th class="px-4 py-3 text-left">{{ __('customer/myticket.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($booking as $book)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $book->booking_code }}</td>
                                    <td class="px-4 py-3">{{ $book->amount }}</td>
                                    <td class="px-4 py-3">{{ $book->campany->name }}</td>
                                    <td class="px-4 py-3">{{ $book->travel_date ?? '' }}</td>
                                    <td class="px-4 py-3">{{ $book->created_at }}</td>
                                    <td class="px-4 py-3">
                                        @if ($book->payment_status == 'Paid')
                                            <span
                                                class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">{{ __('customer/myticket.Paid') }}</span>
                                        @elseif($book->payment_status == 'Unpaid')
                                            <span
                                                class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">{{ __('customer/myticket.Unpaid') }}</span>
                                        @elseif ($book->payment_status == 'resaved')
                                            <span
                                                class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs">{{ __('customer/busroot.resaved_ticket') }}</span>
                                        @elseif ($book->payment_status == 'Cancel')
                                            <span
                                                class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">Cancel</span>
                                        @else
                                            <span
                                                class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">{{ __('customer/myticket.Failed') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($book->payment_status == 'Paid')
                                            <div class="relative inline-block group">
                                                <form action="{{ route('customer.cancel') }}" method="get">
                                                    @csrf
                                                    <input type="hidden" name="booking_id" value="{{ $book->id }}">
                                                    <button
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded"
                                                        title="cancel">
                                                        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414zM10 0a10 10 0 100 20 10 10 0 000-20z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <div
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    {{ __('all.cancel_title') }}
                                                </div>
                                            </div>

                                            <div class="relative inline-block group">
                                                <form action="{{ route('customer.rebook') }}" method="get"
                                                    onsubmit="return confirm('This action will delete existing one. Are you sure you want to rebook this ticket?')">
                                                    @csrf
                                                    <input type="hidden" name="order_id" value="{{ $book->id }}">
                                                    <button type="submit"
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded"
                                                        title="rebook">
                                                        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M9 5a2 2 0 00-2 2v5a2 2 0 002 2h5a2 2 0 002-2V7a2 2 0 00-2-2H9zM7.646 6.646a.5.5 0 01.708 0l3 3a.5.5 0 010 .708l-3 3a.5.5 0 01-.708-.708L10.293 10 7.646 7.354a.5.5 0 010-.708zM10 2a8 8 0 110 16 8 8 0 010-16z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <div
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    {{ __('all.rebook_title') }}
                                                </div>
                                            </div>

                                            <div class="relative inline-block group">
                                                <form action="{{ route('customer.edit', ['id' => $book->id]) }}"
                                                    method="get">
                                                    @csrf
                                                    <input type="hidden" name="booking_id" value="{{ $book->id }}">
                                                    <button
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-2 rounded"
                                                        title="edit">
                                                        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M17.414 2.586a2 2 0 00-2.828 0L3 12.5862 2 0 000-2.828zM4 14.414L9.586 9 11 10.414 5.414 16H4v-1.586z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <div
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    {{ __('all.edit_title') }}
                                                </div>
                                            </div>

                                            <div class="relative inline-block group">
                                                <form action="{{ route('ticket.print') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="data" value="{{ $book }}">
                                                    <button
                                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-2 rounded"
                                                        title="print">
                                                        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M5 1a2 2 0 00-2 2v3h14V3a2 2 0 00-2-2H5zM4 8v10h12V8H4zm4 4h4v2H8v-2z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <div
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    {{ __('all.print_title') }}
                                                </div>
                                            </div>

                                            <!-- Refund Button -->
                                            <div class="relative inline-block group">
                                                <button type="button"
                                                    class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-2 rounded"
                                                    data-bs-toggle="modal" data-bs-target="#refundModal{{ $book->id }}"
                                                    data-book-id="{{ $book->id }}"
                                                    aria-label="Request refund for booking {{ $book->id }}"
                                                    title="Refund">
                                                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10l-2.3 2.3a1 1 0 11-1.4 1.4L15 14l-5.7-5.7a1 1 0 01-1.4-1.4L4 6v4H2a2 2 0 002 2v4a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a1 1 0 110-2 1 1 0 010 2z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    Refund
                                                </div>
                                            </div>

                                            <!-- Refund Modal -->
                                            <div class="modal fade hidden" id="refundModal{{ $book->id }}"
                                                tabindex="-1" aria-labelledby="refundModalLabel{{ $book->id }}"
                                                aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
                                                <div
                                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                                                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h5 class="text-lg font-bold text-gray-800"
                                                                id="refundModalLabel{{ $book->id }}">{{ __('all.refund_title') }} Request
                                                            </h5>
                                                            <button type="button"
                                                                class="text-gray-500 hover:text-gray-700 text-2xl leading-none cursor-pointer"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close">&times;</button>
                                                        </div>
                                                        <form action="{{ route('customer.refund') }}" method="POST"
                                                            id="refundForm{{ $book->id }}" class="needs-validation"
                                                            novalidate>
                                                            @csrf
                                                            <input type="hidden" name="booking_id"
                                                                value="{{ $book->id }}">
                                                            <div class="mb-4">
                                                                <label for="fullname{{ $book->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('all.full_name') }}</label>
                                                                <input type="text"
                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                                    id="fullname{{ $book->id }}" name="fullname"
                                                                    required placeholder="{{ __('all.enter_full_name') }}">
                                                                <div
                                                                    class="text-red-600 text-xs mt-1 hidden invalid:block">
                                                                    {{ __('all.enter_full_name') }}.</div>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label for="mobile_number{{ $book->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('all.mobile_number') }}</label>
                                                                <input type="tel"
                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                                    id="mobile_number{{ $book->id }}"
                                                                    name="mobile_number" required pattern="[0-9]{10,15}" placeholder="{{ __('all.enter_mobile_number') }}">
                                                                <div
                                                                    class="text-red-600 text-xs mt-1 hidden invalid:block">
                                                                    {{ __('all.enter_mobile_number') }} (10-15 digits).</div>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label for="bank_number{{ $book->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('all.bank_account_number') }}</label>
                                                                <input type="text"
                                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                                    id="bank_number{{ $book->id }}"
                                                                    name="bank_number" required placeholder="{{ __('all.enter_bank_account_number') }}">
                                                                <div
                                                                    class="text-red-600 text-xs mt-1 hidden invalid:block">
                                                                    {{ __('all.enter_bank_account_number') }}.</div>
                                                            </div>
                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" onclick="close()" id="close"
                                                                    class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md"
                                                                    data-bs-dismiss="modal">{{ __('all.close_button') }}</button>
                                                                <button type="submit"
                                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">{{ __('all.submit_refund_request') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                function close() {
                                                    document.getElementById('refundModal{{ $book->id }}').style.display = 'none';
                                                }
                                            </script>
                                        @elseif($book->payment_status == 'Unpaid')
                                            <div class="relative inline-block group">
                                                <button
                                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-2 rounded"
                                                    title="fail">
                                                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 0a10 10 0 100 20 10 10 0 000-20zm0 11a1 1 0 110 2 1 1 0 010-2zm0-7a1 1 0 011 1v4a1 1 0 11-2 0V5a1 1 0 011-1z" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    {{ __('all.fail_title') }}
                                                </div>
                                            </div>
                                        @elseif($book->payment_status == 'resaved')
                                            <div class="flex space-x-2">
                                                <div class="relative inline-block group">
                                                    <form action="{{ route('customer.edit', ['id' => $book->id]) }}" method="get">
                                                        @csrf
                                                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-2 rounded" title="edit">
                                                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L3 12.5862 2 0 000-2.828zM4 14.414L9.586 9 11 10.414 5.414 16H4v-1.586z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        {{ __('all.edit_title') }}
                                                    </div>
                                                </div>

                                                <div class="relative inline-block group">
                                                    <form action="{{ route('customer.cancel.resaved', ['id' => $book->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this resaved ticket?')">
                                                        @csrf
                                                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded" title="cancel">
                                                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414zM10 0a10 10 0 100 20 10 10 0 000-20z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        {{ __('all.cancel_title') }}
                                                    </div>
                                                </div>

                                                <div class="relative inline-block group">
                                                    <form action="{{ route('customer.pay.resaved', ['id' => $book->id]) }}" method="get">
                                                        @csrf
                                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-2 rounded" title="pay">
                                                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 11h2v2h-2v-2zm0-8h2v6h-2V5z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        {{ __('all.pay_button') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="relative inline-block group">
                                                <button
                                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-2 rounded"
                                                    title="fail">
                                                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 0a10 10 0 100 20 10 10 0 000-20zm0 11a1 1 0 110 2 1 1 0 010-2zm0-7a1 1 0 011 1v4a1 1 0 11-2 0V5a1 1 0 011-1z" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1.5 rounded bg-gray-800 px-2 py-1.5 text-xs font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    {{ __('all.cancelled') }}
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-3 text-center">
                                        {{ __('customer/myticket.no_booking_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing modals and form validation');

            // Client-side form validation
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.querySelectorAll('input').forEach(function(input) {
                        if (!input.checkValidity()) {
                            input.nextElementSibling.classList.remove('hidden');
                        } else {
                            input.nextElementSibling.classList.add('hidden');
                        }
                    });
                    form.classList.add('was-validated');
                }, false);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#busTable').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                language: {
                    emptyTable: "{{ __('customer/myticket.no_booking_found') }}"
                }
            });
        });
    </script>
@endsection
