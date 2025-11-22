@extends('vender.app')

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
            {{ __('vender/busroot.deposit_to_vendor_wallet') }}
        </h2>

        {{-- Success & Error Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Deposit Form --}}
        <form method="POST" action="{{ route('vender.wallet.processDeposit') }}" class="space-y-5">
            @csrf

            {{-- Amount --}}
            <div>
                <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1">
                    {{ __('vender/busroot.amount') }}
                </label>
                <input id="amount" type="number" name="amount" min="1"
                    value="{{ old('amount') }}"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-200 h-10 px-2 focus:border-blue-500 @error('amount') border-red-500 @enderror"
                    required autofocus>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Payment Method --}}
            <div>
                <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-1">
                    {{ __('vender/busroot.payment_method') }}
                </label>
                <select id="payment_method" name="payment_method" onchange="toggleTigosecureFields()"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-200 h-10 px-2 focus:border-blue-500 @error('payment_method') border-red-500 @enderror"
                    required>
                    <option value="">{{ __('vender/busroot.select_method') }}</option>
                    {{-- <option value="tigosecure" {{ old('payment_method') == 'tigosecure' ? 'selected' : '' }}>Tigosecure</option> --}}
                    <option value="pdo" {{ old('payment_method') == 'pdo' ? 'selected' : '' }}>{{ __('vender/busroot.pdo') }}</option>
                </select>
                @error('payment_method')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tigosecure Fields --}}
            <div id="tigosecure-fields" class="space-y-4"
                style="display: {{ old('payment_method') == 'tigosecure' ? 'block' : 'none' }};">

                <div>
                    <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-1">
                        {{ __('vender/busroot.phone_number_tigosecure') }}
                    </label>
                    <input id="phone_number" type="text" name="phone_number"
                        value="{{ old('phone_number') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-200 h-10 px-2 focus:border-blue-500 @error('phone_number') border-red-500 @enderror">
                    @error('phone_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-1">
                        {{ __('vender/busroot.first_name_tigosecure') }}
                    </label>
                    <input id="first_name" type="text" name="first_name"
                        value="{{ old('first_name') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-200 h-10 px-2 focus:border-blue-500 @error('first_name') border-red-500 @enderror">
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-1">
                        {{ __('vender/busroot.last_name_tigosecure') }}
                    </label>
                    <input id="last_name" type="text" name="last_name"
                        value="{{ old('last_name') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-200 h-10 px-2 focus:border-blue-500 @error('last_name') border-red-500 @enderror">
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                        {{ __('vender/busroot.email_tigosecure') }}
                    </label>
                    <input id="email" type="email" name="email"
                        value="{{ old('email') }}"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-200 h-10 px-2 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="text-center">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow focus:outline-none focus:ring-blue-300 transition">
                    {{ __('vender/busroot.deposit_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleTigosecureFields() {
        const paymentMethod = document.getElementById('payment_method').value;
        const tigosecureFields = document.getElementById('tigosecure-fields');
        tigosecureFields.style.display = paymentMethod === 'tigosecure' ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', toggleTigosecureFields);
</script>
@endsection
