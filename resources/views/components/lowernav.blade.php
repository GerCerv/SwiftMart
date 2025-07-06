<nav class="navbar navbar-expand-lg navbar-light hide-on-mobile">
    <!-- Category Button (Fixed) -->
    <div class="position-relative ms-3">
        <button id="categoryButton" class="buttonhere" style="width: 150px; height: 50px; text-align: center;">
            <p class="fw-medium"><i class="fa fa-th-large fa-lg me-2"></i>Categories</p>
        </button>

        <!-- Category Pop-Up -->
        <div id="categoryPopup" class="category-popup shadow rounded p-4 position-absolute" style="display: none; z-index: 1050; width: 380px; padding: 2rem;">
            <div class="row gx-2 gy-2">
                <div class="col-6">
                    <a href="{{ route('usersnavs.shop') }}?category=Fruits" class="btn border-1 border-green-600 text-green-800  hover:bg-green-200 hover:text-green-900 font-extrabold w-100 text-truncate">Fruits</a>
                </div>
                <div class="col-6">
                    <a href="{{ route('usersnavs.shop') }}?category=Vegetables" class="btn border-1 border-green-600 text-green-800  hover:bg-green-200 hover:text-green-900 font-extrabold w-100 text-truncate">Vegetables</a>
                </div>
                <div class="col-6">
                    <a href="{{ route('usersnavs.shop') }}?category=Meat" class="btn border-1 border-green-600 text-green-800  hover:bg-green-200 hover:text-green-900 font-extrabold w-100 text-truncate">Meat</a>
                </div>
                <div class="col-6">
                    <a href="{{ route('usersnavs.shop') }}?category=Seafood" class="btn border-1 border-green-600 text-green-800  hover:bg-green-200 hover:text-green-900 font-extrabold w-100 text-truncate">Seafood</a>
                </div>
                <div class="col-6">
                    <a href="{{ route('usersnavs.shop') }}?category=Dairy" class="btn border-1 border-green-600 text-green-800  hover:bg-green-200 hover:text-green-900 font-extrabold w-100 text-truncate">Dairy</a>
                </div>
                <div class="col-6">
                    <a href="{{ route('usersnavs.shop') }}?category=Spices" class="btn border-1 border-green-600 text-green-800  hover:bg-green-200 hover:text-green-900 font-extrabold w-100 text-truncate">Spices</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar Links (Hidden on Mobile, Visible on Medium+ Screens) -->
    <ul class="navbar-nav d-none d-md-flex gap-3">
        <li class="nav-item">
            <a class="nav-link custom-nav-link" href="{{ route('usersnavs.shop', ['query' => 'Discount']) }}">
            🔥 Discounts
            </a>
        </li>
        @auth
        <li class="nav-item">
            <a class="nav-link custom-nav-link" href="{{ route('home', Auth::user()->name) }}">Home</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link custom-nav-link" href="#">About</a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link custom-nav-link" href="{{ route('usersnavs.shop', Auth::user()->name) }}">Shop</a>
        </li>
        @else
        <li class="nav-item">
            <a class="nav-link custom-nav-link" href="/home">Home</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link custom-nav-link" href="#">About</a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link custom-nav-link" href="/shop">Shop</a>
        </li>
        @endauth
    </ul>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryButton = document.getElementById('categoryButton');
    const categoryPopup = document.getElementById('categoryPopup');

    if (categoryButton && categoryPopup) {
        // Toggle popup visibility
        categoryButton.addEventListener('click', function(e) {
            e.preventDefault();
            const isVisible = categoryPopup.style.display === 'block';
            categoryPopup.style.display = isVisible ? 'none' : 'block';
        });

        // Close popup when clicking outside
        document.addEventListener('click', function(e) {
            if (!categoryPopup.contains(e.target) && !categoryButton.contains(e.target)) {
                categoryPopup.style.display = 'none';
            }
        });

        // Close popup when a category is clicked
        categoryPopup.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                categoryPopup.style.display = 'none';
            });
        });
    }
});
</script>

<style>
.category-popup {
    background: #ffffff;
    width: 250px;
    animation: slideDown 0.3s ease-out;
}
@keyframes slideDown {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>