 
@extends('layouts.auth')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-2xl">
        <div class="text-center mb-8">
            <h4 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome Back</h4>
            <p class="text-gray-500 text-sm mt-1">Sign in to continue your journey</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
            @csrf
            
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </span>
                    <input id="email" type="email" 
                           class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('email') border-red-400 focus:ring-red-400 @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                        </svg>
                    </span>
                    <input id="password" type="password" 
                           class="w-full pl-10 pr-12 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('password') border-red-400 focus:ring-red-400 @enderror" 
                           name="password" required autocomplete="current-password" placeholder="Enter your password">
                    <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-500 transition-colors" data-target="password">
                        <svg class="w-5 h-5 bi-eye" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6 flex justify-between items-center">
                <div class="flex items-center">
                    <input class="h-4 w-4 text-blue-500 focus:ring-blue-400 border-gray-300 rounded transition-all duration-200" 
                           type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="ml-2 block text-sm text-gray-600" for="remember">Remember Me</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:text-blue-600 font-medium transition-colors">Forgot Password?</a>
            </div>

            <div class="mb-6">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11 16h2v-6h-2v6zM9 16h2v-6H9v6zm6 0h2v-6h-2v6zM3 20.5v-9c0-.83.67-1.5 1.5-1.5h15c.83 0 1.5.67 1.5 1.5v9c0 .83-.67 1.5-1.5 1.5h-15c-.83 0-1.5-.67-1.5-1.5zm1.5-.5h15v-9h-15v9z"/>
                    </svg>
                    Sign In
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-500">Don't have an account? 
                    <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-600 font-medium transition-colors">Create one here</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('svg');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.innerHTML = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';
                } else {
                    input.type = 'password';
                    icon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
                }
            });
        });

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