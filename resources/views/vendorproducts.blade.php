<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Fresh Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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

        /* Sidebar */
        .sidebar {
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
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

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 1.5rem;
            transition: margin-left 0.3s ease;
        }

        /* Cards */
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

        /* Product Card for Mobile */
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

        /* Responsive Adjustments */
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

        /* Phone-Specific Styles */
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

            /* Hide the table and show cards on phones */
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

        /* Overlay for off-canvas */
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

        /* Loading Spinner for Pagination */
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

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<!-- Overlay for Mobile Sidebar -->
<div class="overlay" id="sidebarOverlay"></div>

<div class="d-flex flex-column">
    <!-- Navbar for Mobile -->
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

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3" id="sidebar">
        <!-- Brand -->
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
            <i class="bi bi-shop me-2 text-success" style="font-size: 1.5rem;"></i>
            <span class="fs-4 fw-bold">SwiftMart Vendors</span>
        </a>

        <!-- Vendor Dropdown -->
        <div class="dropdown my-3">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('images/hina.jpg') }}" alt="Vendor" class="vendor-avatar me-2">
                @auth('vendor')
                    <strong>{{ Auth::guard('vendor')->user()->name }}</strong>
                @else
                    <strong>Green Farms</strong>
                @endauth
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <form action="{{ route('vendor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Sign out</button>
                </form>
            </ul>
        </div>

        <hr>

        <!-- Navigation -->
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
            <a href="{{ url('vendor/dashboard/' . $name) }}#dashboard" class="nav-link active" onclick="window.location.reload();">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            </li>
            <li>
                <a href="#products" class="nav-link" data-bs-toggle="tab" >
                    <i class="bi bi-basket"></i>
                    My Products
                </a>
            </li>
            <li>
                <a href="#orders" class="nav-link" data-bs-toggle="tab">
                    <i class="bi bi-cart3"></i>
                    Orders
                </a>
            </li>
            <li>
                <a href="#reports" class="nav-link" data-bs-toggle="tab">
                    <i class="bi bi-graph-up"></i>
                    Sales Reports
                </a>
            </li>
            <li>
                <a href="#settings" class="nav-link" data-bs-toggle="tab">
                    <i class="bi bi-gear"></i>
                    Stall Settings
                </a>
            </li>
        </ul>

        <hr>
    </div>

    <!-- Main Content -->
    <div class="main-content flex-grow-1">
        <div class="tab-content">
            <!-- Dashboard Tab -->
            <div class="tab-pane fade show active" id="dashboard">
                <h2 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Vendor Dashboard</h2>
                
                <!-- Stats Cards -->
                <div class="row mb-4 g-3">
                    <div class="col-6 col-md-3">
                        <div class="card bg-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-2">Total Sales</h6>
                                        <h3 class="mb-0">$3,450</h3>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 12.5%</small>
                                    </div>
                                    <div class="icon bg-success bg-opacity-10 text-success rounded p-2">
                                        <i class="bi bi-currency-dollar fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card bg-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-2">Total Orders</h6>
                                        <h3 class="mb-0">78</h3>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 8.2%</small>
                                    </div>
                                    <div class="icon bg-primary bg-opacity-10 text-primary rounded p-2">
                                        <i class="bi bi-cart-check fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card bg-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-2">Active Products</h6>
                                        <h3 class="mb-0">{{$totalActiveProducts}}</h3>
                                        
                                        <small class="text-{{ $newActiveProducts > 0 ? 'danger' : 'success' }}"><i class="bi bi-arrow-{{ $newActiveProducts > 0 ? 'down' : 'up' }}"></i> {{$newActiveProducts}} out of stock</small>
                                    </div>
                                    <div class="icon bg-info bg-opacity-10 text-info rounded p-2">
                                        <i class="bi bi-basket fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card bg-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-2">Pending Orders</h6>
                                        <h3 class="mb-0">5</h3>
                                        <small class="text-danger"><i class="bi bi-arrow-down"></i> 2</small>
                                    </div>
                                    <div class="icon bg-warning bg-opacity-10 text-warning rounded p-2">
                                        <i class="bi bi-hourglass-split fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Recent Orders</h5>
                                    <a href="#orders" class="btn btn-sm btn-outline-success">View All</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#ORD-1024</td>
                                                <td>John Smith</td>
                                                <td>$45.50</td>
                                                <td><span class="badge bg-success">Delivered</span></td>
                                            </tr>
                                            <tr>
                                                <td>#ORD-1023</td>
                                                <td>Sarah Johnson</td>
                                                <td>$32.75</td>
                                                <td><span class="badge bg-primary">Shipped</span></td>
                                            </tr>
                                            <tr>
                                                <td>#ORD-1022</td>
                                                <td>Michael Brown</td>
                                                <td>$68.20</td>
                                                <td><span class="badge bg-warning text-dark">Processing</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Top Selling Products</h5>
                                    <a href="#products" class="btn btn-sm btn-outline-success">View All</a>
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Organic Tomatoes</h6>
                                            <small class="text-success">28 sold</small>
                                        </div>
                                        <p class="mb-1">$2.99 per kg</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Fresh Basil</h6>
                                            <small class="text-success">22 sold</small>
                                        </div>
                                        <p class="mb-1">$1.50 per bunch</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Free Range Eggs</h6>
                                            <small class="text-success">18 sold</small>
                                        </div>
                                        <p class="mb-1">$5.00 per dozen</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Tab -->
            <div class="tab-pane fade" id="products">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h2><i class="bi bi-basket me-2"></i>My Product List</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Product
                        </button>
                        <div class="btn-group">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">All Categories</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Vegetables</a></li>
                                <li><a class="dropdown-item" href="#">Fruits</a></li>
                                <li><a class="dropdown-item" href="#">Meat</a></li>
                                <li><a class="dropdown-item" href="#">Fish</a></li>
                                <li><a class="dropdown-item" href="#">Spices</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Add Product Modal -->
                <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="addProductModalLabel">
                                    <i class="bi bi-plus-circle me-2"></i>Add New Product
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="productName" class="form-label">Product Name *</label>
                                            <input type="text" class="form-control" id="productName" name="name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="productCode" class="form-label">Product Code</label>
                                            <input type="text" class="form-control" id="productCode" name="product_code" placeholder="Auto-generate">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="productCategory" class="form-label">Category *</label>
                                            <select class="form-select" id="productCategory" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="Vegetables">Vegetables</option>
                                                <option value="Fruits">Fruits</option>
                                                <option value="Meat">Meat</option>
                                                <option value="Fish">Fish</option>
                                                <option value="Spices">Spices</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="productStatus" class="form-label">Status</label>
                                            <select class="form-select" id="productStatus" name="status">
                                                <option value="active" selected>Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="productPrice" class="form-label">Price *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" class="form-control" id="productPrice" name="price" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="productStock" class="form-label">Stock *</label>
                                            <input type="number" class="form-control" id="productStock" name="stock" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="productDescription" class="form-label">Description</label>
                                            <textarea class="form-control" id="productDescription" name="description" rows="3"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="productImage" class="form-label">Product Image</label>
                                            <input class="form-control" type="file" id="productImage" name="image" accept="image/*">
                                            <small class="text-muted">Recommended: 500x500px (Max 2MB)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save me-1"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Products Table (for larger screens) -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="products-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product Code</th>
                                        <th>Name & Description</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="products-table-body">
                                    @foreach($products as $product)
                                    <tr>
                                        <td><strong>{{ $product->product_code }}</strong></td>
                                        <td>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->description ?? 'No description' }}</small>
                                        </td>
                                        <td>{{ $product->category }}</td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>
                                            {{ $product->stock }}
                                            @if ($product->stock == 0)
                                                <div><small class="text-danger">Out of stock</small></div>
                                            @else
                                                <div><small class="text-success">In stock</small></div>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('products.updateStatus', $product->id) }}" method="POST" class="status-form">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="Active" {{ $product->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Inactive" {{ $product->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg') }}" alt="Image" class="rounded product-img">
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary edit-product-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal{{ $product->id }}" 
                                                    data-id="{{ $product->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Product Cards (for phones) -->
                        <div id="products-cards-body">
                            @foreach($products as $product)
                            <div class="product-card">
                                <div class="product-header">
                                    <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg') }}" alt="Image" class="product-img">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">{{ $product->product_code }}</small>
                                    </div>
                                </div>
                                <div class="product-details">
                                    <p><strong>Category:</strong> {{ $product->category }}</p>
                                    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                                    <p><strong>Stock:</strong> {{ $product->stock }}
                                        @if ($product->stock == 0)
                                            <small class="text-danger"> (Out of stock)</small>
                                        @else
                                            <small class="text-success"> (In stock)</small>
                                        @endif
                                    </p>
                                    <p><strong>Status:</strong>
                                        <form action="{{ route('products.updateStatus', $product->id) }}" method="POST" class="status-form d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                                <option value="Active" {{ $product->status === 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ $product->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </form>
                                    </p>
                                    <p><strong>Description:</strong> {{ $product->description ?? 'No description' }}</p>
                                </div>
                                <div class="product-actions">
                                    <button class="btn btn-sm btn-outline-primary edit-product-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $product->id }}" 
                                            data-id="{{ $product->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-3" id="pagination-container">
                    <div class="pagination-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="pagination-links">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                <!-- Edit Modals -->
                @foreach($products as $product)
                <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="edit-product-form" data-id="{{ $product->id }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="editModalLabel{{ $product->id }}">Edit: {{ $product->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body bg-light">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name{{ $product->id }}" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="category{{ $product->id }}" class="form-label">Category</label>
                                            <input type="text" name="category" class="form-control" value="{{ $product->category }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" class="form-control" rows="2">{{ $product->description }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Price</label>
                                            <input type="number" name="price" class="form-control" step="0.01" value="{{ $product->price }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Stock</label>
                                            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Product Image</label>
                                            <input type="file" name="image" class="form-control">
                                            @if($product->image)
                                                <div class="mt-2">
                                                    <small>Current Image:</small>
                                                    <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg') }}" class="img-thumbnail mt-1" style="max-height: 100px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-white">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Orders Tab -->
            <div class="tab-pane fade" id="orders">
                <h2 class="mb-4"><i class="bi bi-cart3 me-2"></i>Order Management</h2>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="card-title mb-0">Recent Orders</h5>
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-filter me-1"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">All Orders</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Pending</a></li>
                                    <li><a class="dropdown-item" href="#">Processing</a></li>
                                    <li><a class="dropdown-item" href="#">Shipped</a></li>
                                    <li><a class="dropdown-item" href="#">Delivered</a></li>
                                    <li><a class="dropdown-item" href="#">Cancelled</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#ORD-1024</td>
                                        <td>2023-05-15</td>
                                        <td>John Smith</td>
                                        <td>3</td>
                                        <td>$45.50</td>
                                        <td><span class="badge bg-success">Delivered</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#ORD-1023</td>
                                        <td>2023-05-14</td>
                                        <td>Sarah Johnson</td>
                                        <td>2</td>
                                        <td>$32.75</td>
                                        <td><span class="badge bg-primary">Shipped</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#ORD-1022</td>
                                        <td>2023-05-14</td>
                                        <td>Michael Brown</td>
                                        <td>5</td>
                                        <td>$68.20</td>
                                        <td><span class="badge bg-warning text-dark">Processing</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#ORD-1021</td>
                                        <td>2023-05-13</td>
                                        <td>Emily Davis</td>
                                        <td>1</td>
                                        <td>$12.99</td>
                                        <td><span class="badge bg-secondary">Pending</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Tab -->
            <div class="tab-pane fade" id="reports">
                <h2 class="mb-4"><i class="bi bi-graph-up me-2"></i>Sales Reports</h2>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="card-title mb-0">Sales Overview</h5>
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-calendar me-1"></i> This Month
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Week</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Custom Range</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="chart-container" style="height: 300px; position: relative;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Top Selling Products</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Sales</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Organic Tomatoes</td>
                                                <td>28</td>
                                                <td>$83.72</td>
                                            </tr>
                                            <tr>
                                                <td>Fresh Basil</td>
                                                <td>22</td>
                                                <td>$33.00</td>
                                            </tr>
                                            <tr>
                                                <td>Free Range Eggs</td>
                                                <td>18</td>
                                                <td>$90.00</td>
                                            </tr>
                                            <tr>
                                                <td>Carrots</td>
                                                <td>15</td>
                                                <td>$22.50</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Sales by Category</h5>
                                <div class="chart-container" style="height: 300px; position: relative;">
                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div class="tab-pane fade" id="settings">
                <h2 class="mb-4"><i class="bi bi-gear me-2"></i>Stall Settings</h2>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Stall Information</h5>
                                <form>
                                    <div class="mb-3">
                                        <label class="form-label">Stall Name</label>
                                        <input type="text" class="form-control" value="Green Farms">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="3">We provide fresh organic produce directly from our farm to your table.</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Logo</label>
                                        <input type="file" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Banner Image</label>
                                        <input type="file" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Business Information</h5>
                                <form>
                                    <div class="mb-3">
                                        <label class="form-label">Business Name</label>
                                        <input type="text" class="form-control" value="Green Farms LLC">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tax ID</label>
                                        <input type="text" class="form-control" value="TAX-123456789">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Business Address</label>
                                        <input type="text" class="form-control mb-2" value="123 Farm Road">
                                        <div class="row g-2 mb-2">
                                            <div class="col-12">
                                                <input type="text" class="form-control" value="Farmville">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" value="NY">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" value="12345">
                                            </div>
                                        </div>
                                        <select class="form-select">
                                            <option>United States</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Save Business Info</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Contact Information</h5>
                                <form>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="contact@greenfarms.com">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" class="form-control" value="(555) 123-4567">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Social Media Links</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                                            <input type="text" class="form-control" placeholder="Facebook URL">
                                        </div>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                            <input type="text" class="form-control" placeholder="Instagram URL">
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                                            <input type="text" class="form-control" placeholder="Twitter URL">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">Save Contact Info</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Payment Settings</h5>
                                <form>
                                    <div class="mb-3">
                                        <label class="form-label">Bank Account</label>
                                        <input type="text" class="form-control" placeholder="Account Number">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Routing Number</label>
                                        <input type="text" class="form-control" placeholder="Routing Number">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Account Holder Name</label>
                                        <input type="text" class="form-control" placeholder="Full Name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" class="form-control" placeholder="Bank Name">
                                    </div>
                                    <button type="submit" class="btn btn-success">Save Payment Info</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle for Mobile
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

    // Initialize Bootstrap Tabs
    const triggerTabList = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
    triggerTabList.forEach(triggerEl => {
        triggerEl.addEventListener('click', event => {
            event.preventDefault();
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
            if (window.innerWidth <= 767.98) {
                toggleSidebar();
            }
        });
    });

    // AJAX Pagination for Products
    $(document).off('click', '#pagination-links a').on('click', '#pagination-links a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (!url || url === '#') {
            console.log('Invalid pagination URL:', url);
            return;
        }

        // Show loading spinner
        $('#products-table-body, #products-cards-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
        $('#pagination-links').hide();
        $('.pagination-loading').show();

        console.log('Fetching pagination URL:', url);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log('AJAX Success:', response);
                const $response = $(response);

                // Update table and cards
                const tableBody = $response.find('#products-table-body').html();
                const cardsBody = $response.find('#products-cards-body').html();
                const paginationLinks = $response.find('#pagination-links').html();

                if (!tableBody || !cardsBody || !paginationLinks) {
                    console.error('Missing content in response:', { tableBody, cardsBody, paginationLinks });
                    showToast('Error: Incomplete response from server', 'error');
                    return;
                }

                $('#products-table-body').html(tableBody);
                $('#products-cards-body').html(cardsBody);
                $('#pagination-links').html(paginationLinks);

                // Hide loading spinner and show pagination
                $('.pagination-loading').hide();
                $('#pagination-links').show();

                // Reinitialize handlers
                initializeProductHandlers();

                // Scroll to top of the products section
                $('html, body').animate({
                    scrollTop: $('#products').offset().top
                }, 300);
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                let errorMessage = 'Error loading products. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showToast(errorMessage, 'error');

                // Restore pagination links in case of error
                $('.pagination-loading').hide();
                $('#pagination-links').show();
            }
        });
    });

    // Handle edit button click
    $(document).on('click', '.edit-product-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('id');
        $(`#editModal${productId}`).modal('show');
    });

    // Handle edit form submission
    $(document).on('submit', '.edit-product-form', function(e) {
        e.preventDefault();
        const form = this;
        const productId = $(this).data('id');
        const formData = new FormData(form);

        $.ajax({
            url: form.action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                $(`#editModal${productId}`).modal('hide');
                showToast('Product updated successfully!');
                reloadProductsTable();
            },
            error: function(xhr) {
                let errorMessage = 'Error updating product';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                showToast(errorMessage, 'error');
            }
        });
    });

    // Handle add product form submission
    $('#addProductModal form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        
        $.ajax({
            url: form.action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#addProductModal').modal('hide');
                form.reset();
                showToast('Product added successfully!');
                reloadProductsTable();
            },
            error: function(xhr) {
                let errorMessage = 'Error adding product';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                showToast(errorMessage, 'error');
            }
        });
    });

    // Function to reload products table
    function reloadProductsTable() {
        const currentPage = $('#pagination-links .page-item.active a').attr('href') || window.location.href;
        console.log('Reloading products table with URL:', currentPage);

        $('#products-table-body, #products-cards-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
        $('#pagination-links').hide();
        $('.pagination-loading').show();

        $.ajax({
            url: currentPage,
            type: 'GET',
            success: function(response) {
                console.log('Reload Success:', response);
                const $response = $(response);

                const tableBody = $response.find('#products-table-body').html();
                const cardsBody = $response.find('#products-cards-body').html();
                const paginationLinks = $response.find('#pagination-links').html();

                if (!tableBody || !cardsBody || !paginationLinks) {
                    console.error('Missing content in reload response:', { tableBody, cardsBody, paginationLinks });
                    showToast('Error: Incomplete response from server', 'error');
                    return;
                }

                $('#products-table-body').html(tableBody);
                $('#products-cards-body').html(cardsBody);
                $('#pagination-links').html(paginationLinks);

                $('.pagination-loading').hide();
                $('#pagination-links').show();

                initializeProductHandlers();
            },
            error: function(xhr) {
                console.error('Reload Error:', xhr);
                showToast('Error reloading products', 'error');
                $('.pagination-loading').hide();
                $('#pagination-links').show();
            }
        });
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        if (!toastEl) {
            console.warn('Toast element not found');
            return;
        }
        
        const toastBody = toastEl.querySelector('.toast-body');
        toastBody.textContent = message;
        
        const toastHeader = toastEl.querySelector('.toast-header');
        toastHeader.className = 'toast-header';
        toastHeader.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');
        toastHeader.classList.add('text-white');
        
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    // Initialize product handlers
    function initializeProductHandlers() {
        $('.status-form .form-select-sm').off('change').on('change', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-HTTP-Method-Override': 'PATCH'
                },
                success: function() {
                    showToast('Status updated successfully');
                    reloadProductsTable();
                },
                error: function() {
                    showToast('Error updating status', 'error');
                }
            });
        });

        $('.btn-outline-danger').off('click').on('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure?')) {
                const form = $(this).closest('form');
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-HTTP-Method-Override': 'DELETE'
                    },
                    success: function() {
                        showToast('Product deleted successfully');
                        reloadProductsTable();
                    },
                    error: function() {
                        showToast('Error deleting product', 'error');
                    }
                });
            }
        });
    }

    try {
        initializeProductHandlers();
    } catch (error) {
        console.error('Error initializing product handlers:', error);
    }

    // Debug tab switching
    $('.nav-link[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        console.log('Tab switched to: ' + $(e.target).attr('href'));
    });

    // Chart.js Initialization
    try {
        const salesChart = new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Sales',
                    data: [100, 200, 150, 300, 250],
                    borderColor: '#28a745',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const categoryChart = new Chart(document.getElementById('categoryChart'), {
            type: 'pie',
            data: {
                labels: ['Vegetables', 'Fruits', 'Meat', 'Fish'],
                datasets: [{
                    data: [40, 30, 20, 10],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
});

</script>
</body>
</html>
<!-- //backup -->