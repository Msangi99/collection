@extends('test.ap')

@section('content')
<div class="min-h-screen flex items-center justify-center  py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-gray-400">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ __('all.booking_information') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('all.enter_email_phone_number_to_view_booking') }}
            </p>
        </div>
        
        <form action="{{ route('booking_info') }}" method="post" class="mt-8 space-y-6 p-3">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="contactInput" class="sr-only">{{ __('all.email_or_phone_number') }}</label>
                    <input 
                        id="contactInput" 
                        name="data" 
                        type="text" 
                        required 
                        class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                        placeholder="{{ __('all.email_or_phone_number') }}"
                    >
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                >
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <!-- Heroicon name: solid/lock-closed -->
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    {{ __('all.submit_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
