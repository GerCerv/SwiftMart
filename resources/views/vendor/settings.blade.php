<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - SwiftMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #28a745;
            --secondary: #6c757d;
            --light: #f8f9fa;
        }

        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        .sidebar {
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: #495057;
            border-radius: 5px;
            margin-bottom: 5px;
            font-size: 1rem;
            padding: 10px 15px;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--primary);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 280px;
            padding: 1.5rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .vendor-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .product-img {
            height: 80px;
            width: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-featured {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .product-card {
            display: none;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            background: white;
        }

        .product-card .product-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .product-card .product-details p {
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }

        .product-card .product-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-toggler {
                display: block;
            }

            .card {
                margin-bottom: 1rem;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table {
                font-size: 0.9rem;
            }

            .table th, .table td {
                padding: 0.5rem;
            }

            .modal-dialog {
                margin: 0;
                width: 100%;
                max-width: none;
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .modal-content {
                flex-grow: 1;
                border-radius: 0;
            }

            .chart-container {
                height: 200px !important;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            h5 {
                font-size: 1.1rem;
            }

            .pagination {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 575.98px) {
            .sidebar {
                width: 80%;
                max-width: 220px;
            }

            .main-content {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            #products-table {
                display: none;
            }

            .product-card {
                display: block;
            }

            .form-control,
            .form-select {
                font-size: 0.9rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-footer {
                padding: 0.75rem;
            }

            .toast-container {
                padding: 0.5rem;
            }

            .toast {
                width: 100%;
                max-width: 300px;
                font-size: 0.9rem;
            }
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        .overlay.show {
            display: block;
        }

        .pagination-loading {
            display: none;
            text-align: center;
            padding: 1rem;
        }
    </style>
</head>
<body>
    @if (session('success'))
    <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div class="toast align-items-center text-white bg-success border-0 show" id="autoDismissToast" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const toastEl = document.getElementById('autoDismissToast');
            if (toastEl) {
                const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
                toast.hide();
            }
        }, 5000);
    </script>
    @endif

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<div class="overlay" id="sidebarOverlay"></div>

<div class="d-flex flex-column">
    <nav class="navbar navbar-light bg-white shadow-sm d-md-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/">
                <i class="bi bi-shop me-1 text-success"></i> SwiftMart
            </a>
        </div>
    </nav>

    <div class="sidebar d-flex flex-column flex-shrink-0 p-3" id="sidebar">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
            <i class="bi bi-shop me-2 text-success" style="font-size: 1.5rem;"></i>
            <span class="fs-4 fw-bold">SwiftMart Vendors</span>
        </a>

        <div class="dropdown my-3">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('images/hina.jpg') }}" alt="Vendor" class="vendor-avatar me-2">
                @auth('vendor')
                    <strong>{{ (ucfirst(Auth::guard('vendor')->user()->name)) }}</strong>
                @else
                    <strong>Green Farms</strong>
                @endauth
            </a>
            <!-- <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('vendor.settings') }}">Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <form action="{{ route('vendor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Sign out</button>
                </form>
            </ul> -->
        </div>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('vendor.dashboard') }}" class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('vendor.products') }}" class="nav-link {{ request()->routeIs('vendor.products') ? 'active' : '' }}">
                    <i class="bi bi-basket"></i> My Products
                </a>
            </li>
            <li>
                <a href="{{ route('vendor.orders') }}" class="nav-link {{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i> Orders
                </a>
            </li>
            <li>
                <a href="{{ route('vendor.reports') }}" class="nav-link {{ request()->routeIs('vendor.reports') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> Sales Reports
                </a>
            </li>
            <li>
                <a href="{{ route('vendor.settings') }}" class="nav-link {{ request()->routeIs('vendor.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Stall Settings
                </a>
            </li>
            <hr>
            <li>
                <form action="{{ route('vendor.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100 {{ request()->routeIs('vendor.logout') ? 'active' : '' }}" style="text-decoration: none;">
                        <i class="bi bi-box-arrow-right"></i> Sign out
                    </button>
                </form>
            </li>
        </ul>

        <hr>
    </div>

    <div class="main-content flex-grow-1">
@php
    $vendor = Auth::guard('vendor')->user();
    $stallSettings = $vendor->stallSettings ?? new App\Models\StallSetting();
@endphp
<h2 class="mb-4"><i class="bi bi-gear me-2"></i>Stall Settings</h2>

<div class="container mt-5">
    <form id="stallSettingsForm" method="POST" action="{{ route('vendor.stall-settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="card shadow-lg border-0 position-relative" id="cardBackground" style="max-width: 900px; margin: 0 auto; padding: 30px; background-size: cover; background-position: center; ">
            <!-- Edit/Save Button -->
            <button type="button" id="editBtn" class="btn btn-outline-primary position-absolute" style="top: 15px; right: 15px; z-index: 10;">Edit</button>
            <button type="submit" id="saveBtn" class="btn btn-primary position-absolute" style="top: 15px; right: 15px; z-index: 10; display: none;">Save Changes</button>
            
            <div class="card-body d-flex align-items-start p-5 position-relative">
                <!-- Vendor Profile Image Section -->
                <div class="me-5 d-flex flex-column align-items-center">
                    <!-- Circular Vendor Profile Image -->
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border position-relative image-container" style="width: 150px; height: 150px; background-image: url('{{ $stallSettings->profile_image ? asset('storage/'.$stallSettings->profile_image) : '' }}'); background-size: cover;" data-editable="false">
                        @if(!$stallSettings->profile_image)
                        <span class="text-muted fs-4 image-text">IMG</span>
                        @endif
                        <span class="plus-sign position-absolute text-primary fs-1" style="display: none;">+</span>
                        <input type="file" class="d-none" id="profileImageInput" name="profile_image" accept="image/*">
                    </div>
                </div>
                <!-- Form Fields Section -->
                <div class="flex-grow-1">
                    <!-- Fields -->
                    <div class="mb-4">
                        <label for="storeName" class="form-label fw-bold">Store Name</label>
                        <input type="text" class="form-control" id="storeName" name="store_name" value="{{ old('store_name', $vendor->store_name) ?? 'Add Store' }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $vendor->name) ?? 'Add Name' }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $vendor->email) ?? 'Add Email' }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label fw-bold">Phone/Contact</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $vendor->phone) ?? 'Add Phone' }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="facebook" class="form-label fw-bold">Facebook</label>
                        <input type="text" class="form-control" id="facebook" name="facebook" value="{{ old('facebook', $stallSettings->facebook) ?? '' }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="instagram" class="form-label fw-bold">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram', $stallSettings->instagram) ?? '' }}" readonly>
                    </div>
                </div>
            </div>
            <!-- Hidden Background Image Selector -->
            <div class="position-absolute bottom-0 end-0 p-3 image-container" style="z-index: 10;" data-editable="false">
                <span class="plus-sign text-primary fs-1" style="display: none;">+</span>
                <input type="file" class="d-none" id="backgroundImageInput" name="background_image" accept="image/*">
            </div>
        </div>
    </form>
