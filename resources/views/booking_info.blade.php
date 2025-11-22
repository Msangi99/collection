@extends('test.ap')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3 tracking-tight">{{ __('all.your_booking_details') }}</h1>
                <p class="text-gray-600 max-w-lg mx-auto">{{ __('all.view_manage_travel_bookings') }}</p>
            </div>

            <!-- Search Form -->
            <div
                class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden p-1 mb-12 transition-all duration-300 hover:shadow-lg">
                <form action="{{ route('booking_info') }}" method="post" class="flex items-center">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="data"
                            class="w-full py-3 px-4 border-0 focus:ring-0 focus:outline-none text-gray-700 placeholder-gray-400"
                            placeholder="{{ __('all.enter_email_phone_number') }}" required>
                    </div>
                    <button type="submit"
                        class="ml-2 bg-gradient-to-r from-blue-600 to-blue-800 text-white py-3 px-6 rounded-lg font-medium transition-all duration-300 hover:shadow-md transform hover:-translate-y-0.5">
                        {{ __('all.search_button') }}
                    </button>
                </form>
            </div>

            <!-- Bookings Display -->
            <div class="overflow-x-auto">
                <table id="busTable" class="w-full table-auto text-sm text-gray-700">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <tr>
                            <th class="px-4 py-3">{{ __('customer/myticket.no_booking_found') }}</th>
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
                        @forelse ($bookings as $book)
                            <tr>
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $book->booking_code }}</td>
                                <td class="px-4 py-3">{{ $book->amount }}</td>
                                <td class="px-4 py-3">{{ $book->campany->name }}</td>
                                <td class="px-4 py-3">
                                    {{ $book->travel_date ? \Carbon\Carbon::parse($book->travel_date)->format('D, M d, Y') : __('all.not_available_short') }}
                                </td>
                                <td class="px-4 py-3">{{ $book->created_at }}</td>
                                <td class="px-4 py-3">
                                    @if ($book->payment_status == 'Paid')
                                        <span
                                            class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">{{ __('customer/myticket.Paid') }}</span>
                                    @elseif($book->payment_status == 'Unpaid')
                                        <span
                                            class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">{{ __('customer/myticket.Unpaid') }}</span>
                                    @elseif ($book->payment_status == 'Cancel')
                                        <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">{{ __('all.cancel_button') }}</span>
                                    @else
                                        <span
                                            class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">{{ __('customer/myticket.Failed') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex space-x-2">
                                    @if ($book->payment_status == 'Paid')
                                        <!-- Cancel Button and Modal -->
                                        <div x-data="{ openCancelModal: false }">
                                            <button @click="openCancelModal = true"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded"
                                                title="{{ __('all.cancel_title') }}">
                                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414zM10 0a10 10 0 100 20 10 10 0 000-20z" />
                                                </svg>
                                            </button>
                                            <div x-show="openCancelModal" class="fixed inset-0 overflow-y-auto"
                                                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                <div
                                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                                        aria-hidden="true"></div>
                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                        aria-hidden="true">&#8203;</span>
                                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                                        @click.away="openCancelModal = false">
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="sm:flex sm:items-start">
                                                                <div
                                                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                    <svg class="h-6 w-6 text-red-600"
                                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor"
                                                                        aria-hidden="true">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3l-7.48-8.22c-.663-.728-1.705-.728-2.368 0L1.938 15c-.77 1.333.154 3 1.732 3z" />
                                                                    </svg>
                                                                </div>
                                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900"
                                                                        id="modal-title">{{ __('all.cancel_button') }}</h3>
                                                                    <div class="mt-2">
                                                                        <p class="text-sm text-gray-500">
                                                                            {{ __('all.cancel_booking_confirmation') }}
                                                                        </p>
                                                                    </div>
                                                                    <form action="{{ route('cancel') }}" method="post"
                                                                        class="mt-4">
                                                                        @csrf
                                                                        <input type="hidden" name="booking_id"
                                                                            value="{{ $book->id }}">
                                                                        <input type="text" name="key"
                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                            placeholder="{{ __('all.enter_your_key') }}" required>
                                                                        <button type="submit"
                                                                            class="mt-4 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                                                                            {{ __('all.cancel_booking_action') }}
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="button"
                                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                                @click="openCancelModal = false">
                                                                {{ __('all.close_button') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Button -->
                                        <div class="relative inline-block">
                                            <form action="{{ route('booking.edit', ['id' => $book->id]) }}"
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
                                        </div>

                                        <!-- Print Button -->
                                        <div class="relative inline-block">
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
                                        </div>
                                    @elseif($book->payment_status == 'Unpaid')
                                        <div class="relative inline-block">
                                            <button
                                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-2 rounded"
                                                title="fail">
                                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 0a10 10 0 100 20 10 10 0 000-20zm0 11a1 1 0 110 2 1 1 0 010-2zm0-7a1 1 0 011 1v4a1 1 0 11-2 0V5a1 1 0 011-1z" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="relative inline-block">
                                            <button
                                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-2 rounded"
                                                title="fail">
                                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 0a10 10 0 100 20 10 10 0 000-20zm0 11a1 1 0 110 2 1 1 0 010-2zm0-7a1 1 0 011 1v4a1 1 0 11-2 0V5a1 1 0 011-1z" />
                                                </svg>
                                            </button>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#busTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "columnDefs": [{
                        "orderable": false,
                        "targets": 7
                    } // Disable sorting on Actions column
                ]
            });
        });
    </script>
@endsection
