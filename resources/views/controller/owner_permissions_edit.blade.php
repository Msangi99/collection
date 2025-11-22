@extends('admin.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-center">
        <div class="w-full lg:w-3/4 xl:w-2/3">
            <!-- Card Container -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <!-- Card Header -->
                <div class="px-4 sm:px-6 py-4 bg-blue-600 text-white">
                    <h3 class="text-lg sm:text-xl font-semibold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Local Admin: <span class="font-medium ml-1">{{ $user->name }}</span>
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="p-4 sm:p-6">
                    <!-- Update Form - Stacked on mobile, row on larger screens -->
                    <form action="{{ route('admin.update.role') }}" method="POST" class="space-y-4 md:space-y-0 md:flex md:items-end md:gap-4">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        
                        <!-- Access Select - Full width on mobile -->
                        <div class="flex-1 min-w-0">
                            <label for="access" class="block text-sm font-medium text-gray-700 mb-1">Access</label>
                            <select name="link" id="access" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="index">Dashboard</option>
                                        <option value="buses">Buses</option>
                                        <option value="routes">Routes</option>
                                        <option value="schedules">Schedules</option>
                                        <option value="cities">Cities</option>
                                        <option value="history">Booking History</option>
                                        <option value="erning">Earnings Payments</option>
                                        <option value="local.bus.owners">Local Bus Owners</option>
                                        <option value="owner.permissions.view">Owner Permissions View</option>
                                        <option value="owner.permissions.edit">Owner Permissions Edit</option>
                                        <option value="profile">Profile</option>
                                        <option value="logout">Logout</option>
                            </select>
                        </div>
                        
                        <!-- Status Select - Full width on mobile -->
                        <div class="flex-1 min-w-0">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="inactive">Inactive</option>
                                <option value="active">Active</option>
                            </select>
                        </div>
                        
                        <!-- Submit Button - Full width on mobile -->
                        <div class="w-full md:w-auto">
                            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Update
                            </button>
                        </div>
                    </form>

                    <!-- Current Access Table -->
                    <div class="mt-8">
                        <h4 class="text-md sm:text-lg font-medium text-gray-800 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Current Access Permissions
                        </h4>
                        
                        <!-- Responsive Table Container -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Access</th>
                                        <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($user->access as $index => $access)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-3 sm:px-4 py-2 sm:py-3 whitespace-nowrap">{{ $index + 1 }}</td>
                                        <td class="px-3 sm:px-4 py-2 sm:py-3 whitespace-nowrap capitalize">{{ str_replace('-', ' ', $access->link) }}</td>
                                        <td class="px-3 sm:px-4 py-2 sm:py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($access->status == 'active') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($access->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection