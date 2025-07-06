<!-- Sidebar (Offcanvas) - Mobile Dark Mode -->
<div class="offcanvas offcanvas-end" id="offcanvasNavbar">
    <div class="offcanvas-header">
       <h5 class="welcome-heading">WELCOME TO SWIFTMART</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            
            
            <!-- Categories with Subcategories -->
            <li class="nav-item">
                <a class="nav-link" href="#!" data-bs-toggle="collapse" data-bs-target="#categoriesSubmenu" aria-expanded="false" aria-controls="categoriesSubmenu">
                    Categories  <i class="fa fa-angle-double-down"></i>
                </a>
                <!-- Subcategories Menu (Hidden Initially) -->
                <ul class="collapse" id="categoriesSubmenu">
                    <li><a class="nav-link" href="{{ route('usersnavs.shop') }}?category=Vegetables">Vegetables</a></li>
                    <li><a class="nav-link" href="{{ route('usersnavs.shop') }}?category=Fruits">Fruits</a></li>
                    <li><a class="nav-link" href="{{ route('usersnavs.shop') }}?category=Meat">Meat</a></li>
                    <li><a class="nav-link" href="{{ route('usersnavs.shop') }}?category=Seafood">SeaFood</a></li>
                    <li><a class="nav-link" href="{{ route('usersnavs.shop') }}?category=Spices">Spices</a></li>
                </ul>
            </li>

            
        </ul>

        

        <hr class="mt-5">
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="logout-form px-3 mt-2">
                        @csrf
                        <button type="submit" class="sidebar-logout-btn">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                @else
                    <a class="sidebar-login-btn nav-link px-3 mt-2" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                @endauth


        
    </div>
</div>