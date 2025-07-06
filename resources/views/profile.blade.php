<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SWIFT MART</title>
    <link rel="stylesheet" href="{{ asset('css/style.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/modal.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/uppernav.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/middlenav.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/popups.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/lowernav.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/profile.css')}}" />

    



    


    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/YOUR-FONTAWESOME-KIT.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
       
    </style>
</head>
<body>
    <script>
        window.currentUserName = "{{ Auth::user()->name }}";
    </script>
    <script>
        window.saveProfileRoute = "{{ route('profile.save') }}";
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body bg-green-500 text-white px-4 py-2 rounded-md shadow-lg"></div>
        </div>
    </div>

    <!-- Upper navigation -->
    <x-uppernav/>

    <!-- Login Modal -->
    <x-loginmodal/>

    <!-- Sign Up Modal -->
    <x-signupmodal/>

    <!-- Middle Navigation (Sticky) -->
    <x-middlenav/>

    <!-- Lower Navigation -->
    <x-lowernav/>

    <!-- Side Navigation Bar -->
    <x-sidebar/>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Sidebar Navigation (Left) -->
        <div class="profile-card sidebar">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-person-fill text-dark"></i>
                </div>
                <h5 class="m-0 ms-2">MY PROFILE</h5>
            </div>

            <!-- My Account with Submenu -->
            <div class="menu-item" onclick="toggleSubmenu('account-submenu')">
                <i class="bi bi-person"></i>
                <span class="profnav">My Account</span>
                <i class="bi bi-chevron-down ms-auto" id="account-chevron"></i>
            </div>
            <div class="submenu {{ request()->is('profile/dashboard/*') || request()->is('profile/account/*') ? 'show' : '' }}" id="account-submenu">
                
                <div class="submenu-item mobile-only {{ request()->is('profile/account/*') ? 'active' : '' }}" data-view="account" onclick="showView('account')">Account Details</div>
            </div>

            <!-- Other Menu Items -->
            <div class="menu-item {{ request()->is('profile/wishlist/*') ? 'active' : '' }}" data-view="wishlist" onclick="showView('wishlist')">
                <i class="bi bi-heart"></i>
                <span class="profnav">Wishlist</span>
            </div>
            <div class="menu-item {{ request()->is('profile/cart/*') ? 'active' : '' }}" data-view="cart" onclick="showView('cart')">
                <i class="bi bi-cart"></i>
                <span class="profnav">Cart</span>
            </div>
            <div class="menu-item {{ request()->is('profile/purchase/*') ? 'active' : '' }}" data-view="purchase" onclick="showView('purchase')">
                <i class="bi bi-box-seam"></i>
                <span class="profnav">Purchase</span>
            </div>
            <div class="menu-item {{ request()->is('profile/address/*') ? 'active' : '' }}" data-view="address" onclick="showView('address')">
                <i class="bi bi-geo-alt"></i>
                <span class="profnav">Address</span>
            </div>
            <!-- <div class="menu-item {{ request()->is('profile/history/*') ? 'active' : '' }}" data-view="history" onclick="showView('history')">
                <i class="bi bi-clock-history"></i>
                <span class="profnav">Order History</span>
            </div> -->
        </div>

        <!-- Main Content Area (Middle) -->
        <div class="profile-card main-content">
            <!-- Dashboard View -->
            <div class="content-view {{ request()->is('profile/dashboard/*') ? 'active' : '' }}" id="dashboard-view">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="dashboard-content">
                    
                </div>
            </div>

            <!-- Account Details View (only visible in mobile) -->
            <div class="content-view {{ request()->is('profile/account/*') ? 'active' : '' }}" id="account-view">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="account-content">
                 @include('profilecomponents.accountdetails')
                </div>
            </div>

            <!-- Wishlist View -->
            <div class="content-view {{ request()->is('profile/wishlist/*') ? 'active' : '' }}" id="wishlist-view">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="wishlist-content">
                    @include('profilecomponents.wishlist')
                </div>
            </div>

            <!-- Cart View -->
            <div class="content-view {{ request()->is('profile/cart/*') ? 'active' : '' }}" id="cart-view">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="cart-content">
                    @include('profilecomponents.cart')
                </div>
            </div>

            <!-- Purchase View -->
            <div class="content-view {{ request()->is('profile/purchase/*') ? 'active' : '' }}" id="purchase-view">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="purchase-content">
                    @include('profilecomponents.purchases')
                </div>
            </div>

            <!-- Address View -->
            <div class="content-view {{ request()->is('profile/address/*') ? 'active' : '' }}" id="address-view">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="address-content">
                   @include('profilecomponents.address')
                </div>
            </div>

            <!-- History View
            <div class="content-view {{ request()->is('profile/history/*') ? 'active' : '' }}" id="history-view">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="history-content">
                   
                </div>
            </div> -->
        </div>

        <!-- Desktop Profile Section (Right) -->
        <div class="profile-card desktop-profile-section">
            <div class="profile-info-card">
                <div class="profile-header">
                    <h4 class="profile-title">Account Details</h4>
                    <button class="edit-btn" onclick="enableEditing('desktop')">
                        <i class="bi bi-pencil"></i> EDIT
                    </button>
                </div>
                <div class="profile-image-container">
                    <div class="profile-image" id="desktop-profile-image">
                        @if(auth()->user()->profile && auth()->user()->profile->image)
                            <img src="{{ asset('storage/images/' . auth()->user()->profile->image) }}">
                        @else
                            <div class="profile-image-placeholder">
                                <i class="bi bi-person"></i>
                            </div>
                        @endif
                    </div>
                    <form action="{{ route('profile.upload.image') }}" method="POST" enctype="multipart/form-data" id="imageUploadFormDesktop">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="file" id="imageInputDesktop" accept="image/*" class="d-none">
                            <label for="imageInputDesktop" class="custom-file-upload">
                                <i class="bi bi-camera"></i> ADD IMAGE
                            </label>
                        </div>
                    </form>
                </div>
                @if(session('user_id'))
                <div class="profile-field">
                    <label>NAME</label>
                    <div class="value" id="desktop-name">{{ Auth::user()->name }}</div>
                </div>
                <div class="profile-field">
                    <label>EMAIL</label>
                    <div class="value" id="desktop-email">{{ Auth::user()->email }}</div>
                </div>
                @endif
                <div class="profile-field">
                    <label>PHONE</label>
                    <div class="value" id="desktop-phone">{{ Auth::user()->phone ?? 'ADD NUMBER' }}</div>
                </div>
                <div class="profile-field">
                    <label>GENDER</label>
                    <div class="gender-options">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender-desktop" id="male-desktop" value="male"
                                   {{ (Auth::user()->profile->gender ?? '') == 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="male-desktop">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender-desktop" id="female-desktop" value="female"
                                   {{ (Auth::user()->profile->gender ?? '') == 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="female-desktop">Female</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender-desktop" id="other-desktop" value="other"
                                   {{ (Auth::user()->profile->gender ?? '') == 'other' ? 'checked' : '' }}>
                            <label class="form-check-label" for="other-desktop">Other</label>
                        </div>
                    </div>
                </div>
                <div class="profile-field">
                    <label>DATE</label>
                    <div class="value" id="desktop-date">{{ Auth::user()->profile->date_of_birth ?? 'ADD DATE THE FORMAT IS YEAR-MONTH-DAY' }}</div>
                </div>
                <button class="save-btn" id="desktop-save-btn" style="display:none;" onclick="saveProfileData('desktop')">SAVE</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <x-footernav/>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
    
</body>
</html>