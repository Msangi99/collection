<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Safari Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8 bg-white shadow-md rounded-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Safari Confirmation</h1>
        <p class="text-lg text-gray-700 mb-4">
            Dear <span class="font-semibold">{{ $booking->customer_name }}</span>,
        </p>
        <p class="text-gray-700 mb-4">
            Karibu <span class="font-semibold">{{ $booking->campany->name }}</span>. Utasafiri na basi namba <span class="font-semibold">{{ $booking->bus->bus_number }}</span> Tarehe <span class="font-semibold">{{ $booking->travel_date }}</span> kutoka <span class="font-semibold">{{ $booking->pickup_point }}</span> kwenda <span class="font-semibold">{{ $booking->dropping_point }}</span> muda wa kuondoka ni <span class="font-semibold">{{ $booking->bus->route->route_start }}</span>. Tafadhali report kituoni mapema kwa safari.
        </p>
        <p class="text-gray-700 mb-4">
            Namba ya kiti chako ni <span class="font-semibold">{{ $booking->seat }}</span> na namba yako ya safari ni <span class="font-semibold">{{ $booking->booking_code }}</span>.
        </p>
        <p class="text-gray-700 mb-4">
            Kwa mawasiliano piga <span class="font-semibold">{{ $booking->bus->conductor }}</span>. HIGHLINK ISGC inakutakia safari njema.
        </p>
    </div>
</body>
</html>