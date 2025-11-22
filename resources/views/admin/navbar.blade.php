 <!-- Logo Section (Centered) -->
 <div class="flex justify-center flex-1">
     <div class="flex-shrink-0 flex items-center">
         <a href="{{ url('/') }}" class="flex items-center text-lg font-semibold text-gray-800">
             <i class="bi bi-bus-front mr-2"></i>HIGHLINK ISGC
         </a>
     </div>
 </div>

 <!-- User Menu (Right, visible in all modes) -->
 <div class="flex items-center">
     <div class="relative">
         <button class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none" id="userMenuButton"
             aria-expanded="false" aria-haspopup="true">
             <i class="bi bi-person-circle mr-1"></i>
             <span>{{ auth()->user()->name }}</span>
         </button>
     </div>

     <div class="ml-4">
         <select class="form-select appearance-none
                       block
                       w-full
                       px-3
                       py-1.5
                       text-base
                       font-normal
                       text-gray-700
                       bg-white bg-clip-padding bg-no-repeat
                       border border-solid border-gray-300
                       rounded
                       transition
                       ease-in-out
                       m-0
                       focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                 aria-label="Language"
                 onchange="window.location.href = '{{ route('set.locale') }}?lang=' + this.value">
             <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
             <option value="sw" {{ app()->getLocale() == 'sw' ? 'selected' : '' }}>Kiswahili</option>
         </select>
     </div>
 </div>
