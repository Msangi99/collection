@extends('customer.app')

@section('content')
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-xl font-bold text-gray-800">{{ __('all.edit_booking') }}</h1>
                </div>
            </div>
            <div class="p-4 sm:p-6 w-6/12 justify-center">
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 text-sm rounded-md" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded-md" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('customer.update') }}" method="post">
                    @csrf

                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                     
                    <div class="mb-4">
                        <label for="name"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('all.name') }}</label>
                        <input type="text" id="name" name="name" value="{{ $booking->customer_name ?? '' }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="email"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('all.email') }}</label>
                        <input type="email" id="email" name="email" value="{{ $booking->customer_email ?? '' }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                     <div class="mb-4">
                        <label for="email"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('all.phone') }}</label>
                        <input type="text" id="phone" name="phone" value="{{ $booking->customer_phone ?? '' }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div> 

                    <!-- Add other relevant fields for editing, e.g., seats, etc. -->

                    <div class="flex items-center justify-between">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                            {{ __('all.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
