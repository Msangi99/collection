@extends('layouts.app')

@section('content')
    {{-- Premium Payment Waiting Experience --}}
    <div class="payment-waiting-container">
        {{-- Animated Background --}}
        <div class="animated-bg">
            <div class="gradient-orb orb-1"></div>
            <div class="gradient-orb orb-2"></div>
            <div class="gradient-orb orb-3"></div>
        </div>

        <div class="payment-card">
            {{-- Glowing Border Effect --}}
            <div class="card-glow"></div>
            
            {{-- Header with Animated Icon --}}
            <div class="payment-header">
                <div class="icon-wrapper">
                    <div class="pulse-ring"></div>
                    <div class="pulse-ring delay-1"></div>
                    <div class="pulse-ring delay-2"></div>
                    <div class="icon-circle">
                        <svg class="phone-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="5" y="2" width="14" height="20" rx="3" stroke="currentColor" stroke-width="1.5"/>
                            <circle cx="12" cy="18" r="1" fill="currentColor"/>
                            <line x1="9" y1="5" x2="15" y2="5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <h1 class="payment-title">{{ __('all.payment_pending') }}</h1>
                <p class="payment-subtitle">{{ __('all.check_your_phone') }}</p>
            </div>

            {{-- Status Banner --}}
            <div class="status-banner">
                <div class="status-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <p class="status-text">
                    {{ $message ?? 'Payment request sent to your phone. Please check your mobile device and enter your PIN to complete the payment.' }}
                </p>
            </div>

            {{-- Transaction Details --}}
            <div class="transaction-details">
                <div class="detail-row">
                    <span class="detail-label">{{ __('all.order_reference') }}</span>
                    <span class="detail-value">{{ $order_id }}</span>
                </div>
                <div class="detail-divider"></div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('all.transaction_id') }}</span>
                    <span class="detail-value mono">{{ $transaction_id }}</span>
                </div>
                <div class="detail-divider"></div>
                <div class="detail-row amount-row">
                    <span class="detail-label">{{ __('all.amount') }}</span>
                    <span class="detail-value amount">TZS {{ convert_money($amount) }}</span>
                </div>
                <div class="detail-divider"></div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('all.status') }}</span>
                    <div class="status-badge">
                        <span class="status-dot"></span>
                        <span class="status-text-badge">{{ strtoupper($status) }}</span>
                    </div>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="instructions-section">
                <h3 class="instructions-title">{{ __('all.payment_instructions') }}</h3>
                <div class="instruction-steps">
                    <div class="instruction-step">
                        <div class="step-number">1</div>
                        <span class="step-text">{{ __('all.check_ussd_prompt') }}</span>
                    </div>
                    <div class="instruction-step">
                        <div class="step-number">2</div>
                        <span class="step-text">{{ __('all.enter_pin') }}</span>
                    </div>
                    <div class="instruction-step">
                        <div class="step-number">3</div>
                        <span class="step-text">{{ __('all.confirm_payment') }}</span>
                    </div>
                </div>
            </div>

            {{-- Loading Animation --}}
            <div class="loading-section">
                <div class="loading-dots">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
                <p class="loading-text">{{ __('all.waiting_for_confirmation') }}</p>
            </div>

            {{-- Auto-refresh Notice --}}
            <div class="refresh-notice">
                <svg class="refresh-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 12a9 9 0 11-2.636-6.364" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M21 6v4h-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>{{ __('all.page_will_refresh') }}</span>
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons">
                <button onclick="if(typeof manualCheckStatus === 'function') manualCheckStatus(); else window.location.reload();" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 12a9 9 0 11-2.636-6.364" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M21 6v4h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ __('all.check_status') }}
                </button>
                <a href="{{ route('home') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ __('all.return_home') }}
                </a>
            </div>
        </div>

        {{-- Help Footer --}}
        <div class="help-footer">
            <p>{{ __('all.need_help') }}
                <a href="{{ route('contact') }}">{{ __('all.contact_support') }}</a>
            </p>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --primary: #0D9488;
            --primary-light: #14B8A6;
            --primary-dark: #0F766E;
            --accent: #F59E0B;
            --accent-light: #FBBF24;
            --bg-dark: #0A0F1C;
            --bg-card: #111827;
            --bg-elevated: #1F2937;
            --text-primary: #F9FAFB;
            --text-secondary: #9CA3AF;
            --text-muted: #6B7280;
            --border-color: rgba(255, 255, 255, 0.08);
            --success: #10B981;
            --warning: #F59E0B;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .payment-waiting-container {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: var(--bg-dark);
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        .animated-bg {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }

        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 20s ease-in-out infinite;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--accent) 0%, #D97706 100%);
            bottom: -50px;
            left: -50px;
            animation-delay: -7s;
        }

        .orb-3 {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -14s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(20px, 30px) scale(1.02); }
        }

        /* Payment Card */
        .payment-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            background: var(--bg-card);
            border-radius: 24px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .card-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), var(--accent), var(--primary), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Header */
        .payment-header {
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
            background: linear-gradient(180deg, rgba(13, 148, 136, 0.1) 0%, transparent 100%);
        }

        .icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .pulse-ring {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid var(--primary);
            opacity: 0;
            animation: pulse-ring 2s ease-out infinite;
        }

        .pulse-ring.delay-1 { animation-delay: 0.5s; }
        .pulse-ring.delay-2 { animation-delay: 1s; }

        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.8; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(13, 148, 136, 0.4);
        }

        .phone-icon {
            width: 36px;
            height: 36px;
            color: white;
        }

        .payment-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .payment-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        /* Status Banner */
        .status-banner {
            margin: 0 1.5rem;
            padding: 1rem 1.25rem;
            background: rgba(13, 148, 136, 0.1);
            border: 1px solid rgba(13, 148, 136, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .status-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            color: var(--primary-light);
        }

        .status-icon svg {
            width: 100%;
            height: 100%;
        }

        .status-banner .status-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        /* Transaction Details */
        .transaction-details {
            padding: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
        }

        .detail-label {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .detail-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .detail-value.mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            letter-spacing: 0.02em;
        }

        .detail-value.amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent);
        }

        .detail-divider {
            height: 1px;
            background: var(--border-color);
        }

        .amount-row {
            padding: 1rem 0;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.875rem;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 100px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--warning);
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .status-text-badge {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent-light);
            letter-spacing: 0.05em;
        }

        /* Instructions */
        .instructions-section {
            padding: 1.25rem 1.5rem;
            background: var(--bg-elevated);
            margin: 0 1rem;
            border-radius: 16px;
        }

        .instructions-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .instruction-steps {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
        }

        .instruction-step {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .step-number {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        .step-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Loading Section */
        .loading-section {
            padding: 1.5rem;
            text-align: center;
        }

        .loading-dots {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .loading-dots .dot {
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
            animation: bounce 1.4s ease-in-out infinite;
        }

        .loading-dots .dot:nth-child(1) { animation-delay: 0s; }
        .loading-dots .dot:nth-child(2) { animation-delay: 0.2s; }
        .loading-dots .dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1.2); opacity: 1; }
        }

        .loading-text {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Refresh Notice */
        .refresh-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: rgba(245, 158, 11, 0.08);
            border-top: 1px solid rgba(245, 158, 11, 0.15);
            border-bottom: 1px solid rgba(245, 158, 11, 0.15);
        }

        .refresh-icon {
            width: 14px;
            height: 14px;
            color: var(--accent);
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .refresh-notice span {
            font-size: 0.75rem;
            color: var(--accent);
        }

        /* Action Buttons */
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            padding: 1.5rem;
        }

        .btn-primary,
        .btn-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.5);
        }

        .btn-primary svg,
        .btn-secondary svg {
            width: 18px;
            height: 18px;
        }

        .btn-secondary {
            background: var(--bg-elevated);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Help Footer */
        .help-footer {
            margin-top: 1.5rem;
            text-align: center;
            z-index: 1;
        }

        .help-footer p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .help-footer a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .help-footer a:hover {
            color: var(--accent);
        }

        /* Success State */
        .payment-card.success .status-badge {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .payment-card.success .status-dot {
            background: var(--success);
            animation: none;
        }

        .payment-card.success .status-text-badge {
            color: #34D399;
        }

        /* Failed State */
        .payment-card.failed .status-badge {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .payment-card.failed .status-dot {
            background: #EF4444;
            animation: none;
        }

        .payment-card.failed .status-text-badge {
            color: #F87171;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .payment-waiting-container {
                padding: 1rem;
            }

            .payment-header {
                padding: 2rem 1.5rem 1.25rem;
            }

            .payment-title {
                font-size: 1.5rem;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .status-banner {
                margin: 0 1rem;
            }

            .instructions-section {
                margin: 0 0.75rem;
            }
        }
    </style>

    {{-- Payment status polling script --}}
    <script>
        (function() {
            const orderReference = '{{ $order_id }}';
            const checkStatusUrl = '{{ route("clickpesa.check-status") }}';
            const paymentCard = document.querySelector('.payment-card');
            
            let pollCount = 0;
            const maxPolls = 60;
            const pollInterval = 5000;
            
            const statusBadge = document.querySelector('.status-badge');
            const statusDot = document.querySelector('.status-dot');
            const statusTextBadge = document.querySelector('.status-text-badge');
            const loadingDots = document.querySelectorAll('.loading-dots .dot');
            const loadingText = document.querySelector('.loading-text');
            
            function updateUI(status, message) {
                if (loadingText) {
                    loadingText.textContent = message || 'Checking payment status...';
                }
            }
            
            function showSuccess() {
                paymentCard.classList.remove('failed');
                paymentCard.classList.add('success');
                if (statusTextBadge) {
                    statusTextBadge.textContent = 'SUCCESS';
                }
                loadingDots.forEach(dot => dot.style.animationPlayState = 'paused');
            }
            
            function showFailed(status) {
                paymentCard.classList.remove('success');
                paymentCard.classList.add('failed');
                if (statusTextBadge) {
                    statusTextBadge.textContent = status.toUpperCase();
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
                        
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1500);
                        
                    } else if (data.status === 'failed' || data.status === 'cancelled') {
                        clearInterval(pollTimer);
                        showFailed(data.status);
                        updateUI(data.status, data.message || 'Payment was ' + data.status);
                        
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 2000);
                        
                    } else {
                        updateUI('pending', 'Waiting for payment confirmation... (Check ' + pollCount + '/' + maxPolls + ')');
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                    updateUI('error', 'Error checking status. Retrying...');
                });
            }
            
            checkPaymentStatus();
            const pollTimer = setInterval(checkPaymentStatus, pollInterval);
            
            window.addEventListener('beforeunload', function() {
                clearInterval(pollTimer);
            });
            
            window.manualCheckStatus = checkPaymentStatus;
        })();
    </script>
@endsection
