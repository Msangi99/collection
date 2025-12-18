@extends('layouts.app')

@section('content')
    <div class="payment-result-container">
        <div class="animated-bg">
            <div class="gradient-orb orb-1"></div>
            <div class="gradient-orb orb-2"></div>
        </div>

        <div class="result-card warning">
            <div class="card-glow warning"></div>
            
            <div class="result-header">
                <div class="icon-wrapper">
                    <div class="icon-circle warning">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 9v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <h1 class="result-title">{{ __('all.verification_error') ?? 'Verification Error' }}</h1>
                <p class="result-subtitle">{{ __('all.verification_error_subtitle') ?? 'We could not verify your payment status.' }}</p>
            </div>

            <div class="transaction-details">
                <div class="detail-row">
                    <span class="detail-label">{{ __('all.reference') ?? 'Reference' }}</span>
                    <span class="detail-value mono">{{ $reference ?? 'N/A' }}</span>
                </div>
                <div class="detail-divider"></div>
                <div class="detail-row">
                    <span class="detail-label">{{ __('all.status') ?? 'Status' }}</span>
                    <div class="status-badge warning">
                        <span class="status-dot"></span>
                        <span class="status-text-badge">{{ strtoupper($status ?? 'VERIFICATION ERROR') }}</span>
                    </div>
                </div>
            </div>

            <div class="warning-box critical">
                <svg class="warning-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="warning-content">
                    <h4>{{ __('all.important_notice') ?? 'Important Notice' }}</h4>
                    <p>{{ $message ?? 'If money was deducted from your account but your ticket is not issued, please contact our support team immediately with the reference number above.' }}</p>
                </div>
            </div>

            <div class="info-box">
                <h4>{{ __('all.what_to_do') ?? 'What should you do?' }}</h4>
                <ul>
                    <li>{{ __('all.check_mobile_money') ?? 'Check your mobile money account for the transaction' }}</li>
                    <li>{{ __('all.note_reference') ?? 'Note down the reference number above' }}</li>
                    <li>{{ __('all.contact_support_with_ref') ?? 'Contact support with this reference if money was deducted' }}</li>
                    <li>{{ __('all.wait_for_sms') ?? 'You may receive an SMS confirmation shortly' }}</li>
                </ul>
            </div>

            @if(isset($error))
            <div class="error-details">
                <span class="error-label">{{ __('all.technical_details') ?? 'Technical Details' }}:</span>
                <code>{{ $error }}</code>
            </div>
            @endif

            <div class="action-buttons">
                <a href="{{ route('contact') }}" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ __('all.contact_support') ?? 'Contact Support' }}
                </a>
                <a href="{{ route('home') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ __('all.return_home') ?? 'Return Home' }}
                </a>
            </div>

            <div class="support-info">
                <p><strong>{{ __('all.support_phone') ?? 'Support Phone' }}:</strong> +255 XXX XXX XXX</p>
                <p><strong>{{ __('all.support_email') ?? 'Email' }}:</strong> support@example.com</p>
            </div>
        </div>

        <div class="help-footer">
            <p>{{ __('all.reference_copy_note') ?? 'Please save your reference number for support inquiries.' }}</p>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --primary: #0D9488;
            --primary-light: #14B8A6;
            --primary-dark: #0F766E;
            --bg-dark: #0A0F1C;
            --bg-card: #111827;
            --bg-elevated: #1F2937;
            --text-primary: #F9FAFB;
            --text-secondary: #9CA3AF;
            --text-muted: #6B7280;
            --border-color: rgba(255, 255, 255, 0.08);
            --warning: #F59E0B;
            --warning-light: #FBBF24;
            --error: #EF4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .payment-result-container {
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
            opacity: 0.3;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--warning) 0%, #D97706 100%);
            top: -100px;
            right: -100px;
        }

        .orb-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            bottom: -50px;
            left: -50px;
        }

        .result-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            background: var(--bg-card);
            border-radius: 24px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .card-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
        }

        .card-glow.warning {
            background: linear-gradient(90deg, transparent, var(--warning), var(--warning-light), var(--warning), transparent);
        }

        .result-header {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }

        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle.warning {
            background: linear-gradient(135deg, var(--warning) 0%, #D97706 100%);
            box-shadow: 0 10px 40px rgba(245, 158, 11, 0.4);
        }

        .icon-circle svg {
            width: 40px;
            height: 40px;
            color: white;
        }

        .result-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .result-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .transaction-details {
            padding: 1rem 1.5rem;
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
            background: var(--bg-elevated);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .detail-divider {
            height: 1px;
            background: var(--border-color);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.875rem;
            border-radius: 100px;
        }

        .status-badge.warning {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-badge.warning .status-dot {
            background: var(--warning);
            animation: blink 1.5s ease-in-out infinite;
        }

        .status-badge.warning .status-text-badge {
            color: var(--warning-light);
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-text-badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        .warning-box {
            margin: 0 1.5rem 1rem;
            padding: 1rem;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 12px;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .warning-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            color: var(--error);
        }

        .warning-content h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--error);
            margin-bottom: 0.25rem;
        }

        .warning-content p {
            font-size: 0.8rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .info-box {
            margin: 0 1.5rem 1rem;
            padding: 1rem;
            background: var(--bg-elevated);
            border-radius: 12px;
        }

        .info-box h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }

        .info-box ul {
            list-style: none;
            padding: 0;
        }

        .info-box li {
            font-size: 0.8rem;
            color: var(--text-secondary);
            padding: 0.375rem 0;
            padding-left: 1.25rem;
            position: relative;
        }

        .info-box li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: var(--primary-light);
        }

        .error-details {
            margin: 0 1.5rem 1rem;
            padding: 0.75rem;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            font-size: 0.75rem;
        }

        .error-label {
            color: var(--text-muted);
        }

        .error-details code {
            display: block;
            margin-top: 0.5rem;
            font-family: 'JetBrains Mono', monospace;
            color: var(--text-secondary);
            word-break: break-all;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
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
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--warning) 0%, #D97706 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
        }

        .btn-secondary {
            background: var(--bg-elevated);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-primary svg,
        .btn-secondary svg {
            width: 18px;
            height: 18px;
        }

        .support-info {
            padding: 1rem 1.5rem;
            background: var(--bg-elevated);
            border-top: 1px solid var(--border-color);
            text-align: center;
        }

        .support-info p {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 0.25rem 0;
        }

        .support-info strong {
            color: var(--text-primary);
        }

        .help-footer {
            margin-top: 1.5rem;
            text-align: center;
            z-index: 1;
        }

        .help-footer p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        @media (max-width: 480px) {
            .action-buttons {
                grid-template-columns: 1fr;
            }
            
            .result-card {
                max-width: 100%;
            }
        }
    </style>
@endsection

