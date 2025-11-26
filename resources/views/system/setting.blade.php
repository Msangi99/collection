@extends('system.app')

@section('content')
<style>
    .toggle-visual {
        width: 44px;
        height: 24px;
        border-radius: 9999px;
        background-color: #e5e7eb;
        position: relative;
        transition: background-color 0.2s ease;
    }
    .toggle-visual::after {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 18px;
        height: 18px;
        border-radius: 9999px;
        background-color: #fff;
        transition: transform 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .toggle-visual.on {
        background-color: #2563eb;
    }
    .toggle-visual.on::after {
        transform: translateX(20px);
    }
</style>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Insurance Settings
            </h1>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('setting.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Insurance Amounts Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Insurance Amounts
                    </h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Foreign Insurance -->
                    <div>
                        <label for="international" class="block text-sm font-medium text-gray-700 mb-1">Foreign Insurance Amount</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="international" name="international" value="{{ old('international', $settings->international ?? '') }}" step="0.01" required
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md" placeholder="0.00">
                        </div>
                        @error('international')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Local Insurance -->
                    <div>
                        <label for="local" class="block text-sm font-medium text-gray-700 mb-1">Local Insurance Amount</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="local" name="local" value="{{ old('local', $settings->local ?? '') }}" step="0.01" required
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md" placeholder="0.00">
                        </div>
                        @error('local')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Service Amounts Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Service Amounts
                    </h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Service Amount -->
                    <div>
                        <label for="service" class="block text-sm font-medium text-gray-700 mb-1">Service Amount</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="service" name="service" value="{{ old('service', $settings->service ?? '') }}" step="0.01" required
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md" placeholder="0.00">
                        </div>
                        @error('service')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Percentage -->
                    <div>
                        <label for="service_percentage" class="block text-sm font-medium text-gray-700 mb-1">Service Percentage</label>
                        <div class="relative rounded-md shadow-sm">
                            <input style="padding-left: 30px;" type="number" id="service_percentage" name="service_percentage" value="{{ old('service_percentage', $settings->service_percentage ?? '') }}" step="0.01" required
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 py-2 border border-gray-300 rounded-md" placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        @error('service_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden space-y-6">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Notification Preferences
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Choose who should get notified after payments clear.</p>
                </div>

                <div class="px-6 pb-6 space-y-4">
                    <h3 class="text-md font-semibold text-gray-700">Customer notifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start justify-between border rounded-lg p-4">
                            <div>
                                <p class="text-base font-medium text-gray-800">Customer SMS</p>
                                <p class="text-sm text-gray-500">Send trip details to the passenger phone number.</p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_customer_sms_notifications" value="1"
                                       class="sr-only toggle-input"
                                       data-toggle-target="toggle-customer-sms"
                                       {{ ($settings->enable_customer_sms_notifications ?? true) ? 'checked' : '' }}>
                                <div id="toggle-customer-sms" class="toggle-visual {{ ($settings->enable_customer_sms_notifications ?? true) ? 'on' : '' }}"></div>
                            </label>
                        </div>
                        <div class="flex items-start justify-between border rounded-lg p-4">
                            <div>
                                <p class="text-base font-medium text-gray-800">Customer email</p>
                                <p class="text-sm text-gray-500">Send confirmation emails to the passenger email address.</p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_customer_email_notifications" value="1"
                                       class="sr-only toggle-input"
                                       data-toggle-target="toggle-customer-email"
                                       {{ ($settings->enable_customer_email_notifications ?? true) ? 'checked' : '' }}>
                                <div id="toggle-customer-email" class="toggle-visual {{ ($settings->enable_customer_email_notifications ?? true) ? 'on' : '' }}"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-6 space-y-4 border-t border-gray-100">
                    <h3 class="text-md font-semibold text-gray-700">Conductor notifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start justify-between border rounded-lg p-4">
                            <div>
                                <p class="text-base font-medium text-gray-800">Conductor SMS</p>
                                <p class="text-sm text-gray-500">Send seat assignment to the conductor phone number.</p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_conductor_sms_notifications" value="1"
                                       class="sr-only toggle-input"
                                       data-toggle-target="toggle-conductor-sms"
                                       {{ ($settings->enable_conductor_sms_notifications ?? true) ? 'checked' : '' }}>
                                <div id="toggle-conductor-sms" class="toggle-visual {{ ($settings->enable_conductor_sms_notifications ?? true) ? 'on' : '' }}"></div>
                            </label>
                        </div>
                        <div class="flex items-start justify-between border rounded-lg p-4">
                            <div>
                                <p class="text-base font-medium text-gray-800">Conductor email</p>
                                <p class="text-sm text-gray-500">Email itinerary to the conductor/company email if available.</p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_conductor_email_notifications" value="1"
                                       class="sr-only toggle-input"
                                       data-toggle-target="toggle-conductor-email"
                                       {{ ($settings->enable_conductor_email_notifications ?? true) ? 'checked' : '' }}>
                                <div id="toggle-conductor-email" class="toggle-visual {{ ($settings->enable_conductor_email_notifications ?? true) ? 'on' : '' }}"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-input').forEach(function (input) {
            var target = document.getElementById(input.dataset.toggleTarget);
            if (!target) return;
            var sync = function () {
                target.classList.toggle('on', input.checked);
            };
            input.addEventListener('change', sync);
            sync();
        });
    });
</script>
@endsection