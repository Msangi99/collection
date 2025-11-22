@extends('vender.app')

@section('content')
    <!-- Heroicons CDN for icons -->
    <link href="https://unpkg.com/heroicons@2.1.1/dist/heroicons.min.css" rel="stylesheet">

    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h4 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Update Profile
            </h4>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('vender.profile.update') }}" method="POST">
                @csrf
                <!-- Personal Information Section -->
                <h5 class="text-lg font-semibold text-blue-500 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0H9a3 3 0 116 0h-1m-4 0a3 3 0 11-6 0m9 0a3 3 0 01-3 3"></path>
                    </svg>
                    Personal Information
                </h5>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Name
                    </label>
                    <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="name" name="name"
                           value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email
                    </label>
                    <input type="email" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="email" name="email"
                           value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="contact" class="block text-sm font-medium text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Contact
                    </label>
                    <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="contact" name="contact"
                           value="{{ old('contact', auth()->user()->contact ?? '') }}">
                    @error('contact')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Vendor Account Information Section -->
                @if (!auth()->user()->VenderAccount || empty(auth()->user()->VenderAccount->tin))
                    <h5 class="text-lg font-semibold text-blue-500 mb-4 mt-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Vendor Account Information
                    </h5>
                    <div class="mb-4">
                        <label for="tin" class="block text-sm font-medium text-gray-700">TIN</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="tin" name="tin"
                               value="{{ old('tin', auth()->user()->VenderAccount->tin ?? '') }}">
                        @error('tin')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <!-- Physical Address Section -->
                <h5 class="text-lg font-semibold text-blue-500 mb-4 mt-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Physical Address
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="house_number" class="block text-sm font-medium text-gray-700">House Number</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="house_number" name="house_number"
                               value="{{ old('house_number', auth()->user()->VenderAccount->house_number ?? '') }}">
                        @error('house_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="street" class="block text-sm font-medium text-gray-700">Street</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="street" name="street"
                               value="{{ old('street', auth()->user()->VenderAccount->street ?? '') }}">
                        @error('street')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="town" class="block text-sm font-medium text-gray-700">Town</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="town" name="town"
                               value="{{ old('town', auth()->user()->VenderAccount->town ?? '') }}">
                        @error('town')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-gray-700">District/City</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="city" name="city"
                               value="{{ old('city', auth()->user()->VenderAccount->city ?? '') }}">
                        @error('city')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="province" class="block text-sm font-medium text-gray-700">Region/Province</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="province" name="province"
                               value="{{ old('province', auth()->user()->VenderAccount->province ?? '') }}">
                        @error('province')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="country" name="country"
                           value="{{ old('country', auth()->user()->VenderAccount->country ?? '') }}">
                    @error('country')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="work" class="block text-sm font-medium text-gray-700">Working Center</label>
                    <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="work" name="work"
                           value="{{ old('work', auth()->user()->VenderAccount->work ?? '') }}">
                    @error('work')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contact Information Section -->
                <h5 class="text-lg font-semibold text-blue-500 mb-4 mt-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Contact Information
                </h5>
                <div class="mb-4">
                    <label for="altenative_number" class="block text-sm font-medium text-gray-700">Alternative Number</label>
                    <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="altenative_number" name="altenative_number"
                           value="{{ old('altenative_number', auth()->user()->VenderAccount->altenative_number ?? '') }}">
                    @error('altenative_number')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Bank Account Section -->
                @if (
                    !auth()->user()->VenderAccount ||
                        (empty(auth()->user()->VenderAccount->bank_name) && empty(auth()->user()->VenderAccount->bank_number)))
                    <h5 class="text-lg font-semibold text-blue-500 mb-4 mt-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Bank Account
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                            <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="bank_name" name="bank_name"
                                   value="{{ old('bank_name', auth()->user()->VenderAccount->bank_name ?? '') }}">
                            @error('bank_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="bank_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                            <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="bank_number" name="bank_number"
                                   value="{{ old('bank_number', auth()->user()->VenderAccount->bank_number ?? '') }}">
                            @error('bank_number')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <!-- Vendor Balance Section -->
                <h5 class="text-lg font-semibold text-blue-500 mb-4 mt-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Vendor Balance
                </h5>
                <div class="mb-4">
                    <label for="payment_number" class="block text-sm font-medium text-gray-700">Payment Number</label>
                    <input type="text" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="payment_number" name="payment_number"
                           value="{{ old('payment_number', auth()->user()->VenderBalances->payment_number ?? '') }}">
                    @error('payment_number')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Section -->
                <h5 class="text-lg font-semibold text-blue-500 mb-4 mt-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.104-.896 2-2 2s-2-.896-2-2 2-4 2-4 2 .896 2 2zM16 11c0 1.104-.896 2-2 2s-2-.896-2-2 2-4 2-4 2 .896 2 2zM8 11c0 1.104-.896 2-2 2s-2-.896-2-2 2-4 2-4 2 .896 2 2z"></path>
                    </svg>
                    Security
                </h5>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password (leave blank to keep unchanged)</label>
                    <input type="password" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Leave blank to keep unchanged"
                           id="password" name="password">
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="password_confirmation" name="password_confirmation">
                    @error('password_confirmation')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection