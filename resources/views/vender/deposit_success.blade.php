@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('vender/busroot.deposit_successful') }}</div>

                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        {{ session('success', __('vender/busroot.deposit_processed_successfully')) }}
                    </div>
                    <p>{{ __('vender/busroot.deposit_thank_you_message') }}</p>
                    <a href="{{ route('vender.index') }}" class="btn btn-primary">{{ __('vender/busroot.go_to_dashboard') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
