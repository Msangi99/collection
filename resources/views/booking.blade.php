
@extends('layouts.app')

@section('title', __('all.search_buses'))

@section('content')
    <div class="container p-3">
        @include('search')
    </div>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Search Form with Select Input -->

                <!-- Search Results -->
                @if (isset($bus) && $bus->isNotEmpty())
                    @foreach ($bus as $company)
                        @foreach ($company->bus as $busItem)
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $company->name }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        {{ __('all.bus_no') }} {{ $busItem->bus_number }}
                                    </h6>

                                    @if ($busItem->schedules->isNotEmpty())
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex flex-wrap schedule-container">
                                                    @foreach ($busItem->schedules as $schedule)
                                                        <div class="schedule-item p-2">
                                                            <a href="{{ route('booking_form', ['id' => $schedule->bus_id, 'from' => $schedule->from, 'to' => $schedule->to ]) }}" class="btn btn-sm btn-primary text-center">
                                                                <div><strong>{{ __('all.from') }}</strong> {{ $schedule->from }}</div>
                                                                <div><strong>{{ __('all.to') }}</strong> {{ $schedule->to }}</div>
                                                                <div><strong>{{ __('all.date') }}</strong> {{ $schedule->schedule_date }}
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>{{ __('all.no_schedules_available') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    <div class="text-center" style="padding-top: 9cm;">
                        <p>{{ __('all.no_buses_found') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
        }

        .form-select {
            padding: 0.75rem;
        }

        .schedule-container {
            gap: 15px;
            justify-content: flex-start;
        }

        .schedule-item {
            flex: 1;
            min-width: 200px;
            max-width: 250px;
        }

        .btn-sm {
            font-size: 0.875rem;
            width: 100%;
            padding: 10px;
        }

        .btn-primary {
            white-space: normal;
            word-wrap: break-word;
        }
    </style>
@endsection
