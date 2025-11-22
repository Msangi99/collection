@extends('vender.app')

@section('content')
    <div class="min-vh-100 d-flex align-items-center bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Main Card -->
                    <div class="card border-0 shadow-lg overflow-hidden animate__animated animate__fadeIn">
                        <!-- Green Header Strip -->
                        <div class="bg-success py-4 text-center">
                            <h2 class="text-white mb-0"><i class="fas fa-check-circle mr-2"></i> Payment Successful</h2>
                        </div>

                        <!-- Body Content -->
                        <div class="card-body p-0">
                            <!-- Animated Checkmark -->
                            <div class="py-5 px-4 text-center bg-white">
                                <div class="success-animation mb-4">
                                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                        <circle class="checkmark__circle" cx="26" cy="26" r="25"
                                            fill="none" />
                                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                    </svg>
                                </div>

                                <h3 class="text-dark mb-3 animate__animated animate__fadeInUp">Thank You For Your Booking!
                                </h3>
                                <p class="text-muted animate__animated animate__fadeIn animate__delay-1s">Your payment was
                                    processed successfully</p>
                            </div>

                            <!-- Booking Details Card -->
                            <div class="bg-light p-4 animate__animated animate__fadeIn animate__delay-1s">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-card mb-4">
                                            <h5 class="detail-title"><i class="fas fa-receipt text-primary mr-2"></i>
                                                Booking Summary</h5>
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><strong>Bus:</strong> <span
                                                        class="float-right">{{ $data->bus->busname->name ?? 'A/N' }} |
                                                        {{ $data->bus->bus_number ?? 'A/N' }}</span></li>
                                                <li class="mb-2"><strong>Booking Code:</strong> <span
                                                        class="float-right">{{ $data->booking_code }}</span></li>
                                                <li class="mb-2"><strong>Bus Route:</strong> <span
                                                        class="float-right">{{ $data->bus->route->from ?? 'N/A' }} To
                                                        {{ $data->bus->route->to ?? 'N/A' }}</span></li>
                                                <li class="mb-2"><strong>User Route:</strong> <span
                                                        class="float-right">{{ $data->pickup_point ?? 'N/A' }} To
                                                        {{ $data->dropping_point ?? 'N/A' }}</span></li>
                                                <li class="mb-2"><strong>Travel Date:</strong> <span
                                                        class="float-right">{{ $data->travel_date }}
                                                        {{ $data->bus->route->route_start ?? 'A/N'}}</span></li>
                                                <li class="mb-2"><strong>Seat:</strong> <span
                                                        class="float-right">{{ $data->seat }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-card mb-4">
                                            <h5 class="detail-title"><i
                                                    class="fas fa-money-bill-wave text-success mr-2"></i> Payment Details
                                            </h5>
                                            <ul class="list-unstyled">
                                                <li class="mb-2"><strong>Ticket Fee:</strong> <span
                                                        class="float-right">{{ number_format($data->amount, 2) }}</span>
                                                </li>
                                                 <li class="mb-2"><strong>Service Fee:</strong> <span
                                                        class="float-right">{{ number_format(500, 2) }}</span>
                                                </li>
                                                @if ($data->bima == 1)
                                                    <li class="mb-2"><strong>Insurance amount</strong> <span
                                                            class="float-right text-truncate">{{ number_format($data->bima_amount, 2) }}</span>
                                                    </li>
                                                    <li class="mb-2"><strong>Amount Paid:</strong> <span
                                                        class="float-right">{{ number_format($data->amount + 500 + $data->bima_amount, 2) }}</span>
                                                </li>
                                                @else
                                                <li class="mb-2"><strong>Amount Paid:</strong> <span
                                                        class="float-right">{{ number_format($data->amount + 500, 2) }}</span>
                                                </li>
                                                @endif
                                                 
                                                <li class="mb-2"><strong>Transaction ID:</strong> <span
                                                        class="float-right text-truncate">{{ $data->transaction_ref_id }}</span>
                                                </li>
                                                
                                                <li class="mb-2"><strong>Status:</strong> <span
                                                        class="float-right badge bg-success">Confirmed</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Verification Code Section -->
                            <div class="p-4 bg-white animate__animated animate__fadeIn animate__delay-2s">
                                <div class="verification-box text-center py-3">
                                    <h5 class="text-uppercase text-muted mb-3">Your Verification Code</h5>
                                    <div class="verification-code animate__animated animate__pulse animate__infinite">
                                        {{ $data->booking_code }}
                                    </div>
                                    <p class="text-muted mt-3 mb-0"><small>Present this code when boarding the bus</small>
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="p-4 bg-light text-center animate__animated animate__fadeIn animate__delay-3s">
                                <a href="{{ url('/') }}" class="btn btn-primary btn-lg mr-3">
                                    <i class="fas fa-home mr-2"></i> Return Home
                                </a>
                                <button onclick="printTicket()" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-print mr-2"></i> Print Ticket
                                </button>
                            </div>
                        </div>

                       

                        <!-- Footer Note -->
                        <div class="card-footer bg-white text-center text-muted">
                            <small>A confirmation email has been sent to {{ $data->customer_email }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script></script>
    <style>
        /* Success Animation */
        .success-animation {
            position: relative;
            margin: 0 auto 30px;
            width: 120px;
            b height: 120px;
        }

        .checkmark {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: block;
            stroke-width: 5;
            stroke: #28a745;
            stroke-miterlimit: 10;
            box-shadow: 0 0 0 rgba(40, 167, 69, 0.4);
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 5;
            stroke-miterlimit: 10;
            stroke: #28a745;
            fill: none;
            animation: stroke .6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke .3s cubic-bezier(0.65, 0, 0.45, 1) .8s forwards;
        }

        /* Detail Cards */
        .detail-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .detail-title {
            color: #444;
            font-size: 1rem;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* Verification Code */
        .verification-box {
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px dashed #ddd;
        }

        .verification-code {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 5px;
            color: #28a745;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Animations */
        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {

            0%,
            100% {
                transform: none;
            }

            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0 0 0 100px rgba(40, 167, 69, 0);
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .verification-code {
                font-size: 1.8rem;
                letter-spacing: 3px;
            }

            .btn-lg {
                padding: .5rem 1rem;
                font-size: 1rem;
                margin-bottom: 10px;
                width: 100%;
            }
        }
    </style>

    <!-- Add Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection
