<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('all.higlink_premium_travel') }}</title>  
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles()
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --accent: #ec4899;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        .hero-bg {
            background-image: linear-gradient(135deg, rgba(39, 39, 42, 0.95) 0%, rgba(24, 24, 27, 0.95) 100%), url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .nav-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .mobile-nav-glass {
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .btn-glow {
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
            background-image: linear-gradient(45deg, var(--primary), var(--secondary));
        }

        .btn-glow:hover {
            box-shadow: 0 6px 24px rgba(99, 102, 241, 0.5);
            transform: translateY(-2px);
        }

        .btn-accent {
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.3);
            background-image: linear-gradient(45deg, var(--accent), #f43f5e);
        }

        .btn-accent:hover {
            box-shadow: 0 6px 24px rgba(236, 72, 153, 0.4);
        }

        .mobile-menu {
            transform: translateY(-150%);
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .mobile-menu.active {
            transform: translateY(0);
        }

        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .gradient-text {
            background-image: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .gradient-border {
            position: relative;
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        /* Link hover effects */
        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Route card hover effect */
        .route-card {
            transition: all 0.3s ease;
        }

        .route-card:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        }

        /* Floating bubbles */
        .bubble {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.3;
            z-index: -1;
        }

        /* Animated gradient background */
        .animated-gradient {
            background: linear-gradient(-45deg, #6366f1, #8b5cf6, #ec4899, #f43f5e);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.8s ease forwards;
        }

        /* Delay animations */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
    </style>
</head>
<body class="font-sans bg-gray-50">
    <!-- Navigation -->
    @include('test.nav')

    <!-- Hero Section -->
    <section id="home" class="hero-bg text-white pt-28 pb-24 relative overflow-hidden">
        <!-- Floating bubbles -->
        <div class="bubble w-64 h-64 bg-indigo-500 top-1/4 -left-20"></div>
        <div class="bubble w-96 h-96 bg-pink-500 bottom-0 -right-40"></div>
        
        <div class="container mx-auto px-4 relative z-10">
             
            <!-- Search Form -->
           @include('test.sach')
        </div>
    </section>

    <!-- Popular Routes --> 
    @include('test.popular')
    <!-- Features -->
    <section id="features" class="py-20 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <div class="bubble w-96 h-96 bg-indigo-100 -top-60 -left-60"></div>
        <div class="bubble w-80 h-80 bg-pink-100 bottom-0 -right-40"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12 fade-in">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">{{ __('all.why_choose_higlink') }} <span class="gradient-text">HIGHLINK</span>?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">{{ __('all.experience_travel_redefined') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-card p-8 fade-in delay-100">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-6 text-white text-2xl">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-center">{{ __('all.safety_first') }}</h3>
                    <p class="text-gray-600 text-center">{{ __('all.safety_first_description') }}</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card p-8 fade-in delay-200">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-6 text-white text-2xl">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-center">{{ __('all.premium_comfort') }}</h3>
                    <p class="text-gray-600 text-center">{{ __('all.premium_comfort_description') }}</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-8 fade-in delay-300">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-6 text-white text-2xl">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-center">{{ __('all.hassle_free_booking') }}</h3>
                    <p class="text-gray-600 text-center">{{ __('all.hassle_free_booking_description') }}</p>
                </div>
            </div>

            <!-- Additional Features -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                <div class="glass-card p-8 fade-in delay-400">
                    <div class="flex items-start">
                        <div class="bg-gradient-to-r from-pink-500 to-rose-500 w-14 h-14 rounded-xl flex items-center justify-center mr-6 text-white text-xl">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl mb-3">{{ __('all.punctual_service') }}</h3>
                            <p class="text-gray-600">{{ __('all.punctual_service_description') }}</p>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-8 fade-in delay-500">
                    <div class="flex items-start">
                        <div class="bg-gradient-to-r from-pink-500 to-rose-500 w-14 h-14 rounded-xl flex items-center justify-center mr-6 text-white text-xl">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl mb-3">{{ __('all.support_24_7') }}</h3>
                            <p class="text-gray-600">{{ __('all.support_24_7_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Steps -->
    <section id="book" class="py-20 bg-white relative overflow-hidden">
        <div class="bubble w-64 h-64 bg-indigo-100 top-1/4 -left-20"></div>
        <div class="bubble w-96 h-96 bg-pink-100 bottom-0 -right-40"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12 fade-in">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">{{ __('all.how_to_book') }} <span class="gradient-text">Book</span></h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">{{ __('all.three_simple_steps') }}</p>
            </div>

            <div class="flex flex-col md:flex-row justify-center items-center md:items-start space-y-12 md:space-y-0 md:space-x-12">
                <!-- Step 1 -->
                <div class="flex flex-col items-center text-center max-w-xs fade-in delay-100">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold mb-6 floating">1</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('all.search_select') }}</h3>
                    <p class="text-gray-600">{{ __('all.search_select_description') }}</p>
                </div>

                <div class="hidden md:block mt-14 fade-in delay-150">
                    <div class="w-20 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
                    <div class="w-20 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full mt-1 opacity-60"></div>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col items-center text-center max-w-xs fade-in delay-200">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold mb-6 floating" style="animation-delay: 0.2s">2</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('all.enter_details') }}</h3>
                    <p class="text-gray-600">{{ __('all.enter_details_description') }}</p>
                </div>

                <div class="hidden md:block mt-14 fade-in delay-250">
                    <div class="w-20 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
                    <div class="w-20 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full mt-1 opacity-60"></div>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col items-center text-center max-w-xs fade-in delay-300">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold mb-6 floating" style="animation-delay: 0.4s">3</div>
                    <h3 class="font-bold text-xl mb-3">{{ __('all.pay_travel') }}</h3>
                    <p class="text-gray-600">{{ __('all.pay_travel_description') }}</p>
                </div>
            </div>

             
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-20 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <div class="bubble w-80 h-80 bg-indigo-100 -top-40 -right-40"></div>
        <div class="bubble w-64 h-64 bg-pink-100 bottom-20 -left-20"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12 fade-in">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">{{ __('all.what_passengers_say') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">{{ __('all.hear_from_thousands') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="glass-card p-6 fade-in delay-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center mr-4 overflow-hidden">
                            <img src="{{ asset('testimonials/IMG-20251015-WA0005.jpg') }}" alt="Rose Mshanga" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold">Rose Mshanga</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"{{ __('all.sarah_testimonial') }}"</p>
                    <div class="text-xs text-gray-500">{{ __('all.traveled_dar_mwanza_may') }}</div>
                </div>

                <!-- Testimonial 2 -->
                <div class="glass-card p-6 fade-in delay-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center mr-4 overflow-hidden">
                            <img src="{{ asset('testimonials/IMG-20251016-WA0000.jpg') }}" alt="Christina Ekarist" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold">Christina Ekarist</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"{{ __('all.michael_testimonial') }}"</p>
                    <div class="text-xs text-gray-500">{{ __('all.traveled_arusha_dodoma_june') }}</div>
                </div>

                <!-- Testimonial 3 -->
                <div class="glass-card p-6 fade-in delay-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center mr-4 overflow-hidden">
                            <img src="{{ asset('testimonials/IMG-20251017-WA0005.jpg') }}" alt="Pokea Panja" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold">Pokea Panja</h4>
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"{{ __('all.amina_testimonial') }}"</p>
                    <div class="text-xs text-gray-500">{{ __('all.traveled_dar_mbeya_april') }}</div>
                </div>
            </div>

            <div class="text-center mt-10 fade-in delay-400">
                <button class="px-6 py-3 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 font-medium transition-all btn-glow">
                    {{ __('all.view_more_testimonials') }} <i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Special Offers -->
    
    <!-- Download App -->
    <section class="py-20 bg-gradient-to-r from-indigo-500 to-purple-600 text-white relative overflow-hidden">
        <div class="bubble w-64 h-64 bg-white/10 top-1/4 -left-20"></div>
        <div class="bubble w-96 h-96 bg-white/10 bottom-0 -right-40"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0 md:pr-10 fade-in">
                    <h2 class="text-3xl md:text-4xl font-extrabold mb-4">{{ __('all.get_mobile_app') }}</h2>
                    <p class="mb-6 text-indigo-100 max-w-md">{{ __('all.app_description') }}</p>
                    
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <button class="bg-black/90 hover:bg-black text-white px-6 py-3 rounded-xl flex items-center justify-center transition-all glass-card">
                            <i class="fab fa-apple text-2xl mr-3"></i>
                            <div class="text-left">
                                <div class="text-xs text-gray-300">{{ __('all.download_on_the') }}</div>
                                <div class="font-bold">{{ __('all.app_store') }}</div>
                            </div>
                        </button>
                        <button class="bg-black/90 hover:bg-black text-white px-6 py-3 rounded-xl flex items-center justify-center transition-all glass-card">
                            <i class="fab fa-google-play text-2xl mr-3"></i>
                            <div class="text-left">
                                <div class="text-xs text-gray-300">{{ __('all.get_it_on') }}</div>
                                <div class="font-bold">{{ __('all.google_play') }}</div>
                            </div>
                        </button>
                    </div>
                    
                    <div class="mt-8 flex items-center space-x-4">
                        <div class="flex -space-x-2">
                            <img src="{{ asset('testimonials/IMG-20251015-WA0005.jpg') }}" class="w-10 h-10 rounded-full border-2 border-white" alt="{{ __('all.user_alt_text') }}">
                            <img src="{{ asset('testimonials/IMG-20251016-WA0000.jpg') }}" class="w-10 h-10 rounded-full border-2 border-white" alt="{{ __('all.user_alt_text') }}">
                            <img src="{{ asset('testimonials/IMG-20251017-WA0005.jpg') }}" class="w-10 h-10 rounded-full border-2 border-white" alt="{{ __('all.user_alt_text') }}">
                        </div>
                        <div class="text-sm text-indigo-100">
                            <div class="font-medium">{{ __('all.join_happy_users') }}</div>
                            <div class="flex text-yellow-400 text-xs">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="text-indigo-200 ml-1">{{ __('all.reviews_4_8') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:w-1/2 flex justify-center relative fade-in delay-200">
                    <img src="https://cdn.pixabay.com/photo/2017/01/22/12/07/imac-1999636_1280.png" alt="{{ __('all.mobile_app_alt_text') }}" class="w-64 h-auto floating">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-400/20 rounded-full filter blur-xl"></div>
                    <div class="absolute left-10 bottom-0 w-40 h-40 bg-pink-400/20 rounded-full filter blur-xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    @include('test.faq')

    <!-- Newsletter -->
    <section class="py-16 bg-gradient-to-r from-indigo-500 to-purple-600 text-white relative overflow-hidden">
        <div class="bubble w-64 h-64 bg-white/10 top-1/4 -left-20"></div>
        <div class="bubble w-96 h-96 bg-white/10 bottom-0 -right-40"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="glass-card p-8 md:p-12 max-w-4xl mx-auto">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 mb-6 md:mb-0 md:pr-8 fade-in">
                        <h2 class="text-2xl md:text-3xl font-extrabold mb-3">{{ __('all.stay_updated') }}</h2>
                        <p class="text-indigo-100">{{ __('all.subscribe_newsletter') }}</p>
                    </div>
                    <div class="md:w-1/2 w-full fade-in delay-200">
                        <form class="flex">
                            <input type="email" placeholder="{{ __('all.your_email_address') }}" class="flex-1 px-4 py-3 rounded-l-lg focus:outline-none text-gray-900">
                            <button class="bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-6 py-3 rounded-r-lg font-medium transition-all btn-accent">
                                {{ __('all.subscribe') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('test.footer')

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-8 right-8 bg-gradient-to-r from-indigo-500 to-purple-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform z-50 hidden">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            mobileMenuBtn.innerHTML = mobileMenu.classList.contains('active') ? 
                '<i class="fas fa-times text-xl"></i>' : '<i class="fas fa-bars text-xl"></i>';
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
            });
        });

        // FAQ accordion
        document.querySelectorAll('.glass-card .flex.items-center').forEach(item => {
            item.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('i');
                
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        });

        // Back to top button
        const backToTopButton = document.getElementById('back-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('hidden');
            } else {
                backToTopButton.classList.add('hidden');
            }
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Smooth scroll for nav links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Tab switching
        const tabs = document.querySelectorAll('.search-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => {
                    t.classList.remove('active');
                });
                tab.classList.add('active');
            });
        });

        // Animation on scroll
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const fadeInOnScroll = () => {
            fadeElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };
        
        // Initialize elements as hidden
        fadeElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        });
        
        window.addEventListener('scroll', fadeInOnScroll);
        window.addEventListener('load', fadeInOnScroll);
    </script>
    @livewireScripts()
</body>
</html>
