<div>
    <label for="key" class="block text-sm font-medium text-gray-700 mb-1">
        Temp wallet key
    </label>
    <div class="flex items-center w-full border border-gray-300 rounded-lg bg-gray-100 focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition">
        <input type="text" id="key" name="key" wire:model.live="key"
               class="flex-grow bg-transparent text-gray-800 w-full px-4 py-2 border-0 focus:ring-0 rounded-l-lg">

        @if($amount !== null)
            <div class="flex items-center whitespace-nowrap px-4 py-2 border-l border-gray-300 bg-gray-200/50">
                <span class="text-sm font-medium text-gray-600">Amount:</span>
                <span class="ml-2 text-gray-900 font-semibold">{{ convert_money( $amount) }} {{ $currency }}</span>
                <input type="hidden" name="amount_cancel" value="{{ $amount }}">
            </div>
        @endif
    </div>
</div>
