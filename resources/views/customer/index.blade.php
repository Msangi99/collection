<!-- customer/index.blade.php -->
@extends('customer.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto py-10 px-4">

        <!-- Welcome Header -->
        <div class="mb-12 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">
                {{ __('all.welcome') }} <span class="text-indigo-600">{{ auth()->user()->name }}</span>!
            </h1>
            <p class="text-lg text-gray-500 mt-2">{{ __('all.welcome_to_your_dashboard') }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Successful Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-4 transform hover:scale-105 transition-transform duration-300 ease-in-out">
                <div class="bg-teal-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('all.paid') }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $paidCount }}</p>
                </div>
            </div>
            <!-- Failed Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-4 transform hover:scale-105 transition-transform duration-300 ease-in-out">
                <div class="bg-rose-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('all.failed') }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $failedCount }}</p>
                </div>
            </div>
            <!-- Unpaid Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-4 transform hover:scale-105 transition-transform duration-300 ease-in-out">
                <div class="bg-amber-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('all.unpaid') }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $unpaidCount }}</p>
                </div>
            </div>
            <!-- Cancelled Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center space-x-4 transform hover:scale-105 transition-transform duration-300 ease-in-out">
                <div class="bg-slate-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">{{ __('all.cancelled') }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $cancelledCount }}</p>
                </div>
            </div>
        </div>

        <!-- Action Center -->
        <div class="mt-16">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center md:text-left">{{ __('all.quick_actions') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- My Bookings -->
                <a href="{{ route('customer.mybooking') }}" class="group bg-white p-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center">
                    <div class="bg-indigo-100 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-indigo-600">{{ __('all.view_my_bookings') }}</h3>
                    <p class="text-gray-500 mt-2">{{ __('all.here_are_some_things_you_can_do') }}</p>
                </a>
                <!-- Search for Buses -->
                <a href="{{ route('customer.by_route') }}" class="group bg-white p-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center">
                    <div class="bg-indigo-100 p-4 rounded-full mb-4">
                         <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-indigo-600">{{ __('all.search_buses') }}</h3>
                    <p class="text-gray-500 mt-2">{{ __('all.here_are_some_things_you_can_do') }}</p>
                </a>
                <!-- Edit Profile -->
                <a href="{{ route('customer.profile') }}" class="group bg-white p-8 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center">
                    <div class="bg-indigo-100 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-indigo-600">{{ __('all.profile') }}</h3>
                    <p class="text-gray-500 mt-2">{{ __('all.profile_information') }}</p>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
