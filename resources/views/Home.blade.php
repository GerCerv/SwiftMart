<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SwiftMart | Fresh, Quick and Smooth Organic Vegetables</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Animate.css for existing shake animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/uppernav.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/middlenav.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/popups.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/lowernav.css') }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/YOUR-FONTAWESOME-KIT.js" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary: #2f855a;
            --primary-dark: #276749;
            --secondary: #f56565;
            --accent: #f6e05e;
            --dark: #1a202c;
            --light: #f7fafc;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(to bottom, #f0fdf4, #ecfdf5);
            color: var(--dark);
            transition: all 0.3s ease;
            overflow-x: hidden;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background: #1a202c;
            color: #e2e8f0;
        }
        .dark-mode .card, .dark-mode .product-card, .dark-mode .category-card, .dark-mode .hot-deals-card {
            background: #2d3748;
            border-color: #4a5568;
        }
        .dark-mode .text-dark {
            color: #e2e8f0 !important;
        }
        .dark-mode .bg-light {
            background: #2d3748 !important;
        }

        /* Product Cards */
        .product-card {
            transition: all 0.4s ease;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            border: 2px solid var(--primary);
        }
        .product-img {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-img {
            transform: scale(1.08);
        }

        /* Category Cards */
        .category-card {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            background:#f6e05e;
        }
        .category-img {
            height: 140px;
            object-fit: cover;
            transition: transform 0.5s ease, filter 0.5s ease;
        }
        .category-card:hover .category-img {
            transform: scale(1.1);
            filter: brightness(1.1);
        }
        .category-card h5 {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;
        }

        /* Carousel */
        .carousel-item {
            height: 400px;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
        }
        .carousel-item img {
            height: 100%;
            object-fit: cover;
            filter: brightness(0.8);
        }
        .carousel-caption {
            position: absolute;
            transform: translate(-50%, -50%);
            margin-top:20px;
            padding: 15px 30px;
            border-radius: 12px;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }
        .carousel-caption h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            margin: 0;
        }
        .carousel-caption a {
            display: inline-block;
            margin-top: 10px;
            background: var(--accent);
            color: var(--dark);
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        .carousel-caption a:hover {
            background: #d6c94e;
            transform: scale(1.05);
        }

        
        .slider-container {
            overflow: hidden;
            position: relative;
        }
        .slider {
            display: flex;
            transition: transform 0.8s ease;
        }
        .slider-item {
            flex: 0 0 50%;
            padding: 0 10px;
        }

        .promo-sections {
            position: relative;
            height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
            border-radius: 16px;
            margin: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .promo-sections h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .promo-sections::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(47, 133, 90, 0.4), rgba(47, 133, 90, 0.8));
            border-radius: 16px;
        }

        /* Promotional Section */
        .promo-section {
            position: relative;
            background: url('{{ asset('images/farm-background.jpg') }}') no-repeat center center;
            background-size: cover;
            height: 450px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
            border-radius: 16px;
            margin: 5rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .promo-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(47, 133, 90, 0.4), rgba(47, 133, 90, 0.8));
            border-radius: 16px;
        }
        .promo-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .promo-section p {
            font-size: 1.3rem;
            z-index: 1;
            margin: 1rem 0;
        }
        .promo-section a {
            background: var(--secondary);
            color: #fff;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            z-index: 1;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .promo-section a:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Badges */
        .badge-organic {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }
        .badge-sale {
            background: var(--secondary);
            color: #fff;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }
        .stock-low {
            background: var(--secondary);
            color: white;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .carousel-item {
                height: 300px;
            }
            .carousel-caption h3 {
                font-size: 1.5rem;
            }
            .promo-section {
                height: 350px;
            }
            .promo-section h1 {
                font-size: 2rem;
            }
            .promo-section p {
                font-size: 1.1rem;
            }
            .product-img {
                height: 180px;
            }
             /* Hot Deals Section */
            .hot-deals-container {
                max-width: 100%;
            }
            .hot-deals-container h2 {
                font-size: 1.8rem;
                margin-bottom: 1.5rem;
            }
            .hot-deals-slider {
                width: 300% !important; /* Ensure 3 items take full width */
            }
            .hot-deal-item {
                width: 100% !important; /* Show one item at a time */
            }
            .hot-deals-slider {
                transform: translateX(-${active * 100}%); /* Adjust transform for single item view */
            }
            .hot-deal-item .relative {
                height: 200px !important;
            }
            .hot-deal-item img {
                height: 100%;
            }
            .hot-deal-item h5 {
                font-size: 1rem;
            }
            .hot-deal-item .price {
                font-size: 0.9rem;
            }
            .hot-deal-item .absolute.top-2 {
                font-size: 0.8rem;
                padding: 4px 8px;
            }
            .hot-deals-container .flex.justify-center {
                margin-top: 1rem;
            }
            .hot-deals-container .w-3 {
                width: 8px;
                height: 8px;
            }
            .hot-deals-container .w-6 {
                width: 20px;
            }
            
        }
        @media (max-width: 768px) {
            .hero-section {
                padding: 3rem 0;
                text-align: center;
            }
            .hero-title {
                font-size: 2rem;
            }
            .hero-subtitle {
                font-size: 1rem;
            }
            .carousel-item {
                height: 250px;
            }
        }
        .hot-deals-container {
        perspective: 1000px;
        }
        .hot-deal-item {
            transition: transform 0.5s ease, opacity 0.5s ease, z-index 0.5s ease;
        }
        .hot-deal-item.scale-110 {
            transform: scale(1.1) translateY(-10px);
            z-index: 10;
            opacity: 1;
        }
        .hot-deal-item:not(.scale-110) {
            filter: brightness(0.9);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="autoDismissAlert" data-aos="fade-down">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance('#autoDismissAlert');
            alert.close();
        }, 5000);
    </script>
    @endif
    @if(session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="autoDismissAlert" data-aos="fade-down">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance('#autoDismissAlert');
            alert.close();
        }, 5000);
    </script>
    @endif
    

    <!-- Navigation Components -->
    <x-uppernav/>
    <x-loginmodal/>
    <x-signupmodal/>
    <x-middlenav/>
    <x-lowernav/>
    <x-sidebar/>

    <!-- Main Content -->
    <div class="container my-6">
        <div class="row g-4">
            <!-- Carousel -->
            <div class="col-lg-6 mb-4" data-aos="fade-up">
                
                <div id="carouselExample" class="carousel slide shadow-lg rounded-3 overflow-hidden" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('images/chono.jpg') }}" class="d-block w-100" alt="Shop Now">
                            <div class="carousel-caption" data-aos="zoom-in">
                                <h3>Shop now in our site</h3>
                                <a href="#">Explore Now</a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('images/anime.jpg') }}" class="d-block w-100" alt="Sign Up">
                            <div class="carousel-caption" data-aos="zoom-in">
                                <h3>Sign up now</h3>
                                <a href="#">Join Today</a>
                            </div>
                        </div>
                        <!-- Dynamic Ad from Database -->
                        @if($advertisements->isNotEmpty())
                            @foreach($advertisements as $index => $ad)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/'.$ad->image_path) }}" class="d-block w-100" alt="{{ $ad->title }}" style="height: 400px; object-fit: cover;">
                                    @else
                                        <div class="d-block w-100 bg-secondary" style="height: 400px;"></div>
                                    @endif
                                    <div class="carousel-caption" data-aos="zoom-in">
                                        <h3>{{ \Illuminate\Support\Str::limit($ad->description, 100) }}</h3>
                                        
                                        <a href="{{ $ad->title }}">Shop Now!</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Can add promotion here
                            Fallback if no ads in database
                            <div class="carousel-item active">
                                <div class="d-block w-100 bg-light" style="height: 400px;"></div>
                                <div class="carousel-caption text-dark">
                                    <h3>Special Promotion</h3>
                                    <p>Check our latest offers</p>
                                    <a href="#" class="btn btn-outline-dark">Learn More</a>
                                </div>
                            </div> -->
                        @endif
                        
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>

                 <!-- Featured Products -->
                 <div class="row mt-2 g-3">
                    @forelse ($topProducts as $index => $product)
                       <div class="col-6" data-aos="fade-up" data-aos-delay="50">
                            <div class="card product-card h-100 border-0">
                                <div class="position-relative">
                                    {{-- Product Badge --}}
                                    @if($product->badge ?? false)
                                        <span class="badge position-absolute top-0 end-0 m-2 px-3 py-1 
                                            {{ $product->badge === 'Organic' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->badge }}
                                        </span>
                                    @endif

                                    {{-- Discount Ribbon --}}
                                    @if($product->discount)
                                        <div class="position-absolute top-0 start-0 z-1" style="width: 0; height: 0;">
                                            <div class="bg-pink-600 text-white text-center fw-bold small px-2 py-1 shadow-sm rounded-end"
                                                style="transform: rotate(-45deg); position: absolute; top: 10px; left: -35px; width: 120px;">
                                                {{ $product->discount }}% OFF
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Product Image --}}
                                    <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/default-product.jpg') }}"
                                        class="card-img-top object-fit-cover"
                                        style="height: 200px;"
                                        alt="{{ $product->name }}">
                                </div>

                                {{-- Card Body --}}
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h5 class="card-title mb-2 text-dark fw-semibold text-green-600">
                                        <a href="{{ route('product.show', $product->id) }}" class="hover:text-green-600 text-l">
                                            {{ ucfirst(strtolower($product->name)) }}
                                        </a>
                                    </h5>

                                    <div class="d-flex align-items-center gap-1 text-green-700 mb-2">
                                        <i class="fas fa-store"></i>
                                        <a href="{{ route('vendor.show', $product->vendor->vendor_id) }}" class="hover:underline text-green-700 font-semibold">
                                            {{ ucfirst($product->vendor->store_name) }}
                                        </a>
                                    </div>

                                    {{-- Ratings --}}
                                    <div class="mb-2 text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-star {{ $product->averageRating() >= $i ? 'fas' : ($product->averageRating() >= $i - 0.5 ? 'fas fa-star-half-alt' : 'far') }}"></i>
                                        @endfor
                                    </div>

                                    {{-- Price + Button --}}
                                    <div>
                                        <div class="d-flex align-items-baseline gap-2 mb-2">
                                            @if($product->discount)
                                                @php
                                                    $discountedPrice = $product->price - ($product->price * ($product->discount / 100));
                                                @endphp
                                                <span class="fw-bold text-success">₱{{ number_format($discountedPrice, 2) }}</span>
                                                <span class="text-muted text-decoration-line-through small">₱{{ number_format($product->price, 2) }}</span>
                                            @else
                                                <span class="fw-bold text-success">₱{{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Optional: fallback products or message here --}}
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No top products found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            
            <div class="col-lg-6 " data-aos="fade-up">
                
                
                <div class="container" style="margin-top: 0.5 rem;">
                    <div class="row">
                        <div class="col-12">
                            <div class="card position-relative overflow-hidden border-0 rounded-4" data-aos="fade-up" style="height: 100px;">
                                <a href="{{ route('usersnavs.shop', ['query' => 'Discount']) }}" class="position-relative d-block h-100">
                                    <!-- Image -->
                                    <img src="{{ asset('images/ds.png') }}" class="card-img object-fit-cover w-100 h-100" alt="Discounted Items" style="object-fit: cover;">

                                    <!-- Dark overlay -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" style="z-index: 1;"></div>

                                    <!-- Centered text -->
                                    <div class="position-absolute top-50 start-50 translate-middle text-center text-white px-4 py-2 rounded-pill bg-yellow-400 bg-opacity-75" style="text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6); z-index: 2;">
                                    <a href="{{ route('usersnavs.shop', ['query' => 'Discount']) }}">
                                        <h3 class="fw-bold mb-0 fs-3 ">
                                            Discounted Items 🔥
                                        </h3>
                                    </a>   
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Desktop Discount Products Section (Hidden on Mobile) -->
                <div class="row g-3 mt-2 border-b-2 border-green-400  d-none d-md-flex">
                    @foreach($discountedProducts as $index => $product)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="category-card h-100">
                            <a href="{{ route('product.show', $product->id) }}">
                            <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg') }}" 
                                class="img-fluid category-img" 
                                alt="{{ $product->name }}" 
                                style="height:200px; object-fit: cover; width: 100%;">
                            </a>
                                 @if($product->discount > 0)
                                    <!-- Discount Badge on top of the image -->
                                    <div class="absolute top-4 -right-16 bg-pink-600 text-white text-lg font-extrabold px-16 py-1 shadow-lg transform rotate-45 hover:scale-110 transition-transform duration-300 z-30">
                                        {{ $product->discount }}% OFF
                                    </div>
                                @endif
                            <div class="p-3 text-center">
                                <h5 style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.88);">{{ $product->name }}</h5>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>


                <!-- Mobile View (Slider for Discounted Products) -->
                <div class="d-md-none mt-3">
                    <div class="slider-container overflow-auto px-2" style="white-space: nowrap; scroll-snap-type: x mandatory;">
                        @foreach($discountedProducts as $index => $product)
                            <div class="slider-item d-inline-block" style="width: 70%; scroll-snap-align: start; margin-right: 1rem;" data-aos="fade-right" data-aos-delay="{{ $index * 100 }}">
                                <a href="{{ route('product.show', $product->id) }}" class="category-card h-100 d-block">
                                    <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg') }}" 
                                        class="img-fluid category-img" 
                                        alt="{{ $product->name }}" 
                                        style="height:150px; width:100%; object-fit:cover;">
                                        @if($product->discount > 0)
                                            <!-- Discount Badge on top of the image -->
                                            <div class="absolute top-6 -right-16 bg-pink-600 text-white text-lg font-bold px-16 py-1 shadow-lg transform rotate-45 hover:scale-110 transition-transform duration-300 z-30">
                                                {{ $product->discount }}% OFF
                                            </div>
                                        @endif
                                    <div class="p-3 text-center">
                                        <h5>{{ $product->name }}</h5>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                
                <!-- Hot Deals Section DESKTOP-->
                <div class="mt-2 py-4 bg-red-500 rounded-xl d-none d-md-block" 
                style="background-image: url('{{ asset('images/das1.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
                data-aos="fade-up">
                    <div class="container mx-auto px-4">
                        <h2 class="text-center mb-8 text-3xl font-bold text-white" data-aos="fade-up">🔥 Hot Deals</h2>
                        
                        <div class="hot-deals-container relative max-w-4xl mx-auto"
                            x-data="{ 
                                active: 0, 
                                deals: {{ $hotDeals->count() }},
                                
                                hotDeals: [
                                    @foreach($hotDeals as $deal)
                                    { 
                                        img: '{{ $deal->image_path ? asset('storage/products/' . $deal->image_path) : asset('images/default-product.jpg') }}', 
                                        name: '{{ $deal->product->name }}', 
                                        price: '₱{{ number_format(
                                            $deal->product->price * (1 - (($deal->discount_percentage + $deal->product->discount) / 100)),
                                            2
                                        ) }}',
                                        originalPrice: '₱{{ number_format($deal->product->price, 2) }}',
                                        discount: '{{ $deal->discount_percentage + $deal->product->discount }}% OFF',
                                        link: '{{ route('product.show', $deal->product->id) }}'
                                    },
                                    @endforeach
                                ]
                            }"
                            x-init="
                                setInterval(() => {
                                    active = (active + 1) % deals;
                                }, 4000);
                            ">
                            @if($hotDeals->count() > 0)
                            <!-- Slider Container -->
                            <div class="relative overflow-hidden h-64">
                                <div class="hot-deals-slider flex transition-transform duration-700 ease-in-out"
                                    :style="`transform: translateX(-${active * (40 / deals)}%); width: ${deals * 40}%`">
                                    
                                    <!-- Deal Items -->
                                    <template x-for="(deal, index) in hotDeals" :key="index">
                                        <div class="w-1/3 px-4 flex-shrink-0 transition-all duration-500 ease-in-out transform"
                                            :class="{ 
                                                'scale-110 z-10': active === index, 
                                                'scale-90 opacity-70': active !== index 
                                            }">
                                            <a :href="deal.link" class="block w-full h-full">
                                            <div class="relative w-full h-full rounded-xl overflow-hidden shadow-2xl border-4 border-white">
                                                <img class="w-full h-full object-cover object-center"
                                                    :src="deal.img"
                                                    :alt="deal.name">
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                                    <h5 class="font-extrabold text-white text-lg" x-text="deal.name"
                                                    style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.88);"></h5>
                                                    <div class="flex items-center ">
                                                    <div class="price text-green-400 font-extrabold mr-1" 
                                                        x-text="deal.price" 
                                                        style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.88);">
                                                    </div>
                                                    <div class="original-price text-gray-400 text-xs line-through" 
                                                        x-text="deal.originalPrice" 
                                                        style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.88);">
                                                    </div>
                                                    </div>
                                                </div>
                                                <div x-show="active === index" class="absolute top-8 -right-20 bg-pink-600 text-white text-lg font-bold px-20 py-1 shadow-lg transform rotate-45 hover:scale-110 transition-transform duration-300 z-30">
                                                    <span class="text-white" x-text="deal.discount"></span>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Indicators -->
                            <div class="flex justify-center mt-6 space-x-2">
                                <template x-for="i in deals">
                                    <button class="w-3 h-3 rounded-full transition-all transform hover:scale-125"
                                            :class="{ 'bg-green-600 w-6': active === i-1, 'bg-gray-300': active !== i-1 }"
                                            @click="active = i-1"></button>
                                </template>
                            </div>
                            @else
                            <div class="text-center py-8 text-white">
                                <p>No hot deals available at the moment. Check back later!</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Hot Deals Section MOBILE -->
                <div class="mt-2 py-4 bg-red-500 rounded-xl d-block d-md-none" 
                    style="background-image: url('{{ asset('images/das1.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
                    data-aos="fade-up">
                    <div class="container mx-auto px-4">
                        <h2 class="text-center mb-8 text-3xl font-bold text-white" data-aos="fade-up">🔥 Hot Deals</h2>

                        <div class="hot-deals-container relative max-w-4xl mx-auto"
                            x-data="{ 
                                active: 0, 
                                deals: {{ $hotDeals->count() }},
                                hotDeals: [
                                    @foreach($hotDeals as $deal)
                                    { 
                                        img: '{{ $deal->image_path ? asset('storage/products/' . $deal->image_path) : asset('images/default-product.jpg') }}', 
                                        name: '{{ $deal->product->name }}', 
                                        price: '₱{{ number_format(
                                            $deal->product->price * (1 - (($deal->discount_percentage + $deal->product->discount) / 100)),
                                            2
                                        ) }}',
                                        originalPrice: '₱{{ number_format($deal->product->price, 2) }}',
                                        discount: '{{ $deal->discount_percentage + $deal->product->discount }}% OFF',
                                        link: '{{ route('product.show', $deal->product->id) }}'
                                    },
                                    @endforeach
                                ]
                            }"
                            x-init="
                                setInterval(() => {
                                    if (deals > 0) {
                                        active = (active + 1) % deals;
                                    }
                                }, 4000);
                            ">
                            
                            @if($hotDeals->count() > 0)
                            <!-- Slider Container -->
                            <div class="relative overflow-hidden h-64">
                                <div class="hot-deals-slider flex transition-transform duration-700 ease-in-out"
                                    :style="`transform: translateX(-${active * (100 / deals)}%); width: ${deals * 100}%`">
                                    
                                    <!-- Deal Items -->
                                    <template x-for="(deal, index) in hotDeals" :key="index">
                                        <div class="w-1/3 px-4 flex-shrink-0 transition-all duration-500 ease-in-out transform"
                                            :class="{ 
                                                'scale-110 z-10': active === index, 
                                                'scale-90 opacity-70': active !== index 
                                            }">
                                            <a :href="deal.link" class="block w-full h-full">
                                            <div class="relative w-full h-full rounded-xl overflow-hidden shadow-2xl border-4 border-white z-50">
                                                <img class="w-full h-full object-cover object-center "
                                                    :src="deal.img"
                                                    :alt="deal.name">
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                                    <h5 class="font-bold text-white text-lg" x-text="deal.name"></h5>
                                                    <div class="flex items-center">
                                                        <div class="price text-green-300 font-bold mr-2" x-text="deal.price"></div>
                                                        <div class="original-price text-gray-300 text-sm line-through" x-text="deal.originalPrice"></div>
                                                    </div>
                                                </div>
                                                <div x-show="active === index" class="absolute top-8 -right-20 bg-pink-600 text-white text-lg font-bold px-20 py-1 shadow-lg transform rotate-45 hover:scale-110 transition-transform duration-300 z-30">
                                                    <span class="text-white" x-text="deal.discount"></span>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Indicators -->
                            <div class="flex justify-center mt-6 space-x-2">
                                <template x-for="i in deals">
                                    <button class="w-3 h-3 rounded-full transition-all transform hover:scale-125"
                                            :class="{ 'bg-green-600 w-6': active === i-1, 'bg-gray-300': active !== i-1 }"
                                            @click="active = i-1"></button>
                                </template>
                            </div>
                            @else
                            <div class="text-center py-8 text-white">
                                <p>No hot deals available at the moment. Check back later!</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                


            </div>
        </div>
    </div>

    <!-- Fresh Picks Section -->
    <div class="container my-6">
        <div class="promo-sections mb-4" data-aos="fade-up">
            <h1 class="fw-bold text-uppercase">Fresh Picks</h1>
        </div>
        <div class="row g-4">
            @foreach($recentProducts as $index => $product)
                <div class="col-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="card product-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            {{-- Product Badge --}}
                            @if($product->badge ?? false)
                                <span class="badge position-absolute top-0 end-0 m-2 px-3 py-1 
                                    {{ $product->badge === 'Organic' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->badge }}
                                </span>
                            @endif

                            {{-- Discount Ribbon --}}
                            @if($product->discount)
                                <div class="position-absolute top-0 start-0 z-1" style="width: 0; height: 0;">
                                    <div class="bg-pink-600 text-white text-center fw-bold small px-2 py-1 shadow-sm rounded-end"
                                        style="transform: rotate(-45deg); position: absolute; top: 10px; left: -35px; width: 120px;">
                                        {{ $product->discount }}% OFF
                                    </div>
                                </div>
                            @endif

                            {{-- Product Image --}}
                            <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/default-product.jpg') }}"
                                class="card-img-top object-fit-cover"
                                style="height: 200px;"
                                alt="{{ $product->name }}">
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body d-flex flex-column justify-content-between">
                            
                            <h5 class="card-title mb-2 text-dark fw-semibold text-green-600"> <a href="{{ route('product.show', $product->id) }}" class="hover:text-green-600 text-l"> {{ ucfirst(strtolower($product->name)) }}</a></h5>

                            <div class="d-flex align-items-center gap-1 text-green-700">
                                <i class="fas fa-store"></i>
                                <p class="text-l text-green-700 font-semibold">
                                    
                                    <a href="{{ route('vendor.show', $product->vendor->vendor_id) }}" class="hover:underline text-green-700">
                                        {{ ucfirst($product->vendor->store_name) }}
                                    </a>
                                </p>
                            </div>

                            {{-- Ratings --}}
                            <div class="mb-2 text-warning small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-star {{ $product->averageRating() >= $i ? 'fas' : ($product->averageRating() >= $i - 0.5 ? 'fas fa-star-half-alt' : 'far') }}"></i>
                                @endfor
                            </div>

                            {{-- Price + Button --}}
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="d-flex align-items-baseline gap-2">
                                    @if($product->discount)
                                        @php
                                            $discountedPrice = $product->price - ($product->price * ($product->discount / 100));
                                        @endphp
                                        <span class="fw-bold text-success">₱{{ number_format($discountedPrice, 2) }}</span>
                                        <span class="text-muted text-decoration-line-through small">₱{{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="fw-bold text-success">₱{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                
                            </div>
                            <!-- <button class="btn btn-success w-100 rounded-pill fw-semibold shadow-sm mt-2">
                                SHOW
                            </button> -->
                                                        
                            
                            
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>



    <!-- Promotional Section -->
    <div class="container">
        <div class="promo-section" data-aos="fade-up">
            <h1>From Farm to Table</h1>
            <p>Discover the freshest organic produce, sustainably sourced.</p>
            <a href="#">Shop Fresh</a>
        </div>
    </div>

    <!-- Footer -->
    <x-footernav/>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            once: true
        });

        // Dark Mode Toggle
        const darkModeSwitch = document.getElementById('darkModeSwitch');
        const darkModeSwitchMobile = document.getElementById('darkModeSwitchMobile');
        const body = document.body;

        function setDarkMode(enabled) {
            if (enabled) {
                body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
                if (darkModeSwitch) darkModeSwitch.checked = true;
                if (darkModeSwitchMobile) darkModeSwitchMobile.checked = true;
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
                if (darkModeSwitch) darkModeSwitch.checked = false;
                if (darkModeSwitchMobile) darkModeSwitchMobile.checked = false;
            }
        }

        // Check for saved preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            setDarkMode(true);
        }

        // Event listeners
        if (darkModeSwitch) {
            darkModeSwitch.addEventListener('change', () => setDarkMode(darkModeSwitch.checked));
        }
        if (darkModeSwitchMobile) {
            darkModeSwitchMobile.addEventListener('change', () => setDarkMode(darkModeSwitchMobile.checked));
        }
        function refreshDiscounts() {
            fetch(window.location.href) // Reloads the current page
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newDiscounts = doc.querySelector('#discounted-products').innerHTML;
                    document.querySelector('#discounted-products').innerHTML = newDiscounts;
                    
                    // Reinitialize animations if needed
                    if (typeof AOS !== 'undefined') AOS.refresh();
                });
        }

        // Refresh every minute (60000ms)
        setInterval(refreshDiscounts, 60000);
    </script>
</body>
</html>