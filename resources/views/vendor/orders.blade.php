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
        .product-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .customer-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
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
        <h2 class="mb-4"><i class="bi bi-cart3 me-2"></i>Order Management</h2>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-current-filter="{{ request()->query('status', 'all') }}">
                            <i class="bi bi-filter me-1"></i> {{ request()->query('status', 'All Orders') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-filter="all">All Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-filter="Pending">Pending</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="Processing">Processing</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="Ready for Pickup">Ready for Pickup</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="Out for Delivery">Out for Delivery</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="Delivered">Delivered</a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    @if(isset($vendororders) && $vendororders->isEmpty())
                        <div class="alert alert-info">No orders found.</div>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    
                                    <th>Status & Delivery Man</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendororders as $order)
                                    <tr class="order-row" data-order-id="{{ $order['id'] }}">
                                        <td>#{{ $order['id'] }}</td>
                                        <td>{{ $order['created_at']->format('Y-m-d') }}</td>
                                        <td>
                                        <div class="d-flex align-items-center">
                                            
                                            <img src="{{ asset('storage/images/' . ($order['user']->profile->image ?? 'emp.png')) }}" alt="{{ $order['user']->name }}" class="customer-avatar me-2">
                                            <div>
                                                <div class="fw-semibold">{{ ucfirst($order['user']->name) }}</div>
                                                <small class="text-muted">{{ $order['shipping_address'] }}</small>
                                            </div>
                                        </div>
                                        </td>
                                        <td>
                                        <div class="d-flex flex-column">
                                            @foreach($order['items'] as $item)
                                            <div class="d-flex align-items-center mb-2">
                                                @if($item->product->image)
                                                <img src="{{ asset('storage/products/' . $item->product->image) }}" class="product-thumbnail me-2">
                                                @else
                                                <div class="product-thumbnail bg-light d-flex align-items-center justify-content-center me-2">
                                                    <i class="bi bi-box-seam text-muted"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    <div>{{ $item->product->name }} </div>

                                                    <small class="text-muted">
                                                        price: ₱ {{ number_format($item->product->price, 2) }} (x{{ $item->quantity }} {{ $item->pack_size }}kg)
                                                    </small><br>

                                                    <small class="text-muted">
                                                        discount: {{ (int)$item->product->discount }}% OFF
                                                    </small><br>

                                                    <small class="text-muted">
                                                        status: {{ $item->status ?? '-' }}
                                                    </small>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                         </td>
                                        <td>₱{{ number_format($order['total'], 2) }}</td>
                                        
                                        <td>
                                            <div class="order-status">
                                                <form class="update-status-form" data-order-id="{{ $order['id'] }}">
                                                    @csrf
                                                    
                                                        <select name="status" class="form-control status-select" data-item-id="{{ $item->id }}">
                                                            <option value="Pending" {{ $item->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="Processing" {{ $item->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                                            <option value="Ready for Pickup" {{ $item->status == 'Ready for Pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                                            <option value="Out for Delivery" {{ $item->status == 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                                            <option value="Delivered" {{ $item->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                            <option value="Cancelled" {{ $item->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                
                                                </form>
                                            </div>
                                            <label>Assign Delivery Man:</label>
                                            <form action="{{ route('vendor.assign.delivery') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                                                <input type="hidden" name="vendor_id" value="{{ $item->vendor_id }}">

                                                <select name="delivery_man_id" onchange="this.form.submit()" class="form-select">
                                                    <option value="" {{ is_null($item->delivery_man_id) ? 'selected' : '' }}>Select</option>

                                                    @foreach($deliveryMen as $man)
                                                        @if($man->is_active)
                                                            @php
                                                                $hasDeclined = $declinedItems->contains(function ($decline) use ($order, $item, $man) {
                                                                    return $decline->orderItem &&
                                                                        $decline->orderItem->order_id === $order['id'] &&
                                                                        $decline->delivery_man_id === $man->id &&
                                                                        $decline->product_id === $item->product_id &&
                                                                        $decline->status === 'declined';
                                                                });
                                                            @endphp

                                                            <option 
                                                                value="{{ $man->id }}" 
                                                                {{ $man->id == $item->delivery_man_id ? 'selected' : '' }}
                                                                {{ $hasDeclined ? 'disabled style=background-color:#f8d7da;' : '' }}
                                                                title="{{ $hasDeclined ? 'This delivery man already declined this item' : '' }}"
                                                            >
                                                                {{ $man->name }} {{ $hasDeclined ? '❌ (Declined)' : '' }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </form>


                                        </td>
                                        
                                                
                                                    
                                                
                                        <td>
                                            
                                            <!-- //DECLINE BY DELIVERY MAN -->
                                            @php
                                                $declinedItem = $declinedItems->first(fn($decline) =>
                                                    $decline->orderItem &&
                                                    $decline->orderItem->order_id === $order['id'] &&
                                                    $decline->delivery_man_id === $item->delivery_man_id &&
                                                    $decline->status === 'declined'
                                                );
                                            @endphp

                                            @if($declinedItem)
                                                <span class="badge d-inline-flex align-items-center bg-danger text-white fw-semibold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.85rem;">
                                                <i class="bi bi-x-circle-fill me-1"></i>
                                                {{ $declinedItem->reason }} 
                                            </span>

                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                    
                                    </tr>
                                @endforeach
                            
                            </tbody>
                            
                        </table>
                        <div class="d-flex justify-content-center mt-3" id="pagination-container">
                            <div class="pagination-loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div id="pagination-links">
                                {{ $vendororders->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                        
                        
                        
                    @endif
                </div>
                        
            </div>
                        
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    
    $(document).ready(function() {
    // Initialize Toastr options
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-bottom-right',
        timeOut: 5000
    };

    $(document).ready(function() {
        $('.status-select').change(function() {
            const form = $(this).closest('form');
            const orderId = form.data('order-id');
            const status = $(this).val();
            
            $.ajax({
                url: `/vendor/orders/${orderId}/update-status`,
                method: 'POST',
                data: {
                    status: status,
                    _token: form.find('input[name="_token"]').val()
                },
                success: function(response) {
                    toastr.success('Status updated successfully');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    toastr.error('Failed to update status');
                    $(this).val($(this).data('previous-value'));
                }
            });
        });

        $('.status-select').each(function() {
            $(this).data('previous-value', $(this).val());
        });
    });

    // Filter handler
    $('.dropdown-item[data-filter]').on('click', function(e) {
        e.preventDefault();
        const status = $(this).data('filter');
        loadOrders(status);
        // Update dropdown button text
        const filterText = $(this).text();
        $(this).closest('.btn-group').find('.dropdown-toggle').text(filterText);
    });

    // Pagination handler
    $(document).on('click', '#pagination-links a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (!url || url === '#') return;
        loadOrders(null, url);
    });

    // Function to load orders
    function loadOrders(status = null, url = '{{ route("vendor.orders") }}') {
        // Get current status filter if not provided
        const currentStatus = status || $('.dropdown-toggle').data('current-filter') || 'all';
        if (status) {
            $('.dropdown-toggle').data('current-filter', status);
        }

        // Show loading spinner
        $('tbody').html('<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
        $('.pagination-loading').show();
        $('#pagination-links').hide();

        // Build query parameters
        const params = new URLSearchParams();
        if (currentStatus !== 'all') {
            params.append('status', currentStatus);
        }

        // Append query parameters to URL if not already present
        const requestUrl = url.includes('?') ? url : `${url}${params.toString() ? '?' + params.toString() : ''}`;

        $.ajax({
            url: requestUrl,
            method: 'GET',
            success: function(response) {
                const $response = $(response);
                const tableBody = $response.find('tbody').html();
                const paginationLinks = $response.find('#pagination-links').html();

                if (!tableBody || !paginationLinks) {
                    toastr.error('Error: Incomplete response from server');
                    return;
                }

                $('tbody').html(tableBody);
                $('#pagination-links').html(paginationLinks);

                $('.pagination-loading').hide();
                $('#pagination-links').show();
            },
            error: function(xhr) {
                let errorMessage = 'Failed to load orders';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
                $('.pagination-loading').hide();
                $('#pagination-links').show();
            }
        });
    }

    // Sidebar toggle
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
});
</script>
<!-- //working -->
</body>
</html>

