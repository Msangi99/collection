@extends('layouts.app')

@section('content')
    <div class="payment-result-container">
        <div class="animated-bg">
            <div class="gradient-orb orb-1"></div>
            <div class="gradient-orb orb-2"></div>
        </div>

        <div class="result-card error">
            <div class="card-glow error"></div>
            
            <div class="result-header">
                <div class="icon-wrapper">
                    <div class="icon-circle error">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <h1 class="result-title">{{ __('all.error_occurred') ?? 'An Error Occurred' }}</h1>
                <p class="result-subtitle">{{ $message ?? 'Something went wrong while processing your payment.' }}</p>
            </div>

            @if(isset($reference))
            <div class="transaction-details">
                <div class="detail-row">
                    <span class="detail-label">{{ __('all.reference') ?? 'Reference' }}</span>
                    <span class="detail-value mono">{{ $reference }}</span>
                </div>
            </div>
            @endif

            <div class="info-box">
                <svg class="info-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M12 7v5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="12" cy="16" r="1" fill="currentColor"/>
                </svg>
                <p>{{ __('all.error_contact_support') ?? 'Please contact support if you believe this is an error, especially if money was deducted from your account.' }}</p>
            </div>

            <div class="action-buttons">
                <a href="{{ url()->previous() }}" class="btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 12a9 9 0 11-2.636-6.364" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M21 6v4h-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ __('all.try_again') ?? 'Try Again' }}
                </a>
                <a href="{{ route('home') }}" class="btn-secondary">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ __('all.return_home') ?? 'Return Home' }}
                </a>
            </div>
        </div>

        <div class="help-footer">
            <p>{{ __('all.need_help') ?? 'Need help?' }}
                <a href="{{ route('contact') }}">{{ __('all.contact_support') ?? 'Contact Support' }}</a>
            </p>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --primary: #0D9488;
            --primary-light: #14B8A6;
            --bg-dark: #0A0F1C;
            --bg-card: #111827;
            --bg-elevated: #1F2937;
            --text-primary: #F9FAFB;
            --text-secondary: #9CA3AF;
            --text-muted: #6B7280;
            --border-color: rgba(255, 255, 255, 0.08);
            --error: #EF4444;
            --error-light: #F87171;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

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

        .animated-bg { position: absolute; inset: 0; overflow: hidden; z-index: 0; }
        .gradient-orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.3; }
        .orb-1 { width: 400px; height: 400px; background: linear-gradient(135deg, var(--error) 0%, #991B1B 100%); top: -100px; right: -100px; }
        .orb-2 { width: 300px; height: 300px; background: linear-gradient(135deg, var(--primary) 0%, #0F766E 100%); bottom: -50px; left: -50px; }

        .result-card {
            position: relative; z-index: 1; width: 100%; max-width: 440px;
            background: var(--bg-card); border-radius: 24px;
            border: 1px solid var(--border-color); overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .card-glow { position: absolute; top: 0; left: 0; right: 0; height: 2px; }
        .card-glow.error { background: linear-gradient(90deg, transparent, var(--error), var(--error-light), var(--error), transparent); }

        .result-header { padding: 2.5rem 2rem 1.5rem; text-align: center; }
        .icon-wrapper { display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; }
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .icon-circle.error { background: linear-gradient(135deg, var(--error) 0%, #991B1B 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4); }
        .icon-circle svg { width: 40px; height: 40px; color: white; }

        .result-title { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; }
        .result-subtitle { font-size: 1rem; color: var(--text-secondary); }

        .transaction-details { padding: 1.5rem; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; }
        .detail-label { font-size: 0.875rem; color: var(--text-muted); }
        .detail-value { font-size: 0.875rem; font-weight: 600; color: var(--text-primary); }
        .detail-value.mono { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; }

        .info-box {
            margin: 0 1.5rem 1.5rem; padding: 1rem;
            background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 12px; display: flex; align-items: flex-start; gap: 0.75rem;
        }
        .info-icon { flex-shrink: 0; width: 20px; height: 20px; color: var(--error-light); }
        .info-box p { font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5; }

        .action-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; padding: 1.5rem; }
        .btn-primary, .btn-secondary {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.875rem 1rem; border-radius: 12px; font-size: 0.875rem;
            font-weight: 600; text-decoration: none; transition: all 0.2s ease;
        }
        .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, #0F766E 100%); color: white; box-shadow: 0 4px 15px rgba(13, 148, 136, 0.4); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(13, 148, 136, 0.5); }
        .btn-secondary { background: var(--bg-elevated); color: var(--text-primary); border: 1px solid var(--border-color); }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.1); }
        .btn-primary svg, .btn-secondary svg { width: 18px; height: 18px; }

        .help-footer { margin-top: 1.5rem; text-align: center; z-index: 1; }
        .help-footer p { font-size: 0.875rem; color: var(--text-muted); }
        .help-footer a { color: var(--primary-light); text-decoration: none; font-weight: 500; }

        @media (max-width: 480px) { .action-buttons { grid-template-columns: 1fr; } }
    </style>
@endsection

