<!-- customer/index.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('all.customer_dashboard') }}</title>
    <!-- Add your CSS links here (e.g., Bootstrap) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: sans-serif;
        }
        .dashboard-container {
            padding: 20px;
        }
        .dashboard-card {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .payment-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            text-align: center;
        }
        .payment-card h5 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">{{ __('all.customer_dashboard') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- Add your navigation links here -->
                <li class="nav-item active">
                    <a class="nav-link" href="#">{{ __('all.home') }} <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.mybooking') }}">{{ __('all.my_bookings') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.by_route') }}">{{ __('all.search_buses') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('all.profile') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">{{ __('all.logout') }}</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-container">
        <h1>{{ __('all.welcome_to_your_dashboard') }}</h1>

        <div class="row">
            <div class="col-md-3">
                <div class="payment-card bg-success text-white">
                    <h5>{{ __('all.paid') }}</h5>
                    <h3>{{ $paidCount }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="payment-card bg-danger text-white">
                    <h5>{{ __('all.failed') }}</h5>
                    <h3>{{ $failedCount }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="payment-card bg-warning text-dark">
                    <h5>{{ __('all.unpaid') }}</h5>
                    <h3>{{ $unpaidCount }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="payment-card bg-secondary text-white">
                    <h5>{{ __('all.cancelled') }}</h5>
                    <h3>{{ $cancelledCount }}</h3>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <h2>{{ __('all.quick_actions') }}</h2>
            <p>{{ __('all.here_are_some_things_you_can_do') }}</p>
            <ul>
                <li><a href="{{ route('customer.mybooking') }}">{{ __('all.view_my_bookings') }}</a></li>
                <li><a href="{{ route('customer.by_route') }}">{{ __('all.search_buses') }}</a></li>
                <!-- Add more links as needed -->
            </ul>
        </div>

        <div class="dashboard-card">
            <h2>{{ __('all.profile_information') }}</h2>
            <!-- Display user information here, e.g., name, email -->
            <p>{{ __('all.welcome') }} {{ auth()->user()->name }}</p>
            <p>{{ __('all.email') }}: {{ auth()->user()->email }}</p>
            <!-- Link to profile edit page -->
            <a href="#">{{ __('all.edit_profile') }}</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
