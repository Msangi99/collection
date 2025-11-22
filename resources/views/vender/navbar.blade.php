 <!-- Logo Section (Centered) -->
 <div class="flex justify-center flex-1">
     <div class="flex-shrink-0 flex items-center">
         <a href="#" class="flex items-center text-lg font-semibold text-gray-800">
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
 </div>
