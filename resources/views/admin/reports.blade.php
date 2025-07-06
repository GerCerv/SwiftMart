<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Reports - SwiftMart Admin</title>
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
            gap: 1px;
            margin-bottom: 0.5rem;
        }

        .product-card .product-details p {
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .product-card .product-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        /* New Report Styles */
        .filter-form {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: #218838;
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

            .filter-form {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.5rem;
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
            <h2 class="mb-4"><i class="bi bi-graph-up me-2"></i>Sales Reports</h2>

            <!-- Filter Form -->
            <div class="filter-form mb-4">
                <form action="{{ route('admin.reports') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3 col-sm-6">
                            <label for="period" class="form-label">Date Range</label>
                            <select name="period" id="period" class="form-select" onchange="toggleCustomRange()">
                                <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="30" {{ request('period') == '30' || !request('period') ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 Days</option>
                                <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 custom-range" style="display: {{ request('period') == 'custom' ? 'block' : 'none' }};">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3 col-sm-6 custom-range" style="display: {{ request('period') == 'custom' ? 'block' : 'none' }};">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1"></i>Apply</button>
                        </div>
                    </div>
                </form>
                
            </div>

            <!-- Sales Summary -->
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card p-3">
                        <h6 class="card-title">Total Sales</h6>
                        <p class="stat-value">{{ $totalSales }}</p>
                        <p class="text-muted mb-0">{{ Str::plural('order', $totalSales) }}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card p-3">
                        <h6 class="card-title">Total Revenue</h6>
                        <p class="stat-value">₱{{ number_format($totalRevenue, 2) }}</p>
                        <p class="text-muted mb-0">After fees</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card p-3">
                        <h6 class="card-title">Average Order</h6>
                        <p class="stat-value">₱{{ $totalSales > 0 ? number_format($totalRevenue / $totalSales, 2) : '0.00' }}</p>
                        <p class="text-muted mb-0">Per order</p>
                    </div>
                </div>
            </div>

            <!-- Sales Chart -->
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
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Products -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="card-title mb-0">Top Products</h6>
                </div>
                <div class="card-body">
                    @if (empty($topProducts))
                        <div class="alert alert-info text-center">
                            No product sales data available for the selected period.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Units Sold</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->total_quantity }}</td>
                                            <td>₱{{ number_format($product->total_revenue, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (!empty($salesData))
        <script>
            window.salesData = @json($salesData);
        </script>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Toggle custom date range
        function toggleCustomRange() {
            const period = document.getElementById('period').value;
            const customFields = document.querySelectorAll('.custom-range');
            customFields.forEach(field => {
                field.style.display = period === 'custom' ? 'block' : 'none';
            });
        }

        // Chart initialization
        document.addEventListener('DOMContentLoaded', function () {
        const salesChartCanvas = document.getElementById('salesChart');
        if (!salesChartCanvas) return;

        let chart = null;
        const salesData = window.salesData || {};
        console.log('Chart Data:', salesData); // Debug log
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

            // Toastr notifications
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
</body>
</html>