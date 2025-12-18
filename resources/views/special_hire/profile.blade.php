@extends('special_hire.app')

@section('title', 'Profile')
@section('page_title', 'Profile Settings')
@section('page_subtitle', 'Manage your account information')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Profile Info -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Account Information</h3>
        </div>

        <form action="{{ route('special_hire.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="flex items-center space-x-6 mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 2) }}</span>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-gray-800">{{ $user->name }}</h4>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-teal-100 text-teal-700">
                        Special Hire
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="contact" value="{{ old('contact', $user->contact) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                           placeholder="+255...">
                    @error('contact')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Change Password</h4>
                <p class="text-sm text-gray-500 mb-4">Leave blank to keep your current password</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="Enter new password">
                        @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary px-6 py-2.5 text-white rounded-xl font-medium">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Account Stats -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Account Statistics</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <p class="text-2xl font-bold text-teal-600">{{ \App\Models\Coaster::byUser($user->id)->count() }}</p>
                    <p class="text-sm text-gray-500">Coasters</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\SpecialHireOrder::byUser($user->id)->count() }}</p>
                    <p class="text-sm text-gray-500">Total Orders</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\SpecialHireOrder::byUser($user->id)->completed()->count() }}</p>
                    <p class="text-sm text-gray-500">Completed</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\SpecialHireOrder::byUser($user->id)->pending()->count() }}</p>
                    <p class="text-sm text-gray-500">Pending</p>
                </div>
            </div>

            <div class="mt-6 p-4 bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Revenue (All Time)</p>
                        <p class="text-2xl font-bold text-teal-700">Tsh {{ number_format(\App\Models\SpecialHireOrder::byUser($user->id)->paid()->sum('total_amount')) }}</p>
                    </div>
                    <a href="{{ route('special_hire.earnings') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Since -->
    <div class="text-center text-sm text-gray-500">
        Member since {{ $user->created_at->format('F Y') }}
    </div>
</div>
@endsection

