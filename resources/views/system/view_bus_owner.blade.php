@extends('system.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 flex flex-col justify-center sm:py-12">
  <div class="relative py-3 sm:max-w-xl sm:mx-auto">
    <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-purple-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
    <div class="relative px-6 py-8 bg-white shadow-lg sm:rounded-3xl sm:p-10">
      
      <!-- Header -->
      <div class="flex items-center space-x-3 mb-6">
        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-800">Update Profile</h1>
      </div>

      <form action="{{ route('profile.update.bus') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">

        <!-- Personal Info -->
        <div class="space-y-3">
          <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Personal Information
          </h2>
          
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label for="name" class="block text-xs font-medium text-gray-500">Full Name</label>
              <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
              @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
              <label for="email" class="block text-xs font-medium text-gray-500">Email</label>
              <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
              @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
              <label for="contact" class="block text-xs font-medium text-gray-500">Phone</label>
              <input type="text" id="contact" name="contact" value="{{ old('contact', $user->contact ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
              @error('contact')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>

        <!-- Company Info -->
        <div class="space-y-3">
          <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Company Information
          </h2>
          
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label for="company_name" class="block text-xs font-medium text-gray-500">Company Name</label>
              <input type="text" id="company_name" name="campany_name" value="{{ old('campany_name', $user->campany->name ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
              @error('company_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
              <label for="registration_number" class="block text-xs font-medium text-gray-500">Reg. Number</label>
              <input type="text" id="registration_number" name="registration_number" value="{{ old('registration_number', $user->campany->busOwnerAccount->registration_number ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
              @error('registration_number')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
              <label for="tin" class="block text-xs font-medium text-gray-500">TIN</label>
              <input type="text" id="tin" name="tin" value="{{ old('tin', $user->campany->busOwnerAccount->tin ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
              @error('tin')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
              <label for="vrn" class="block text-xs font-medium text-gray-500">VRN</label>
              <input type="text" id="vrn" name="vrn" value="{{ old('vrn', $user->campany->busOwnerAccount->vrn ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
              @error('vrn')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>

        <!-- Address -->
        <div class="space-y-3">
          <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Address
          </h2>
          
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <label for="office_number" class="block text-xs font-medium text-gray-500">Office No.</label>
              <input type="text" id="office_number" name="office_number" value="{{ old('office_number', $user->campany->busOwnerAccount->office_number ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            
            <div>
              <label for="street" class="block text-xs font-medium text-gray-500">Street</label>
              <input type="text" id="street" name="street" value="{{ old('street', $user->campany->busOwnerAccount->street ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            
            <div>
              <label for="box" class="block text-xs font-medium text-gray-500">P.O. Box</label>
              <input type="text" id="box" name="box" value="{{ old('box', $user->campany->busOwnerAccount->box ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            
            <div>
              <label for="town" class="block text-xs font-medium text-gray-500">Town</label>
              <input type="text" id="town" name="town" value="{{ old('town', $user->campany->busOwnerAccount->town ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            
            <div>
              <label for="city" class="block text-xs font-medium text-gray-500">City/District</label>
              <input type="text" id="city" name="city" value="{{ old('city', $user->campany->busOwnerAccount->city ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            
            <div>
              <label for="region" class="block text-xs font-medium text-gray-500">Region</label>
              <input type="text" id="region" name="region" value="{{ old('region', $user->campany->busOwnerAccount->region ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
          </div>
        </div>

        <!-- Contact & Bank -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="space-y-3">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              Contact
            </h2>
            
            <div>
              <label for="whatsapp_number" class="block text-xs font-medium text-gray-500">WhatsApp</label>
              <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $user->campany->busOwnerAccount->whatsapp_number ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
          </div>
          
          <div class="space-y-3">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
              </svg>
              Bank Details
            </h2>
            
            <div>
              <label for="bank_name" class="block text-xs font-medium text-gray-500">Bank Name</label>
              <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $user->campany->busOwnerAccount->bank_name ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
            
            <div>
              <label for="account_number" class="block text-xs font-medium text-gray-500">Account No.</label>
              <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $user->campany->busOwnerAccount->bank_number ?? '') }}" 
                     class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
          </div>
        </div>

        <!-- Security -->
        <div class="space-y-3">
          <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Security
          </h2>
          
          <div>
            <label for="password" class="block text-xs font-medium text-gray-500">New Password</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current" 
                   class="mt-1 block w-full border border-gray-200 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
          </div>
        </div>

        <!-- Submit -->
        <div class="pt-4">
          <button type="submit" class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            Update Profile
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection