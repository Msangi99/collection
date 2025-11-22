
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Journey Details</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Bus:</span>
                    <span class="fw-bold">{{ $booking->bus_name->name }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Route:</span>
                    <span class="fw-bold">{{ $booking->route_name->from }} to {{ $booking->route_name->to }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Travel Date:</span>
                    <span class="fw-bold">{{ $booking->travel_date }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Pickup Point:</span>
                    <span class="fw-bold">{{ $booking->pickup_point }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Dropoff Point:</span>
                    <span class="fw-bold">{{ $booking->dropoff_point }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Seat Number:</span>
                    <span class="fw-bold">{{ $booking->seat }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Passenger Details</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Name:</span>
                    <span class="fw-bold">{{ $booking->user->name }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Phone:</span>
                    <span class="fw-bold">{{ $booking->phone_number }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Email:</span>
                    <span class="fw-bold">{{ $booking->user->email }}</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Payment Details</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Amount:</span>
                    <span class="fw-bold">Tsh {{ $booking->amount }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment Method:</span>
                    <span class="fw-bold">{{ $booking->payment_method }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Transaction Ref:</span>
                    <span class="fw-bold">{{ $booking->transaction_ref }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status:</span>
                    <span class="badge bg-success">Paid</span>
                </div>
            </div>
        </div>
    </div>
</div> 