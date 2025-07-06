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
        .image-upload-container {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.image-upload {
    width: 100px;
    height: 100px;
    border: 2px dashed #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    position: relative;
}
.image-upload i {
    font-size: 24px;
    color: #666;
}
.image-upload input {
    opacity: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    cursor: pointer;
}
.image-preview {
    position: relative;
    width: 100px;
    height: 100px;
}
.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.image-preview .remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: red;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    line-height: 18px;
    text-align: center;
    font-size: 14px;
    cursor: pointer;
    padding: 0;
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
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2><i class="bi bi-basket me-2"></i>My Product List</h2>
    <div class="d-flex gap-2">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-circle me-1"></i> Add Product
        </button>
        <div class="btn-group">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-current-filter="{{ request()->query('category', 'all') }}">
                <i class="bi bi-filter me-1"></i> {{ request()->query('category', 'All Categories') }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" data-filter="all">All Categories</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" data-filter="Vegetables">Vegetables</a></li>
                <li><a class="dropdown-item" href="#" data-filter="Fruits">Fruits</a></li>
                <li><a class="dropdown-item" href="#" data-filter="Meat">Meat</a></li>
                <li><a class="dropdown-item" href="#" data-filter="Fish">Fish</a></li>
                <li><a class="dropdown-item" href="#" data-filter="Spices">Spices</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Add New Product
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="productName" class="form-label">Product Name *</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="col-md-6 "style="display:none">
                            <label for="productCode" class="form-label" >Product Code</label>
                            <input type="text" class="form-control" id="productCode" name="product_code" placeholder="Auto-generate">
                        </div>
                        <div class="col-md-6">
                            <label for="discount" class="form-label">Discount</label>
                            <input type="number" class="form-control" id="productDiscount" name="discount" value="{{ old('discount', 0) }}" required>
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
                                <span class="input-group-text">₱</span>
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
                            <label class="form-label">Product Images (Max 5)</label>
                            <div class="image-upload-container d-flex gap-3 flex-wrap">
                                <div class="image-upload-container-inner" data-field="image">
                                    <div class="image-upload">
                                        <i class="bi bi-plus"></i>
                                        <input type="file" name="image" accept="image/*">
                                    </div>
                                </div>
                                <div class="image-upload-container-inner" data-field="image2" style="display: none;">
                                    <div class="image-upload">
                                        <i class="bi bi-plus"></i>
                                        <input type="file" name="image2" accept="image/*">
                                    </div>
                                </div>
                                <div class="image-upload-container-inner" data-field="image3" style="display: none;">
                                    <div class="image-upload">
                                        <i class="bi bi-plus"></i>
                                        <input type="file" name="image3" accept="image/*">
                                    </div>
                                </div>
                                <div class="image-upload-container-inner" data-field="image4" style="display: none;">
                                    <div class="image-upload">
                                        <i class="bi bi-plus"></i>
                                        <input type="file" name="image4" accept="image/*">
                                    </div>
                                </div>
                                <div class="image-upload-container-inner" data-field="image5" style="display: none;">
                                    <div class="image-upload">
                                        <i class="bi bi-plus"></i>
                                        <input type="file" name="image5" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Recommended: 500x500px (Max 2MB each)</small>
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
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                    @foreach($products as $product)
                    <tr>
                        <td><strong>{{ $product->product_code ?? 'N/A' }}</strong></td>
                        <td>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->description ?? 'No description' }}</small>
                        </td>
                        <td>{{ $product->category }}</td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>
                            {{ $product->stock }}
                            @if ($product->stock == 0)
                                <div><small class="text-danger">Out of stock</small></div>
                            @else
                                <div><small class="text-success">In stock</small></div>
                            @endif
                        </td>
                        <td>
                            @if ($product->discount == 0)
                                    <div><small class="text-danger">No discount</small></div>
                            @else
                                <div><small class="text-success">{{$product->discount}}% Discount</small></div> 
                            @endif
                        </td>
                        <td>
                            <span class="badge d-flex align-items-center gap-1 {{ $product->status === 'inactive' ? 'bg-danger' : 'bg-success' }}">
                                <i class="bi {{ $product->status === 'inactive' ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            @foreach(['image', 'image2', 'image3', 'image4', 'image5'] as $imgField)
                                @if($product->$imgField)
                                    <img src="{{ asset('storage/products/' . $product->$imgField) }}" alt="Image" class="rounded product-img" style="width: 50px; height: 50px; margin-right: 5px;">
                                @endif
                            @endforeach
                            @if(!$product->image && !$product->image2 && !$product->image3 && !$product->image4 && !$product->image5)
                                <small>No images</small>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-product-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal{{ $product->id }}" 
                                    data-id="{{ $product->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" class="d-inline">
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

        <div id="products-cards-body">
            @foreach($products as $product)
            <div class="product-card">
                <div class="product-header">
                    <div class="d-flex gap-2">
                        @foreach(['image', 'image2', 'image3', 'image4', 'image5'] as $imgField)
                            @if($product->$imgField)
                                <img src="{{ asset('storage/products/' . $product->$imgField) }}" alt="Image" class="product-img">
                            @endif
                        @endforeach
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $product->name }}</h6>
                        <small class="text-muted">{{ $product->product_code ?? 'N/A' }}</small>
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
                        <form action="{{ route('vendor.products.updateStatus', $product->id) }}" method="POST" class="status-form d-inline">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                    <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" class="d-inline">
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

