@extends('test.ap')

@section('content')
    <section class="bg-gradient-to-b from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('customer/busroot.complete_your_payment') }}</h2>
                </div>
                <div class="text-sm text-white bg-red-600 px-4 py-2 rounded-lg shadow-sm flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <span>{{ __('customer/busroot.your_session_expires_in') }} <span id="minutes">06</span>
                        {{ __('customer/busroot.mins') }} <span id="seconds">40</span>
                        {{ __('customer/busroot.secs') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Payment Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact Details Card -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                {{ __('customer/busroot.contact_details') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ __('customer/busroot.fill_traveler_details') }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Country Code -->
                                <div>
                                    <label for="countrycode"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.country_code') }}</label>
                                    <select id="countrycode" onchange="setPhoneMaxLength()"
                                        class="w-full px-4 text-gray-600 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">{{ __('customer/busroot.select_country_code') }}</option>
                                        <option value="+255" selected>{{ __('customer/busroot.tz_code') }}</option>
                                    </select>
                                </div>

                                <!-- Mobile Number -->
                                <div>
                                    <label for="contactNumber"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.mobile_number') }}</label>
                                    <input type="text" id="contactNumber" maxlength="12" onkeyup="CheckMobLen(this)"
                                        class="w-full text-black px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 onlydigits"
                                        placeholder="{{ __('customer/busroot.enter_mobile_number') }}" required>
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="contactEmail"
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.email_address') }}</label>
                                    <input type="email" id="contactEmail" maxlength="50" autocomplete="off"
                                        class="w-full text-black px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="{{ __('customer/busroot.enter_email_address') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Options Card -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                {{ __('customer/busroot.payment_options') }}</h3>

                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- Payment Methods Sidebar -->
                                <div class="md:w-1/3">
                                    <div class="space-y-2" role="tablist"
                                        aria-label="{{ __('customer/busroot.payment_methods') }}">
                                        <button type="button"
                                            class="w-full text-left px-4 py-3 rounded-lg bg-blue-100 text-blue-700 font-medium"
                                            id="tab1-btn" data-bs-toggle="tab" data-bs-target="#tab1" role="tab"
                                            aria-controls="tab1" aria-selected="true">
                                            <i class="fas fa-mobile-alt mr-2"></i> {{ __('customer/busroot.mixx_by_yas') }}
                                        </button>
                                        <button type="button"
                                            class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-100" id="tab2-btn"
                                            data-bs-toggle="tab" data-bs-target="#tab2" role="tab" aria-controls="tab2">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            {{ __('customer/busroot.dpo_payment') }}
                                        </button>   
                                    </div>
                                </div>

                                <!-- Payment Method Content -->
                                <div class="md:w-2/3">
                                    <div class="tab-content">
                                        <!-- Mixx By Yas Payment -->
                                        <div id="tab1" class="tab-pane active" role="tabpanel"
                                            aria-labelledby="tab1-btn">
                                            <form id="tigo" action="{{ route('round.trip.get_payment') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="payment_method" value="mixx">
                                                <div class="space-y-4">
                                                    <div class="p-4 bg-blue-50 rounded-lg">
                                                        <p class="text-sm text-gray-700 mb-1">
                                                            {{ __('customer/busroot.session_expiry_warning') }}</p>
                                                        <p class="text-lg font-bold text-green-600">
                                                            {{ __('customer/busroot.total') }} {{ $currency }}.
                                                            {{ convert_money($price + $fees) }}</p>
                                                    </div>

                                                    <p class="text-gray-700">
                                                        {{ __('customer/busroot.enter_yas_mobile_number') }}</p>

                                                    <div>
                                                        <label for="paymentContact"
                                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.mobile_number') }}</label>
                                                        <input type="text" name="payment_contact" id="paymentContact"
                                                            maxlength="10"
                                                            class="text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 onlydigits"
                                                            placeholder="{{ __('customer/busroot.connected_mobile_number') }}"
                                                            required>
                                                    </div>

                                                    <input type="hidden" name="amount" value="{{ $price + $fees }}">

                                                    <div class="flex items-start">
                                                        <div class="flex items-center h-5">
                                                            <input id="payment_term_0" name="payment_term_0"
                                                                type="checkbox" value="1" checked
                                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="payment_term_0"
                                                                class="font-medium text-gray-700">{{ __('customer/busroot.i_accept') }}
                                                                <a href="{{ route('ticket.purchase') }}"
                                                                    class="text-blue-600 hover:text-blue-500">{{ __('customer/busroot.terms_and_conditions') }}</a></label>
                                                        </div>
                                                    </div>

                                                    <button type="submit"
                                                        class="w-full mt-4 py-3 px-6 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-medium rounded-lg shadow-md transition-all duration-300 flex items-center justify-center">
                                                        <i class="fas fa-lock mr-2"></i>
                                                        {{ __('customer/busroot.proceed_to_pay') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- DPO Payment -->
                                        <div id="tab2" class="tab-pane" role="tabpanel" aria-labelledby="tab2-btn">
                                            <form id="dpo" action="{{ route('round.trip.get_payment') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="payment_method" value="dpo">
                                                <div class="space-y-4">
                                                    <div class="p-4 bg-blue-50 rounded-lg">
                                                        <p class="text-sm text-gray-700 mb-1">
                                                            {{ __('customer/busroot.session_expiry_warning') }}</p>
                                                        <p class="text-lg font-bold text-green-600">
                                                            {{ __('customer/busroot.total') }} {{ $currency }}.
                                                            {{ convert_money($price + $fees) }}</p>
                                                    </div>

                                                    <div>
                                                        <label for="dpo_amount"
                                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.amount') }}</label>
                                                        <input type="text" name="amount_2" id="dpo_amount"
                                                            value="{{ convert_money($price + $fees) }}" readonly
                                                            class="text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                        <input type="hidden" name="amount" id="dpo_amount"
                                                            value="{{ $price + $fees }}" readonly
                                                            class="text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            required>
                                                    </div>

                                                    <!--
                                                    <div>
                                                        <label for="first_name"
                                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.first_name') }}</label>
                                                        <input type="text" name="first_name" id="first_name"
                                                            class="text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="{{ __('customer/busroot.enter_first_name') }}"
                                                            required>
                                                    </div>
                                                    -->

                                                    <!--
                                                    <div>
                                                        <label for="last_name"
                                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.last_name') }}</label>
                                                        <input type="text" name="last_name" id="last_name"
                                                            class="text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="{{ __('customer/busroot.enter_last_name') }}"
                                                            required>
                                                    </div>
                                                    -->

                                                    <div>
                                                        <label for="phone"
                                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.mobile_number') }}</label>
                                                        <!-- <input type="text" name="customer_number" id="phone"
                                                            maxlength="12"
                                                            class="text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 onlydigits"
                                                            placeholder="{{ __('customer/busroot.enter_mobile_number') }}"
                                                            required> -->
                                                    </div>

                                                    <!--
                                                    <div>
                                                        <label for="email"
                                                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('customer/busroot.email_address') }}</label>
                                                        <input type="email" name="customer_email" id="email"
                                                            class="text-black w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="{{ __('customer/busroot.enter_email_address') }}"
                                                            required>
                                                    </div>
                                                    -->

                                                    <div class="flex items-start">
                                                        <div class="flex items-center h-5">
                                                            <input id="dpo_terms" name="dpo_terms" type="checkbox"
                                                                value="1" checked
                                                                class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <label for="dpo_terms"
                                                                class="font-medium text-gray-700">{{ __('customer/busroot.i_accept') }}
                                                                <a href="{{ route('ticket.purchase') }}"
                                                                    class="text-blue-600 hover:text-blue-500">{{ __('customer/busroot.terms_and_conditions') }}</a></label>
                                                        </div>
                                                    </div>

                                                    <button type="submit"
                                                        class="w-full mt-4 py-3 px-6 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-medium rounded-lg shadow-md transition-all duration-300 flex items-center justify-center">
                                                        <i class="fas fa-lock mr-2"></i>
                                                        {{ __('customer/busroot.proceed_to_pay') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Cash Payment -->
                                        <div id="tab3" class="tab-pane" role="tabpanel" aria-labelledby="tab3-btn">
                                            <form id="cash" action="{{ route('round.trip.get_payment') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="payment_method" value="cash">
                                                <input type="hidden" name="amount" value="{{ $price + $fees }}">
                                                <div class="space-y-4">
                                                    <div class="p-4 bg-blue-50 rounded-lg">
                                                        <p class="text-sm text-gray-700 mb-1">
                                                            {{ __('customer/busroot.session_expiry_warning') }}</p>
                                                        <p class="text-lg font-bold text-green-600">
                                                            {{ __('customer/busroot.total') }} {{ $currency }}.
                                                            {{ convert_money($price + $fees) }}</p>
                                                    </div>

                                                    <button type="submit"
                                                        class="w-full mt-4 py-3 px-6 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-medium rounded-lg shadow-md transition-all duration-300 flex items-center justify-center">
                                                        <i class="fas fa-money-bill mr-2"></i>
                                                        {{ __('customer/busroot.confirm_cash_payment') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Price Summary -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden h-fit sticky top-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-receipt mr-2 text-blue-500"></i> {{ __('customer/busroot.price_summary') }}
                        </h3>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">{{ __('customer/busroot.discount') }}</span>
                                <span class="text-sm font-medium text-gray-500">TZS {{ number_format($dis, 2) }}</span>
                            </div>

                            @if (isset($ins) && $ins > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">{{ __('customer/busroot.insurance') }}</span>
                                    <span class="text-sm font-medium text-gray-500">TZS {{ number_format($ins) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">{{ __('customer/busroot.system_charge') }}</span>
                                <span class="text-sm font-medium text-gray-500">TZS {{ convert_money($fees) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">{{ __('customer/busroot.bus_fare') }}</span>
                                <span class="text-sm font-medium text-gray-500">TZS
                                    {{ convert_money($price - $ins) }}</span>
                            </div>

                            <div class="border-t border-gray-200 pt-2 mt-2 flex justify-between">
                                <span class="text-base font-semibold">{{ __('customer/busroot.total_payable') }}</span>
                                <span class="text-base font-bold text-blue-600">
                                    TZS {{ convert_money($price + $fees) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="flex items-center text-sm text-blue-700">
                                <i class="fas fa-shield-alt mr-2"></i> {{ __('customer/busroot.secure_ssl_payment') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Timer countdown functionality
        function startTimer(duration, displayMinutes, displaySeconds) {
            let timer = duration,
                minutes, seconds;
            setInterval(function() {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                displayMinutes.textContent = minutes;
                displaySeconds.textContent = seconds;

                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
        }

        window.onload = function() {
            const fiveMinutes = 60 * 6 + 40, // 6 minutes and 40 seconds
                displayMinutes = document.querySelector('#minutes'),
                displaySeconds = document.querySelector('#seconds');
            startTimer(fiveMinutes, displayMinutes, displaySeconds);
        };

        // Form submission handler for Tigo form
        document.getElementById('tigo').addEventListener('submit', function(event) {
            event.preventDefault();

            // Get contact details
            const code = document.getElementById('countrycode').value;
            const phone = document.getElementById('contactNumber').value;
            const email = document.getElementById('contactEmail').value;

            // Create hidden inputs
            const codeInput = document.createElement('input');
            codeInput.type = 'hidden';
            codeInput.name = 'countrycode';
            codeInput.value = code;

            const phoneInput = document.createElement('input');
            phoneInput.type = 'hidden';
            phoneInput.name = 'contactNumber';
            phoneInput.value = phone;

            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'contactEmail';
            emailInput.value = email;

            // Append to form
            this.appendChild(codeInput);
            this.appendChild(phoneInput);
            this.appendChild(emailInput);

            // Submit form
            this.submit();
        });

        // Form submission handler for DPO form
        document.getElementById('dpo').addEventListener('submit', function(event) {
            event.preventDefault();

            // Get contact details
            const code = document.getElementById('countrycode').value;
            const phone = document.getElementById('contactNumber').value;
            const email = document.getElementById('contactEmail').value;

            // Create hidden inputs
            const codeInput = document.createElement('input');
            codeInput.type = 'hidden';
            codeInput.name = 'countrycode';
            codeInput.value = code;

            const phoneInput = document.createElement('input');
            phoneInput.type = 'hidden';
            phoneInput.name = 'contactNumber';
            phoneInput.value = phone;

            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'contactEmail';
            emailInput.value = email;

            // Append to form
            this.appendChild(codeInput);
            this.appendChild(phoneInput);
            this.appendChild(emailInput);

            // Submit form
            this.submit();
        });

        // Form submission handler for Cash form
        document.getElementById('cash').addEventListener('submit', function(event) {
            event.preventDefault();

            // Get contact details
            const code = document.getElementById('countrycode').value;
            const phone = document.getElementById('contactNumber').value;
            const email = document.getElementById('contactEmail').value;

            // Create hidden inputs
            const codeInput = document.createElement('input');
            codeInput.type = 'hidden';
            codeInput.name = 'countrycode';
            codeInput.value = code;

            const phoneInput = document.createElement('input');
            phoneInput.type = 'hidden';
            phoneInput.name = 'contactNumber';
            phoneInput.value = phone;

            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'contactEmail';
            emailInput.value = email;

            // Append to form
            this.appendChild(codeInput);
            this.appendChild(phoneInput);
            this.appendChild(emailInput);

            // Submit form
            this.submit();
        });

        // Tab functionality
        document.querySelectorAll('[role="tablist"] button').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active states
                document.querySelectorAll('[role="tablist"] button').forEach(btn => {
                    btn.classList.remove('bg-blue-100', 'text-blue-700');
                    btn.classList.add('hover:bg-gray-100');
                });
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('active');
                });

                // Add active states
                button.classList.add('bg-blue-100', 'text-blue-700');
                button.classList.remove('hover:bg-gray-100');
                document.querySelector(button.dataset.bsTarget).classList.add('active');
            });
        });
    </script>

    <style>
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .onlydigits {
            -moz-appearance: textfield;
        }

        .onlydigits::-webkit-outer-spin-button,
        .onlydigits::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection
