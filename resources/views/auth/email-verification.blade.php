@extends('layouts.auth')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Email Verification</h2>
        <p class="text-gray-600">Please verify your email address to continue</p>
    </div>

    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
        <strong>Email Verification Required</strong><br>
        Please check your email and enter the 6-digit verification code that was sent to your email address.
    </div>

    <form method="POST" action="{{ route('email.verification.verify') }}" class="space-y-4">
        @csrf

        <div>
            <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
            <input id="verification_code" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('verification_code') border-red-500 @enderror" 
                   name="verification_code" value="{{ old('verification_code') }}" required 
                   maxlength="6" pattern="[0-9]{6}" placeholder="123456">
            @error('verification_code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
            Verify Email
        </button>
    </form>

    <div class="mt-6 pt-4 border-t border-gray-200">
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-3">Didn't receive the code?</p>
            <form method="POST" action="{{ route('email.verification.resend') }}" class="inline">
                @csrf
                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium underline">
                    Resend Verification Code
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                Back to Login
            </a>
        </div>
    </div>
</div>

<script>
// Auto-focus on verification code input
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('verification_code');
    
    if (codeInput) {
        codeInput.focus();
    }
    
    // Auto-submit when 6 digits are entered
    codeInput.addEventListener('input', function() {
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
});
</script>
@endsection

