@extends('test.ap')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h1 class="text-xl font-bold text-gray-800">{{ __('all.bus_schedules') }}</h1>
            </div>
        </div>
        <div class="p-4 sm:p-6">
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
            <div class="overflow-x-auto">
                <table id="busTable" class="w-full table-auto text-sm text-gray-700">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <tr>
                            <th>{{ __('all.no') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('all.bus_number_label') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('all.from_label') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('all.to_label') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('all.time_24hrs') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('all.schedule_date') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('all.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($busList as $bus)
                            @if (!empty($bus['schedules']))
                                @foreach ($bus['schedules'] as $schedule)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">{{ $bus['bus_number'] ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">{{ $schedule['from'] ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">{{ $schedule['to'] ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">{{ $schedule['start'] ?? 'N/A' }} -> {{ $schedule['end'] ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">{{ $schedule['schedule_date'] ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-2">
                                                <form action="{{ route('by_route_search') }}" method="POST">
                                                    @csrf
                                                    <!-- Assuming city IDs need to be resolved in the controller -->
                                                    <input type="hidden" name="departure_city" value="{{ App\Models\City::where('name', $schedule['from'])->value('id') ?? '' }}">
                                                    <input type="hidden" name="arrival_city" value="{{  App\Models\City::where('name', $schedule['to'])->value('id') ?? '' }}">
                                                    <input type="hidden" name="departure_date" value="{{ $schedule['schedule_date'] ?? '' }}">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-600 rounded-md hover:bg-green-200 transition-colors" aria-label="Book schedule">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                                        </svg>
                                                        {{ __('all.booking_button') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $bus['bus_number'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">N/A</td>
                                    <td class="px-4 py-3">N/A</td>
                                    <td class="px-4 py-3">N/A</td>
                                    <td class="px-4 py-3">N/A</td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <span class="text-gray-500">{{ __('all.no_schedule') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<dialog id="scheduleModal" class="p-6 rounded-lg shadow-lg bg-white max-w-lg w-full">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">{{ __('all.schedule_details') }}</h2>
        <button id="closeModal" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div class="space-y-2">
        <p><strong>{{ __('all.bus_label') }}</strong> <span id="modal-bus"></span></p>
        <p><strong>{{ __('all.from_colon') }}</strong> <span id="modal-from"></span></p>
        <p><strong>{{ __('all.to_colon') }}</strong> <span id="modal-to"></span></p>
        <p><strong>{{ __('all.time_colon') }}</strong> <span id="modal-time"></span></p>
        <p><strong>{{ __('all.date_colon') }}</strong> <span id="modal-date"></span></p>
    </div>
    <div class="mt-6 flex justify-end">
        <button id="closeModalBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">{{ __('all.close_button_modal') }}</button>
    </div>
</dialog>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#busTable').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            language: {
                emptyTable: "{{ __('all.no_buses_schedules_found') }}"
            }
        });

        const modal = document.getElementById('scheduleModal');
        const closeModal = document.getElementById('closeModal');
        const closeModalBtn = document.getElementById('closeModalBtn');

        $('.hover\\:bg-gray-50').on('click', function(e) {
            if ($(e.target).closest('button, a').length) return; // Prevent modal if clicking action button
            const row = $(this);
            const busNumber = row.find('td').eq(1).text();
            const from = row.find('td').eq(2).text();
            const to = row.find('td').eq(3).text();
            const time = row.find('td').eq(4).text();
            const date = row.find('td').eq(5).text();

            if (from !== 'N/A' && to !== 'N/A') { // Only show modal if schedule exists
                $('#modal-bus').text(busNumber);
                $('#modal-from').text(from);
                $('#modal-to').text(to);
                $('#modal-time').text(time);
                $('#modal-date').text(date);
                modal.showModal();
            }
        });

        closeModal.addEventListener('click', () => modal.close());
        closeModalBtn.addEventListener('click', () => modal.close());
    });
</script>
@endsection
