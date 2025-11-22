 
@extends('customer.app')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">{{ __('customer/busroot.available_buses', ['departureCityName' => $departureCityName, 'arrivalCityName' => $arrivalCityName, 'departure_date' => $departure_date]) }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if (count($busList) > 0)
                @foreach ($busList as $bus)
                    @if ($bus->schedule != null)
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <form>
                                @csrf
                                <input type="hidden" name="bus_id" value="{{ $bus->id }}">
                                <input type="hidden" name="from" value="{{ $bus->schedule->from ?? '' }}">
                                <input type="hidden" name="to" value="{{ $bus->schedule->to ?? '' }}">
                                <div class="p-4">
                                    <h5 class="text-lg font-semibold text-gray-800">{{ $bus->busname->name }}
                                        ({{ $bus->bus_number }})</h5>
                                    <p class="text-gray-700">
                                        <strong class="font-medium">{{ __('customer/busroot.route') }}</strong> {{ $departureCityName }} to {{ $arrivalCityName }}<br>
                                        <strong class="font-medium">{{ __('customer/busroot.departure_date') }}</strong> {{ $departure_date }}<br>
                                        <strong class="font-medium">{{ __('customer/busroot.schedule') }}</strong>
                                        {{ $bus->schedule->from ?? '' }} ({{ $bus->schedule->start ?? '' }}) - {{ $bus->schedule->to ?? '' }} ({{ $bus->schedule->end ?? '' }})<br>
                                        <strong class="font-medium">{{ __('customer/busroot.price') }}</strong> {{ convert_money($bus->route->price) }} {{ $currency }}<br>
                                        <strong class="font-medium">{{ __('customer/busroot.remaining_seats') }}</strong> {{ $bus->remain_seats }}
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('customer.booking_form', ['id' => $bus->id, 'from' => $bus->schedule->from ?? '', 'to' => $bus->schedule->to ?? '']) }}"
                                            style="background-color: #204f81;"
                                            class="inline-block hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            {{ __('customer/busroot.book_now') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-span-2">
                    <p class="text-gray-600">{{ __('customer/busroot.no_buses_available') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection 