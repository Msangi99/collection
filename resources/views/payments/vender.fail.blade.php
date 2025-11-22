@extends('test.ap')

@section('content')
<div class="min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Main Card -->
                <div class="card border-0 shadow-lg overflow-hidden animate__animated animate__fadeIn">
                    <!-- Red Header Strip -->
                    <div class="bg-danger py-4 text-center">
                        <h2 class="text-white mb-0"><i class="fas fa-exclamation-triangle mr-2"></i> Payment Failed</h2>
                    </div>
                    
                    <!-- Body Content -->
                    <div class="card-body p-0">
                        <!-- Animated X Mark -->
                        <div class="py-5 px-4 text-center bg-white">
                            <div class="error-animation mb-4">
                                <svg class="xmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="xmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                    <path class="xmark__x" fill="none" d="M16 16 36 36 M36 16 16 36"/>
                                </svg>
                            </div>
                            
                            <h3 class="text-dark mb-3 animate__animated animate__fadeInUp">Payment Unsuccessful</h3>
                            <p class="text-muted animate__animated animate__fadeIn animate__delay-1s">We couldn't process your payment</p>
                        </div>
                        
                        <!-- Error Details Card -->
                        <div class="bg-light p-4 animate__animated animate__fadeIn animate__delay-1s">
                            <div class="error-card p-4">
                                <div class="error-icon animate__animated animate__headShake animate__infinite animate__slower">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                
                                <h5 class="text-center mb-4">What went wrong?</h5>
                                
                                <ul class="list-unstyled error-reasons">
                                    <li class="mb-3 animate__animated animate__fadeInLeft animate__delay-2s">
                                        <i class="fas fa-times-circle text-danger mr-2"></i> 
                                        Insufficient funds or incorrect card details
                                    </li>
                                    <li class="mb-3 animate__animated animate__fadeInLeft animate__delay-3s">
                                        <i class="fas fa-times-circle text-danger mr-2"></i> 
                                        Network or connectivity issues
                                    </li>
                                    <li class="mb-3 animate__animated animate__fadeInLeft animate__delay-4s">
                                        <i class="fas fa-times-circle text-danger mr-2"></i> 
                                        Payment authorization failed
                                    </li>
                                </ul>
                                
                                @isset($data)
                                <div class="alert alert-warning mt-4 animate__animated animate__fadeIn animate__delay-4s">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle fa-2x mr-3"></i>
                                        <div>
                                            <h6 class="mb-1">Transaction Reference</h6>
                                            <p class="mb-0 small">{{ $data->transaction_ref_id }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endisset
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="p-4 bg-white text-center animate__animated animate__fadeIn animate__delay-5s">
                            <div class="d-flex flex-column flex-md-row justify-content-center">
                                <a href="#" onclick="window.history.back()" class="btn btn-danger btn-lg mb-3 mb-md-0 mr-md-3">
                                    <i class="fas fa-credit-card mr-2"></i> Retry Payment
                                </a>
                                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-home mr-2"></i> Return Home
                                </a>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-muted mb-2">Need help?</p>
                                <a href="#" class="btn btn-link">
                                    <i class="fas fa-phone-alt mr-2"></i> Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Error Animation */
    .error-animation {
        position: relative;
        margin: 0 auto 30px;
        width: 120px;
        height: 120px;
    }
    
    .xmark {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: block;
        stroke-width: 5;
        stroke: #dc3545;
        stroke-miterlimit: 10;
        box-shadow: 0 0 0 rgba(220, 53, 69, 0.4);
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }
    
    .xmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 5;
        stroke-miterlimit: 10;
        stroke: #dc3545;
        fill: none;
        animation: stroke .6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .xmark__x {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke .3s cubic-bezier(0.65, 0, 0.45, 1) .8s forwards;
    }
    
    /* Error Card */
    .error-card {
        background: white;
        border-radius: 8px;
        position: relative;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .error-icon {
        width: 80px;
        height: 80px;
        background: #ffe6e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #dc3545;
        margin: -65px auto 20px;
        border: 5px solid white;
    }
    
    .error-reasons li {
        padding: 10px 15px;
        background: #f8f9fa;
        border-radius: 5px;
        border-left: 4px solid #dc3545;
    }
    
    /* Animations */
    @keyframes stroke {
        100% { stroke-dashoffset: 0; }
    }

    @keyframes scale {
        0%, 100% { transform: none; }
        50% { transform: scale3d(1.1, 1.1, 1); }
    }

    @keyframes fill {
        100% { box-shadow: inset 0 0 0 100px rgba(220, 53, 69, 0); }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .error-reasons li {
            font-size: 0.9rem;
        }
        
        .btn-lg {
            padding: .5rem 1rem;
            font-size: 1rem;
            width: 100%;
        }
    }
</style>

<!-- Add Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection