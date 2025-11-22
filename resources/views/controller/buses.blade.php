@extends('admin.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>

<style>
    .no-print { display: block; }
    .print-header { display: none; }

    @media print {
        .no-print,
        .dataTables_length,
        .dataTables_filter,
        .dataTables_paginate,
        .dataTables_info,
        .dt-buttons {
            display: none !important;
        }
        .print-header { display: block !important; }

        /* Just in case, though we remove the column for print anyway */
        th.no-print-cell, td.no-print-cell { display: none !important; }

        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<div class="container-fluid py-4">
    <div class="mb-6">
        <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl">

            <!-- Screen header -->
            <div class="no-print bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-t-2xl p-4 flex justify-between items-center">
                <h5 class="text-lg font-semibold tracking-tight">{{ __('vender/mybus.my_buses') }}</h5>

                <div class="flex space-x-2">
                    <a href="{{ route('bus.print.pdf') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all duration-200" target="_blank">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v2h12V3z"/>
                        </svg>
                        {{ __('Print Bus Info (PDF)') }}
                    </a>

                    <a href="{{ route('add_bus') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 5v6H5v2h7v6h2v-6h7v-2h-7V5h-2z"/>
                        </svg>
                        {{ __('vender/mybus.add_new_bus') }}
                    </a>
                </div>
            </div>

            <!-- Print-only header -->
            <div class="print-header p-4">
                <h1 style="margin:0;font-size:20px;">{{ config('app.name') }} — {{ __('vender/mybus.my_buses') }}</h1>
                <div style="font-size:12px;color:#555;">
                    Printed at: {{ now()->format('Y-m-d H:i') }}
                </div>
                <hr style="margin-top:10px;border-color:#e5e7eb;">
            </div>

            <div class="p-4">
                <div class="overflow-x-auto">
                    <table id="busTable" class="w-full text-left display">
                        <thead>
                            <tr>
                                <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/mybus.sn') }}</th>
                                <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/mybus.bus_name') }}</th>
                                <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/mybus.plate_number') }}</th>
                                <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/mybus.route') }}</th>
                                <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/mybus.total_seats') }}</th>
                                <th class="text-xs font-semibold text-gray-500 uppercase py-2">{{ __('vender/mybus.conductor_phone') }}</th>
                                <th class="text-xs font-semibold text-gray-500 uppercase py-2 no-print-cell">{{ __('vender/mybus.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if (isset($buses) && $buses->count() > 0)
                            @foreach ($buses as $key => $bus)
                                <tr class="border-t border-gray-200">
                                    <td class="py-3 font-semibold text-gray-900">{{ $key + 1 }}</td>
                                    <td class="py-3">
                                        <div class="flex items-center">
                                            <img src="{{ asset('bus.jpg') }}" class="w-5 h-5 mr-3 rounded-full" alt="{{ $bus->busname->name ?? 'Bus' }}">
                                            <h6 class="text-sm font-semibold text-gray-900">{{ $bus->busname->name ?? 'N/A' }}</h6>
                                        </div>
                                    </td>
                                    <td class="py-3 text-xs font-semibold text-gray-900">{{ $bus->bus_number ?? 'N/A' }}</td>
                                    <td class="py-3 text-xs font-semibold text-gray-900">
                                        {{ $bus->route->from ?? 'N/A' }} {{ $bus->route?->from && $bus->route?->to ? '→' : '' }} {{ $bus->route->to ?? 'N/A' }}
                                    </td>
                                    <td class="py-3 text-xs font-semibold text-gray-900">{{ $bus->total_seats ?? 'N/A' }}</td>
                                    <td class="py-3 text-xs font-semibold text-gray-900">{{ $bus->conductor ?? 'N/A' }}</td>
                                    <td class="py-3 no-print-cell">
                                        <div class="flex gap-2">
                                            <a href="{{ route('edit.bus', ['id' => $bus->id]) }}"
                                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                                </svg>
                                                {{ __('vender/mybus.edit') }}
                                            </a>

                                            <form action="{{ route('bus.delete') }}" method="POST" onsubmit="return confirm('{{ __('vender/mybus.confirm_delete_bus') }}')">
                                                @csrf 
                                                <input type="hidden" name="bus_id" value="{{ $bus->id }}">
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                                    </svg>
                                                    {{ __('vender/mybus.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="py-3 text-center text-gray-500">{{ __('vender/mybus.no_buses_found') }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dt = $('#busTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true,
        language: { emptyTable: "{{ __('vender/mybus.no_buses_found') }}" },
        columnDefs: [{ targets: -1, orderable: false }]
    });

    document.querySelectorAll('.timepicker').forEach(function(el) {
        flatpickr(el, { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true });
    });
});
</script>
@endsection
