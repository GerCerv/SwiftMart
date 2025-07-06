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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/YOUR-FONTAWESOME-KIT.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
        }
        .profile-container {
            max-width: 100%;
            margin-left: 10px;
            margin-right: 10px;
            margin-top: 20px;
        }
        .profile-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 15px;
        }
        .sidebar {
            background: #729979;
            color: white;
        }
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            transition: background 0.2s;
        }
        .menu-item:hover {
            background: rgba(0,0,0,0.1);
        }
        .menu-item i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
        .sidebar .menu-item .profnav {
            color: rgb(255, 255, 255) !important;
        }
        .submenu {
            padding-left: 34px;
            display: none;
        }
        .submenu.show {
            display: block;
        }
        .submenu-item {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            cursor: pointer;
        }
        .submenu-item:hover {
            background: rgba(0,0,0,0.05);
        }
        .submenu-item.active {
            font-weight: 500;
        }
        .content-view {
            display: none;
        }
        .content-view.active {
            display: block;
        }
        .profile-info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            position: relative;
        }
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .profile-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }
        .edit-btn {
            background: none;
            border: none;
            color: #729979;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .profile-image-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-image-placeholder {
            font-size: 40px;
            color: #999;
        }
        .upload-btn {
            background-color: white;
            border: 1px solid #729979;
            color: #729979;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .profile-field {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .profile-field label {
            display: block;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 5px;
        }
        .profile-field .value {
            font-size: 1rem;
            color: #333;
        }
        .gender-options {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        .save-btn {
            background-color: #729979;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
        }
        .desktop-profile-section {
            display: none;
        }
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 1rem;
        }
        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #729979;
            color: #729979;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }
        .custom-file-upload:hover {
            background: rgb(137, 184, 145);
            color: black;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .custom-file-upload:active {
            transform: translateY(0);
        }
        @media (min-width: 992px) {
            .profile-container {
                display: flex;
                gap: 15px;
            }
            .sidebar {
                flex: 0 0 250px;
                order: 1;
            }
            .main-content {
                flex: 2;
                order: 2;
            }
            .desktop-profile-section {
                flex: 1;
                order: 3;
                display: block;
            }
            #account-view {
                display: none !important;
            }
            .dashboard-content {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            .profnav {
                color: white;
            }
        }
    </style>
</head>
<body>
    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
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
                <div class="submenu-item {{ request()->is('profile/dashboard/*') ? 'active' : '' }}" data-view="dashboard" onclick="showView('dashboard')">Dashboard</div>
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
            <div class="menu-item {{ request()->is('profile/history/*') ? 'active' : '' }}" data-view="history" onclick="showView('history')">
                <i class="bi bi-clock-history"></i>
                <span class="profnav">Order History</span>
            </div>
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
                    @include('profilecomponents.dashboard')
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
                    @include('profilecomponents.account')
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

            <!-- History View -->
            <div class="content-view {{ request()->is('profile/history/*') ? 'active' : '' }}" id="history-view">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="history-content">
                    @include('profilecomponents.history')
                </div>
            </div>
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
                    <div class="value" id="desktop-date">{{ Auth::user()->profile->date_of_birth ?? 'ADD DATE' }}</div>
                </div>
                <button class="save-btn" id="desktop-save-btn" style="display:none;" onclick="saveProfileData('desktop')">SAVE</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <x-footernav/>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Toggle submenu visibility
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id);
            const chevron = document.getElementById(id.replace('submenu', 'chevron'));
            submenu.classList.toggle('show');
            chevron.classList.toggle('bi-chevron-down');
            chevron.classList.toggle('bi-chevron-up');
        }

        // Switch between content views with AJAX
        function showView(viewName) {
            // Update active states
            document.querySelectorAll('.submenu-item, .menu-item').forEach(item => {
                item.classList.remove('active');
            });
            const clickedItem = document.querySelector(`[data-view="${viewName}"]`);
            if (clickedItem) {
                clickedItem.classList.add('active');
            }

            // Show/hide content views
            document.querySelectorAll('.content-view').forEach(view => {
                view.classList.remove('active');
            });
            const $view = document.getElementById(`${viewName}-view`);
            $view.classList.add('active');

            // Update URL
            const newUrl = `/profile/${viewName}/${{{ json_encode(Auth::user()->name) }}}`;
            history.pushState({ view: viewName }, '', newUrl);

            // Load content via AJAX
            const $contentArea = $view.querySelector(`.${viewName}-content`);
            const $spinner = $view.querySelector('.loading-spinner');
            $spinner.style.display = 'block';
            $contentArea.style.display = 'none';

            $.ajax({
                url: `/profile/tab/${viewName}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $contentArea.innerHTML = response;
                    $spinner.style.display = 'none';
                    $contentArea.style.display = 'block';
                    reinitializeBootstrapComponents(viewName);
                },
                error: function(xhr) {
                    $spinner.style.display = 'none';
                    $contentArea.style.display = 'block';
                    showToast(`Error loading ${viewName} content`, 'error');
                }
            });
        }

        // Handle browser back/forward navigation
        window.addEventListener('popstate', function(event) {
            const viewName = event.state ? event.state.view : 'dashboard';
            const $view = document.getElementById(`${viewName}-view`);
            const $contentArea = $view.querySelector(`.${viewName}-content`);
            const $spinner = $view.querySelector('.loading-spinner');

            // Update active states
            document.querySelectorAll('.submenu-item, .menu-item').forEach(item => {
                item.classList.remove('active');
            });
            const activeItem = document.querySelector(`[data-view="${viewName}"]`);
            if (activeItem) {
                activeItem.classList.add('active');
            }

            // Show/hide content views
            document.querySelectorAll('.content-view').forEach(view => {
                view.classList.remove('active');
            });
            $view.classList.add('active');

            // Load content via AJAX
            $spinner.style.display = 'block';
            $contentArea.style.display = 'none';

            $.ajax({
                url: `/profile/tab/${viewName}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $contentArea.innerHTML = response;
                    $spinner.style.display = 'none';
                    $contentArea.style.display = 'block';
                    reinitializeBootstrapComponents(viewName);
                },
                error: function(xhr) {
                    $spinner.style.display = 'none';
                    $contentArea.style.display = 'block';
                    showToast(`Error loading ${viewName} content`, 'error');
                }
            });
        });

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            if (!toastEl) return;

            const toastBody = toastEl.querySelector('.toast-body');
            toastBody.textContent = message;

            const toastHeader = toastEl.querySelector('.toast-header');
            toastHeader.className = 'toast-header';
            toastHeader.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');
            toastHeader.classList.add('text-white');

            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        // Reinitialize Bootstrap components
        function reinitializeBootstrapComponents(viewName) {
            const $view = document.getElementById(`${viewName}-view`);
            $($view).find('[data-bs-toggle="modal"]').off('click').on('click', function() {
                const target = $(this).data('bs-target');
                if (target) {
                    $(target).modal('show');
                }
            });

            $($view).find('.dropdown-toggle').each(function() {
                new bootstrap.Dropdown(this);
            });
        }

        // Initialize with current view
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('account-submenu').classList.add('show');

            if (window.matchMedia('(min-width: 992px)').matches) {
                document.querySelectorAll('.mobile-only').forEach(item => {
                    item.style.display = 'none';
                });
            }

            // Load initial view based on URL
            const currentPath = window.location.pathname.split('/')[2] || 'dashboard';
            showView(currentPath);
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.matchMedia('(min-width: 992px)').matches) {
                document.querySelectorAll('.mobile-only').forEach(item => {
                    item.style.display = 'none';
                });
                showView('dashboard');
            } else {
                document.querySelectorAll('.mobile-only').forEach(item => {
                    item.style.display = 'block';
                });
            }
        });

        // Profile editing functions
        function enableEditing(viewType) {
            const prefix = viewType === 'mobile' ? 'mobile' : 'desktop';
            document.getElementById(`${prefix}-save-btn`).style.display = 'block';
            const fields = ['name', 'email', 'phone', 'date'];
            fields.forEach(field => {
                const value = document.getElementById(`${prefix}-${field}`).textContent;
                document.getElementById(`${prefix}-${field}`).innerHTML = `
                    <input type="text" class="form-control" value="${value}" id="${prefix}-${field}-input">
                `;
            });
        }

        function saveProfileData(viewType) {
            const prefix = viewType === 'mobile' ? 'mobile' : 'desktop';
            const genderInputName = viewType === 'mobile' ? 'gender' : 'gender-desktop';
            const gender = document.querySelector(`input[name="${genderInputName}"]:checked`).value;
            const profileData = {
                name: document.getElementById(`${prefix}-name-input`).value,
                email: document.getElementById(`${prefix}-email-input`).value,
                phone: document.getElementById(`${prefix}-phone-input`).value,
                gender: gender,
                date: document.getElementById(`${prefix}-date-input`).value,
                _token: '{{ csrf_token() }}'
            };
            fetch('{{ route("profile.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(profileData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    disableEditing(viewType);
                    window.location.reload();
                } else {
                    alert('Error updating profile: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving. Please try again.');
            });
        }

        function disableEditing(viewType) {
            const prefix = viewType === 'mobile' ? 'mobile' : 'desktop';
            document.getElementById(`${prefix}-save-btn`).style.display = 'none';
            const fields = ['name', 'email', 'phone', 'date'];
            fields.forEach(field => {
                const input = document.getElementById(`${prefix}-${field}-input`);
                if (input) {
                    const value = input.value;
                    document.getElementById(`${prefix}-${field}`).textContent = value;
                }
            });
        }

        // Auto-submit image form
        document.getElementById('imageInputDesktop').addEventListener('change', function() {
            document.getElementById('imageUploadFormDesktop').submit();
        });
    </script>
</body>
</html>