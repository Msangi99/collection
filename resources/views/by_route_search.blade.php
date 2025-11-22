@extends('test.ap')

@section('content')
    @include('test.sach')
    
    <section class="py-8 bg-transparent">
        @if ($busList->isEmpty())
            <div class="max-w-4xl mx-auto glass-card p-6 text-center">
                <div class="bg-indigo-100/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bus text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">{{ __('all.no_buses_available') }}</h3>
                <p class="text-gray-600 mb-4">{{ __('all.try_different_search_criteria') }}</p>
                <a href="{{ URL::previous() }}" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-md text-sm font-medium inline-flex items-center">
                    <i class="fas fa-arrow-left mr-1"></i> {{ __('all.back_button') }}
                </a>
            </div>
        @else
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($busList as $bus)
                    <div class="rounded-2xl p-4 hover:shadow-md transition-all bg-gray-500">
                        <!-- Bus Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $bus->busname->name ?? 'N/A' }}</h3>
                                <div class="flex items-center mt-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    @php
                                        $mode = [
                                            '10' => __('all.luxury'),
                                            '20' => __('all.upper_semiluxury'),
                                            '30' => __('all.lower_semiluxury'),
                                            '40' => __('all.ordinary'),
                                        ];
                                        $busType = isset($bus->bus_type) && array_key_exists($bus->bus_type, $mode) ? $mode[$bus->bus_type] : 'N/A';
                                    @endphp
                                    <span class="text-xs text-white">{{ $busType }}</span>
                                </div>
                            </div>
                            <div class="bg-indigo-100/30 px-2 py-1 rounded text-xs text-indigo-800">
                                {{ $bus->bus_number ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Route Info -->
                        <div class="flex items-center justify-between text-sm mb-3">
                            <div class="font-medium">{{ $bus->schedule->from ?? 'N/A' }}</div>
                            <div class="text-gray-500 mx-2">
                                @php
                                    $durationText = 'N/A';
                                    if ($bus->route && $bus->schedule->start && $bus->schedule->end) {
                                        try {
                                            $startTime = \Carbon\Carbon::parse($bus->schedule->start);
                                            $endTime = \Carbon\Carbon::parse($bus->schedule->end);
                                            
                                            if ($endTime->lessThan($startTime)) {
                                                $endTime->addDay();
                                            }
                                            
                                            $totalMinutes = $startTime->diffInMinutes($endTime);
                                            $hours = floor($totalMinutes / 60);
                                            $minutes = $totalMinutes % 60;
                                            $durationText = sprintf('%dh%dm', $hours, $minutes);
                                        } catch (\Exception $e) {
                                            $durationText = 'N/A';
                                        }
                                    }
                                @endphp
                                {{ $durationText }}
                            </div>
                            <div class="font-medium">{{ $bus->schedule->to ?? 'N/A' }}</div>
                        </div>

                        <!-- Timing -->
                        <div class="flex justify-between text-xs text-white mb-3">
                            <div>{{ $bus->schedule->start ?? 'N/A' }}</div>
                            <div>{{ $bus->schedule->end ?? 'N/A' }}</div>
                        </div>

                        <!-- Price & CTA -->
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                            <div>
                                <div class="text-xs text-gray-500">{{ __('all.from_price') }}</div>
                                <div class="font-bold text-indigo-600">Tsh. {{ number_format($bus->route->price ?? 0) }}</div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-white">
                                    <i class="fas fa-chair text-yellow-500 mr-1"></i>{{ $bus->remain_seats ?? 'N/A' }}
                                </span>
                                <a href="{{ route('booking_form', ['id' => $bus->id, 'from' => $bus->schedule->from ?? 'N/A', 'to' => $bus->schedule->to ?? 'N/A']) }}" 
                                   class="px-3 py-1 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded text-xs font-medium hover:opacity-90">
                                    {{ __('all.book_button') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
