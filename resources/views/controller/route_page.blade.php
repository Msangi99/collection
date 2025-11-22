 
@extends('admin.app')

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h5 class="text-lg font-semibold text-gray-800">{{ __('vender/route.buses_and_routes') }}</h5>
                <a href="{{ route('route') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('vender/route.add_route') }}
                </a>
            </div>
        </div>
        <div class="p-4 sm:p-6">
            <div class="overflow-x-auto">
                <table id="busTable" class="w-full table-auto text-sm text-gray-700">
                    <thead>
                        <tr class="bg-gray-100 text-xs uppercase text-gray-500 font-semibold">
                            <th class="px-4 py-3 text-left">{{ __('vender/route.bus') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('vender/route.route') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('vender/route.stops') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('vender/route.fare') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('vender/route.status') }}</th>
                            <th class="px-4 py-3 text-left">{{ __('vender/route.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mybus as $car)
                            @foreach ($car->routes as $route)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ asset($car->image ?? 'bus.jpg') }}" class="w-8 h-8 rounded-full object-cover" alt="{{ $car->busname->name }} Image">
                                            <div>
                                                <h6 class="text-sm font-medium text-gray-800">{{ $car->busname->name }}</h6>
                                                <p class="text-xs text-gray-500">{{ $car->total_seats }} {{ __('vender/route.seats') }}</p>
                                                <p class="text-xs text-gray-500">{{ $car->bus_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-medium text-gray-800">{{ $route->from }} {{ __('vender/route.to') }} {{ $route->to }}</p>
                                        <p class="text-xs text-gray-500">{{ __('vender/route.via') }} {{ $route->via->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block px-2 py-1 text-xs font-medium text-white bg-blue-500 rounded">{{ count($route->points) }} {{ __('vender/route.stops') }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-medium text-gray-800">{{ __('vender/route.tsh') }} {{ $route->price }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $route->status == 'active' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                            {{ ucfirst($route->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <a href="{{ route('edit.route', ['id' => $route->id]) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition-colors" aria-label="{{ __('vender/route.edit_route') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('route.delete') }}" method="POST" onsubmit="return confirm('{{ __('vender/route.confirm_delete_route') }}');">
                                                @csrf
                                                <input type="hidden" name="route_id" value="{{ $route->id }}">
                                                <button class="inline-flex items-center px-3 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors" aria-label="{{ __('vender/route.delete_route') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">{{ __('vender/route.no_buses_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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
                emptyTable: "{{ __('vender/route.no_buses_found') }}"
            }
        });
    });
</script>
@endsection 