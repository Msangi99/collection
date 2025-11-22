@extends('test.ap')

@section('content')
<div class="min-h-screen flex items-center">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Roundtrip Booking Status Card -->
        <div class="rounded-xl bg-gray-50 shadow-xl overflow-hidden transition-all duration-300 transform hover:shadow-2xl">
            <!-- Header -->
            <div class="bg-blue-600 py-6 px-6 text-center">
                <div class="flex items-center justify-center space-x-3">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h2 class="text-2xl font-bold text-white">Roundtrip Booking Status</h2>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 sm:p-8">
                <!-- Booking Details Message -->
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Your Roundtrip Booking Details</h3>
                    <p class="text-gray-600">Here are the details for your two bookings.</p>
                </div>

                <!-- Booking One Details -->
                <h4 class="text-xl font-bold text-gray-900 mb-4">Booking One Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Booking Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h4 class="text-lg font-semibold text-gray-800">Booking Summary</h4>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bus:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->bus->busname->name }} | {{ $bookingone->bus->bus_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking Code:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->booking_code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bus Route:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->bus->route->from ?? 'N/A' }} To {{ $bookingone->bus->route->to ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">User Route:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->pickup_point ?? 'N/A' }} To {{ $bookingone->dropping_point ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Travel Date:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->travel_date }} {{ $bookingone->bus->route->route_start }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Seat:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->seat }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="text-lg font-semibold text-gray-800">Payment Details</h4>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ticket Fee:</span>
                                <span class="font-medium text-gray-600">{{ number_format($bookingone->busFee, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service Fee:</span>
                                <span class="font-medium text-gray-600">{{ number_format($bookingone->service + $bookingone->vender_service + $bookingone->service_vat, 2) }}</span>
                            </div>
                            @if ($bookingone->vender_id > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vender Name:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->vender->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vender Contact:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->vender->contact }}</span>
                            </div>
                            @endif
                            @if ($bookingone->bima == 1)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Insurance amount:</span>
                                <span class="font-medium text-gray-600">{{ number_format($bookingone->bima_amount, 2) }}</span>
                            </div>
                            @endif
                            @if (!empty($bookingone->discount))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount Percentage:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->discounta->percentage }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount Amount:</span>
                                <span class="font-medium text-gray-600">{{ number_format($bookingone->discounta->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                <span class="font-semibold">Total Amount:</span>
                                <span class="font-bold text-blue-600">
                                    {{ number_format($bookingone->busFee + $bookingone->service + $bookingone->vender_service + $bookingone->service_vat + $bookingone->bima_amount, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID:</span>
                                <span class="font-medium text-gray-600">{{ $bookingone->transaction_ref_id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                <span class="px-2 py-1 {{ $bookingone->payment_status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-sm font-medium rounded-full">{{ $bookingone->payment_status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Two Details -->
                <h4 class="text-xl font-bold text-gray-900 mb-4 mt-8">Booking Two Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Booking Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h4 class="text-lg font-semibold text-gray-800">Booking Summary</h4>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bus:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->bus->busname->name }} | {{ $bookingtwo->bus->bus_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking Code:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->booking_code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bus Route:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->bus->route->from ?? 'N/A' }} To {{ $bookingtwo->bus->route->to ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">User Route:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->pickup_point ?? 'N/A' }} To {{ $bookingtwo->dropping_point ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Travel Date:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->travel_date }} {{ $bookingtwo->bus->route->route_start }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Seat:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->seat }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="text-lg font-semibold text-gray-800">Payment Details</h4>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ticket Fee:</span>
                                <span class="font-medium text-gray-600">{{ number_format($bookingtwo->busFee, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service Fee:</span>
                                <span class="font-medium text-gray-600">{{ number_format($bookingtwo->service + $bookingtwo->vender_service + $bookingtwo->service_vat, 2) }}</span>
                            </div>
                            @if ($bookingtwo->vender_id > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vender Name:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->vender->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vender Contact:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->vender->contact }}</span>
                            </div>
                            @endif
                            @if ($bookingtwo->bima == 1)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Insurance amount:</span>
                                <span class="font-medium text-gray-600">{{ number_format($bookingtwo->bima_amount, 2) }}</span>
                            </div>
                            @endif
                            @if (!empty($bookingtwo->discount))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount Percentage:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->discounta->percentage }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount Amount:</span>
                                <span class="font-medium text-gray-600">{{ number_format($bookingtwo->discounta->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                <span class="font-semibold">Total Amount:</span>
                                <span class="font-bold text-blue-600">
                                    {{ number_format($bookingtwo->busFee + $bookingtwo->service + $bookingtwo->vender_service + $bookingtwo->service_vat + $bookingtwo->bima_amount, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID:</span>
                                <span class="font-medium text-gray-600">{{ $bookingtwo->transaction_ref_id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                <span class="px-2 py-1 {{ $bookingtwo->payment_status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-sm font-medium rounded-full">{{ $bookingtwo->payment_status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-8">
                    <a href="{{ url('/') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Return Home
                    </a>
                    <form action="{{ route('ticket.print') }}" method="POST">
                        @csrf
                        <input type="hidden" name="data" value="{{ $bookingone }}">
                        <button type="submit" class="px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition duration-200 flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print Ticket (Booking One)
                        </button>
                    </form>
                    <form action="{{ route('ticket.print') }}" method="POST">
                        @csrf
                        <input type="hidden" name="data" value="{{ $bookingtwo }}">
                        <button type="submit" class="px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition duration-200 flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print Ticket (Booking Two)
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="text-center text-sm text-gray-500">
                    <p>Nunua ticket mtandaoni kwa usalama wa hali ya juu wakati wowote na bila usumbufu kwa
                        kutembelea www.hisgc.co.tz au piga <a href="tel:*149*46*36#" class="text-blue-600 hover:text-blue-800">*149*46*36#</a> halafu
                        fuata maelekezo ya kununua ticket au piga <a href="tel:+255755879793" class="text-blue-600 hover:text-blue-800">+255 755 879
                        793</a> kwa msaada zaidi. Highlink ISGC</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-check circle {
        animation: dash 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .animate-check path {
        animation: dash 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }
    @keyframes dash {
        to { stroke-dashoffset: 0; }
    }
</style>
@endsection