@foreach($products as $product)
<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="edit-product-form" data-id="{{ $product->id }}">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editModalLabel{{ $product->id }}">Edit: {{ $product->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name{{ $product->id }}" class="form-label">Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>
                        <div class="col-md-6" style="display:none">
                            <label for="product_code{{ $product->id }}" class="form-label">Product Code</label>
                            <input type="text" name="product_code" class="form-control" value="{{ $product->product_code }}">
                        </div>
                        <div class="col-md-6">
                            <label for="discount{{ $product->id }}" class="form-label">Discount</label>
                            <input type="number" name="discount" class="form-control" value="{{ $product->discount }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="category{{ $product->id }}" class="form-label">Category *</label>
                            <select class="form-select" name="category" required>
                                <option value="Vegetables" {{ $product->category == 'Vegetables' ? 'selected' : '' }}>Vegetables</option>
                                <option value="Fruits" {{ $product->category == 'Fruits' ? 'selected' : '' }}>Fruits</option>
                                <option value="Meat" {{ $product->category == 'Meat' ? 'selected' : '' }}>Meat</option>
                                <option value="Fish" {{ $product->category == 'Fish' ? 'selected' : '' }}>Fish</option>
                                <option value="Spices" {{ $product->category == 'Spices' ? 'selected' : '' }}>Spices</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status{{ $product->id }}" class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="price{{ $product->id }}" class="form-label">Price *</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="stock{{ $product->id }}" class="form-label">Stock *</label>
                            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                        </div>
                        <div class="col-12">
                            <label for="description{{ $product->id }}" class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Product Images (Max 5)</label>
                            <div class="image-upload-container d-flex gap-3 flex-wrap">
                                @php
                                    $fields = ['image', 'image2', 'image3', 'image4', 'image5'];
                                @endphp

                                @foreach ($fields as $index => $img)
                                    <div class="image-upload-container-inner" data-field="{{ $img }}">
                                        @if ($product->$img)
                                            <div class="image-preview">
                                                <img src="{{ asset('storage/products/' . $product->$img) }}" alt="Image">
                                                <a href="{{ route('product.deleteImage', ['id' => $product->id, 'imageField' => $img]) }}"
                                                class="remove-image" title="Remove">×</a>
                                            </div>
                                        @else
                                            <div class="image-upload">
                                                <i class="bi bi-plus-lg"></i>
                                                <input type="file" name="{{ $img }}" accept="image/*">
                                            </div>
                                        @endif
                                        <input type="hidden" name="clear_{{ $img }}" class="clear-image" value="0">
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Recommended: 500x500px (Max 2MB each)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
jQuery(document).ready(function ($) {
    function initializeImageUploads(container) {
        container.find('.image-upload input[type="file"]').off('change').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                const $parent = $(e.target).closest('.image-upload-container-inner');
                const field = $parent.data('field');
                $parent.find('.image-upload').hide();
                $parent.find('.image-preview').remove(); // Remove any existing preview
                $parent.append(`
                    <div class="image-preview">
                        <img src="${event.target.result}" alt="Image">
                        <button type="button" class="remove-image">x</button>
                        <input type="hidden" name="clear_${field}" value="0" class="clear-image">
                    </div>
                `);
                const $input = $parent.find('input[type="file"]');
                $input.css('display', 'none');

                // Show next box if available
                const $nextBox = $parent.next('.image-upload-container-inner');
                if ($nextBox.length && container.find('.image-preview').length + container.find('.image-upload:visible').length < 5) {
                    $nextBox.css({ opacity: 0, transform: 'scale(0.8)' }).show().animate({
                        opacity: 1,
                        transform: 'scale(1)'
                    }, 300);
                }

                // Bind remove button for new preview
                $parent.find('.image-preview .remove-image').on('click', function () {
                    $parent.find('.image-preview').remove();
                    $parent.find('.image-upload').show();
                    $input.val('').css('display', '');
                    // Set clear flag
                    $parent.find('.clear-image').val('1');
                    // Hide next empty boxes
                    updateVisibleBoxes(container);
                });
            };
            reader.readAsDataURL(file);
        });

        container.find('.image-preview .remove-image').off('click').on('click', function () {
            const $parent = $(this).closest('.image-upload-container-inner');
            $parent.find('.image-preview').remove();
            $parent.find('.image-upload').show();
            const $input = $parent.find('input[type="file"]');
            $input.val('').css('display', '');
            // Set clear flag
            $parent.find('.clear-image').val('1');
            // Hide next empty boxes
            updateVisibleBoxes(container);
        });

        function updateVisibleBoxes(container) {
            const $boxes = container.find('.image-upload-container-inner');
            let hasImageOrUpload = false;
            $boxes.each(function (index) {
                const $box = $(this);
                const isLast = index === $boxes.length - 1;
                if ($box.find('.image-preview').length || $box.find('.image-upload:visible').length) {
                    hasImageOrUpload = true;
                    $box.show();
                } else if (hasImageOrUpload && !isLast && container.find('.image-preview').length + container.find('.image-upload:visible').length < 5) {
                    $box.show();
                } else {
                    $box.hide();
                }
            });
        }
    }

    $('.image-upload-container').each(function () {
        initializeImageUploads($(this));
    });

    // Reset add modal on open
    $('#addProductModal').on('show.bs.modal', function () {
        const $container = $(this).find('.image-upload-container');
        $container.find('.image-upload-container-inner').each(function (index) {
            if (index === 0) {
                $(this).show().find('.image-preview').remove();
                $(this).find('.image-upload').show();
                $(this).find('input[type="file"]').val('').css('display', '');
            } else {
                $(this).hide();
            }
        });
        initializeImageUploads($container);
    });

    // Initialize edit modal
    $('.edit-product-form').each(function () {
        initializeImageUploads($(this).find('.image-upload-container'));
    });
});
    document.addEventListener('DOMContentLoaded', function() {
            // Initialize Toastr options
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-bottom-right',
                timeOut: 5000
            };

            // Pagination
            $(document).on('click', '#pagination-links a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (!url || url === '#') {
                    console.log('Invalid pagination URL:', url);
                    return;
                }

                $('#products-table-body, #products-cards-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                $('#pagination-links').hide();
                $('.pagination-loading').show();

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        const $response = $(response);
                        const tableBody = $response.find('#products-table-body').html();
                        const cardsBody = $response.find('#products-cards-body').html();
                        const paginationLinks = $response.find('#pagination-links').html();

                        if (!tableBody || !cardsBody || !paginationLinks) {
                            toastr.error('Error: Incomplete response from server');
                            return;
                        }

                        $('#products-table-body').html(tableBody);
                        $('#products-cards-body').html(cardsBody);
                        $('#pagination-links').html(paginationLinks);

                        $('.pagination-loading').hide();
                        $('#pagination-links').show();

                        initializeProductHandlers();

                        $('html, body').animate({
                            scrollTop: $('#products').offset().top
                        }, 300);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error loading products. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                        $('.pagination-loading').hide();
                        $('#pagination-links').show();
                    }
                });
            });

            // Edit product modal
            $(document).on('click', '.edit-product-btn', function(e) {
                e.preventDefault();
                const productId = $(this).data('id');
                $.ajax({
                    url: `{{ route('vendor.products.show', '') }}/${productId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const product = response.product;
                            const modal = $(`#editModal${productId}`);
                            modal.find('input[name="name"]').val(product.name);
                            modal.find('textarea[name="description"]').val(product.description || '');
                            modal.find('select[name="category"]').val(product.category);
                            modal.find('input[name="price"]').val(product.price);
                            modal.find('input[name="stock"]').val(product.stock);
                            modal.find('input[name="discount"]').val(product.discount);
                            modal.find('input[name="product_code"]').val(product.product_code || '');
                            modal.modal('show');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Error fetching product details');
                    }
                });
            });

            // Status update
            $(document).on('change', '.status-form select', function(e) {
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
                        toastr.success('Product status updated successfully');
                        reloadProductsTable();
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error updating status';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    }
                });
            });

            // Delete product
            $(document).on('click', '.delete-product-btn', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this product?')) {
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
                            toastr.success('Product deleted successfully');
                            reloadProductsTable();
                        },
                        error: function(xhr) {
                            let errorMessage = 'Error deleting product';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            toastr.error(errorMessage);
                        }
                    });
                }
            });

            function reloadProductsTable() {
                const currentPage = $('#pagination-links .page-item.active a').attr('href') || '{{ route('vendor.products') }}';
                $('#products-table-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                $('#products-cards-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-success" role="status">
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
                        const $response = $(response);
                        const tableBody = $response.find('#products-table-body').html();
                        const cardsBody = $response.find('#products-cards-body').html();
                        const paginationLinks = $response.find('#pagination-links').html();

                        if (!tableBody || !cardsBody || !paginationLinks) {
                            toastr.error('Error: Incomplete response from server');
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
                        toastr.error('Error refreshing products');
                        $('.pagination-loading').hide();
                        $('#pagination-links').show();
                    }
                });
            }

            function initializeProductHandlers() {
                // Handlers are already attached via event delegation
            }

            initializeProductHandlers();
        });
        // Filter handler
