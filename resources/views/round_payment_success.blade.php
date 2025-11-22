@extends('test.ap')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">Payment Successful!</div>

                <div class="card-body">
                    <h3 class="text-center text-success">Your round trip booking has been confirmed.</h3>
                    <p class="text-center">Thank you for your payment. Your tickets have been successfully booked.</p>

                    @if(isset($booking1) && $booking1)
                        <div class="alert alert-info mt-3">
                            <h5>First Leg Booking Details:</h5>
                            <p><strong>Booking Code:</strong> {{ $booking1->booking_code }}</p>
                            <p><strong>From:</strong> {{ $booking1->pickup_point }}</p>
                            <p><strong>To:</strong> {{ $booking1->dropping_point }}</p>
                            <p><strong>Travel Date:</strong> {{ $booking1->travel_date }}</p>
                            <p><strong>Seats:</strong> {{ $booking1->seat }}</p>
                            <p><strong>Amount Paid:</strong> TZS {{ number_format($booking1->amount, 2) }}</p>
                        </div>
                    @endif

                    @if(isset($booking2) && $booking2)
                        <div class="alert alert-info mt-3">
                            <h5>Second Leg Booking Details:</h5>
                            <p><strong>Booking Code:</strong> {{ $booking2->booking_code }}</p>
                            <p><strong>From:</strong> {{ $booking2->pickup_point }}</p>
                            <p><strong>To:</strong> {{ $booking2->dropping_point }}</p>
                            <p><strong>Travel Date:</strong> {{ $booking2->travel_date }}</p>
                            <p><strong>Seats:</strong> {{ $booking2->seat }}</p>
                            <p><strong>Amount Paid:</strong> TZS {{ number_format($booking2->amount, 2) }}</p>
                        </div>
                    @endif

                    <p class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary">Go to Home</a>
                        @if(auth()->check() && auth()->user()->isCustomer())
                            <a href="{{ route('customer.mybooking') }}" class="btn btn-secondary">View My Bookings</a>
                        @elseif(auth()->check() && auth()->user()->isVender())
                            <a href="{{ route('vender.history') }}" class="btn btn-secondary">View My Bookings</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
