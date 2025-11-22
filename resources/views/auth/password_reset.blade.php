@extends('layouts.auth')

@section('content')
<div class="container max-w-4xl mx-auto mt-8 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Email Reset Card -->
        <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl transition-all duration-300 hover:shadow-2xl">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-t-2xl p-6 text-center">
                <h3 class="text-lg font-semibold tracking-tight">
                    <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    Reset Password via Email
                </h3>
            </div>
            <div class="p-6 text-center">
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-50 mb-4 mx-auto">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm mb-6">Request a password reset link to be sent to your registered email address.</p>
                <form action="{{ route('password.email') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-6">
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </span>
                            <input type="email" name="email" 
                                   class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200"
                                   placeholder="Enter your email" required>
                        </div>
                    </div>
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                        Send Reset Link
                    </button>
                </form>
            </div>
        </div>

        <!-- Phone Reset Card -->
        <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl transition-all duration-300 hover:shadow-2xl">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-t-2xl p-6 text-center">
                <h3 class="text-lg font-semibold tracking-tight">
                    <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.12.35.03.74-.24 1.02l-2.2 2.2z"/>
                    </svg>
                    Reset Password via Phone
                </h3>
            </div>
            <div class="p-6 text-center">
                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-50 mb-4 mx-auto">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.12.35.03.74-.24 1.02l-2.2 2.2z"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm mb-6">Receive a password reset code via SMS to your registered phone number.</p>
                <form action="{{ route('password.phone') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-6">
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.12.35.03.74-.24 1.02l-2.2 2.2z"/>
                                </svg>
                            </span>
                            <input type="tel" name="phone" 
                                   class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200"
                                   placeholder="Enter your phone number" required>
                        </div>
                    </div>
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                        Send Reset Code
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>
@endsection 