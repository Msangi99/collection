
@extends('admin.app')

@section('title', __('vender/dashboard.admin_dashboard'))

@section('content')
<div class="container-fluid py-4">
    @if (isset($error))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                <p class="text-yellow-700">{{ $error }}</p>
                <button type="button" class="ml-auto text-yellow-700 hover:text-yellow-900" data-bs-dismiss="alert" aria-label="{{ __('vender/dashboard.close') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @else
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl p-4 transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-teal-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11 2h2v2h8v16H3V4h8V2zm2 2v2h-2V4h2zm-2 6h2v6h-2v-6zm-4 0h2v6H7v-6zm8 0h2v6h-2v-6z"/>
                    </svg>
                    <div>
                        <h5 class="text-sm font-semibold text-gray-700">{{ __('vender/dashboard.todays_earnings') }}</h5>
                        <h4 class="text-lg font-bold text-gray-900">{{ $summary['earnings'] ?? 'Tsh 0' }}</h4>
                        <span class="text-xs text-teal-500">{{ $summary['earnings_change'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl p-4 transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-teal-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
                    </svg>
                    <div>
                        <h5 class="text-sm font-semibold text-gray-700">{{ __('vender/dashboard.todays_bookings') }}</h5>
                        <h4 class="text-lg font-bold text-gray-900">{{ $summary['bookings'] ?? 0 }}</h4>
                        <span class="text-xs text-teal-500">{{ $summary['bookings_change'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl p-4 transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-teal-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM4 8h16v2H4V8zm0 4h16v6H4v-6z"/>
                    </svg>
                    <div>
                        <h5 class="text-sm font-semibold text-gray-700">{{ __('vender/dashboard.bus_with_schedules') }}</h5>
                        <h4 class="text-lg font-bold text-gray-900">{{ $summary['active_buses'] ?? '0/0' }}</h4>
                        <span class="text-xs text-yellow-500">{{ $summary['buses_status'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl p-4 transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-teal-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <div>
                        <h5 class="text-sm font-semibold text-gray-700">{{ __('vender/dashboard.todays_passengers') }}</h5>
                        <h4 class="text-lg font-bold text-gray-900">{{ $summary['passengers'] ?? 0 }}</h4>
                        <span class="text-xs text-teal-500">{{ $summary['occupancy'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Recent Bookings -->
            <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="p-4 border-b border-gray-200">
                    <h6 class="text-base font-semibold text-gray-900">{{ __('vender/dashboard.recent_bookings') }}</h6>
                </div>
                <div class="p-4">
                    @if ($recentBookings->isEmpty())
                        <p class="text-sm text-gray-500">{{ __('vender/dashboard.no_bookings_today') }}</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($recentBookings as $booking)
                                <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                    <svg class="w-5 h-5 text-{{ $booking['icon_class'] }} mr-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h6 class="text-sm font-semibold text-gray-900">{{ $booking['name'] }}</h6>
                                        <p class="text-xs text-gray-500">{{ $booking['route'] }} • {{ $booking['time'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-gray-900">{{ $booking['amount'] }}</p>
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-{{ $booking['status_class'] }}-100 text-{{ $booking['status_class'] }}-700">{{ $booking['status'] }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Today's Trips -->
            <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h6 class="text-base font-semibold text-gray-900">{{ __('vender/dashboard.todays_trips') }}</h6>
                    <a href="#" class="text-sm text-teal-500 hover:text-teal-600 font-medium transition-colors">{{ __('vender/dashboard.view_all') }}</a>
                </div>
                <div class="p-4">
                    @if ($todaysTrips->isEmpty())
                        <p class="text-sm text-gray-500">{{ __('vender/dashboard.no_trips_today') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr>
                                        <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/dashboard.bus') }}</th>
                                        <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/dashboard.route') }}</th>
                                        <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/dashboard.deperture_time') }}</th>
                                        <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/dashboard.date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todaysTrips as $trip)
                                        <tr class="border-t border-gray-200">
                                            <td class="py-3">
                                                <h6 class="text-sm font-semibold text-gray-900">{{ $trip['bus'] }}</h6>
                                            </td>
                                            <td class="py-3">
                                                <p class="text-xs font-semibold text-gray-900">{{ $trip['route'] }}</p>
                                            </td>
                                            <td class="py-3">
                                                <p class="text-xs font-semibold text-gray-900">{{ $trip['start'] }}</p>
                                            </td>
                                            <td class="py-3">
                                                <p class="text-xs font-semibold text-gray-900">{{ $trip['schedule_date'] }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection