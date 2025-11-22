<section class="py-20 bg-white relative overflow-hidden">
  <div class="bubble w-80 h-80 bg-indigo-100 -top-40 -right-40"></div>
  <div class="bubble w-64 h-64 bg-pink-100 bottom-20 -left-20"></div>

  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center mb-12 fade-in">
      <h2 class="text-3xl md:text-4xl font-extrabold mb-4">{{ __('all.frequently_asked_questions') }}</h2>
      <p class="text-gray-600 max-w-2xl mx-auto text-lg">{{ __('all.find_answers_common_questions') }}</p>
    </div>

    <div class="max-w-3xl mx-auto">
      <!-- NEW: Step-by-Step (from doc) — now FIRST -->
      <div class="glass-card p-6 mb-4 fade-in delay-50">
        <div class="flex justify-between items-center cursor-pointer">
          <h3 class="font-bold text-lg">Step-by-Step: How to Book a Bus Ticket Online</h3>
          <i class="fas fa-chevron-down text-indigo-600 transition-transform"></i>
        </div>
        <div class="mt-3 text-gray-600 hidden">
          <ol class="list-decimal pl-5 space-y-2">
            <li>
              Visit the official site:
              <a href="https://ticket.hisgc.co.tz" class="text-indigo-600 underline" target="_blank" rel="noopener">ticket.hisgc.co.tz</a>
              (Home page shows route search, date & passenger selectors).
            </li>
            <li>Search: pick <em>From</em>, <em>To</em>, travel date → click <strong>Search Buses</strong> (see company, time, fare, seats, type).</li>
            <li>(Optional) Set pickup/drop-off and click <strong>Calculate distance</strong>, then proceed.</li>
            <li>Choose bus & seat(s): click <strong>View Seats</strong> and select available seats.</li>
            <li>Enter passenger details (name, gender, age group, phone/ID). Logged-in users can auto-fill.</li>
            <li>Review booking (route, date, seats, total, discounts/fees) → <strong>Continue to Payment</strong>.</li>
            <li>Pay securely: Mobile Money (M-Pesa/Tigo/Airtel/Halopesa), Bank/Card, or Agency as available.</li>
            <li>Get your e-ticket (PDF/QR) plus SMS/email confirmation. Download or save it.</li>
            <li>Boarding: arrive ~30 minutes early; show QR or reference to staff for verification.</li>
          </ol>
          <div class="mt-4 text-sm text-gray-500">
            Optional: In your dashboard you can reschedule, cancel (policy-based), download invoices, and view trip history.
          </div>
        </div>
      </div>

      <!-- FAQ Item 2 -->
      <div class="glass-card p-6 mb-4 fade-in delay-200">
        <div class="flex justify-between items-center cursor-pointer">
          <h3 class="font-bold text-lg">{{ __('all.what_payment_methods_accept') }}</h3>
          <i class="fas fa-chevron-down text-indigo-600 transition-transform"></i>
        </div>
        <div class="mt-3 text-gray-600 hidden">
          <p>{{ __('all.what_payment_methods_accept_answer') }}</p>
        </div>
      </div>

      <!-- FAQ Item 3 -->
      <div class="glass-card p-6 mb-4 fade-in delay-300">
        <div class="flex justify-between items-center cursor-pointer">
          <h3 class="font-bold text-lg">{{ __('all.cancel_modify_booking') }}</h3>
          <i class="fas fa-chevron-down text-indigo-600 transition-transform"></i>
        </div>
        <div class="mt-3 text-gray-600 hidden">
          <p>{{ __('all.cancel_modify_booking_answer') }}</p>
        </div>
      </div>

      <!-- FAQ Item 4 -->
      <div class="glass-card p-6 mb-4 fade-in delay-400">
        <div class="flex justify-between items-center cursor-pointer">
          <h3 class="font-bold text-lg">{{ __('all.amenities_available_buses') }}</h3>
          <i class="fas fa-chevron-down text-indigo-600 transition-transform"></i>
        </div>
        <div class="mt-3 text-gray-600 hidden">
          <p>{{ __('all.amenities_available_buses_answer') }}</p>
        </div>
      </div>

      <!-- FAQ Item 5 -->
      <div class="glass-card p-6 fade-in delay-500">
        <div class="flex justify-between items-center cursor-pointer">
          <h3 class="font-bold text-lg">{{ __('all.how_early_arrive_departure') }}</h3>
          <i class="fas fa-chevron-down text-indigo-600 transition-transform"></i>
        </div>
        <div class="mt-3 text-gray-600 hidden">
          <p>{{ __('all.how_early_arrive_departure_answer') }}</p>
        </div>
      </div>
    </div>

    <div class="text-center mt-10 fade-in delay-600">
      <button class="px-6 py-3 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 font-medium transition-all btn-glow">
        {{ __('all.view_full_faq') }} <i class="fas fa-chevron-right ml-2"></i>
      </button>
    </div>
  </div>
</section>
