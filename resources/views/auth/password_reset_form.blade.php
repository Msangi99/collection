
@extends('layouts.auth')

@section('content')
<div class="container max-w-md mx-auto mt-8 mb-8">
    <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl transition-all duration-300 hover:shadow-2xl">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-t-2xl p-6 text-center">
            <h3 class="text-lg font-semibold tracking-tight">
                <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                </svg>
                Reset Your Password
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('password.reset') }}" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" 
                               class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('password') border-red-400 focus:ring-red-400 @enderror"
                               placeholder="Enter new password" required>
                        @error('password')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('password_confirmation') border-red-400 focus:ring-red-400 @enderror"
                               placeholder="Confirm new password" required>
                        @error('password_confirmation')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                        Reset Password
                    </button>
                </div>
            </form>
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