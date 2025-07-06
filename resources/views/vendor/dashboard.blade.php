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
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none ">
            <i class="bi bi-shop me-2 text-success" style="font-size: 1.5rem;"></i>
            <span class="fs-4 fw-bold text-green-600">SwiftMart Vendors</span>
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
        <h2 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Vendor Dashboard</h2>

<div class="row mb-4 g-3">
    <div class="col-6 col-md-3">
        <div class="card bg-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Revenue</h6>
                        <h3 class="mb-0">₱ {{ number_format($totalRevenue) }}</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> {{$delivered}} Total Sales </small>
                    </div>
                    <div class="icon bg-success bg-opacity-10 text-success rounded p-2">
                    <i class="bi bi-cash fs-4"></i>
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
                        <h3 class="mb-0">{{$allitem}}</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> {{$delivered}} Delivered Order</small>
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
                        <h3 class="mb-0">{{ $totalActiveProducts }}</h3>
                        <small class="text-{{ $newActiveProducts > 0 ? 'danger' : 'success' }}"><i class="bi bi-arrow-{{ $newActiveProducts > 0 ? 'down' : 'up' }}"></i> {{ $newActiveProducts }} out of stock</small>
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
                        <h6 class="text-muted mb-2">Order Status</h6>
                        <div class="row text-xs">
                            <div class="col-6">
                                <h6 class="text-warning">
                                    <i class="bi bi-hourglass-split"></i> {{$pending}} Pending
                                </h6>
                            </div>
                            <div class="col-6">
                                <h6 class="text-warning">
                                    <i class="bi bi-arrow-repeat"></i> {{$processing}} Processing
                                </h6>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success">
                                    <i class="bi bi-check-circle"></i> {{$ready}} Pickup
                                </h6>
                            </div>
                            <div class="col-6">
                                <h6 class="text-success">
                                    <i class="bi bi-truck"></i> {{$delivery}} Delivery
                                </h6>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Sales Chart -->
<div class="row g-3">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">Sales Over Time</h6>
                <select id="chartType" class="form-select form-select-sm w-auto">
                    <option value="line">Line</option>
                    <option value="bar">Bar</option>
                </select>
            </div>
            <div class="card-body">
                @if (empty($salesData))
                    <div class="alert alert-info text-center">
                        No sales data available for the selected period.
                    </div>
                @else
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                    <a href="{{ route('vendor.orders') }}" class="btn btn-sm btn-outline-success">View All</a>
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
                                
                            </tr>
                        </thead>
                            @foreach($vendororders as $order)
                            <tr class="order-row" data-order-id="{{ $order->id }}">
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    @foreach($order->items as $item)
                                        {{ $item->product->name }} (x{{ $item->quantity }} {{ $item->pack_size }}kg)<br>
                                        <p>{{ (int)$item->product->price }} <small>({{ (int)$item->discount }}%OFF)</small></p>
                                        
                                    @endforeach
                                    
                                </td>
                                <td>₱{{ number_format($order->total, 2) }}</td>
                                <td>{{ $order->status}}</td>
                            @endforeach
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
                    <a href="{{ route('vendor.products') }}" class="btn btn-sm btn-outline-success">View All</a>
                </div>
                <div class="list-group">
                    @if($topSellingProducts->isEmpty())
                        <div class="list-group-item">
                            <p class="mb-0 text-muted">No delivered products in the last 30 days.</p>
                        </div>
                    @else
                        @foreach($topSellingProducts as $product)
                            <a href="{{ route('vendor.products') }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $product['name'] }}</h6>
                                    <small class="text-success">{{ $product['total_sold'] }} sold</small>
                                </div>
                                <p class="mb-1">₱{{ number_format($product['price'], 2) }} per unit</p>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>


@if (!empty($salesData))
    <script>
        window.salesData = @json($salesData);
    </script>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart initialization
    document.addEventListener('DOMContentLoaded', function () {
        const salesChartCanvas = document.getElementById('salesChart');
        if (!salesChartCanvas) return;

        let chart = null;
        const salesData = window.salesData || {};
        const labels = Object.keys(salesData).sort();
        const data = labels.map(date => salesData[date]);

        function renderChart(type = 'line') {
            if (chart) chart.destroy();
            chart = new Chart(salesChartCanvas, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales (₱)',
                        data: data,
                        borderColor: '#28a745',
                        backgroundColor: type === 'line' ? 'rgba(40, 167, 69, 0.1)' : '#28a745',
                        fill: type === 'line',
                        tension: 0.3,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Amount (₱)' }
                        },
                        x: {
                            title: { display: true, text: 'Date' }
                        }
                    },
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: context => '₱ ' + context.raw.toLocaleString()
                            }
                        }
                    }
                }
            });
        }

        renderChart();
        document.getElementById('chartType').addEventListener('change', function () {
            renderChart(this.value);
        });
    });
</script>
</body>
</html>
