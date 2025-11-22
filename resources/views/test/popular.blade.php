<?php

use App\Models\bus;
use App\Models\Campany;
use App\Models\Schedule;
use Carbon\Carbon;

$cars = bus::with('campany', 'route', 'schedule')
    ->whereHas('campany', function ($query) {
        $query->where('status', 1);
    })
    ->whereHas('schedule', function ($query) {
        $query->where('schedule_date','=', Carbon::now()->toDateString());
    })
    ->limit(4)
    ->get();
?>

<section id="routes" class="py-20 bg-white relative overflow-hidden">
        <div class="bubble w-80 h-80 bg-indigo-100 -top-40 -right-40"></div>
        <div class="bubble w-64 h-64 bg-pink-100 bottom-20 -left-20"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12 fade-in">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Available <span class="gradient-text">Schedules</span></h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Explore Tanzania's most traveled destinations with our premium bus services</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($cars as $bus): ?>
                <?php if ($bus->routes->isNotEmpty()): ?>
                <?php $firstRoute = $bus->routes->first(); ?>
                <div class="glass-card p-6 route-card fade-in delay-100">
                <h3 class="font-bold"><?= $bus->campany->name ?? 'N/A' ?></h3>
                    <div class="flex justify-between items-start mb-4">
                        <div>
                                <h3 class="font-bold text-xl"><?= $firstRoute->schedule->from ?? 'N/A' ?></h3>
                            <p class="text-gray-600 text-sm">to</p>
                                <h3 class="font-bold text-xl"><?= $firstRoute->schedule->to ?? 'N/A' ?></h3>
                        </div>
                        <div class="bg-indigo-100/50 p-2 rounded-lg">
                            <i class="fas fa-bus text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <i class="fas fa-clock mr-2"></i>
                            <span><?php echo $firstRoute->schedule->start ?></span>
                        </div>
                    <a href="{{ route('booking_form', ['id' => $bus->id, 'from' => $bus->schedule->from, 'to' => $bus->schedule->to]) }}" 
                        class="w-full py-2 px-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-lg transition-all btn-glow">
                        Book Now <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <!--
                <form action="{{ route('booking_form', ['id' => $bus->id, 'from' => $bus->schedule->from, 'to' => $bus->schedule->to]) }}"></form>
                -->
                <?php endif; ?>
            <?php endforeach; ?>
                        </div>
            <div class="text-center mt-10 fade-in delay-500">
                <button class="px-6 py-3 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 font-medium transition-all btn-glow">
                    Explore All 50+ Routes <i class="fas fa-route ml-2"></i>
                </button>
            </div>
        </div>
    </section>