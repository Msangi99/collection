@extends('layouts.app')

@section('title', 'Access Forbidden')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Main Card -->
                <div class="card border-0 shadow-lg overflow-hidden">
                    <!-- Red Header Strip -->
                    <div class="bg-danger py-4 text-center">
                        <h2 class="text-white mb-0">
                            <i class="fas fa-ban mr-2"></i> Access Forbidden
                        </h2>
                    </div>
                    
                    <!-- Body Content -->
                    <div class="card-body p-0">
                        <!-- Error Icon -->
                        <div class="py-5 px-4 text-center bg-white">
                            <div class="mb-4">
                                <i class="fas fa-lock text-danger" style="font-size: 4rem;"></i>
                            </div>
                            
                            <h3 class="text-dark mb-3">403 - Access Forbidden</h3>
                            <p class="text-muted mb-4">
                                @if(isset($message))
                                    {{ $message }}
                                @else
                                    You do not have permission to access this resource.
                                @endif
                            </p>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="bg-light p-4 text-center">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-lg w-100">
                                        <i class="fas fa-arrow-left mr-2"></i> Go Back
                                    </a>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-home mr-2"></i> Go to Home
                                    </a>
                                </div>
                            </div>
                            
                            @auth
                            <div class="mt-3">
                                <small class="text-muted">
                                    Logged in as: <strong>{{ Auth::user()->name ?? Auth::user()->email }}</strong>
                                    @if(Auth::user()->role)
                                        ({{ ucfirst(Auth::user()->role) }})
                                    @endif
                                </small>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 15px;
    }
    
    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .fas {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
    }
</style>
@endsection
