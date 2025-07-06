<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="{{ asset('css/style.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/modal.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/uppernav.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/middlenav.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/popups.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/lowernav.css')}}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/YOUR-FONTAWESOME-KIT.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        body {
            background: linear-gradient(to bottom, #f0fdf4, #ecfdf5);
            font-family: 'Inter', sans-serif;
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            background: #ffffff;
            border:1px solid rgba(176, 248, 176, 0.69);
            box-shadow: 0 4px 8px rgba(0, 128, 0, 0.15);
        }
        .product-card:hover {
            transform: translateY(-8px);
            border:1px solid rgba(28, 161, 28, 0.94);
            box-shadow: 0 12px 24px rgba(0, 128, 0, 0.15);
        }
        .stock-low {
            color: #dc2626;
            font-weight: 600;
        }
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #22c55e, transparent);
            opacity: 0;
            animation: fadeInDivider 0.8s ease-out forwards;
            margin: 0.5rem auto;
            width: 90%;
        }
        @keyframes fadeInDivider {
            from { opacity: 0; width: 0; }
            to { opacity: 1; width: 90%; }
        }
        .image-container {
            position: relative;
            height: 200px;
            background: #f3f4f6;
            overflow: hidden;
        }
        .image-container img {
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
        .image-container:hover img {
            transform: scale(1.05);
        }
        .badge {
            transition: all 0.3s ease;
        }
        .badge:hover {
            transform: scale(1.1);
            background: #15803d !important;
            color: #ffffff !important;
        }
        .btn-primary {
            background: #22c55e;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn-primary:hover {
            background: #16a34a;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #f59e0b;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn-secondary:hover {
            background: #d97706;
            transform: translateY(-2px);
        }
        .wishlist-btn {
            transition: all 0.3s ease;
        }
        .wishlist-btn:hover {
            transform: scale(1.2);
            background: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-100">
<x-uppernav/>
<x-loginmodal/>
<x-signupmodal/>
<x-middlenav/>
<x-lowernav/>
<x-sidebar/>
<div class="container mx-auto px-4 py-4">
        <!-- Products Section -->
        <div class="products-section">
            <div class="divider mb-4"></div>
            <div class="max-w-auto mx-auto bg-white border border-green-300 rounded-2xl shadow-lg overflow-hidden mb-8 p-4 md:p-6 flex flex-col md:flex-row items-center gap-6 relative transition-all duration-300 hover:shadow-xl">
                <!-- Store Image -->
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-green-100 flex-shrink-0 shadow-md">
                    <img src="{{ $vendor->stallSettings && $vendor->stallSettings->profile_image ? asset('storage/' . $vendor->stallSettings->profile_image) : asset('images/chono.jpg') }}" 
                        alt="{{ $vendor->store_name }}" 
                        class="w-full h-full object-cover hover:scale-105 transition duration-500">
                </div>
                
                <!-- Content -->
                <div class="flex-1 w-full md:w-auto">
                    <!-- Social Icons - Mobile Top Right -->
                    <div class="absolute top-4 right-4 md:top-6 md:right-6 flex space-x-2">
                        <a href="{{ $vendor->stallSettings->facebook ?? '#' }}" target="_blank" 
                        class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 hover:bg-blue-100 transition">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="{{ $vendor->stallSettings->instagram ?? '#' }}" target="_blank" 
                        class="w-8 h-8 rounded-full bg-pink-50 flex items-center justify-center text-pink-600 hover:bg-pink-100 transition">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="mailto:{{ $vendor->email }}" 
                        class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100 transition">
                            <i class="fas fa-envelope text-sm"></i>
                        </a>
                    </div>
                    
                    <!-- Store Info -->
                    <div class="space-y-2 md:space-y-3">
                        <!-- Store Name -->
                        <h2 class="text-2xl md:text-3xl font-bold text-green-800 group">
                            <i class="fas fa-store text-green-600 mr-2 group-hover:text-green-700 transition"></i> 
                            <span class="hover:text-green-700 transition">{{ ucfirst($vendor->store_name) }}</span>
                        </h2>

                        <!-- Owner Name -->
                        <p class="text-gray-700 text-base md:text-lg font-medium flex items-center">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-sm mr-2">
                                <i class="fas fa-user-tie mr-1"></i> Owner
                            </span>
                            {{ ucfirst($vendor->name) }}
                        </p>

                        <!-- Rating and Sold -->
                        <div class="flex flex-wrap items-center gap-4 pt-1">
                            <!-- Rating -->
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-2">
                                    @php
                                        $vendorRating = $vendor->vendor_rating;
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo '<i class="fa-star ' . ($vendorRating >= $i ? 'fas text-yellow-400' : ($vendorRating >= $i - 0.5 ? 'fas fa-star-half-alt text-yellow-400' : 'far text-gray-300')) . '"></i>';
                                        }
                                    @endphp
                                </div>
                                <span class="text-sm font-medium text-gray-600">{{ number_format($vendor->vendor_rating, 1) }}/5</span>
                            </div>
                            
                            <!-- Sold Products -->
                            <div class="flex items-center text-sm font-medium">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-gray-600">{{ $vendor->deliveredOrders->count() }} successful orders</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fresh Tag - Bottom Right -->
                <div class="absolute bottom-4 right-4 md:bottom-6 md:right-6 bg-gradient-to-r from-green-100 to-green-50 text-green-700 text-xs md:text-sm font-semibold px-3 py-1 rounded-full flex items-center border border-green-200">
                    <i class="fas fa-leaf text-green-500 mr-2"></i> 
                    <span class="hidden sm:inline">FRESH VEGETABLES</span>
                    <span class="sm:hidden">FRESH</span>
                </div>
            </div>


            
            
            <div class="divider mb-4"></div>

            @if($vendor->products->isEmpty())
                <div class="text-center py-16 bg-white rounded-lg shadow-md">
                    <p class="text-gray-600 text-xl">
                        No products available from this vendor yet.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($vendor->products as $product)
                        <div class="product-card relative flex flex-col justify-between">
                            <div class="absolute top-3 right-3 z-10">
                                @auth
                                    <button class="wishlist-btn p-2 rounded-full bg-white shadow-md {{ auth()->user()->hasInWishlist($product->id) ? 'text-red-500' : 'text-gray-500' }}"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ number_format($product->price, 2, '.', '') }}"
                                            data-product-image="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg') }}"
                                            title="{{ auth()->user()->hasInWishlist($product->id) ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                                            data-product-stock="{{ $product->stock }}">
                                        <i class="fa-heart {{ auth()->user()->hasInWishlist($product->id) ? 'fas' : 'far' }}"></i>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="wishlist-btn p-2 rounded-full bg-white text-gray-500 shadow-md"
                                    title="Login to add to wishlist">
                                        <i class="far fa-heart"></i>
                                    </a>
                                @endauth
                            </div>
                            <div x-data="{
                                    images: [
                                        @if($product->image) '{{ asset('storage/products/' . $product->image) }}', @endif
                                        @if($product->image2) '{{ asset('storage/products/' . $product->image2) }}', @endif
                                        @if($product->image3) '{{ asset('storage/products/' . $product->image3) }}', @endif
                                        @if($product->image4) '{{ asset('storage/products/' . $product->image4) }}', @endif
                                        @if($product->image5) '{{ asset('storage/products/' . $product->image5) }}' @endif
                                    ],
                                    active: 0,
                                    hover: false,
                                    interval: null,
                                    startCarousel() {
                                        if (this.images.length <= 1) return;
                                        this.hover = true;
                                        this.interval = setInterval(() => {
                                            if (!this.hover) return;
                                            this.active = (this.active + 1) % this.images.length;
                                        }, 1500);
                                    },
                                    stopCarousel() {
                                        this.hover = false;
                                        clearInterval(this.interval);
                                    }
                                }"
                                @mouseenter="startCarousel()"
                                @mouseleave="stopCarousel()"
                                class="image-container">
                                <template x-if="images.length > 0">
                                    <img :src="images[active]" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </template>
                                <template x-if="images.length === 0">
                                    <i class="fas fa-image text-5xl text-gray-400 flex items-center justify-center h-full"></i>
                                </template>
                            </div>
                            <div class="p-4 flex flex-col justify-between flex-grow">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-bold text-lg text-green-900 line-clamp-2 flex-1">
                                            <a href="{{ route('product.show', $product->id) }}" class="hover:text-green-600 text-l">
                                                {{ ucfirst(strtolower($product->name)) }}
                                            </a>
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-star {{ $product->averageRating() >= $i ? 'fas text-yellow-400' : ($product->averageRating() >= $i - 0.5 ? 'fas fa-star-half-alt text-yellow-400' : 'far text-gray-300') }}"></i>
                                                @endfor
                                            </div>
                                        </h3>
                                        <div class="relative inline-block">
                                            @if($product->discount > 0)
                                                <div class="absolute -top-6 -right-7 transform rotate-12 border border-pink-700 bg-pink-600 text-white text-xs font-extrabold px-2 py-1 shadow-lg rounded-tr-md z-100 transition-transform duration-300 hover:scale-110">
                                                    {{ $product->discount }}% OFF
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    <span class="text-green-700 font-bold text-3xl">
                                                        ₱{{ number_format($product->price - ($product->price * ($product->discount / 100)), 2) }}
                                                    </span>
                                                    <small class="text-gray-500 line-through text-sm">
                                                        ₱{{ number_format($product->price, 2) }}
                                                    </small>
                                                </div>
                                            @else
                                                <span class="text-green-700 font-bold text-2xl">
                                                    ₱{{ number_format($product->price, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    @if($product->vendor)
                                        <p class="text-l text-green-700 font-semibold mb-2">
                                            <i class="fas fa-store text-green-700 text-l"></i>
                                            <a href="{{ route('vendor.show', $product->vendor->vendor_id) }}" class="hover:underline text-green-700">
                                                {{ ucfirst($product->vendor->store_name) }}
                                            </a>
                                        </p>
                                    @endif
                                    <p class="text-sm text-gray-500 mb-3">
                                        {{ Str::limit(ucfirst($product->description), 80, '...') }}
                                        @if(strlen($product->description) > 80)
                                            <a href="{{ route('product.show', $product->id) }}" class="text-green-700 hover:underline">see more</a>
                                        @endif
                                    </p>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-sm {{ $product->stock < 10 ? 'stock-low' : 'text-green-700 font-semibold' }}">
                                            Stock: {{ $product->stock }}
                                        </span>
                                        <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full font-semibold">
                                            {{ ucfirst($product->category) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <button class="add-to-cart-btn btn-primary text-white px-2 py-2 rounded-lg flex items-center text-sm w-full justify-center"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ number_format($product->price, 2, '.', '') }}"
                                            data-product-image="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg') }}"
                                            data-product-store="{{ $product->vendor ? $product->vendor->store_name : 'Unknown' }}"
                                            data-product-category="{{ $product->category }}"
                                            data-product-stock="{{ $product->stock }}"
                                            {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-shopping-basket mr-2"></i> Add to Cart
                                    </button>
                                    <!-- <button class="buy-now-btn btn-secondary text-white px-4 py-2 rounded-lg flex items-center text-sm w-full justify-center"
                                            data-product-id="{{ $product->id }}"
                                            {{ $product->stock < 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-seedling mr-2"></i> Buy Now
                                    </button> -->
                                </div>
                                <div class="flex items-center space-x-2 mt-2">
                                    @if($product->wishlists->count() > 0)
                                        <i class="bi bi-heart-fill text-red-500"></i>
                                        <span class="text-sm text-gray-600"><i class="far fa-heart text-red-600"></i> by {{ $product->wishlists->count() }} people</span>
                                    @endif
                                    @if($product->carts->count() > 0)
                                        <i class="bi bi-heart-fill text-red-500 items-end"></i>
                                        <span class="text-sm text-gray-600"><i class="fas fa-shopping-basket text-green-600"></i> by {{ $product->carts->count() }} people</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
</div>
<!-- Footer -->
<x-footernav/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            @auth
                const productId = this.getAttribute('data-product-id');
                const isInWishlist = this.classList.contains('text-red-500');
                const url = isInWishlist ? `/wishlist/remove/${productId}` : `/wishlist/add/${productId}`;
                const method = isInWishlist ? 'DELETE' : 'POST';
                this.disabled = true;
                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const icon = this.querySelector('i');
                        if (isInWishlist) {
                            this.classList.replace('text-red-500', 'text-gray-500');
                            icon.classList.replace('fas', 'far');
                            this.setAttribute('title', 'Add to Wishlist');
                            const event = new CustomEvent('wishlist-updated', {
                                detail: { 
                                    productId: productId, 
                                    action: 'removed',
                                    countChange: -1
                                }
                            });
                            document.dispatchEvent(event);
                        } else {
                            this.classList.replace('text-gray-500', 'text-red-500');
                            icon.classList.replace('far', 'fas');
                            this.setAttribute('title', 'Remove from Wishlist');
                            const event = new CustomEvent('wishlist-updated', {
                                detail: { 
                                    productId: productId, 
                                    action: 'added',
                                    countChange: 1,
                                    product: {
                                        id: productId,
                                        name: this.getAttribute('data-product-name'),
                                        price: parseFloat(this.getAttribute('data-product-price')),
                                        image: this.getAttribute('data-product-image')
                                    }
                                }
                            });
                            document.dispatchEvent(event);
                        }
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    this.disabled = false;
                });
            @else
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
                const feedback = document.createElement('div');
                feedback.className = 'alert alert-warning position-fixed top-0 start-50 translate-middle-x mt-3';
                feedback.style.zIndex = '1100';
                feedback.textContent = 'Please login to use wishlist';
                document.body.appendChild(feedback);
                setTimeout(() => {
                    feedback.style.opacity = '0';
                    setTimeout(() => feedback.remove(), 300);
                }, 3000);
            @endauth
        });
    });

    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            @auth
                const productId = this.getAttribute('data-product-id');
                this.disabled = true;
                fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const event = new CustomEvent('cart-updated', {
                            detail: {
                                productId: productId,
                                action: 'added',
                                countChange: 1,
                                product: {
                                    id: productId,
                                    name: this.getAttribute('data-product-name'),
                                    price: parseFloat(this.getAttribute('data-product-price')),
                                    image: this.getAttribute('data-product-image'),
                                    store_name: this.getAttribute('data-product-store'),
                                    category: this.getAttribute('data-product-category'),
                                    quantity: 1,
                                    stock: parseFloat(this.getAttribute('data-product-stock')),
                                }
                            }
                        });
                        document.dispatchEvent(event);
                        showToast('Added to cart!', 'success');
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    showToast('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    this.disabled = false;
                });
            @else
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
                const feedback = document.createElement('div');
                feedback.className = 'alert alert-warning position-fixed top-0 start-50 translate-middle-x mt-3';
                feedback.style.zIndex = '1100';
                feedback.textContent = 'Please login to use add to cart';
                document.body.appendChild(feedback);
                setTimeout(() => {
                    feedback.style.opacity = '0';
                    setTimeout(() => feedback.remove(), 300);
                }, 3000);
            @endauth
        });
    });

    document.querySelectorAll('.buy-now-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            @auth
                window.location.href = `/checkout?product_id=${productId}`;
            @else
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
                const feedback = document.createElement('div');
                feedback.className = 'alert alert-warning position-fixed top-0 start-50 translate-middle-x mt-3';
                feedback.style.zIndex = '1100';
                feedback.textContent = 'Please login to buy the product';
                document.body.appendChild(feedback);
                setTimeout(() => {
                    feedback.style.opacity = '0';
                    setTimeout(() => feedback.remove(), 300);
                }, 3000);
            @endauth
        });
    });

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
        }`;
        toast.style.zIndex = '1100';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>
</body>
</html>
