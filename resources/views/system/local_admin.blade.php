@extends('system.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header and Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Local Admins
        </h1>
        <a href="{{ route('system.local_admin.create') }}" class="w-full sm:w-auto px-3 sm:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium rounded-lg flex items-center justify-center transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
    <div class="mb-6 p-3 sm:p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg flex justify-between items-center">
        <p class="text-xs sm:text-sm">{{ session('success') }}</p>
        <button class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    @endif

    @if ($users->isEmpty())
    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 text-center text-gray-500 text-sm sm:text-base">
        No local admins found
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Table Container with Horizontal Scroll -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider w-8 sm:w-12">#</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Email</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider hidden xs:table-cell">Contact</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $admin)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap font-medium text-gray-900">{{ $admin->name }}</td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap hidden sm:table-cell">{{ $admin->email }}</td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap hidden xs:table-cell">{{ $admin->contact ?? 'N/A' }}</td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                @if($admin->status == 'accept') 
                                bg-green-100 text-green-800
                                @elseif($admin->status == 'pending')
                                bg-yellow-100 text-yellow-800
                                @else
                                bg-red-100 text-red-800 
                                @endif">
                                {{ ucfirst($admin->status) }}
                            </span>
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                            <div class="flex items-center space-x-1 sm:space-x-2">
                                <a href="{{ route('system.local_admin.edit', $admin->id) }}" class="px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-800 rounded text-xs sm:text-sm font-medium hover:bg-yellow-200 transition-colors whitespace-nowrap">
                                    Edit
                                </a>
                                <form action="{{ route('system.local_admin.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this local admin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 sm:px-3 py-1 bg-red-100 text-red-800 rounded text-xs sm:text-sm font-medium hover:bg-red-200 transition-colors whitespace-nowrap">
                                        Delete
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
    @endif
</div>
@endsection