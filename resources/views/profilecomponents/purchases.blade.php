@php
    use Illuminate\Support\Facades\Auth;

    // Fetch user's orders with related order items and products
    $orders = Auth::user()->orders()->with(['items.product', 'items.vendor'])->orderBy('created_at', 'desc')->get();
@endphp

<div class="purchase-history">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="autoDismissAlert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            setTimeout(() => {
                const alert = bootstrap.Alert.getOrCreateInstance('#autoDismissAlert');
                alert.close();
            }, 5000);
        </script>
    @endif
    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="autoDismissAlert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            setTimeout(() => {
                const alert = bootstrap.Alert.getOrCreateInstance('#autoDismissAlert');
                alert.close();
            }, 5000);
        </script>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-xl font-medium text-gray-700 mt-4">No Purchases Yet</h2>
            <p class="text-gray-500 mt-2">Your purchase history will appear here once you place an order.</p>
            <a href="/products" class="mt-6 inline-block px-6 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors">
                Browse Products
            </a>
        </div>
    @else
        @foreach($orders as $order)
            <div class="bg-white border border-gray-200 p-4 mb-4 rounded-lg">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-start mb-3 mt-2 border-b pb-2">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="text-md font-bold text-green-900">
                                <i class="fa fa-store"></i>
                                <a href="{{ route('vendor.show', $item->vendor->vendor_id) }}" class="text-decoration-none font-bold text-green-900 ">
                                {{ ucfirst($item->vendor->store_name) }}
                                </a>
                            </span>
                            <!-- <a href="#" class="text-xs text-gray-600 hover:text-teal-600">Chat</a>
                            <span class="text-xs text-gray-400">|</span>
                            <a href="{{ route('vendor.show', $item->vendor->vendor_id) }}" class="text-xs text-gray-600 hover:text-teal-600">View Shop</a> -->
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($item->status === 'cancelled')
                                <span class="text-xs font-semibold text-red-600 uppercase">Cancelled</span>
                            @elseif($item->status === 'Delivered')
                                <div class="flex items-center space-x-1">
                                    
                                    @php
                                        $allDelivered = $order->items->every(function ($item) {
                                            return $item->status === 'Delivered';
                                        });
                                    @endphp
                                    @if($allDelivered)
                                        <div class="flex items-center space-x-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            <span class="text-xs font-semibold text-teal-600 uppercase">Completed</span>
                                        </div> 
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-teal-600 uppercase">Delivered</span>
                                    @endif
                                    
                                </div>
                            
                            @else
                                <span class="text-xs font-semibold text-yellow-600 uppercase">{{ ucfirst($item->status) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 mb-3">
                        <img src="{{ $item->product->image ? asset('storage/products/' . $item->product->image) : asset('images/emp.jpg') }}" 
                             class="w-24 h-24 object-cover border border-gray-200 rounded">
                        <div class="flex-1">
                            <div class="text-md text-green-900 font-bold">
                                {{ ucfirst(strtolower($item->product->name)) }} 
                                @if($item->discount > 0)
                                    <span class="bg-pink-600 text-white text-xs font-semibold px-1 py-1 shadow-lg ml-2 rounded">
                                        {{ $item->product->discount }}% OFF
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-600 mt-1">Variation: {{ $item->pack_size }}kg Pack</div>
                            <div class="text-xs text-gray-600">Category: {{ $item->product->category }}</div>
                            <div class="text-xs text-gray-600">Quantity: x{{ $item->quantity }}</div>
                            @php
                                $packSize = $item->pack_size ?? 1;
                                $basePrice = $item->unit_price * $packSize;
                                $quantity = $item->quantity;
                                $discount = $item->discount;

                                $originalTotal = $basePrice * $quantity;
                                $discountedTotal = $originalTotal * (1 - ($discount / 100));
                            @endphp

                            @if($discount > 0)
                                <div class="text-md font-bold text-green-600 mt-1 text-end">
                                    ₱ {{ number_format($discountedTotal, 2) }}
                                </div>
                                <div class="text-xs font-bold text-gray-500 mt-1 text-end line-through">
                                    ₱ {{ number_format($originalTotal, 2) }}
                                </div>
                            @else
                                <div class="text-md font-bold text-red-600 mt-1 text-end">
                                    ₱ {{ number_format($originalTotal, 2) }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="border-b mb-3"></div>

                    <!-- Complaint Form for Delivered Items -->
                    @if($item->status === 'Delivered')
                        <div class="mt-3">
                            <details class="border rounded p-2">
                                <summary class="font-medium cursor-pointer text-sm text-yellow-700">
                                    File Complaint About This Product
                                </summary>
                                
                                <form method="POST" action="{{ route('complaints.store') }}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                    <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                    <input type="hidden" name="store_name" value="{{ $item->vendor->store_name }}">
                                    
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium mb-1">Complaint Details *</label>
                                        <textarea name="message" class="w-full border rounded p-2 text-sm" rows="3" 
                                                placeholder="Describe your issue with this product..." required></textarea>
                                    </div>
                                    
                                    <button type="submit" class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                                        Submit Complaint
                                    </button>
                                    
                                </form>
                            </details>
                        </div>
                    @endif
                @endforeach
                                        
                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                    <div>
                        @if($order->status === 'cancelled')
                            <span class="text-xs text-gray-600">Cancelled by you</span>
                        @else
                            
                        @endif
                    </div>

                    <div class="flex items-center space-x-5">
                        <div class="flex flex-col text-right">
                            <div class="text-xs"> 
                                Shipping Fee: ₱{{ number_format($order->shipping, 2) }}
                            </div>
                            <div class="text-md font-bold text-red-600">
                                Order Total: ₱{{ number_format($order->total, 2) }}
                            </div>
                        </div>
                        <a href="#" 
                           class="px-4 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 no-underline">
                            Buy Again
                        </a>
                        @if($order->status === 'cancelled')
                            <a href="#" class="text-xs text-gray-600 hover:text-teal-600">
                                View Cancellation Details
                            </a>
                        @endif
                        <!-- <a href="#" 
                           class="text-xs px-3 py-1 bg-white-500 text-black text-sm rounded border border-gray-400 hover:bg-gray-200 no-underline">
                            Contact Seller
                        </a> -->
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<style>
    .purchase-history {
        font-family: 'Arial', sans-serif;
    }
    .purchase-history .bg-white {
        transition: box-shadow 0.2s ease;
    }
    .purchase-history .bg-white:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    /* Style for details/summary */
    details {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
    }
    summary {
        padding: 0.5rem;
        outline: none;
    }
    summary::-webkit-details-marker {
        color: #b45309;
    }
    summary::marker {
        color: #b45309;
    }
    .form-label {
        font-size: 0.875rem;
    }
    .form-control {
        font-size: 0.875rem;
    }
    @media (max-width: 640px) {
        .purchase-history .text-sm {
            font-size: 0.75rem;
        }
        .purchase-history .text-xs {
            font-size: 0.65rem;
        }
        .purchase-history img {
            width: 48px;
            height: 48px;
        }
        .purchase-history .px-4 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        .purchase-history .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
    }
</style>