 
@extends('admin.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modern Card Design -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Gradient Header -->
            <div class="bg-gradient-to-r from-teal-600 to-green-700 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-2 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-white">{{ __('vender/profile.profile_settings') }}</h2>
                    </div>
                    <div class="text-sm text-white/80">
                        {{ __('vender/profile.last_updated') }} {{ now()->format('M d, Y') }}
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6 md:p-8">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-5">
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                            <h3 class="px-3 text-lg font-medium text-gray-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('vender/profile.personal_information') }}
                            </h3>
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.full_name') }}</label>
                                <div class="relative">
                                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-1">
                                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.email_address') }}</label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                           required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-1">
                                <label for="contact" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.phone_number') }}</label>
                                <div class="relative">
                                    <input type="text" id="contact" name="contact" value="{{ old('contact', auth()->user()->contact ?? '') }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('contact')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Company Information Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-5">
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                            <h3 class="px-3 text-lg font-medium text-gray-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ __('vender/profile.company_details') }}
                            </h3>
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label for="company_name" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.company_name') }}</label>
                                <input type="text" id="company_name" name="campany_name" value="{{ old('campany_name', auth()->user()->campany->name ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="space-y-1">
                                <label for="registration_number" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.registration_number') }}</label>
                                <input type="text" id="registration_number" name="registration_number" 
                                       value="{{ old('registration_number', auth()->user()->campany->busOwnerAccount->registration_number ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                @error('registration_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            @if (empty(auth()->user()->campany->busOwnerAccount->tin))
                                <div class="space-y-1">
                                    <label for="tin" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.tin_number') }}</label>
                                    <input type="text" id="tin" name="tin" value="{{ old('tin', auth()->user()->campany->busOwnerAccount->tin ?? '') }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    @error('tin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            
                            @if (empty(auth()->user()->campany->busOwnerAccount->vrn))
                                <div class="space-y-1">
                                    <label for="vrn" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.vrn_number') }}</label>
                                    <input type="text" id="vrn" name="vrn" value="{{ old('vrn', auth()->user()->campany->busOwnerAccount->vrn ?? '') }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    @error('vrn')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-5">
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                            <h3 class="px-3 text-lg font-medium text-gray-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('vender/profile.business_address') }}
                            </h3>
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-1">
                                <label for="office_number" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.office_number') }}</label>
                                <input type="text" id="office_number" name="office_number" 
                                       value="{{ old('office_number', auth()->user()->campany->busOwnerAccount->office_number ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="street" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.street') }}</label>
                                <input type="text" id="street" name="street" 
                                       value="{{ old('street', auth()->user()->campany->busOwnerAccount->street ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="box" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.po_box') }}</label>
                                <input type="text" id="box" name="box" 
                                       value="{{ old('box', auth()->user()->campany->busOwnerAccount->box ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="town" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.town') }}</label>
                                <input type="text" id="town" name="town" 
                                       value="{{ old('town', auth()->user()->campany->busOwnerAccount->town ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="city" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.district_city') }}</label>
                                <input type="text" id="city" name="city" 
                                       value="{{ old('city', auth()->user()->campany->busOwnerAccount->city ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            
                            <div class="space-y-1">
                                <label for="region" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.region_province') }}</label>
                                <input type="text" id="region" name="region" 
                                       value="{{ old('region', auth()->user()->campany->busOwnerAccount->region ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-5">
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                            <h3 class="px-3 text-lg font-medium text-gray-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ __('vender/profile.contact_information') }}
                            </h3>
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.whatsapp_number') }}</label>
                                <div class="relative">
                                    <input type="text" id="whatsapp_number" name="whatsapp_number" 
                                           value="{{ old('whatsapp_number', auth()->user()->campany->busOwnerAccount->whatsapp_number ?? '') }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Account Section -->
                    @if (empty(auth()->user()->campany->busOwnerAccount->bank_name) && empty(auth()->user()->campany->busOwnerAccount->bank_number))
                        <div class="mb-10">
                            <div class="flex items-center mb-5">
                                <div class="h-0.5 bg-gray-200 flex-1"></div>
                                <h3 class="px-3 text-lg font-medium text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                    </svg>
                                    {{ __('vender/profile.bank_account_details') }}
                                </h3>
                                <div class="h-0.5 bg-gray-200 flex-1"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.bank_name') }}</label>
                                    <input type="text" id="bank_name" name="bank_name" 
                                           value="{{ old('bank_name', auth()->user()->campany->busOwnerAccount->bank_name ?? '') }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                </div>
                                
                                <div class="space-y-1">
                                    <label for="account_number" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.account_number') }}</label>
                                    <input type="text" id="account_number" name="account_number" 
                                           value="{{ old('account_number', auth()->user()->campany->busOwnerAccount->bank_number ?? '') }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Security Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-5">
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                            <h3 class="px-3 text-lg font-medium text-gray-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                {{ __('vender/profile.security_settings') }}
                            </h3>
                            <div class="h-0.5 bg-gray-200 flex-1"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('vender/profile.change_password') }}</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" placeholder="{{ __('vender/profile.leave_blank_password') }}"
                                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            {{ __('vender/profile.cancel') }}
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            {{ __('vender/profile.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 