$('.dropdown-item[data-filter]').on('click', function(e) {
    e.preventDefault();
    const category = $(this).data('filter');
    loadProducts(category);
    const filterText = $(this).text();
    $(this).closest('.btn-group').find('.dropdown-toggle').text(filterText);
});

// Load products function
function loadProducts(category = null, url = '{{ route("vendor.products") }}') {
    const currentCategory = category || $('.dropdown-toggle').data('current-filter') || 'all';
    if (category) {
        $('.dropdown-toggle').data('current-filter', category);
    }

    $('#products-table-body, #products-cards-body').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    $('#pagination-links').hide();
    $('.pagination-loading').show();

    const params = new URLSearchParams();
    if (currentCategory !== 'all') {
        params.append('category', currentCategory);
    }
    const requestUrl = url.includes('?') ? url : `${url}${params.toString() ? '?' + params.toString() : ''}`;

    $.ajax({
        url: requestUrl,
        type: 'GET',
        success: function(response) {
            const $response = $(response);
            const tableBody = $response.find('#products-table-body').html();
            const cardsBody = $response.find('#products-cards-body').html();
            const paginationLinks = $response.find('#pagination-links').html();

            if (!tableBody || !cardsBody || !paginationLinks) {
                toastr.error('Error: Incomplete response from server');
                return;
            }

            $('#products-table-body').html(tableBody);
            $('#products-cards-body').html(cardsBody);
            $('#pagination-links').html(paginationLinks);

            $('.pagination-loading').hide();
            $('#pagination-links').show();

            initializeProductHandlers();

            $('html, body').animate({
                scrollTop: $('.card').offset().top
            }, 300);
        },
        error: function(xhr) {
            let errorMessage = 'Error loading products';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            toastr.error(errorMessage);
            $('.pagination-loading').hide();
            $('#pagination-links').show();
        }
    });
}

</script>
</body>
</html>
