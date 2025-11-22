@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">Payment Failed</div>

                <div class="card-body">
                    <h3 class="text-center text-danger">Your round trip payment could not be processed.</h3>
                    <p class="text-center">There was an issue with your payment. Please try again or contact support.</p>

                    @if(session('error'))
                        <div class="alert alert-danger mt-3">
                            <strong>Error:</strong> {{ session('error') }}
                        </div>
                    @endif

                    <p class="text-center mt-4">
                        <a href="{{ route('round.trip.payment') }}" class="btn btn-primary">Try Payment Again</a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Go to Home</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
