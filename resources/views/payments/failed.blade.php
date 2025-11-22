@extends('test.ap')

@section('content')
<div class="min-h-scree flex items-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Error Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-all duration-300 transform hover:shadow-2xl">
            <!-- Header -->
            <div class="bg-red-600 py-6 px-6 text-center">
                <div class="flex items-center justify-center space-x-3">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h2 class="text-2xl font-bold text-white">Payment Failed</h2>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 sm:p-8">
                <!-- Animated X Mark -->
                <div class="flex justify-center mb-8">
                    <div class="relative">
                        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-red-600 animate-xmark" viewBox="0 0 52 52">
                                <circle class="stroke-red-600" cx="26" cy="26" r="25" fill="none" stroke-width="4" stroke-dasharray="166" stroke-dashoffset="166"></circle>
                                <path class="stroke-red-600" fill="none" stroke-width="4" stroke-dasharray="48" stroke-dashoffset="48" d="M16 16 36 36 M36 16 16 36"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Payment Unsuccessful</h3>
                    <p class="text-gray-600">We couldn't process your payment</p>
                </div>

                <!-- Error Details -->
                <div class="bg-red-50 border border-red-100 rounded-lg p-6 mb-8">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-white rounded-full border-4 border-white shadow-md flex items-center justify-center text-red-500">
                            <svg class="h-8 w-8 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>

                    <h5 class="text-center font-medium text-gray-800 mb-4">What went wrong?</h5>

                    <div class="space-y-3">
                        <div class="text-red-700 flex items-start bg-white p-3 rounded-lg border-l-4 border-red-500 animate-fade-in-left">
                            <svg class="h-5 w-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Insufficient funds or incorrect card details</span>
                        </div>
                        <div class="text-red-700 flex items-start bg-white p-3 rounded-lg border-l-4 border-red-500 animate-fade-in-left delay-100">
                            <svg class="h-5 w-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Network or connectivity issues</span>
                        </div>
                        <div class="text-red-700 flex items-start bg-white p-3 rounded-lg border-l-4 border-red-500 animate-fade-in-left delay-200">
                            <svg class="h-5 w-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Payment authorization failed</span>
                        </div>
                    </div>

                    @isset($data)
                    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded animate-fade-in">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Transaction Reference</h3>
                                <div class="mt-1 text-sm text-yellow-700">
                                    <p>{{ $data->transaction_ref_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endisset
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-6">
                    <a href="#" onclick="window.history.back()" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Retry Payment
                    </a>
                    <a href="{{ url('/') }}" class="px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Return Home
                    </a>
                </div>

                <!-- Support -->
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-2">Need help?</p>
                    <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-xmark circle {
        animation: dash 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .animate-xmark path {
        animation: dash 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }
    @keyframes dash {
        to { stroke-dashoffset: 0; }
    }
    .animate-fade-in-left {
        animation: fadeInLeft 0.5s ease-out forwards;
        opacity: 0;
        transform: translateX(-10px);
    }
    .delay-100 {
        animation-delay: 0.1s;
    }
    .delay-200 {
        animation-delay: 0.2s;
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out 0.3s forwards;
        opacity: 0;
    }
    @keyframes fadeInLeft {
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeIn {
        to { opacity: 1; }
    }
</style>
@endsection