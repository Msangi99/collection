@extends('system.app')

@section('title', 'Vendors')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Success Message -->
    @if (session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg flex justify-between items-center">
        <p class="text-sm">{{ session('success') }}</p>
        <button class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-800">Vendors</h3>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                <span class="text-sm font-medium bg-gray-100 px-3 py-1 rounded-lg">
                    Total Balance: <span class="font-bold text-blue-600" id="vendorTotal">0</span>
                </span>
                <h4 class="text-sm font-medium bg-gray-100 px-3 py-1 rounded-lg">HIGHLINK ISGC</h4>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="vendorTable" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Center</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($venders as $key => $vendor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $vendor->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $vendor->contact }}</td>
                        <td class="px-4 py-3 whitespace-nowrap amount" data-amount="{{ $vendor->VenderBalances->amount ?? 0 }}">
                            Tsh {{ number_format($vendor->VenderBalances->amount ?? 0, 2, '.', ',') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $vendor->VenderAccount->work ?? 'N/A' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($vendor->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($vendor->status == 'accept') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($vendor->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <button onclick="openModal('vendorModal{{ $vendor->id }}')" 
                                class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                View
                            </button>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <form class="flex items-center space-x-2" action="{{ route('system.vender.status') }}" method="post">
                                @csrf
                                <select name="status" class="text-xs border border-gray-300 rounded-lg px-2 py-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending" {{ $vendor->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="accept" {{ $vendor->status == 'accept' ? 'selected' : '' }}>Accept</option>
                                    <option value="cancel" {{ $vendor->status == 'cancel' ? 'selected' : '' }}>Cancel</option>
                                </select>
                                <input type="hidden" name="vender_id" value="{{ $vendor->id }}">
                                <button type="submit" class="px-2 py-1 bg-green-600 text-white rounded-lg text-xs font-medium hover:bg-green-700 transition-colors">
                                    Save
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Vendor Modals -->
@foreach ($venders as $vendor)
<div id="vendorModal{{ $vendor->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeModal('vendorModal{{ $vendor->id }}')"></div>
    
    <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl relative max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10">
            <h3 class="text-xl font-semibold text-gray-800">Vendor Details: {{ $vendor->name }}</h3>
            <button onclick="closeModal('vendorModal{{ $vendor->id }}')" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h4 class="text-lg font-medium text-gray-800 border-b pb-2">User Information</h4>
                <div class="space-y-2">
                    <p><span class="font-medium text-gray-700">Name:</span> {{ $vendor->name }}</p>
                    <p><span class="font-medium text-gray-700">Email:</span> {{ $vendor->email }}</p>
                    <p><span class="font-medium text-gray-700">Contact:</span> {{ $vendor->contact }}</p>
                    <p><span class="font-medium text-gray-700">Status:</span> 
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($vendor->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($vendor->status == 'accept') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </p>
                    <p><span class="font-medium text-gray-700">Balance:</span> 
                        Tsh {{ number_format($vendor->VenderBalances->amount ?? 0, 2, '.', ',') }}
                    </p>
                </div>
            </div>
            
            <div class="space-y-4">
                <h4 class="text-lg font-medium text-gray-800 border-b pb-2">Account Information</h4>
                @if ($vendor->VenderAccount)
                <div class="space-y-2">
                    <p><span class="font-medium text-gray-700">TIN:</span> {{ $vendor->VenderAccount->tin ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-700">Address:</span> 
                        {{ $vendor->VenderAccount->house_number ?? '' }}
                        {{ $vendor->VenderAccount->street ?? '' }},
                        {{ $vendor->VenderAccount->town ?? '' }},
                        {{ $vendor->VenderAccount->city ?? '' }},
                        {{ $vendor->VenderAccount->province ?? '' }},
                        {{ $vendor->VenderAccount->country ?? '' }}
                    </p>
                    <p><span class="font-medium text-gray-700">Work Center:</span> {{ $vendor->VenderAccount->work }}</p>
                    <p><span class="font-medium text-gray-700">Alt Number:</span> {{ $vendor->VenderAccount->altenative_number ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-700">Bank:</span> {{ $vendor->VenderAccount->bank_name ?? 'N/A' }} ({{ $vendor->VenderAccount->bank_number ?? 'N/A' }})</p>
                    
                    <form class="flex items-center space-x-2 pt-2" method="post" action="{{ route('vender.percent') }}">
                        @csrf
                        <input type="hidden" name="vender_id" value="{{ $vendor->id }}">
                        <span class="font-medium text-gray-700">Percentage:</span>
                        <input type="number" name="percent" max="100" min="0" value="{{ $vendor->VenderAccount->percentage }}"
                            class="w-20 px-2 py-1 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            Save
                        </button>
                    </form>
                </div>
                @else
                <p class="text-gray-500">No vendor account information available.</p>
                @endif
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-200 flex justify-end">
            <button onclick="closeModal('vendorModal{{ $vendor->id }}')" 
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>
@endforeach

<!-- Include DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<script>
// Initialize DataTable
$(document).ready(function() {
    const vendorTable = $('#vendorTable').DataTable({
        responsive: true,
        paging: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        searching: true,
        ordering: true,
        language: {
            emptyTable: "No vendors found",
            search: "_INPUT_",
            searchPlaceholder: "Search vendors...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ vendors",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        dom: '<"flex justify-between items-center mb-4"f>rt<"flex justify-between items-center mt-4"lip>',
        initComplete: function() {
            $('.dataTables_filter input').addClass('form-input text-sm px-3 py-2 border border-gray-300 rounded-lg');
            $('.dataTables_length select').addClass('form-select text-sm px-3 py-2 border border-gray-300 rounded-lg');
        },
        footerCallback: function(row, data, start, end, display) {
            let api = this.api();
            let total = api
                .rows({ page: 'current' })
                .nodes()
                .toArray()
                .reduce((sum, row) => {
                    let amount = $(row).find('.amount').data('amount') || 0;
                    return sum + parseFloat(amount);
                }, 0);
            $('#vendorTotal').text(total.toLocaleString('en-US', {
                minimumFractionDigits: 2
            }));
        }
    });
});

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('.fixed.inset-0.z-50');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    }
});
</script>

<style>
    #vendorTable {
        font-size: 0.875rem;
    }
    #vendorTable th, #vendorTable td {
        padding: 0.75rem 1rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.5rem;
        margin-left: 0.125rem;
        border-radius: 0.375rem;
        border: 1px solid transparent;
        font-size: 0.75rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #2563eb;
        color: white !important;
        border: 1px solid #2563eb;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #dbeafe;
        border: 1px solid #bfdbfe;
        color: #2563eb !important;
    }
</style>
@endsection