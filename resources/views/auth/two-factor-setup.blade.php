@extends('layouts.auth')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white/80 dark:bg-slate-900/70 backdrop-blur-lg shadow-xl rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-2xl">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">
                Two-Factor Authentication
            </h4>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                Protect your account with an extra layer of security
            </p>
        </div>

        {{-- STATUS & ERRORS --}}
        @if (session('status'))
            <div class="mb-5 rounded-lg border border-green-200/60 dark:border-green-800/50 bg-green-50/70 dark:bg-green-900/30 text-green-800 dark:text-green-100 px-4 py-3">
                <span class="font-medium">Success:</span> {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-lg border border-red-200/60 dark:border-red-800/50 bg-red-50/70 dark:bg-red-900/30 text-red-800 dark:text-red-100 px-4 py-3">
                <span class="font-medium">Error:</span> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-amber-200/60 dark:border-amber-800/50 bg-amber-50/70 dark:bg-amber-900/30 text-amber-800 dark:text-amber-100 px-4 py-3">
                <div class="font-medium mb-1">Please fix the following:</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- END STATUS & ERRORS --}}

        @if (! Auth::user()->two_factor_secret)
            {{-- Not enabled state --}}
            <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/60 dark:bg-slate-800/50 p-5 mb-6">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">2FA is Disabled</h5>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Turn on two-factor authentication to require a one-time code from an authenticator app when you sign in.
                </p>
            </div>

            <form method="POST" action="{{ route('two-factor.enable') }}" class="mt-4">
                @csrf
                <button type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2.5 rounded-lg font-medium
                               hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 016 6v1h1a1 1 0 110 2h-1v1a6 6 0 11-12 0V11H3a1 1 0 110-2h1V8a6 6 0 016-6zm0 2a4 4 0 00-4 4v1h8V8a4 4 0 00-4-4z"/></svg>
                    Enable Two-Factor
                </button>
            </form>

        @else
            {{-- Enabled state --}}
            <div class="rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50/60 dark:bg-emerald-900/30 p-5 mb-6">
                <h5 class="text-lg font-semibold text-emerald-900 dark:text-emerald-100 mb-1">2FA is Enabled</h5>
                <p class="text-sm text-emerald-800/90 dark:text-emerald-100/80">
                    Use your authenticator app to generate a 6-digit code when you sign in.
                </p>
            </div>

            @if (session('status') == 'two-factor-authentication-enabled')
                <div class="rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50/70 dark:bg-blue-900/30 text-blue-900 dark:text-blue-100 px-4 py-3 mb-6">
                    Two-factor authentication is now enabled. Scan the QR code with your authenticator app (e.g., Google Authenticator, Authy).
                </div>
            @endif

            {{-- QR Code --}}
            <div class="mb-8">
                <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scan this QR Code</div>
                <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 inline-block">
                    {!! $qrCodeSvg !!}
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    If you can’t scan, add the secret key manually from your profile’s 2FA details.
                </p>
            </div>

            {{-- Confirm with code --}}
            <div class="mb-8">
                <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">One-Time Code</label>
                        <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" required
                               class="w-full px-3 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-gray-50/60 dark:bg-slate-800 text-gray-900 dark:text-gray-100 placeholder-gray-400
                                      focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition @error('code') border-red-400 focus:ring-red-400 @enderror"
                               placeholder="Enter 6-digit code">
                        @error('code')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4 1.5 1.5L11 16l-3.5-3.5L9 12zm3-9C6.48 3 2 7.48 2 13s4.48 10 10 10 10-4.48 10-10S17.52 3 12 3z"/></svg>
                        Confirm
                    </button>
                </form>
            </div>

            {{-- Recovery codes --}}
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <h6 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Recovery Codes</h6>
                    <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
                        @csrf
                        <button type="submit"
                                class="text-sm inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-200">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M5 4h14v2H5zm0 4h14v2H5zm0 4h9v2H5z"/></svg>
                            Generate New Codes
                        </button>
                    </form>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                    Store these in a secure place. They can be used if you lose access to your authenticator device.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach (json_decode(decrypt(Auth::user()->two_factor_recovery_codes), true) as $code)
                        <div class="font-mono text-sm tracking-wider rounded-md border border-gray-200 dark:border-slate-700 bg-gray-50/60 dark:bg-slate-800 px-3 py-2 text-gray-800 dark:text-gray-100">
                            {{ $code }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Disable 2FA --}}
            <div class="pt-4 border-t border-gray-200 dark:border-slate-700">
                <form method="POST" action="{{ route('two-factor.disable') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-red-400">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2zm5 11H7v-2h10z"/></svg>
                        Disable Two-Factor
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
