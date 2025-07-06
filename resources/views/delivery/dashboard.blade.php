<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SwiftMart Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #4CAF50;
            --light-green: #8BC34A;
            --dark-green: #388E3C;
            --accent-orange: #FF9800;
            --light-bg: #F9F9F9;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1;
            border-radius: 0.5rem;
            width: 150px;
        }
        
        .dropdown:hover .dropdown-content,
        .dropdown:focus-within .dropdown-content {
            display: block;
        }
        
        button:disabled,
        .btn.disabled {
            cursor: not-allowed !important;
            opacity: 0.6;
        }
        
        .status-pending {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .status-out {
            background-color: #CCE5FF;
            color: #004085;
        }
        
        .status-delivered {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        .veg-icon {
            color: var(--primary-green);
        }
        
        .header-bg {
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
        }

        /* Loading spinner styles */
        .loading-spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-green);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 640px) {
            .order-card {
                padding: 1rem;
            }
            
            .item-card {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .item-actions {
                width: 100%;
                justify-content: flex-end;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="min-h-screen">
        <!-- Loading spinner -->
        <div id="loadingSpinner" class="loading-spinner">
            <div class="spinner"></div>
        </div>

        <!-- Mobile-friendly header with vegetable theme -->
        <div class="header-bg text-white shadow-md">
            <div class="container mx-auto px-4 py-4 ">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-truck veg-icon text-2xl" style="color: white;"></i>
                        <h1 class="text-xl font-bold">SwiftMart Delivery</h1>
                    </div>
                    
                    <!-- Mobile-friendly profile dropdown -->
                    <div class="dropdown relative">
                        <button class="flex items-center space-x-2 focus:outline-none">
                            <img src="{{ $deliveryMan->profile_image ? asset('storage/'.$deliveryMan->profile_image) : asset('images/default-profile.jpg') }}" 
                                 alt="{{ $deliveryMan->name }}" 
                                 class="w-8 h-8 rounded-full object-cover border-2 border-white">
                        </button>
                        
                        <div class="dropdown-content mt-2 py-2">
                            <div class="px-4 py-2 text-gray-700">
                                <p class="font-medium">{{ $deliveryMan->name }}</p>
                                <p class="text-sm text-gray-500">Delivery Partner</p>
                            </div>
                            
                            <div class="px-4 py-2 hover:bg-gray-100">
                                <label class="flex items-center cursor-pointer">
                                    <span class="mr-2 text-gray-700"><i class="fas fa-power-off"></i></span>
                                    <input type="checkbox" class="toggle" {{ $deliveryMan->is_active ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Active</span>
                                </label>
                            </div>
                            <a href="{{ route('delivery.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 border-t border-gray-200">
                                <i class="fas fa-home mr-2"></i>Home
                            </a>
                            <!-- Settings Link -->
                            <a href="{{ route('delivery.settings') }}" 
                            class="block px-4 py-2 hover:bg-gray-100 text-gray-700 border-b border-gray-200">
                                <i class="fas fa-cog mr-2"></i>Settings
                            </a>
                            
                            <form action="{{ route('delivery.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-gray-700">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Active status indicator for mobile -->
                <div class="mt-3 flex items-center justify-between bg-white bg-opacity-20 p-2 rounded-lg">
                    <span class="text-sm">Delivery Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                          {{ $deliveryMan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $deliveryMan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <main class="container mx-auto px-4 py-4 ">
            <!-- Orders section -->
            <div class="mb-6 ">
                <h2 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                    <i class="fas fa-clipboard-list veg-icon mr-2"></i>
                    Your Delivery Assignments
                </h2>
                
                @if($orders->isEmpty())
                <div class="text-center py-10 text-gray-500 bg-white rounded-lg shadow-sm ">
                    <i class="fas fa-box-open text-4xl mb-4 veg-icon"></i>
                    <p class="text-gray-600">No orders assigned for delivery</p>
                    <p class="text-sm mt-2 text-gray-500">Check back later or contact support</p>
                </div>
                @else
                <div class="space-y-4 ">
                    @foreach($orders as $order)
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 order-card border-2 border-green-500">
                        <!-- Order header -->
                        <div class="relative flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3">
                            <div class="mb-2 sm:mb-0">
                                <div class="flex items-center">
                                    <i class="fas fa-shopping-basket veg-icon mr-2"></i>
                                    <h3 class="font-bold text-gray-800">Order #{{ $order->id }}</h3>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user mr-1"></i> {{ ucfirst($order->user->name) }}
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i> 
                                    <span class="truncate max-w-[150px] inline-block">{{ $order->shipping_address }} </span>
                                </p>
                            </div>

                            <div class="absolute top-0 right-0">
                                @php
                                    $statusClass = 'status-pending';
                                    if($order->status === 'Out for Delivery') $statusClass = 'status-out';
                                    elseif($order->status === 'Delivered') $statusClass = 'status-delivered';
                                @endphp
                                @if ($order->status === 'Delivered')
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ $statusClass }}">
                                        {{ $order->status }}
                                    </span>
                                @endif
                                
                            </div>
                        </div>


                        <hr class="my-3 border-gray-200" />

                        <!-- Items List -->
                        <h4 class="font-medium mb-2 text-gray-700 flex items-center">
                            <i class="fas fa-carrot veg-icon mr-2"></i>
                            Items to Deliver:
                        </h4>
                        <ul class="space-y-3 mb-4">
                            @php
                                // Group items by vendor ID
                                $groupedItems = $order->items->groupBy('vendor_id');
                            @endphp
                            
                            @foreach($groupedItems as $vendorId => $items)
                            @php
                                $vendor = $items->first()->vendor;
                                $allDelivered = $items->every(fn($item) => $item->status === 'Delivered');
                                $anyOutForDelivery = $items->contains(fn($item) => $item->status === 'Out for Delivery');
                                $totalPrice = $items->sum('total_price');
                            @endphp
                            <li class="border p-3 rounded-lg item-card">
                                <div class="mb-3">
                                    <span class="font-medium text-green-900"><i class="fas fa-store text-green-700 text-l"></i> {{ $vendor->store_name }}</span>
                                </div>
                                
                                <div class="space-y-2 mb-3">
                                    @foreach($items as $item)
                                    <div class="pl-2 border-l-2 border-gray-200">
                                        <div class="font-medium text-gray-800">
                                            {{ ucfirst(strtolower($item->product->name)) }} {{ $item->pack_size }}kg 
                                            <span class="text-gray-500">(x{{ $item->quantity }})</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            <span class="text-xs px-2 py-1 rounded 
                                                @if($item->status === 'Pending') bg-yellow-100 text-yellow-800
                                                @elseif($item->status === 'Out for Delivery') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ $item->status }}
                                            </span>
                                            <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                                ₱{{ number_format($item->total_price, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <div class="flex justify-between items-center border-t pt-3">
                                    <!-- <span class="text-sm font-medium">
                                        Total: ₱{{ number_format($totalPrice, 2) }}
                                    </span> -->
                                    <div class="flex space-x-2 item-actions">
                                        <button 
                                            onclick="declineItems([{{ $items->pluck('id')->join(',') }}], 'Out of Delivery Range')" 
                                            class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg text-sm transition-colors flex items-center"
                                            @if($allDelivered) disabled @endif>
                                            <i class="fas fa-times-circle mr-1"></i> <span class="">Out of Range</span>
                                        </button>
                                        <button 
                                            onclick="updateStatusBulk([{{ $items->pluck('id')->join(',') }}], 'Out for Delivery')" 
                                            class="bg-blue-500 text-white py-1 px-3 rounded-lg hover:bg-blue-600 text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                            @if($allDelivered || $anyOutForDelivery) disabled @endif>
                                            <i class="fas fa-truck mr-1"></i> <span class="">Out for Delivery</span>
                                        </button>
                                        <button 
                                            onclick="updateStatusBulk([{{ $items->pluck('id')->join(',') }}], 'Delivered')" 
                                            class="bg-green-500 text-white py-1 px-3 rounded-lg hover:bg-green-600 text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                            @if($allDelivered) disabled @endif>
                                            <i class="fas fa-check mr-1"></i> <span class="">Delivered</span>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>

                        <!-- Order summary -->
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">₱{{ number_format($order->total - $order->shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Delivery Fee:</span>
                                <span class="font-medium">₱{{ number_format($order->shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Delivery Fee:</span>
                                <span class="font-medium">₱{{ number_format($order->shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold mt-2 pt-2 border-t border-gray-200">
                                <span>Total:</span>
                                <span class="text-green-600">₱{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </main>
    </div>
    
    <script>
        // Function to show/hide loading spinner
        function toggleSpinner(show) {
            const spinner = document.getElementById('loadingSpinner');
            spinner.style.display = show ? 'flex' : 'none';
        }

        function updateStatus(itemId, status) {
            if (confirm('Are you sure you want to update this item to "' + status + '" status?')) {
                toggleSpinner(true);
                fetch(`/delivery/update-status/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status })
                })
                .then(response => response.json())
                .then(data => {
                    toggleSpinner(false);
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error updating status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    toggleSpinner(false);
                    alert('Error updating status: ' + error.message);
                });
            }
        }
        
        function completeOrder(orderId) {
            if (confirm('Mark this entire order as completed?')) {
                toggleSpinner(true);
                fetch(`/delivery/complete-order/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    toggleSpinner(false);
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error completing order: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    toggleSpinner(false);
                    alert('Error completing order: ' + error.message);
                });
            }
        }
        
        // Toggle active status
        document.querySelector('.toggle')?.addEventListener('change', function() {
            if (confirm('Change your availability status?')) {
                toggleSpinner(true);
                fetch('/delivery/update-active-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ is_active: this.checked })
                })
                .then(response => response.json())
                .then(data => {
                    toggleSpinner(false);
                    if (!data.success) {
                        alert('Error updating status');
                        this.checked = !this.checked;
                    } else {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    toggleSpinner(false);
                    alert('Error: ' + error.message);
                    this.checked = !this.checked;
                });
            } else {
                this.checked = !this.checked;
            }
        });

        function updateStatusBulk(itemIds, status) {
            toggleSpinner(true);
            fetch('/update-status-bulk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    item_ids: itemIds,
                    status: status
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                toggleSpinner(false);
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Update failed');
                }
            })
            .catch(error => {
                toggleSpinner(false);
                console.error('Error:', error);
                alert(error.message || 'An error occurred');
            });
        }

        function declineItems(itemIds, reason) {
            if (confirm('Are you sure you want to decline these items? The vendor will be notified.')) {
                toggleSpinner(true);
                fetch('/delivery/decline-items', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_ids: itemIds,
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    toggleSpinner(false);
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    toggleSpinner(false);
                    console.error('Error:', error);
                    alert('An error occurred while processing your request.');
                });
            }
        }
    </script>
</body>
</html>