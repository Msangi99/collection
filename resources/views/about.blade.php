@extends('test.ap')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8 rounded-4xl">
        <!-- Header -->
        <div class="max-w-3xl mx-auto text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('all.about_highlink_company') }}</h1>
            <p class="text-xl text-gray-600">{{ __('all.delivering_solutions_since_2015') }}</p>
        </div>

        <!-- Card Grid -->
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Our Story -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ __('all.our_story') }}</h2>
                        </div>
                        <p class="text-gray-600">
                            {{ __('all.our_story_description') }}
                        </p>
                    </div>
                </div>

                <!-- Vision -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ __('all.our_vision') }}</h2>
                        </div>
                        <p class="text-gray-600">
                            {{ __('all.our_vision_description') }}
                        </p>
                    </div>
                </div>

                <!-- Mission -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ __('all.our_mission') }}</h2>
                        </div>
                        <p class="text-gray-600">
                            {{ __('all.our_mission_description') }}
                        </p>
                    </div>
                </div>

                <!-- Company Philosophy -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ __('all.our_philosophy') }}</h2>
                        </div>
                        <p class="text-gray-600">
                            {{ __('all.our_philosophy_description') }}
                        </p>
                    </div>
                </div>

                <!-- Company Capacity -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 md:col-span-2">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ __('all.our_capacity') }}</h2>
                        </div>
                        <p class="text-gray-600">
                            {{ __('all.our_capacity_description') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-16 bg-white rounded-2xl shadow-lg overflow-hidden p-8 max-w-4xl mx-auto">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('all.contact_us') }}</h2>
                    <div class="space-y-2 text-gray-600">
                        <p>{{ __('all.highlink_company_full_name') }}</p>
                        <p>{{ __('all.address_line_1') }}</p>
                        <p>{{ __('all.address_line_2') }}</p>
                        <p>{{ __('all.email') }} <a href="mailto:support@hisgc.co.tz" class="text-blue-600 hover:text-blue-800 hover:underline">support@hisgc.co.tz</a></p>
                        <p>{{ __('all.phone') }} <a href="tel:+255755879793" class="text-blue-600 hover:text-blue-800 hover:underline">0755 879 793</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
