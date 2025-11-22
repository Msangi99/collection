@extends('layouts.app')

@section('title', __('all.choose_booking_method'))

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-primary">
                            <i class="bi bi-ticket-perforated"></i> {{ __('all.book_your_journey') }}
                        </h2>
                        <p class="text-muted">{{ __('all.choose_preferred_booking_method') }}</p>
                    </div>

                    <div class="row g-4">
                        <!-- Book by Route Option -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper bg-primary-light mb-4">
                                        <i class="bi bi-map text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <h4 class="card-title mb-3">{{ __('all.book_by_route') }}</h4>
                                    <p class="card-text text-muted mb-4">
                                        {{ __('all.select_departure_destination_find_buses') }}
                                    </p>
                                    <a href="{{ route('by_route') }}" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-arrow-right"></i> {{ __('all.continue_button') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Book by Bus Option -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper bg-success-light mb-4">
                                        <i class="bi bi-bus-front text-success" style="font-size: 2rem;"></i>
                                    </div>
                                    <h4 class="card-title mb-3">{{ __('all.book_by_bus') }}</h4>
                                    <p class="card-text text-muted mb-4">
                                        {{ __('all.select_preferred_bus_operator_view_routes') }}
                                    </p>
                                    <a href="{{ route('booking') }}" class="btn btn-outline-success w-100">
                                        <i class="bi bi-arrow-right"></i> {{ __('all.continue_button') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alternative Option -->
                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">{{ __('all.not_sure_which_to_choose') }}</p>
                        <a href="#" class="link-primary" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="bi bi-question-circle"></i> {{ __('all.help_me_decide') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel">{{ __('all.booking_method_help') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary">
                            <i class="bi bi-map me-2"></i>{{ __('all.book_by_route') }}
                        </h6>
                        <p class="text-muted">
                            {{ __('all.book_by_route_help_description') }}
                        </p>
                    </div>
                    <div>
                        <h6 class="fw-bold text-success">
                            <i class="bi bi-bus-front me-2"></i>{{ __('all.book_by_bus') }}
                        </h6>
                        <p class="text-muted">
                            {{ __('all.book_by_bus_help_description') }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('all.close_modal') }}</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-effect {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        .hover-effect:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: rgba(13, 110, 253, 0.2);
        }
        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .bg-primary-light {
            background-color: rgba(13, 110, 253, 0.1);
        }
        .bg-success-light {
            background-color: rgba(25, 135, 84, 0.1);
        }
        .card-title {
            color: #333;
        }
    </style>
@endsection
