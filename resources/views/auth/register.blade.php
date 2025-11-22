@extends('layouts.auth')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl p-6 md:p-8 transition-all duration-300 hover:shadow-2xl">
        <div class="text-center mb-8">
            <h4 class="text-2xl font-bold text-gray-900 tracking-tight">Create New Account</h4>
            <p class="text-gray-500 text-sm mt-1">Fill in your details to get started</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </span>
                    <input id="name" type="text" 
                           class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('name') border-red-400 focus:ring-red-400 @enderror"
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your full name">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

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
                           name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.12.35.03.74-.24 1.02l-2.2 2.2z"/>
                        </svg>
                    </span>
                    <input id="contact" type="tel" 
                           class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('contact') border-red-400 focus:ring-red-400 @enderror"
                           name="contact" value="{{ old('contact') }}" required placeholder="Enter your contact number">
                    @error('contact')
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
                           name="password" required autocomplete="new-password" placeholder="Enter your password">
                    <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-500 transition-colors" data-target="password">
                        <svg class="w-5 h-5 bi-eye" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                    <small class="block mt-1 text-xs text-gray-500">Minimum 8 characters with at least one number</small>
                </div>
            </div>

            <div class="mb-6">
                <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                        </svg>
                    </span>
                    <input id="password-confirm" type="password" 
                           class="w-full pl-10 pr-12 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200"
                           name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
                    <button type="button" class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-blue-500 transition-colors" data-target="password-confirm">
                        <svg class="w-5 h-5 bi-eye" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                        </svg>
                    </span>
                    <select id="role" 
                            class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('role') border-red-400 focus:ring-red-400 @enderror"
                            name="role" required>
                        <option value="" disabled selected>Select your role</option>
                        <option value="bus_campany" {{ old('role') == 'bus_campany' ? 'selected' : '' }}>Bus Company</option>
                        <option value="vender" {{ old('role') == 'vender' ? 'selected' : '' }}>Vendor</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6" id="company-field" style="display: none;">
                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM4 8h16v2H4V8zm0 4h16v6H4v-6z"/>
                        </svg>
                    </span>
                    <input id="company" type="text" 
                           class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('campany') border-red-400 focus:ring-red-400 @enderror"
                           name="campany" value="{{ old('campany') }}" autocomplete="company" placeholder="Enter company name">
                    @error('campany')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6" id="payment-number-field" style="display: none;">
                <label for="payment_number" class="block text-sm font-medium text-gray-700 mb-1">Payment Number</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                        </svg>
                    </span>
                    <input id="payment_number" type="text" 
                           class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 bg-gray-50/50 text-gray-900 placeholder-gray-400 transition-all duration-200 @error('payment_number') border-red-400 focus:ring-red-400 @enderror"
                           name="payment_number" value="{{ old('payment_number') }}" autocomplete="payment_number" placeholder="Enter payment number">
                    @error('payment_number')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" 
                       class="h-4 w-4 text-blue-500 focus:ring-blue-400 border-gray-300 rounded transition-all duration-200" 
                       id="terms" name="terms" required>
                <label class="ml-2 block text-sm text-gray-600" for="terms">
                    I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-blue-500 hover:text-blue-600 font-medium transition-colors">Terms and Conditions</a>
                </label>
                @error('terms')
                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    Register
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-500">Already have an account? 
                    <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-600 font-medium transition-colors">Login here</a>
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

        // Show/hide fields based on role selection
        const roleSelect = document.getElementById('role');
        const companyField = document.getElementById('company-field');
        const paymentNumberField = document.getElementById('payment-number-field');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'bus_campany') {
                companyField.style.display = 'block';
                companyField.querySelector('input').setAttribute('required', 'required');
                paymentNumberField.style.display = 'block';
                paymentNumberField.querySelector('input').setAttribute('required', 'required');
            } else if (this.value === 'vender') {
                companyField.style.display = 'none';
                companyField.querySelector('input').removeAttribute('required');
                paymentNumberField.style.display = 'block';
                paymentNumberField.querySelector('input').setAttribute('required', 'required');
            } else {
                companyField.style.display = 'none';
                companyField.querySelector('input').removeAttribute('required');
                paymentNumberField.style.display = 'none';
                paymentNumberField.querySelector('input').removeAttribute('required');
            }
        });

        // Trigger change event on page load if there's already a selected value
        if (roleSelect.value) {
            roleSelect.dispatchEvent(new Event('change'));
        }

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