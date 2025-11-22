@extends('admin.app')

@section('content')
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-xl font-bold text-gray-800">{{ __('vender/schedule.edit_bus_schedule') }}</h1>
                    <a href="{{ route('schedules') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('vender/schedule.back_to_schedules') }}
                    </a>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded-md">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('update_schedule', $schedule->id) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label for="bus_id" class="block text-sm font-medium text-gray-700">{{ __('vender/schedule.bus') }}</label>
                            <select id="bus_id" name="bus_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                @foreach ($buses as $bus)
                                    <option value="{{ $bus->id }}"
                                        {{ $schedule->bus_id == $bus->id ? 'selected' : '' }}>
                                        {{ $bus->busname->name ?? __('vender/schedule.na') }} ({{ $bus->bus_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="from" class="block text-sm font-medium text-gray-700">{{ __('vender/schedule.from') }}</label>
                            <input type="text" id="from" readonly name="from" value="{{ $schedule->from }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="to" class="block text-sm font-medium text-gray-700">{{ __('vender/schedule.to') }}</label>
                            <input type="text" id="to" readonly name="to" value="{{ $schedule->to }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div class="space-y-2">
                            <label for="schedule_date" class="block text-sm font-medium text-gray-700">{{ __('vender/schedule.schedule_date') }}</label>
                            <input type="date" readonly id="schedule_date" name="schedule_date"
                                value="{{ $schedule->schedule_date }}"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <div class="space-y-2">
                                <label for="start" class="block text-sm font-medium text-gray-700">{{ __('vender/schedule.departure_time') }}</label>
                                <input type="time" id="start" name="start" value="{{ $schedule->start }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div class="space-y-2">
                                <label for="end" class="block text-sm font-medium text-gray-700">{{ __('vender/schedule.arrival_time') }}</label>
                                <input type="time" id="end" name="end" value="{{ $schedule->end }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                </path>
                            </svg>
                            {{ __('vender/schedule.update_schedule') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update from/to fields when route changes
            const routeSelect = document.getElementById('route_id');
            const fromInput = document.getElementById('from');
            const toInput = document.getElementById('to');

            routeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const routeText = selectedOption.text;
                const [from, to] = routeText.split(' → ').map(s => s.trim());

                fromInput.value = from;
                toInput.value = to;
            });
        });
    </script>
@endsection 