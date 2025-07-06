<!-- Desktop Footer -->
<footer class="bg-dark text-white pt-4">
    <div class="container">
        <div class="row text-center text-md-start">
            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase fw-bold text-success">SwiftMart</h5>
                <p class="text-light">
                    Bringing the freshest picks from our farmers to your doorstep. <br>
                    Shop smart. Shop local. Shop SwiftMart.
                </p>
            </div>
            <div class="col-md-4 mb-3">
                <h5 class="text-success">Quick Links</h5>
                @auth
                <ul class="list-unstyled">
                    <li><a href="{{ route('usersnavs.shop', Auth::user()->name) }}" class="text-white text-decoration-none">Shop</a></li>
                    <li><a href="{{ route('home', Auth::user()->name) }}" class="text-white text-decoration-none">Home</a></li>
                    
                    <li><a href="{{ route('usersnavs.shop', ['query' => 'Discount']) }}" class="text-white text-decoration-none">Discounts</a></li>
                    <li><a href="{{ url('vendor/login') }}" class="text-white text-decoration-none">Become a Vendor</a></li>
                </ul>
                @else
                <ul class="list-unstyled">
                    <li><a href="/shop" class="text-white text-decoration-none">Shop</a></li>
                    <li><a href="/home" class="text-white text-decoration-none">Home</a></li>
                    
                    <li><a href="{{ route('usersnavs.shop', ['query' => 'Discount']) }}" class="text-white text-decoration-none">Discounts</a></li>
                    <li><a href="{{ url('vendor/login') }}" class="text-white text-decoration-none">Become a Vendor</a></li>
                </ul>
                @endauth
            </div>
            <div class="col-md-4 mb-3">
                <h5 class="text-success">Contact Us</h5>
                <p>Email: support@SwiftMart.com</p>
                <p>Phone: +1 (234) 567-8901</p>
            </div>
        </div>
        <div class="text-center py-3 border-top border-secondary">
            &copy; 2025 Food Market. All rights reserved.
        </div>
    </div>
</footer>

<!-- Mobile Sticky Navbar -->
<nav class="navbar navbar-light bg-light fixed-bottom d-block d-lg-none shadow-lg">
    <div class="container-fluid d-flex justify-content-around">
        <a href="/home" class="text-success text-center text-decoration-none">
            <i class="fas fa-home fa-lg"></i><br>
            <small>Home</small>
        </a>
        <a href="{{ auth()->check() ? '/profile/wishlist/' . auth()->user()->name : '#' }}" class="text-success text-center text-decoration-none">
            <i class="fas fa-heart fa-lg"></i><br>
            <small>Wishlist</small>
        </a>
        <a href="{{ auth()->check() ? '/profile/cart/' . auth()->user()->name : '#' }}" class="text-success text-center text-decoration-none">
            <i class="fas fa-shopping-cart fa-lg"></i><br>
            <small>Cart</small>
        </a>
        <a href="{{ auth()->check() ? '/profile/wishlist/' . auth()->user()->name : '#' }}" class="text-success text-center text-decoration-none">
            <i class="fas fa-user fa-lg"></i><br>
            <small>Account</small>
        </a>
        <a href="/shop" class="text-success text-center text-decoration-none">
            <i class="fas fa-store fa-lg"></i><br>
            <small>Shop</small>
        </a>
    </div>
</nav>
