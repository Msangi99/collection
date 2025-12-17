@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 px-4 py-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 animate-pulse">
                        <i class="fas fa-mobile-alt text-4xl text-blue-600"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">{{ __('all.payment_pending') }}</h1>
                    <p class="text-blue-100">{{ __('all.check_your_phone') }}</p>
                </div>

                <!-- Content -->
                <div class="px-6 py-8">
                    <!-- Status Message -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 mb-6">
                        <p class="text-sm text-blue-800 text-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ $message ?? 'Payment request sent to your phone. Please check your mobile device and enter your PIN to complete the payment.' }}
                        </p>
                    </div>

                    <!-- Transaction Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm text-gray-600">{{ __('all.order_reference') }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $order_id }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm text-gray-600">{{ __('all.transaction_id') }}</span>
                            <span class="text-sm font-mono text-gray-900">{{ $transaction_id }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-sm text-gray-600">{{ __('all.amount') }}</span>
                            <span class="text-lg font-bold text-blue-600">{{ convert_money($amount) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ __('all.status') }}</span>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
                                {{ strtoupper($status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-gray-50 rounded-lg px-4 py-4 mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('all.payment_instructions') }}</h3>
                        <ol class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-start">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full text-xs font-bold mr-3 flex-shrink-0">1</span>
                                <span>{{ __('all.check_ussd_prompt') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full text-xs font-bold mr-3 flex-shrink-0">2</span>
                                <span>{{ __('all.enter_pin') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full text-xs font-bold mr-3 flex-shrink-0">3</span>
                                <span>{{ __('all.confirm_payment') }}</span>
                            </li>
                        </ol>
                    </div>

                    <!-- Loading Animation -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center space-x-2">
                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s">
                            </div>
                            <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s">
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-3">{{ __('all.waiting_for_confirmation') }}</p>
                    </div>

                    <!-- Auto-refresh notice -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3 text-center mb-4">
                        <p class="text-xs text-yellow-800">
                            <i class="fas fa-sync-alt mr-1"></i>
                            {{ __('all.page_will_refresh') }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="if(typeof manualCheckStatus === 'function') manualCheckStatus(); else window.location.reload();"
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>
                            {{ __('all.check_status') }}
                        </button>
                        <a href="{{ route('home') }}"
                            class="w-full px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold text-center transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>
                            {{ __('all.return_home') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    {{ __('all.need_help') }}
                    <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                        {{ __('all.contact_support') }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Payment status polling script -->
    <script>
        (function() {
            const orderReference = '{{ $order_id }}';
            const checkStatusUrl = '{{ route("clickpesa.check-status") }}';
            
            let pollCount = 0;
            const maxPolls = 60; // Stop after 5 minutes (60 * 5 seconds)
            const pollInterval = 5000; // 5 seconds
            
            const statusElement = document.querySelector('.inline-flex.items-center.px-3.py-1');
            const statusText = statusElement ? statusElement.querySelector('span:last-child') || statusElement : null;
            const loadingDots = document.querySelectorAll('.animate-bounce');
            const waitingMessage = document.querySelector('.text-sm.text-gray-600.mt-3');
            
            function updateUI(status, message) {
                if (waitingMessage) {
                    waitingMessage.textContent = message || 'Checking payment status...';
                }
            }
            
            function showSuccess() {
                if (statusElement) {
                    statusElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                    const dot = statusElement.querySelector('.w-2.h-2');
                    if (dot) {
                        dot.className = 'w-2 h-2 bg-green-400 rounded-full mr-2';
                    }
                }
                if (statusText) {
                    statusText.textContent = 'SUCCESS';
                }
                loadingDots.forEach(dot => dot.style.animationPlayState = 'paused');
            }
            
            function showFailed(status) {
                if (statusElement) {
                    statusElement.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
                    const dot = statusElement.querySelector('.w-2.h-2');
                    if (dot) {
                        dot.className = 'w-2 h-2 bg-red-400 rounded-full mr-2';
                    }
                }
                if (statusText) {
                    statusText.textContent = status.toUpperCase();
                }
                loadingDots.forEach(dot => dot.style.animationPlayState = 'paused');
            }
            
            function checkPaymentStatus() {
                pollCount++;
                
                if (pollCount > maxPolls) {
                    clearInterval(pollTimer);
                    updateUI('timeout', 'Payment check timed out. Please check your phone or try again.');
                    console.log('Payment status polling stopped after timeout');
                    return;
                }
                
                fetch(checkStatusUrl + '?order_reference=' + encodeURIComponent(orderReference), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Payment status check:', data);
                    
                    if (data.status === 'success') {
                        clearInterval(pollTimer);
                        showSuccess();
                        updateUI('success', 'Payment successful! Redirecting...');
                        
                        // Redirect to callback to process the payment
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1500);
                        
                    } else if (data.status === 'failed' || data.status === 'cancelled') {
                        clearInterval(pollTimer);
                        showFailed(data.status);
                        updateUI(data.status, data.message || 'Payment was ' + data.status);
                        
                        // Redirect to cancel page
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 2000);
                        
                    } else {
                        // Still pending - continue polling
                        updateUI('pending', 'Waiting for payment confirmation... (Check ' + pollCount + '/' + maxPolls + ')');
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                    updateUI('error', 'Error checking status. Retrying...');
                });
            }
            
            // Start polling immediately
            checkPaymentStatus();
            
            // Then poll every 5 seconds
            const pollTimer = setInterval(checkPaymentStatus, pollInterval);
            
            // Clear interval when user navigates away
            window.addEventListener('beforeunload', function() {
                clearInterval(pollTimer);
            });
            
            // Also allow manual check
            window.manualCheckStatus = checkPaymentStatus;
        })();
    </script>
@endsection