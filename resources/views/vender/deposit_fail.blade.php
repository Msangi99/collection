@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('vender/busroot.deposit_failed') }}</div>

                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        {{ session('error', __('vender/busroot.deposit_processing_error')) }}
                    </div>
                    <p>{{ __('vender/busroot.deposit_issue_message') }}</p>
                    <a href="{{ route('vender.wallet.deposit') }}" class="btn btn-primary">{{ __('vender/busroot.try_again') }}</a>
                    <a href="{{ route('vender.index') }}" class="btn btn-secondary">{{ __('vender/busroot.go_to_dashboard') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
