@extends('vender.app')

@section('content')
<section class="bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        @if ($busList->isEmpty())
            <div class="bg-white rounded-2xl shadow-md p-6 text-center">
                <i class="fas fa-bus text-6xl text-gray-400 mb-4"></i>
                <h4 class="text-2xl font-bold text-gray-800">{{ __('assistance/booking.no_buses_available') }}</h4>
                <p class="text-gray-500 mt-2">{{ __('assistance/booking.try_different_criteria') }}</p>
                <a href="{{ route('vender.route') }}"
                   class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('assistance/booking.back_to_search') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($busList as $bus)
                    <div class="rounded-2xl p-4 hover:shadow-md transition-assistance/booking bg-gray-500">
                        <!-- Bus Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $bus->busname->name ?? __('assistance/booking.na') }}</h3>
                                <div class="flex items-center mt-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    <span class="text-xs text-white">
                                        @php
                                            $mode = [
                                            '10' => __('assistance/booking.luxury'),
                                            '20' => __('assistance/booking.upper_semiluxury'), 
                                            '30' => __('assistance/booking.lower_semiluxury'),
                                            '40' => __('assistance/booking.ordinary'),
                                        ];
                                            $busType = isset($bus->bus_type) && array_key_exists($bus->bus_type, $mode)
                                                ? $mode[$bus->bus_type]
                                                : __('assistance/booking.na');
                                        @endphp
                                        {{ $busType }}
                                    </span>
                                </div>
                            </div>
                            <div class="bg-indigo-100/30 px-2 py-1 rounded text-xs text-indigo-800">
                                {{ $bus->bus_number ?? __('assistance/booking.na') }}
                            </div>
                        </div>

                        <!-- Route Info -->
                        <div class="flex items-center justify-between text-sm mb-3">
                            <div class="font-medium text-white">{{ $bus->schedule->from ?? __('assistance/booking.na') }}</div>
                            <div class="text-gray-300 mx-2">
                                @php
                                    $durationText = __('assistance/booking.na');
                                    if ($bus->route && $bus->route->route_start && $bus->route->route_end) {
                                        try {
                                            $startTime = \Carbon\Carbon::parse($bus->route->route_start);
                                            $endTime = \Carbon\Carbon::parse($bus->route->route_end);
                                            if ($endTime->lessThan($startTime)) {
                                                $endTime->addDay();
                                            }
                                            $totalMinutes = $startTime->diffInMinutes($endTime);
                                            $hours = floor($totalMinutes / 60);
                                            $minutes = $totalMinutes % 60;
                                            $durationText = sprintf('%dh%dm', $hours, $minutes);
                                        } catch (\Exception $e) {
                                            $durationText = __('assistance/booking.na');
                                        }
                                    }
                                @endphp
                                {{ $durationText }}
                            </div>
                            <div class="font-medium text-white">{{ $bus->schedule->to ?? __('assistance/booking.na') }}</div>
                        </div>

                        <!-- Timing -->
                        <div class="flex justify-between text-xs text-white mb-3">
                            <div>{{ $bus->route->route_start ?? __('assistance/booking.na') }}</div>
                            <div>{{ $bus->route->route_end ?? __('assistance/booking.na') }}</div>
                        </div>

                        <!-- Price & CTA -->
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                            <div>
                                <div class="text-xs text-gray-300">{{ __('assistance/booking.from_label') }}</div>
                                <div class="font-bold text-indigo-600">Tsh. {{ number_format($bus->route->price ?? 0) }}</div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-white">
                                    <i class="fas fa-chair text-yellow-500 mr-1"></i>{{ $bus->remain_seats ?? __('assistance/booking.na') }}
                                </span>
                                <a href="{{ route('vender.booking_form', ['id' => $bus->id, 'from' => $bus->schedule->from ?? __('assistance/booking.na'), 'to' => $bus->schedule->to ?? __('assistance/booking.na')]) }}"
                                   class="px-3 py-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded text-xs font-medium hover:opacity-90 transition"
                                   onclick="checkCurrency(0, 0, 0)">
                                    {{ __('assistance/booking.book') }}
                                </a>
                            </div>
                        </div>

                        <!-- Amenities & Policy -->
                        <div class="flex items-center justify-center space-x-4 mt-3 text-xs text-gray-300">
                            <span class="cursor-pointer hover:text-white transition" onclick="myFunction(0)">{{ __('assistance/booking.amenities') }}</span>
                            <span class="text-gray-400">|</span>
                            <a href="{{ route('policy.booking') }}" class="hover:text-white transition">{{ __('assistance/booking.booking_policy') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
