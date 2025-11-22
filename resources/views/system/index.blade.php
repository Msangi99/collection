@extends('system.app')

@section('title', 'Company Dashboard')

@section('content')
<div class="row mb-4">
    <!-- Today's Total Amount Card -->
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h5 class="card-title">Today's Total Amount</h5>
            <h2 class="card-text text-success">Tsh.{{ number_format($todayAmount, 2) }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <h5 class="card-title">System Total Amount</h5>
            <h2 class="card-text text-success">Tsh.{{ number_format($system, 2) }}</h2>
        </div>
    </div>
</div>

<!-- Weekly Amount Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4">
            <h5 class="card-title text-center">Weekly Booking Amounts</h5>
            <div class="chart-container">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Today's Bookings</h5>
                @if($bookings->isEmpty())
                    <p class="text-muted">No bookings found for today.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking Code</th>
                                    <th>Customer</th>
                                    <th>Company</th>
                                    <th>Bus</th>
                                    <th>Route</th>
                                    <th>Travel Date</th>
                                    <th>Seat</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->booking_code }}</td>
                                        <td>{{ $booking->customer_name }}</td>
                                        <td>{{ $booking->campany ? $booking->campany->name : 'N/A' }}</td>
                                        <td>{{ $booking->bus ? $booking->bus->name : 'N/A' }}</td>
                                        <td>{{ $booking->route ? $booking->route->name : 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->travel_date)->format('d M Y') }}</td>
                                        <td>{{ $booking->seat }}</td>
                                        <td>₦{{ number_format($booking->amount, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $booking->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    // Weekly Amounts Chart
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    const weeklyData = @json($weeklyAmounts);
    const labels = weeklyData.map(item => item.date);
    const amounts = weeklyData.map(item => item.amount);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Booking Amount (₦)',
                data: amounts,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount (₦)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });
</script>
@endsection