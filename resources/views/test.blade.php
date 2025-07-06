<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary: #729979;
            --secondary: #a29bfe;
            --success: #00b894;
            --info: #0984e3;
            --warning: #fdcb6e;
            --danger: #d63031;
            --dark: #2d3436;
        }
        
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%,rgb(149, 189, 98) 100%);
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 5px;
            margin-bottom: 5px;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }
        
        .scrollable-section {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .vendor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
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
        
        .badge-suspended {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary);
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 20px;
            padding: 5px 15px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="bi bi-shop me-2" style="font-size: 1.5rem;"></i>
                <span class="fs-4">Marketplace Admin</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#dashboard" class="nav-link active" data-bs-toggle="tab">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#vendor-management" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-people"></i>
                        Vendor Management
                    </a>
                </li>
                <li>
                    <a href="#product-management" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-box-seam"></i>
                        Product Management
                    </a>
                </li>
                <li>
                    <a href="#order-management" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-cart3"></i>
                        Order Management
                    </a>
                </li>
                <li>
                    <a href="#user-management" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-person-gear"></i>
                        User Management
                    </a>
                </li>
                <li>
                    <a href="#reports" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-graph-up"></i>
                        Reports & Analytics
                    </a>
                </li>
                <li>
                    <a href="#settings" class="nav-link" data-bs-toggle="tab">
                        <i class="bi bi-gear"></i>
                        System Settings
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://via.placeholder.com/40" alt="Admin" width="32" height="32" class="rounded-circle me-2">
                    <strong>Admin</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Sign out</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <div class="tab-content">
                <!-- Dashboard Tab -->
                <div class="tab-pane fade show active" id="dashboard">
                    <h2 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard Overview</h2>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card bg-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Sales</h6>
                                            <h3 class="mb-0">$24,780</h3>
                                            <small class="text-success"><i class="bi bi-arrow-up"></i> 12.5% from last month</small>
                                        </div>
                                        <div class="icon bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card bg-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Orders</h6>
                                            <h3 class="mb-0">1,245</h3>
                                            <small class="text-success"><i class="bi bi-arrow-up"></i> 8.2% from last month</small>
                                        </div>
                                        <div class="icon bg-success bg-opacity-10 text-success">
                                            <i class="bi bi-cart-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card bg-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Active Vendors</h6>
                                            <h3 class="mb-0">48</h3>
                                            <small class="text-success"><i class="bi bi-arrow-up"></i> 3 new this month</small>
                                        </div>
                                        <div class="icon bg-info bg-opacity-10 text-info">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card bg-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Pending Orders</h6>
                                            <h3 class="mb-0">32</h3>
                                            <small class="text-danger"><i class="bi bi-arrow-down"></i> 2 from yesterday</small>
                                        </div>
                                        <div class="icon bg-warning bg-opacity-10 text-warning">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Sales Overview</h5>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                This Month
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="#">Today</a></li>
                                                <li><a class="dropdown-item" href="#">This Week</a></li>
                                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                                <li><a class="dropdown-item" href="#">This Year</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="chart-container" style="height: 300px;">
                                        <canvas id="salesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Revenue Sources</h5>
                                    <div class="chart-container" style="height: 300px;">
                                        <canvas id="revenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">Top Vendors</h5>
                                        <a href="#vendor-management" class="btn btn-sm btn-outline-primary">View All</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Vendor</th>
                                                    <th>Products</th>
                                                    <th>Sales</th>
                                                    <th>Rating</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/40" alt="Vendor" class="vendor-avatar me-2">
                                                            <span>Fresh Foods Co.</span>
                                                        </div>
                                                    </td>
                                                    <td>128</td>
                                                    <td>$5,420</td>
                                                    <td><span class="badge bg-success">4.8 <i class="bi bi-star-fill"></i></span></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/40" alt="Vendor" class="vendor-avatar me-2">
                                                            <span>Tech Gadgets</span>
                                                        </div>
                                                    </td>
                                                    <td>76</td>
                                                    <td>$4,850</td>
                                                    <td><span class="badge bg-success">4.6 <i class="bi bi-star-fill"></i></span></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/40" alt="Vendor" class="vendor-avatar me-2">
                                                            <span>Fashion Hub</span>
                                                        </div>
                                                    </td>
                                                    <td>92</td>
                                                    <td>$3,970</td>
                                                    <td><span class="badge bg-success">4.5 <i class="bi bi-star-fill"></i></span></td>
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
                                        <h5 class="card-title mb-0">Recent Orders</h5>
                                        <a href="#order-management" class="btn btn-sm btn-outline-primary">View All</a>
                                    </div>
                                    <div class="scrollable-section">
                                        <div class="list-group list-group-flush">
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">#ORD-0245</h6>
                                                    <small class="text-success">Completed</small>
                                                </div>
                                                <p class="mb-1">John Smith - $124.50</p>
                                                <small class="text-muted">Today, 10:45 AM</small>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">#ORD-0244</h6>
                                                    <small class="text-primary">Shipped</small>
                                                </div>
                                                <p class="mb-1">Sarah Johnson - $89.99</p>
                                                <small class="text-muted">Today, 9:30 AM</small>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">#ORD-0243</h6>
                                                    <small class="text-warning">Processing</small>
                                                </div>
                                                <p class="mb-1">Michael Brown - $210.00</p>
                                                <small class="text-muted">Today, 8:15 AM</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Management Tab -->
                <div class="tab-pane fade" id="vendor-management">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="bi bi-people me-2"></i>Vendor Management</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Delivery Man
                        </button>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Vendor Approval Queue</h5>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary">Export</button>
                                    <button class="btn btn-sm btn-outline-secondary">Filter</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="vendorsTable">
                                    <thead>
                                        <tr>
                                            <th>Vendor</th>
                                            <th>Business Name</th>
                                            <th>Contact</th>
                                            <th>Registration Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" alt="Vendor" class="vendor-avatar me-2">
                                                    <span>John Smith</span>
                                                </div>
                                            </td>
                                            <td>Smith Electronics</td>
                                            <td>john@example.com<br>(555) 123-4567</td>
                                            <td>2023-05-10</td>
                                            <td><span class="status-badge badge-pending">Pending Review</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-success me-1">Approve</button>
                                                <button class="btn btn-sm btn-danger">Reject</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" alt="Vendor" class="vendor-avatar me-2">
                                                    <span>Maria Garcia</span>
                                                </div>
                                            </td>
                                            <td>Garcia Fashion</td>
                                            <td>maria@example.com<br>(555) 234-5678</td>
                                            <td>2023-05-12</td>
                                            <td><span class="status-badge badge-approved">Approved</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning me-1">Suspend</button>
                                                <button class="btn btn-sm btn-info">Message</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" alt="Vendor" class="vendor-avatar me-2">
                                                    <span>David Kim</span>
                                                </div>
                                            </td>
                                            <td>Kim's Kitchen</td>
                                            <td>david@example.com<br>(555) 345-6789</td>
                                            <td>2023-05-15</td>
                                            <td><span class="status-badge badge-suspended">Suspended</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-success me-1">Reinstate</button>
                                                <button class="btn btn-sm btn-info">Message</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" alt="Vendor" class="vendor-avatar me-2">
                                                    <span>Sarah Johnson</span>
                                                </div>
                                            </td>
                                            <td>Johnson Home Goods</td>
                                            <td>sarah@example.com<br>(555) 456-7890</td>
                                            <td>2023-05-18</td>
                                            <td><span class="status-badge badge-approved">Approved</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning me-1">Suspend</button>
                                                <button class="btn btn-sm btn-info">Message</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Vendor Status Distribution</h5>
                                    <div class="chart-container" style="height: 250px;">
                                        <canvas id="vendorStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">New Vendor Registrations</h5>
                                    <div class="chart-container" style="height: 250px;">
                                        <canvas id="vendorRegistrationsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Management Tab -->
                <div class="tab-pane fade" id="product-management">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="bi bi-box-seam me-2"></i>Product Management</h2>
                        <div>
                            <button class="btn btn-primary me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add Product
                            </button>
                            <button class="btn btn-outline-secondary">
                                <i class="bi bi-filter me-1"></i> Filters
                            </button>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-4" id="product-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="all-products-tab" data-bs-toggle="pill" data-bs-target="#all-products" type="button">All Products</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pending-review-tab" data-bs-toggle="pill" data-bs-target="#pending-review" type="button">Pending Review</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reported-items-tab" data-bs-toggle="pill" data-bs-target="#reported-items" type="button">Reported Items</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="banned-products-tab" data-bs-toggle="pill" data-bs-target="#banned-products" type="button">Banned Products</button>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="product-tabContent">
                                <div class="tab-pane fade show active" id="all-products">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="productsTable">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Vendor</th>
                                                    <th>Category</th>
                                                    <th>Price</th>
                                                    <th>Stock</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/40" alt="Product" class="vendor-avatar me-2">
                                                            <span>Wireless Headphones</span>
                                                        </div>
                                                    </td>
                                                    <td>Tech Gadgets</td>
                                                    <td>Electronics</td>
                                                    <td>$79.99</td>
                                                    <td>45</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary me-1">Edit</button>
                                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/40" alt="Product" class="vendor-avatar me-2">
                                                            <span>Organic Coffee</span>
                                                        </div>
                                                    </td>
                                                    <td>Fresh Foods Co.</td>
                                                    <td>Groceries</td>
                                                    <td>$12.99</td>
                                                    <td>120</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary me-1">Edit</button>
                                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/40" alt="Product" class="vendor-avatar me-2">
                                                            <span>Leather Wallet</span>
                                                        </div>
                                                    </td>
                                                    <td>Fashion Hub</td>
                                                    <td>Accessories</td>
                                                    <td>$34.50</td>
                                                    <td>28</td>
                                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-success me-1">Approve</button>
                                                        <button class="btn btn-sm btn-outline-danger">Reject</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/40" alt="Product" class="vendor-avatar me-2">
                                                            <span>Counterfeit Watch</span>
                                                        </div>
                                                    </td>
                                                    <td>Unverified Seller</td>
                                                    <td>Accessories</td>
                                                    <td>$199.99</td>
                                                    <td>0</td>
                                                    <td><span class="badge bg-danger">Banned</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-secondary me-1">Review</button>
                                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pending-review">Pending products content...</div>
                                <div class="tab-pane fade" id="reported-items">Reported items content...</div>
                                <div class="tab-pane fade" id="banned-products">Banned products content...</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Product Categories</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-container" style="height: 250px;">
                                        <canvas id="productCategoriesChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="scrollable-section" style="max-height: 250px;">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                Electronics
                                                <span class="badge bg-primary rounded-pill">245</span>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                Fashion
                                                <span class="badge bg-primary rounded-pill">189</span>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                Home & Kitchen
                                                <span class="badge bg-primary rounded-pill">156</span>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                Groceries
                                                <span class="badge bg-primary rounded-pill">132</span>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                Beauty
                                                <span class="badge bg-primary rounded-pill">98</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Management Tab -->
                <div class="tab-pane fade" id="order-management">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="bi bi-cart3 me-2"></i>Order Management</h2>
                        <div>
                            <button class="btn btn-outline-secondary me-2">
                                <i class="bi bi-download me-1"></i> Export
                            </button>
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Week</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Pending</a></li>
                                    <li><a class="dropdown-item" href="#">Shipped</a></li>
                                    <li><a class="dropdown-item" href="#">Delivered</a></li>
                                    <li><a class="dropdown-item" href="#">Cancelled</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="ordersTable">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Vendor</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#ORD-0245</td>
                                            <td>John Smith</td>
                                            <td>Tech Gadgets</td>
                                            <td>2023-05-15</td>
                                            <td>$124.50</td>
                                            <td><span class="badge bg-success">Delivered</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#ORD-0244</td>
                                            <td>Sarah Johnson</td>
                                            <td>Fashion Hub</td>
                                            <td>2023-05-14</td>
                                            <td>$89.99</td>
                                            <td><span class="badge bg-primary">Shipped</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#ORD-0243</td>
                                            <td>Michael Brown</td>
                                            <td>Fresh Foods Co.</td>
                                            <td>2023-05-14</td>
                                            <td>$210.00</td>
                                            <td><span class="badge bg-warning text-dark">Processing</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#ORD-0242</td>
                                            <td>Emily Davis</td>
                                            <td>Home Essentials</td>
                                            <td>2023-05-13</td>
                                            <td>$65.75</td>
                                            <td><span class="badge bg-danger">Cancelled</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Order Status</h5>
                                    <div class="chart-container" style="height: 300px;">
                                        <canvas id="orderStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Delivery Performance</h5>
                                    <div class="chart-container" style="height: 300px;">
                                        <canvas id="deliveryPerformanceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Management Tab -->
                <div class="tab-pane fade" id="user-management">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="bi bi-person-gear me-2"></i>User Management</h2>
                        <button class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Add User
                        </button>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Customer Accounts</h5>
                                <div class="input-group" style="width: 300px;">
                                    <input type="text" class="form-control" placeholder="Search users...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="usersTable">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Joined</th>
                                            <th>Orders</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" alt="User" class="vendor-avatar me-2">
                                                    <span>John Smith</span>
                                                </div>
                                            </td>
                                            <td>john@example.com</td>
                                            <td>(555) 123-4567</td>
                                            <td>2023-01-15</td>
                                            <td>12</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-1">Edit</button>
                                                <button class="btn btn-sm btn-outline-danger">Ban</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" alt="User" class="vendor-avatar me-2">
                                                    <span>Sarah Johnson</span>
                                                </div>
                                            </td>
                                            <td>sarah@example.com</td>
                                            <td>(555) 234-5678</td>
                                            <td>2023-02-20</td>
                                            <td>8</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-1">Edit</button>
                                                <button class="btn btn-sm btn-outline-danger">Ban</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" alt="User" class="vendor-avatar me-2">
                                                    <span>Michael Brown</span>
                                                </div>
                                            </td>
                                            <td>michael@example.com</td>
                                            <td>(555) 345-6789</td>
                                            <td>2023-03-05</td>
                                            <td>5</td>
                                            <td><span class="badge bg-warning text-dark">Suspended</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success me-1">Activate</button>
                                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/40" alt="User" class="vendor-avatar me-2">
                                                    <span>Emily Davis</span>
                                                </div>
                                            </td>
                                            <td>emily@example.com</td>
                                            <td>(555) 456-7890</td>
                                            <td>2023-03-18</td>
                                            <td>3</td>
                                            <td><span class="badge bg-danger">Banned</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-secondary me-1">Review</button>
                                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Customer Complaints</h5>
                                    <div class="scrollable-section" style="max-height: 300px;">
                                        <div class="list-group list-group-flush">
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">Late Delivery</h6>
                                                    <small class="text-muted">Today, 10:30 AM</small>
                                                </div>
                                                <p class="mb-1">Order #ORD-0245 was supposed to arrive yesterday...</p>
                                                <small>From: John Smith</small>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">Wrong Item Received</h6>
                                                    <small class="text-muted">Today, 9:15 AM</small>
                                                </div>
                                                <p class="mb-1">Received blue shirt instead of red one in order #ORD-0238</p>
                                                <small>From: Sarah Johnson</small>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">Damaged Product</h6>
                                                    <small class="text-muted">Yesterday, 4:45 PM</small>
                                                </div>
                                                <p class="mb-1">The glass arrived broken in order #ORD-0235</p>
                                                <small>From: Michael Brown</small>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">Vendor Not Responding</h6>
                                                    <small class="text-muted">Yesterday, 2:30 PM</small>
                                                </div>
                                                <p class="mb-1">No response from vendor about refund request for #ORD-0231</p>
                                                <small>From: Emily Davis</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">User Signups</h5>
                                    <div class="chart-container" style="height: 300px;">
                                        <canvas id="userSignupsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports & Analytics Tab -->
                <div class="tab-pane fade" id="reports">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="bi bi-graph-up me-2"></i>Reports & Analytics</h2>
                        <div>
                            <button class="btn btn-primary me-2">
                                <i class="bi bi-download me-1"></i> Export Report
                            </button>
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-calendar me-1"></i> Date Range
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Week</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Quarter</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Custom Range</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Sales Performance</h5>
                                    <div class="chart-container" style="height: 400px;">
                                        <canvas id="salesPerformanceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Key Metrics</h5>
                                    <div class="mb-4">
                                        <h6 class="text-muted mb-2">Total Revenue</h6>
                                        <h3>$58,420</h3>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 15.2% from last month</small>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="text-muted mb-2">Average Order Value</h6>
                                        <h3>$47.85</h3>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 3.5% from last month</small>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="text-muted mb-2">Conversion Rate</h6>
                                        <h3>2.8%</h3>
                                        <small class="text-danger"><i class="bi bi-arrow-down"></i> 0.3% from last month</small>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-2">Customer Retention</h6>
                                        <h3>68%</h3>
                                        <small class="text-success"><i class="bi bi-arrow-up"></i> 5.1% from last month</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Top Selling Products</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Vendor</th>
                                                    <th>Sales</th>
                                                    <th>Revenue</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Wireless Headphones</td>
                                                    <td>Tech Gadgets</td>
                                                    <td>142</td>
                                                    <td>$11,345</td>
                                                </tr>
                                                <tr>
                                                    <td>Organic Coffee</td>
                                                    <td>Fresh Foods Co.</td>
                                                    <td>98</td>
                                                    <td>$9,870</td>
                                                </tr>
                                                <tr>
                                                    <td>Leather Wallet</td>
                                                    <td>Fashion Hub</td>
                                                    <td>76</td>
                                                    <td>$7,650</td>
                                                </tr>
                                                <tr>
                                                    <td>Smart Watch</td>
                                                    <td>Tech Gadgets</td>
                                                    <td>65</td>
                                                    <td>$6,890</td>
                                                </tr>
                                                <tr>
                                                    <td>Yoga Mat</td>
                                                    <td>Sports Gear</td>
                                                    <td>54</td>
                                                    <td>$5,420</td>
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
                                    <h5 class="card-title mb-3">Top Performing Vendors</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Vendor</th>
                                                    <th>Orders</th>
                                                    <th>Revenue</th>
                                                    <th>Rating</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Tech Gadgets</td>
                                                    <td>245</td>
                                                    <td>$24,780</td>
                                                    <td>4.8</td>
                                                </tr>
                                                <tr>
                                                    <td>Fresh Foods Co.</td>
                                                    <td>198</td>
                                                    <td>$18,650</td>
                                                    <td>4.7</td>
                                                </tr>
                                                <tr>
                                                    <td>Fashion Hub</td>
                                                    <td>176</td>
                                                    <td>$15,420</td>
                                                    <td>4.5</td>
                                                </tr>
                                                <tr>
                                                    <td>Home Essentials</td>
                                                    <td>132</td>
                                                    <td>$12,780</td>
                                                    <td>4.3</td>
                                                </tr>
                                                <tr>
                                                    <td>Beauty Care</td>
                                                    <td>98</td>
                                                    <td>$9,650</td>
                                                    <td>4.6</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Settings Tab -->
                <div class="tab-pane fade" id="settings">
                    <h2 class="mb-4"><i class="bi bi-gear me-2"></i>System Settings</h2>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">General Settings</h5>
                                    <form>
                                        <div class="mb-3">
                                            <label class="form-label">Marketplace Name</label>
                                            <input type="text" class="form-control" value="My Marketplace">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Default Currency</label>
                                            <select class="form-select">
                                                <option>US Dollar ($)</option>
                                                <option>Euro (€)</option>
                                                <option>British Pound (£)</option>
                                                <option selected>Canadian Dollar ($)</option>
                                                <option>Japanese Yen (¥)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Timezone</label>
                                            <select class="form-select">
                                                <option>UTC</option>
                                                <option selected>Eastern Time (ET)</option>
                                                <option>Central Time (CT)</option>
                                                <option>Mountain Time (MT)</option>
                                                <option>Pacific Time (PT)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Commission Rate (%)</label>
                                            <input type="number" class="form-control" value="15">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Payment Settings</h5>
                                    <form>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="enablePayments" checked>
                                                <label class="form-check-label" for="enablePayments">Enable Payments</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Payment Methods</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="creditCard" checked>
                                                <label class="form-check-label" for="creditCard">Credit/Debit Card</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="paypal" checked>
                                                <label class="form-check-label" for="paypal">PayPal</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="bankTransfer">
                                                <label class="form-check-label" for="bankTransfer">Bank Transfer</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="cashOnDelivery">
                                                <label class="form-check-label" for="cashOnDelivery">Cash on Delivery</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stripe API Key</label>
                                            <input type="password" class="form-control" value="sk_test_51K...">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">PayPal Client ID</label>
                                            <input type="password" class="form-control" value="AYQ...">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Payment Settings</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Vendor Settings</h5>
                                    <form>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="vendorSignups" checked>
                                                <label class="form-check-label" for="vendorSignups">Allow New Vendor Signups</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="vendorApproval" checked>
                                                <label class="form-check-label" for="vendorApproval">Require Admin Approval</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Minimum Vendor Rating</label>
                                            <input type="range" class="form-range" min="1" max="5" step="0.1" value="3.5" id="vendorRatingRange">
                                            <div class="d-flex justify-content-between">
                                                <small>1.0</small>
                                                <small>2.0</small>
                                                <small>3.0</small>
                                                <small>4.0</small>
                                                <small>5.0</small>
                                            </div>
                                            <div class="text-center mt-1">
                                                <span class="badge bg-primary">Current: 3.5</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Vendor Verification Requirements</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="requireID" checked>
                                                <label class="form-check-label" for="requireID">Government ID</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="requireBank" checked>
                                                <label class="form-check-label" for="requireBank">Bank Account</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="requireTax">
                                                <label class="form-check-label" for="requireTax">Tax Information</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Vendor Settings</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Email Notifications</h5>
                                    <form>
                                        <div class="mb-3">
                                            <label class="form-label">Admin Email</label>
                                            <input type="email" class="form-control" value="admin@marketplace.com">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">SMTP Host</label>
                                            <input type="text" class="form-control" value="smtp.marketplace.com">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">SMTP Port</label>
                                            <input type="number" class="form-control" value="587">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">SMTP Username</label>
                                            <input type="text" class="form-control" value="noreply@marketplace.com">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">SMTP Password</label>
                                            <input type="password" class="form-control" value="********">
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="emailSSL" checked>
                                                <label class="form-check-label" for="emailSSL">Use SSL/TLS</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Email Settings</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Delivery man Modal -->
    <div class="modal fade" id="addVendorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Delivery Man</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('delivery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Vendor</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Initialize DataTables
        $('#vendorsTable').DataTable();
        $('#productsTable').DataTable();
        $('#ordersTable').DataTable();
        $('#usersTable').DataTable();

        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Sales',
                    data: [12000, 19000, 15000, 18000, 22000, 24000, 24780],
                    backgroundColor: 'rgba(108, 92, 231, 0.1)',
                    borderColor: 'rgba(108, 92, 231, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'doughnut',
            data: {
                labels: ['Electronics', 'Fashion', 'Groceries', 'Home', 'Other'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#729979',
                        '#00b894',
                        '#fd79a8',
                        '#fdcb6e',
                        '#a29bfe'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                },
                cutout: '70%'
            }
        });

        // Vendor Status Chart
        const vendorStatusCtx = document.getElementById('vendorStatusChart').getContext('2d');
        const vendorStatusChart = new Chart(vendorStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Suspended'],
                datasets: [{
                    data: [48, 12, 5],
                    backgroundColor: [
                        '#00b894',
                        '#fdcb6e',
                        '#d63031'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                },
                cutout: '70%'
            }
        });

        // Vendor Registrations Chart
        const vendorRegCtx = document.getElementById('vendorRegistrationsChart').getContext('2d');
        const vendorRegChart = new Chart(vendorRegCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'New Vendors',
                    data: [5, 8, 6, 9, 12, 10, 7],
                    backgroundColor: '#729979',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Product Categories Chart
        const productCatCtx = document.getElementById('productCategoriesChart').getContext('2d');
        const productCatChart = new Chart(productCatCtx, {
            type: 'pie',
            data: {
                labels: ['Electronics', 'Fashion', 'Home', 'Groceries', 'Beauty', 'Other'],
                datasets: [{
                    data: [245, 189, 156, 132, 98, 76],
                    backgroundColor: [
                        '#729979',
                        '#00b894',
                        '#fd79a8',
                        '#fdcb6e',
                        '#0984e3',
                        '#a29bfe'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Order Status Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Shipped', 'Processing', 'Cancelled'],
                datasets: [{
                    data: [845, 210, 32, 45],
                    backgroundColor: [
                        '#00b894',
                        '#0984e3',
                        '#fdcb6e',
                        '#d63031'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                },
                cutout: '70%'
            }
        });

        // Delivery Performance Chart
        const deliveryCtx = document.getElementById('deliveryPerformanceChart').getContext('2d');
        const deliveryChart = new Chart(deliveryCtx, {
            type: 'bar',
            data: {
                labels: ['On Time', 'Early', 'Late'],
                datasets: [{
                    label: 'Delivery Performance',
                    data: [78, 15, 7],
                    backgroundColor: [
                        '#00b894',
                        '#0984e3',
                        '#d63031'
                    ],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // User Signups Chart
        const userSignupsCtx = document.getElementById('userSignupsChart').getContext('2d');
        const userSignupsChart = new Chart(userSignupsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'New Users',
                    data: [120, 180, 150, 210, 240, 190, 220],
                    backgroundColor: 'rgba(9, 132, 227, 0.1)',
                    borderColor: 'rgba(9, 132, 227, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Sales Performance Chart
        const salesPerfCtx = document.getElementById('salesPerformanceChart').getContext('2d');
        const salesPerfChart = new Chart(salesPerfCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [
                    {
                        label: 'This Year',
                        data: [12000, 19000, 15000, 18000, 22000, 24000, 24780],
                        backgroundColor: 'rgba(108, 92, 231, 0.1)',
                        borderColor: 'rgba(108, 92, 231, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Last Year',
                        data: [10000, 15000, 13000, 16000, 18000, 21000, 22500],
                        backgroundColor: 'rgba(162, 155, 254, 0.1)',
                        borderColor: 'rgba(162, 155, 254, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        borderDash: [5, 5],
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>