<footer class="bg-gray-50 border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-gray-500 text-sm mb-4 md:mb-0">
                © <span id="currentYear"></span> {{ __('all.highlink_isgc') }}. {{ __('all.all_rights_reserved') }}
            </div>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-500 hover:text-gray-700 text-sm transition-colors duration-200">{{ __('all.terms') }}</a>
                <a href="#" class="text-gray-500 hover:text-gray-700 text-sm transition-colors duration-200">{{ __('all.privacy') }}</a>
                <a href="#" class="text-gray-500 hover:text-gray-700 text-sm transition-colors duration-200">{{ __('all.contact') }}</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Automatically update the year in the footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>
