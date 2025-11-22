@extends('admin.app') {{-- Assuming a common layout --}}

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="flex justify-center">
        <div class="w-full max-w-2xl"> {{-- Adjusted width for the card --}}
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 bg-gradient-to-r from-teal-500 to-teal-400 text-white text-lg font-semibold">Transfer Booking</div>

                <div class="p-4">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('booking.transfer') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="booking_id" class="block text-sm font-medium text-gray-700">Select Booking to Transfer:</label>
                            <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="booking_id" name="booking_id" required onchange="
                                const selectedBookingId = this.value;
                                if (selectedBookingId) {
                                    const url = '{{ route('booking.transfer.form') }}?booking_id=' + selectedBookingId;
                                    console.log('Redirecting to:', url);
                                    window.location.href = url;
                                } else {
                                    console.log('No booking selected, not redirecting.');
                                }
                            ">
                                <option value="">-- Select Booking --</option>
                                @foreach ($bookings as $booking)
                                    <option value="{{ $booking->id }}" {{ $selectedBooking && $selectedBooking->id == $booking->id ? 'selected' : '' }}>
                                        {{ $booking->booking_code }} - {{ $booking->customer_name }} ({{ $booking->bus->busname->name ?? 'N/A' }} - {{ $booking->route_name->from ?? 'N/A' }} to {{ $booking->route_name->to ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($selectedBooking)
                            <hr class="my-4 border-gray-300">
                            <h5 class="text-lg font-semibold mb-2">Current Booking Details:</h5>
                            <p class="text-gray-700 mb-1"><strong>Booking Code:</strong> {{ $selectedBooking->booking_code }}</p>
                            <p class="text-gray-700 mb-1"><strong>Passenger Name:</strong> {{ $selectedBooking->customer_name }}</p>
                            <p class="text-gray-700 mb-1"><strong>Current Bus:</strong> {{ $selectedBooking->bus->bus_number ?? 'N/A' }} ({{ $selectedBooking->bus->busname->name ?? 'N/A' }})</p>
                            <p class="text-gray-700 mb-1"><strong>Current Route:</strong> {{ $selectedBooking->route_name->from ?? 'N/A' }} to {{ $selectedBooking->route_name->to ?? 'N/A' }}</p>
                            <p class="text-gray-700 mb-1"><strong>Current Travel Date:</strong> {{ $selectedBooking->travel_date }}</p>
                            <p class="text-gray-700 mb-1"><strong>Current Seat:</strong> {{ $selectedBooking->seat }}</p>
                            <p class="text-gray-700 mb-4"><strong>Current Amount:</strong> {{ $selectedBooking->amount }}</p>
                            <hr class="my-4 border-gray-300">

                            <div class="mb-4">
                                <label for="new_bus_id" class="block text-sm font-medium text-gray-700">New Bus:</label>
                                <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_bus_id" name="new_bus_id" required>
                                    <option value="">-- Select New Bus --</option>
                                    @foreach ($buses as $bus)
                                        <option value="{{ $bus->id }}">{{ $bus->bus_number }} ({{ $bus->busname->name ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="new_schedule_id" class="block text-sm font-medium text-gray-700">New Schedule:</label>
                                <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_schedule_id" name="new_schedule_id" required>
                                    <option value="">-- Select New Schedule --</option>
                                    @foreach ($schedules as $schedule)
                                        <option value="{{ $schedule->id }}">
                                            {{ $schedule->from }} to {{ $schedule->to }} on {{ $schedule->schedule_date }} ({{ $schedule->start }} - {{ $schedule->end }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="new_travel_date" class="block text-sm font-medium text-gray-700">New Travel Date:</label>
                                <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_travel_date" name="new_travel_date" required value="{{ $selectedBooking->travel_date }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_pickup_point" class="block text-sm font-medium text-gray-700">New Pickup Point:</label>
                                <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_pickup_point" name="new_pickup_point" required value="{{ $selectedBooking->pickup_point }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_dropping_point" class="block text-sm font-medium text-gray-700">New Dropping Point:</label>
                                <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_dropping_point" name="new_dropping_point" required value="{{ $selectedBooking->dropping_point }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_amount" class="block text-sm font-medium text-gray-700">New Amount:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_amount" name="new_amount" required value="{{ $selectedBooking->amount }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_busFee" class="block text-sm font-medium text-gray-700">New Bus Fee:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_busFee" name="new_busFee" required value="{{ $selectedBooking->busFee }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_discount_amount" class="block text-sm font-medium text-gray-700">New Discount Amount:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_discount_amount" name="new_discount_amount" required value="{{ $selectedBooking->discount_amount }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_distance" class="block text-sm font-medium text-gray-700">New Distance:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_distance" name="new_distance" required value="{{ $selectedBooking->distance }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_bima_amount" class="block text-sm font-medium text-gray-700">New Bima Amount:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_bima_amount" name="new_bima_amount" required value="{{ $selectedBooking->bima_amount }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_vat" class="block text-sm font-medium text-gray-700">New VAT:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_vat" name="new_vat" required value="{{ $selectedBooking->vat }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_fee" class="block text-sm font-medium text-gray-700">New Fee:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_fee" name="new_fee" required value="{{ $selectedBooking->fee }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_service" class="block text-sm font-medium text-gray-700">New Service:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_service" name="new_service" required value="{{ $selectedBooking->service }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_vender_fee" class="block text-sm font-medium text-gray-700">New Vender Fee:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_vender_fee" name="new_vender_fee" required value="{{ $selectedBooking->vender_fee }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_vender_service" class="block text-sm font-medium text-gray-700">New Vender Service:</label>
                                <input type="number" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_vender_service" name="new_vender_service" required value="{{ $selectedBooking->vender_service }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_campany_id" class="block text-sm font-medium text-gray-700">New Company ID:</label>
                                <input type="number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_campany_id" name="new_campany_id" required value="{{ $selectedBooking->campany_id }}">
                            </div>

                            <div class="mb-4">
                                <label for="new_route_id" class="block text-sm font-medium text-gray-700">New Route ID:</label>
                                <input type="number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="new_route_id" name="new_route_id" required value="{{ $selectedBooking->route_id }}">
                            </div>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Transfer Booking</button>
                        @else
                            <p class="text-gray-700">Please select a booking to view its details and initiate a transfer.</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        function loadSchedules() {
            const busId = $('#new_bus_id').val();
            const travelDate = $('#new_travel_date').val();
            const newScheduleSelect = $('#new_schedule_id');

            newScheduleSelect.empty().append('<option value="">-- Select New Schedule --</option>');

            if (busId && travelDate) {
                $.ajax({
                    url: '{{ route('get.filtered.schedules') }}',
                    method: 'GET',
                    data: {
                        bus_id: busId,
                        travel_date: travelDate
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            response.forEach(function(schedule) {
                                newScheduleSelect.append(
                                    `<option value="${schedule.id}">${schedule.from} to ${schedule.to} on ${schedule.schedule_date} (${schedule.start} - ${schedule.end})</option>`
                                );
                            });
                        } else {
                            newScheduleSelect.append('<option value="">No schedules found for this bus and date</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching schedules:', xhr);
                        newScheduleSelect.append('<option value="">Error loading schedules</option>');
                    }
                });
            }
        }

        // Load schedules when bus or date changes
        $('#new_bus_id, #new_travel_date').on('change', loadSchedules);

        // Initial load if a bus and date are already selected (e.g., from selectedBooking)
        if ($('#new_bus_id').val() && $('#new_travel_date').val()) {
            loadSchedules();
        }

        function calculateAmounts() {
            const busId = $('#new_bus_id').val();
            const scheduleId = $('#new_schedule_id').val();
            const travelDate = $('#new_travel_date').val();
            const pickupPoint = $('#new_pickup_point').val();
            const droppingPoint = $('#new_dropping_point').val();
            const originalBookingId = $('#booking_id').val(); // Get the currently selected original booking ID

            if (busId && scheduleId && travelDate && pickupPoint && droppingPoint && originalBookingId) {
                $.ajax({
                    url: '{{ route('calculate.transfer.amounts') }}',
                    method: 'GET',
                    data: {
                        bus_id: busId,
                        schedule_id: scheduleId,
                        travel_date: travelDate,
                        pickup_point: pickupPoint,
                        dropping_point: droppingPoint,
                        original_booking_id: originalBookingId
                    },
                    success: function(response) {
                        $('#new_amount').val(response.new_amount);
                        $('#new_busFee').val(response.new_busFee);
                        $('#new_discount_amount').val(response.new_discount_amount);
                        $('#new_distance').val(response.new_distance);
                        $('#new_bima_amount').val(response.new_bima_amount);
                        $('#new_vat').val(response.new_vat);
                        $('#new_fee').val(response.new_fee);
                        $('#new_service').val(response.new_service);
                        $('#new_vender_fee').val(response.new_vender_fee);
                        $('#new_vender_service').val(response.new_vender_service);
                        $('#new_campany_id').val(response.new_campany_id);
                        $('#new_route_id').val(response.new_route_id);
                    },
                    error: function(xhr) {
                        console.error('Error calculating amounts:', xhr);
                        // Optionally, reset fields or show an error message
                    }
                });
            }
        }

        // Trigger amount calculation when relevant fields change
        $('#new_bus_id, #new_schedule_id, #new_travel_date, #new_pickup_point, #new_dropping_point').on('change', calculateAmounts);

        // Also trigger initial calculation if all necessary fields are pre-filled
        if ($('#new_bus_id').val() && $('#new_schedule_id').val() && $('#new_travel_date').val() && $('#new_pickup_point').val() && $('#new_dropping_point').val() && $('#booking_id').val()) {
            calculateAmounts();
        }
    });
</script>
@endsection
