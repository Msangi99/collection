<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> HIGHLINK ISGC - @yield('title')</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Custom styles for sliding sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            width: 250px;
            padding: 20px 0;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                margin-left: -250px;
            }

            .sidebar.active {
                margin-left: 0;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            }

            .main-content {
                margin-left: 0;
            }

            body {
                overflow-x: hidden;
            }

            .sidebar-close-btn {
                display: block !important;
            }
        }

        /* Ensure navbar stays on top */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Sidebar close button */
        .sidebar-close-btn {
            display: none;
            position: absolute;
            right: 10px;
            top: 10px;
            color: white;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .sidebar-close-btn:hover {
            color: #aaa;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar.active~.sidebar-overlay {
            display: block;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay (for mobile) -->
    <div class="sidebar-overlay"></div>

    <div class="d-flex">

        <!-- Main Content -->
        <div class="main-content w-100">
            @include('partials.navbar')

            <main class="container-fluid py-4">
                @yield('content')
            </main>

            @include('partials.footer')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom JS for sidebar toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const navbarToggler = document.querySelector('.navbar-toggler');
            const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');

            // Toggle sidebar when navbar toggler is clicked
            navbarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });

            // Close sidebar when close button is clicked
            sidebarCloseBtn.addEventListener('click', function() {
                sidebar.classList.remove('active');
            });

            // Close sidebar when overlay is clicked
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnNavbarToggler = navbarToggler.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnNavbarToggler && window.innerWidth <= 767.98) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>
</body>

</html>
