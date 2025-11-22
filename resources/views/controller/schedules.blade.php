 @extends('admin.app')

 @section('content')
     <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
     <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
         <div class="bg-white shadow-lg rounded-lg overflow-hidden">
             <div class="p-4 sm:p-6 border-b border-gray-200">
                 <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                     <h1 class="text-xl font-bold text-gray-800">{{ __('vender/schedule.bus_schedules') }}</h1>
                     <a href="{{ route('add_schedule') }}"
                         class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors"
                         aria-label="{{ __('vender/schedule.add_new_schedule_aria') }}">
                         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                             </path>
                         </svg>
                         {{ __('vender/schedule.add_new_schedule') }}
                     </a>
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
                                 <th class="px-4 py-3 text-left">{{ __('vender/schedule.bus') }}</th>
                                 <th class="px-4 py-3 text-left">{{ __('vender/schedule.from') }}</th>
                                 <th class="px-4 py-3 text-left">{{ __('vender/schedule.to') }}</th>
                                 <th class="px-4 py-3 text-left">{{ __('vender/schedule.time_24hrs') }}</th>
                                 <th class="px-4 py-3 text-left">{{ __('vender/schedule.schedule_date') }}</th>
                                 <th class="px-4 py-3 text-left">{{ __('vender/schedule.action') }}</th>
                             </tr>
                         </thead>
                         <tbody>
                             @foreach ($schedules as $schedule)
                                 <tr class="border-b border-gray-200 hover:bg-gray-50">
                                     <td class="px-4 py-3">{{ $schedule->bus->busname->name ?? __('vender/schedule.na') }}
                                         ({{ $schedule->bus->bus_number ?? __('vender/schedule.na') }})</td>
                                     <td class="px-4 py-3">{{ $schedule->from }}</td>
                                     <td class="px-4 py-3">{{ $schedule->to }}</td>
                                     <td class="px-4 py-3">{{ $schedule->start }} -> {{ $schedule->end }}</td>
                                     <td class="px-4 py-3">{{ $schedule->schedule_date }}</td>
                                     <td class="px-4 py-3">
                                         <div class="flex gap-2">
                                             <a href="{{ route('edit.schedule', $schedule->id) }}"
                                                 class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-600 rounded-md hover:bg-yellow-200 transition-colors"
                                                 aria-label="{{ __('vender/schedule.edit_schedule') }}">
                                                 <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                         d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                     </path>
                                                 </svg>
                                             </a>
                                             <form action="{{ route('delete.schedule') }}" method="POST"
                                                 onsubmit="return confirm('{{ __('vender/schedule.confirm_delete_schedule') }}');">
                                                 @csrf
                                                 <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                 <button
                                                     class="inline-flex items-center px-3 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors"
                                                     aria-label="{{ __('vender/schedule.delete_schedule') }}">
                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                         <path stroke-linecap="round" stroke-linejoin="round"
                                                             stroke-width="2"
                                                             d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                         </path>
                                                     </svg>
                                                 </button>
                                             </form>
                                             <button
                                                 class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-600 rounded-md hover:bg-blue-200 transition-colors view-schedule"
                                                 data-schedule='@json($schedule)'
                                                 aria-label="{{ __('vender/schedule.view_schedule') }}">
                                                 <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                         d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6.293-3.293a1 1 0 011.414 1.414l-2.5 2.5a1 1 0 01-1.414 0l-2.5-2.5a1 1 0 011.414-1.414L20 10.586V8a1 1 0 011-1zm-18.586 0a1 1 0 011.414 0l2.5 2.5a1 1 0 01-1.414 1.414l-2.5-2.5a1 1 0 010-1.414zM12 4a8 8 0 00-8 8 8 8 0 008 8 8 8 0 008-8 8 8 0 00-8-8zm0 14a6 6 0 01-6-6 6 6 0 016-6 6 6 0 016 6 6 6 0 01-6 6z">
                                                     </path>
                                                 </svg>
                                             </button>
                                             <form action="{{ route('cancel.schedule', ['id' => $schedule->id]) }}" method="GET">
                                                <input type="hidden" value="{{ $schedule->id }}" name="schedule_id">
                                                <input type="hidden" value="{{ $schedule->route->id }}" name="route_id">

                                                 <button
                                                     class="inline-flex items-center px-3 py-1 bg-red-100 text-red-600 rounded-md hover:bg-red-200 transition-colors view-schedule"
                                                     
                                                     aria-label="{{ __('vender/schedule.view_schedule') }}">
                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                         <path stroke-linecap="round" stroke-linejoin="round"
                                                             stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                     </svg>
                                                 </button>
                                             </form>
                                         </div>
                                     </td>
                                 </tr>
                             @endforeach
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
         <dialog id="scheduleModal" class="rounded-lg shadow-xl p-0 max-w-lg w-full bg-white">
             <div class="p-4 sm:p-6 border-b border-gray-200">
                 <div class="flex justify-between items-center">
                     <h5 class="text-lg font-semibold text-gray-800">{{ __('vender/schedule.schedule_details') }}</h5>
                     <button class="text-gray-600 hover:text-gray-800" id="closeModal"
                         aria-label="{{ __('vender/schedule.close_modal') }}">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M6 18L18 6M6 6l12 12"></path>
                         </svg>
                     </button>
                 </div>
             </div>
             <div class="p-4 sm:p-6">
                 <p class="mb-2"><strong class="text-gray-700">{{ __('vender/schedule.bus') }}:</strong> <span
                         id="modal-bus" class="text-gray-600"></span></p>
                 <p class="mb-2"><strong class="text-gray-700">{{ __('vender/schedule.from') }}:</strong> <span
                         id="modal-from" class="text-gray-600"></span></p>
                 <p class="mb-2"><strong class="text-gray-700">{{ __('vender/schedule.to') }}:</strong> <span
                         id="modal-to" class="text-gray-600"></span></p>
                 <p class="mb-2"><strong class="text-gray-700">{{ __('vender/schedule.via') }}:</strong> <span
                         id="modal-via" class="text-gray-600"></span></p>
                 <p class="mb-2"><strong class="text-gray-700">{{ __('vender/schedule.time_24hrs') }}:</strong> <span
                         id="modal-time" class="text-gray-600"></span></p>
                 <p class="mb-2"><strong class="text-gray-700">{{ __('vender/schedule.schedule_date') }}:</strong>
                     <span id="modal-date" class="text-gray-600"></span></p>
             </div>
             <div class="p-4 sm:p-6 border-t border-gray-200">
                 <button
                     class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors"
                     id="closeModalBtn"
                     aria-label="{{ __('vender/schedule.close_modal') }}">{{ __('vender/schedule.close') }}</button>
             </div>
         </dialog>
     </div>
     <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
     <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
     <script>
         $(document).ready(function() {
             // Define translation strings for JavaScript
             const translations = {
                 empty_table: "{{ __('vender/schedule.no_buses_found') }}",
                 confirm_delete_schedule: "{{ __('vender/schedule.confirm_delete_schedule') }}"
             };

             $('#busTable').DataTable({
                 responsive: true,
                 paging: true,
                 searching: true,
                 ordering: true,
                 language: {
                     emptyTable: translations.empty_table
                 }
             });

             const modal = document.getElementById('scheduleModal');
             const closeModal = document.getElementById('closeModal');
             const closeModalBtn = document.getElementById('closeModalBtn');

             $('.view-schedule').on('click', function() {
                 const schedule = $(this).data('schedule');
                 $('#modal-bus').text(
                     `${schedule.bus.busname?.name ?? translations.na} (${schedule.bus.bus_number ?? translations.na})`
                     );
                 $('#modal-from').text(schedule.from ?? translations.na);
                 $('#modal-to').text(schedule.to ?? translations.na);
                 $('#modal-time').text(`${schedule.route.route_start} -> ${schedule.route.route_end}`);
                 $('#modal-date').text(schedule.schedule_date ?? translations.na);
                 $('#modal-via').text(schedule.route.via?.name ?? translations.na);
                 modal.showModal();
             });

             closeModal.addEventListener('click', () => modal.close());
             closeModalBtn.addEventListener('click', () => modal.close());
         });
     </script>
 @endsection