</div>
</div>
</div>
<script>
    document.getElementById('editBtn').addEventListener('click', function() {
        const inputs = document.querySelectorAll('#stallSettingsForm input[type="text"], #stallSettingsForm input[type="email"], #stallSettingsForm input[type="tel"]');
        const imageContainers = document.querySelectorAll('.image-container');
        const isEditable = this.style.display !== 'none';

        // Toggle text inputs
        inputs.forEach(input => {
            if (isEditable) {
                input.removeAttribute('readonly');
                input.classList.add('border-primary');
            } else {
                input.setAttribute('readonly', 'readonly');
                input.classList.remove('border-primary');
            }
        });

        // Toggle image editability
        imageContainers.forEach(container => {
            const plusSign = container.querySelector('.plus-sign');
            container.setAttribute('data-editable', isEditable);
            plusSign.style.display = isEditable ? 'block' : 'none';
            const imageText = container.querySelector('.image-text');
            if (imageText) imageText.style.opacity = isEditable ? '0.3' : '1';
        });

        // Toggle buttons
        this.style.display = isEditable ? 'none' : 'block';
        document.getElementById('saveBtn').style.display = isEditable ? 'block' : 'none';
    });

    // Add click event for image containers to trigger file input
    document.querySelectorAll('.image-container').forEach(container => {
        container.addEventListener('click', function() {
            if (this.getAttribute('data-editable') === 'true') {
                const fileInput = this.querySelector('input[type="file"]');
                fileInput.click();
            }
        });
    });

    // Handle profile image preview
    document.getElementById('profileImageInput').addEventListener('change', function(e) {
        const container = this.closest('.image-container');
        const imageText = container.querySelector('.image-text');
        if (imageText) imageText.style.opacity = '0';
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.style.backgroundImage = `url(${e.target.result})`;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Handle background image preview
    document.getElementById('backgroundImageInput').addEventListener('change', function(e) {
        const card = document.getElementById('cardBackground');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                card.style.backgroundImage = `url(${e.target.result})`;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Form submission
    document.getElementById('stallSettingsForm').addEventListener('submit', function(e) {
        // You can add validation here if needed
    });
</script>
</body>
</html>

