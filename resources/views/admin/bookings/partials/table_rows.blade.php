
@foreach ($bookings as $booking)
    <tr>
        <td class="ps-4">
            <p class="text-xs font-weight-bold mb-0">{{ $booking->booking_code }}</p>
            <p class="text-xs text-secondary mb-0">Confirmed</p>
        </td>
        <td>
            <div class="d-flex flex-column">
                <h6 class="mb-0 text-sm">{{ $booking->bus_name->name }}</h6>
                <p class="text-xs text-secondary mb-0">{{ $booking->route_name->from }} to {{ $booking->route_name->to }}</p>
            </div>
        </td>
        <td>
            <div class="d-flex flex-column">
                <p class="text-xs font-weight-bold mb-0">{{ $booking->travel_date }}</p>
                <p class="text-xs text-secondary mb-0">Seat: {{ $booking->seat }}</p>
                <p class="text-xs text-secondary mb-0">Pickup: {{ $booking->pickup_point }}</p>
            </div>
        </td>
        <td>
            <div class="d-flex flex-column">
                <p class="text-xs font-weight-bold mb-0">{{ $booking->user->name }}</p>
                <p class="text-xs text-secondary mb-0">{{ $booking->phone_number }}</p>
            </div>
        </td>
        <td>
            <div class="d-flex flex-column">
                <p class="text-xs font-weight-bold mb-0">Tsh {{ $booking->amount }}</p>
            </div>
        </td>
        <td class="align-middle">
            <button class="btn btn-info btn-sm px-3 mb-0 view-booking" data-id="{{ $booking->id }}">
                <i class="bi bi-eye"></i>
            </button>
            <button class="btn btn-success btn-sm px-3 mb-0 print-ticket" data-id="{{ $booking->id }}">
                <i class="bi bi-printer"></i>
            </button>
        </td>
    </tr>
@endforeach