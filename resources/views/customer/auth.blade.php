<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('all.bus_booking') }} - @yield('title')</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            width: 100%;
            max-width: 450px;
        }
        
        .card-title {
            color: #333;
            font-weight: 600;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 10px;
            font-weight: 500;
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-logo img {
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="auth-logo">
            <i class="bi bi-bus-front" style="font-size: 2.5rem; color: #0d6efd;"></i>
            <h3 class="mt-2">{{ __('all.highlink_isgc') }}</h3>
        </div>
        
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
