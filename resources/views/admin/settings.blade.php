<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SwiftMart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .admin-avatar {
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


        body {
        background-color: #f4f6f9;
        font-family: 'Arial', sans-serif;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .image-upload {
        width: 300px;
        height: 220px;
        border: 2px dashed #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        margin: 0 auto;
    }
    .image-upload:hover {
        border-color: #007bff;
    }
    .image-upload .plus-sign {
        font-size: 1.5rem;
        color: #ccc;
    }
    .image-upload img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }
    .image-upload input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .form-control, .form-control-file {
        border-radius: 5px;
    }
    .btn-save {
        background-color: #28a745;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
    }
    .btn-save:hover {
        background-color: #218838;
    }
    .logs-table th, .logs-table td {
        vertical-align: middle;
    }
    .logs-table {
        margin-top: 20px;
    }
    .hot-deals-section {
        margin-top: 20px;
    }
    .image-section {
        text-align: center;
    }
    .save-button-container {
        text-align: center;
        margin: 20px 0;
    }
    .deal-section {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    </style>
</head>
<body>

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
            <span class="fs-4 fw-bold text-success">SwiftMart Admin</span>
        </a>

        <div class="dropdown my-3">
            <a href="#" class="d-flex align-items-center text-decoration-none " id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('images/chono.jpg') }}" alt="Admin" class="admin-avatar me-2">
                @if (session('admin_authenticated'))
                    <span class="fs-4 fw-bold text-success"><strong>{{ session('admin_name') }}</strong></span>
                @else
                    <strong>Admin User</strong>
                @endif
            </a>
            <!-- <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Sign out</button>
                </form>
            </ul> -->
        </div>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.vendors') }}" class="nav-link {{ request()->routeIs('admin.vendors') ? 'active' : '' }}">
                    <i class="bi bi-shop-window"></i> Manage Vendors
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Manage Users
                </a>
            </li>
            <li>
                <a href="{{ route('admin.deliverymans') }}" class="nav-link {{ request()->routeIs('admin.deliverymans') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Manage Delivery Man
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i> Orders
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                    <i class="bi bi-basket"></i> Products
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i> Reports
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
            <hr>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
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
        <div class="container mt-5">
            <!-- Advertisement Section (Unchanged) -->
            <div class="card p-4">
                <h3 class="mb-4">Advertisement Settings</h3>
                <form action="{{ isset($editingAd) ? route('advertisements.update', $editingAd->id) : route('advertisements.store') }}" method="POST" enctype="multipart/form-data" id="advertisement-form">
                    @csrf
                    @if(isset($editingAd))
                        @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-3">
                            <div class="image-upload mb-3">
                                <label for="image" style="cursor: pointer;">
                                    <span class="plus-sign" style="{{ isset($editingAd) && $editingAd->image_path ? 'display: none;' : 'display: block;' }}">+</span>
                                    <img id="ad-preview" src="{{ isset($editingAd) && $editingAd->image_path ? asset('storage/' . $editingAd->image_path) : '#' }}" alt="Image Preview" style="{{ isset($editingAd) && $editingAd->image_path ? 'display: block; max-width: 100%; max-height: 200px;' : 'display: none;' }}">
                                </label>
                                <input type="file" name="image" id="image" accept="image/*" {{ isset($editingAd) ? '' : 'required' }} onchange="previewImage(this)">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title: Put the Url of the Vendor or Product</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter advertisement url of vendor or product" value="{{ isset($editingAd) ? $editingAd->title : '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter description" required>{{ isset($editingAd) ? $editingAd->description : '' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ isset($editingAd) ? $editingAd->start_date->format('Y-m-d') : '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="expiration_date" class="form-label">Expiration Date</label>
                                <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ isset($editingAd) ? $editingAd->expiration_date->format('Y-m-d') : '' }}" required>
                            @error('start_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" id="submit-btn">{{ isset($editingAd) ? 'Update' : 'Save' }}</button>
                            @if(isset($editingAd))
                                <button type="button" class="btn btn-secondary" onclick="resetAdForm()">Cancel</button>
                            @endif
                        </div>
                    </div>
                </form>
                
                <div class="logs-table mt-4">
                    <h5>Advertisement Logs</h5>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>Expiration Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advertisements as $ad)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($ad->image_path)
                                        <img src="{{ asset('storage/' . $ad->image_path) }}" alt="Ad Image" width="50">
                                    @endif
                                </td>
                                <td>{{ $ad->title }}</td>
                                <td>{{ Str::limit($ad->description, 50) }}</td>
                                <td>{{ $ad->start_date->format('Y-m-d') }}</td>
                                <td>{{ $ad->expiration_date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge {{ $ad->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($ad->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-ad-btn"
                                            data-id="{{ $ad->id }}"
                                            data-title="{{ htmlspecialchars($ad->title) }}"
                                            data-description="{{ htmlspecialchars($ad->description) }}"
                                            data-start-date="{{ $ad->start_date->format('Y-m-d') }}"
                                            data-expiration-date="{{ $ad->expiration_date->format('Y-m-d') }}"
                                            data-image-path="{{ $ad->image_path ? asset('storage/' . $ad->image_path) : '' }}">Edit</button>
                                    <form action="{{ route('advertisements.destroy', $ad->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Hot Deals Card -->
            <div class="card p-4 hot-deals-section">
                <h3 class="mb-4">Hot Deals Settings</h3>
                <form action="{{ isset($editingDeal) ? route('hot-deals.update', $editingDeal->id) : route('hot-deals.store') }}" method="POST" id="hot-deals-form">
                    @csrf
                    @if(isset($editingDeal))
                        @method('PUT')
                        <input type="hidden" name="editing_position" id="editing-position" value="{{ $editingDeal->position }}">
                    @endif
                    <div class="row">
                        @foreach(['left', 'middle', 'right'] as $position)
                        @php
                            $currentDeal = isset($editingDeal) && $editingDeal->position === $position ? $editingDeal : $hotDeals->firstWhere('position', $position);
                            $currentProduct = $currentDeal->product ?? null;
                        @endphp
                        <div class="col-md-4 deal-section" id="{{ $position }}-section">
                            <div class="product-image-preview mb-3">
                                @if($currentProduct && $currentProduct->image)
                                    <img src="{{ asset('storage/products/' . $currentProduct->image) }}" 
                                        class="img-fluid" 
                                        alt="{{ $currentProduct->name }}"
                                        style="max-height: 200px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="no-image-placeholder" style="height: 200px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                        <span>Product image will appear here</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="{{ $position }}-product" class="form-label">Select Product</label>
                                <select class="form-control product-select" 
                                        id="{{ $position }}-product" 
                                        name="{{ $position }}_product_id" 
                                        data-position="{{ $position }}"
                                        {{ isset($editingDeal) && $editingDeal->position !== $position ? 'disabled' : '' }}
                                        required>
                                    <option value="">Choose a product</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-image="{{ $product->image ? asset('storage/products/' . $product->image) : '' }}"
                                            {{ $currentDeal && $currentDeal->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->discount }}% OFF)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="{{ $position }}-discount" class="form-label">Discount (%)</label>
                                <input type="number" class="form-control" 
                                    id="{{ $position }}-discount" 
                                    name="{{ $position }}_discount" 
                                    min="1" max="100"
                                    value="{{ $currentDeal ? $currentDeal->discount_percentage : '' }}" 
                                    {{ isset($editingDeal) && $editingDeal->position !== $position ? 'disabled' : '' }}
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="{{ $position }}-start-date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" 
                                    id="{{ $position }}-start-date" 
                                    name="{{ $position }}_start_date"
                                    value="{{ $currentDeal ? $currentDeal->start_date->format('Y-m-d') : '' }}" 
                                    {{ isset($editingDeal) && $editingDeal->position !== $position ? 'disabled' : '' }}
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="{{ $position }}-end-date" class="form-label">End Date</label>
                                <input type="date" class="form-control" 
                                    id="{{ $position }}-end-date" 
                                    name="{{ $position }}_end_date"
                                    value="{{ $currentDeal ? $currentDeal->end_date->format('Y-m-d') : '' }}" 
                                    {{ isset($editingDeal) && $editingDeal->position !== $position ? 'disabled' : '' }}
                                    required>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="save-button-container">
                        <button type="submit" class="btn btn-primary" id="hot-deals-submit-btn">{{ isset($editingDeal) ? 'Update' : 'Save' }}</button>
                        @if(isset($editingDeal))
                            <button type="button" class="btn btn-secondary" onclick="resetHotDealsForm()">Cancel</button>
                        @endif
                    </div>
                </form>
                
                <!-- Hot Deals Logs Table -->
                <div class="logs-table">
                    <h5 class="mt-4">Hot Deals Logs</h5>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Discount (%)</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hotDeals as $deal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $deal->product->name }}</td>
                                <td>
                                    @if($deal->image_path)
                                        <img src="{{ asset('storage/products/' . $deal->image_path) }}" width="50" alt="Hot Deal Image">
                                    @else
                                        <span>No image</span>
                                    @endif
                                </td>
                                <td>{{ $deal->discount_percentage }}</td>
                                <td>{{ $deal->start_date->format('Y-m-d') }}</td>
                                <td>{{ $deal->end_date->format('Y-m-d') }}</td>
                                <td>{{ ucfirst($deal->position) }}</td>
                                <td>
                                    <span class="badge {{ $deal->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $deal->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-deal-btn"
                                            data-id="{{ $deal->id }}"
                                            data-product-id="{{ $deal->product_id }}"
                                            data-product-image="{{ $deal->image_path ? asset('storage/products/' . $deal->image_path) : '' }}"
                                            data-discount="{{ $deal->discount_percentage }}"
                                            data-start-date="{{ $deal->start_date->format('Y-m-d') }}"
                                            data-end-date="{{ $deal->end_date->format('Y-m-d') }}"
                                            data-position="{{ $deal->position }}">Edit</button>
                                    <form action="{{ route('hot-deals.destroy', $deal->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }

    // Initialize toasts
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif
});
// Advertisement Image Preview (Unchanged)
    function previewImage(input) {
        const preview = document.getElementById('ad-preview');
        const plusSign = document.querySelector('.plus-sign');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.maxWidth = '100%';
                preview.style.maxHeight = '200px';
                plusSign.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Advertisement Edit (Unchanged)
    document.querySelectorAll('.edit-ad-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = document.getElementById('advertisement-form');
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');
            const startDate = this.getAttribute('data-start-date');
            const expirationDate = this.getAttribute('data-expiration-date');
            const imagePath = this.getAttribute('data-image-path');

            form.action = `/advertisements/${id}`;
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            } else {
                methodInput.value = 'PUT';
            }

            document.getElementById('title').value = title;
            document.getElementById('description').value = description;
            document.getElementById('start_date').value = startDate;
            document.getElementById('expiration_date').value = expirationDate;

            const preview = document.getElementById('ad-preview');
            const plusSign = document.querySelector('.plus-sign');
            const imageInput = document.getElementById('image');
            if (imagePath) {
                preview.src = imagePath;
                preview.style.display = 'block';
                preview.style.maxWidth = '100%';
                preview.style.maxHeight = '200px';
                plusSign.style.display = 'none';
                imageInput.removeAttribute('required');
            } else {
                preview.style.display = 'none';
                preview.src = '#';
                plusSign.style.display = 'block';
                imageInput.setAttribute('required', 'required');
            }

            const submitButton = document.getElementById('submit-btn');
            submitButton.textContent = 'Update';

            let cancelButton = form.querySelector('.btn-secondary');
            if (!cancelButton) {
                cancelButton = document.createElement('button');
                cancelButton.type = 'button';
                cancelButton.className = 'btn btn-secondary';
                cancelButton.textContent = 'Cancel';
                cancelButton.onclick = resetAdForm;
                submitButton.parentNode.appendChild(cancelButton);
            }

            form.scrollIntoView({ behavior: 'smooth' });
        });
    });

    function resetAdForm() {
        const form = document.getElementById('advertisement-form');
        form.reset();
        form.action = "{{ route('advertisements.store') }}";

        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }

        const preview = document.getElementById('ad-preview');
        const plusSign = document.querySelector('.plus-sign');
        const imageInput = document.getElementById('image');
        preview.style.display = 'none';
        preview.src = '#';
        plusSign.style.display = 'block';
        imageInput.setAttribute('required', 'required');

        const submitButton = document.getElementById('submit-btn');
        submitButton.textContent = 'Save';

        const cancelButton = form.querySelector('.btn-secondary');
        if (cancelButton) {
            cancelButton.remove();
        }
    }

    // Hot Deals Edit
    document.querySelectorAll('.edit-deal-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = document.getElementById('hot-deals-form');
            const id = this.getAttribute('data-id');
            const productId = this.getAttribute('data-product-id');
            const productImage = this.getAttribute('data-product-image');
            const discount = this.getAttribute('data-discount');
            const startDate = this.getAttribute('data-start-date');
            const endDate = this.getAttribute('data-end-date');
            const position = this.getAttribute('data-position');

            // Update form action for update route
            form.action = `/hot-deals/${id}`;

            // Set form method to PUT
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            } else {
                methodInput.value = 'PUT';
            }

            // Set hidden position field
            let positionInput = form.querySelector('#editing-position');
            if (!positionInput) {
                positionInput = document.createElement('input');
                positionInput.type = 'hidden';
                positionInput.id = 'editing-position';
                positionInput.name = 'editing_position';
                form.appendChild(positionInput);
            }
            positionInput.value = position;

            // Enable only the relevant position section
            ['left', 'middle', 'right'].forEach(pos => {
                const section = document.getElementById(`${pos}-section`);
                const inputs = section.querySelectorAll('select, input');
                const imagePreview = section.querySelector('.product-image-preview img');
                const noImagePlaceholder = section.querySelector('.product-image-preview .no-image-placeholder');

                if (pos === position) {
                    inputs.forEach(input => input.removeAttribute('disabled'));
                    document.getElementById(`${pos}-product`).value = productId;
                    document.getElementById(`${pos}-discount`).value = discount;
                    document.getElementById(`${pos}-start-date`).value = startDate;
                    document.getElementById(`${pos}-end-date`).value = endDate;

                    if (productImage) {
                        if (imagePreview) {
                            imagePreview.src = productImage;
                            imagePreview.style.display = 'block';
                        }
                        if (noImagePlaceholder) {
                            noImagePlaceholder.style.display = 'none';
                        }
                    } else {
                        if (imagePreview) {
                            imagePreview.style.display = 'none';
                        }
                        if (noImagePlaceholder) {
                            noImagePlaceholder.style.display = 'flex';
                        }
                    }
                } else {
                    inputs.forEach(input => input.setAttribute('disabled', 'disabled'));
                }
            });

            // Update button text to "Update"
            const submitButton = document.getElementById('hot-deals-submit-btn');
            submitButton.textContent = 'Update';

            // Add Cancel button if not already present
            let cancelButton = form.querySelector('.btn-secondary');
            if (!cancelButton) {
                cancelButton = document.createElement('button');
                cancelButton.type = 'button';
                cancelButton.className = 'btn btn-secondary';
                cancelButton.textContent = 'Cancel';
                cancelButton.onclick = resetHotDealsForm;
                submitButton.parentNode.appendChild(cancelButton);
            }

            // Scroll to the form
            form.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Reset Hot Deals form to create mode
    function resetHotDealsForm() {
        const form = document.getElementById('hot-deals-form');
        form.reset();
        form.action = "{{ route('hot-deals.store') }}";

        // Remove PUT method and position input
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }
        const positionInput = form.querySelector('#editing-position');
        if (positionInput) {
            positionInput.remove();
        }

        // Enable all position sections and reset image previews
        ['left', 'middle', 'right'].forEach(pos => {
            const section = document.getElementById(`${pos}-section`);
            const inputs = section.querySelectorAll('select, input');
            const imagePreview = section.querySelector('.product-image-preview img');
            const noImagePlaceholder = section.querySelector('.product-image-preview .no-image-placeholder');

            inputs.forEach(input => input.removeAttribute('disabled'));
            if (imagePreview) {
                imagePreview.style.display = 'none';
            }
            if (noImagePlaceholder) {
                noImagePlaceholder.style.display = 'flex';
            }
        });

        // Reset button text to "Save"
        const submitButton = document.getElementById('hot-deals-submit-btn');
        submitButton.textContent = 'Save';

        // Remove Cancel button
        const cancelButton = form.querySelector('.btn-secondary');
        if (cancelButton) {
            cancelButton.remove();
        }
    }

    // Update image preview when product selection changes
    document.querySelectorAll('.product-select').forEach(select => {
        select.addEventListener('change', function() {
            const position = this.getAttribute('data-position');
            const selectedOption = this.options[this.selectedIndex];
            const imageUrl = selectedOption.getAttribute('data-image');
            const imagePreview = document.querySelector(`#${position}-section .product-image-preview img`);
            const noImagePlaceholder = document.querySelector(`#${position}-section .product-image-preview .no-image-placeholder`);

            if (imageUrl) {
                if (imagePreview) {
                    imagePreview.src = imageUrl;
                    imagePreview.style.display = 'block';
                }
                if (noImagePlaceholder) {
                    noImagePlaceholder.style.display = 'none';
                }
            } else {
                if (imagePreview) {
                    imagePreview.style.display = 'none';
                }
                if (noImagePlaceholder) {
                    noImagePlaceholder.style.display = 'flex';
                }
            }
        });
    });
</script>

</body>
</html>






