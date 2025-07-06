@php
    use Illuminate\Support\Facades\Auth;

    $cartItems = Auth::user()->cart()->with('product.vendor')->get();
    $userName = Auth::user()->name; // Get user name
@endphp

@if($cartItems->isEmpty())
    <div class="bg-white rounded-lg shadow p-6 text-center max-w-2xl mx-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h2 class="text-lg font-medium text-gray-700 mt-3">Your cart is empty</h2>
        <p class="text-gray-500 mt-1 text-sm">Start shopping to add items to your cart</p>
        <a href="/products" class="mt-4 inline-block px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors text-sm">
            Browse Products
        </a>
    </div>
@else
    <form id="cart-form" class="bg-white rounded-lg shadow max-w-full mx-auto">
        @csrf
        <div class="cart-table-container">
            <table class="w-full">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr class="text-left text-gray-600 text-sm">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" id="select-all" class="rounded text-orange-500 focus:ring-orange-500">
                        </th>
                        <th class="px-4 py-3 font-medium">Product</th>
                        <th class="px-4 py-3 font-medium">Unit Price</th>
                        <th class="px-4 py-3 font-medium">Quantity</th>
                        <th class="px-4 py-3 font-medium">Variations</th>
                        <th class="px-4 py-3 font-medium">Total Price</th>
                        <th class="px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <tr class="cart-item md:table-row flex flex-col md:flex-row md:items-center hover:bg-gray-50 transition-colors py-4 md:py-0" data-vendor-id="{{ $item->product->vendor_id ?? '' }}">
                            <td class="px-4 py-2 md:table-cell flex items-center justify-between">
                                <input type="checkbox" class="select-item rounded text-orange-500 focus:ring-orange-500" data-id="{{ $item->product_id }}">
                                <span class="md:hidden text-sm font-medium text-gray-600">Select</span>
                            </td>
                            <td class="px-4 py-2 md:table-cell">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('product.show', $item->product_id) }}">
                                        <img src="{{ $item->product->image ? asset('storage/products/' . $item->product->image) : asset('images/emp.jpg') }}" 
                                             class="w-14 h-14 object-cover rounded-lg border border-gray-200">
                                    </a>
                                    <div>
                                        <div class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-500">Vendor: {{ $item->product->vendor->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">Store: {{ $item->product->vendor->store_name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">Category: {{ $item->product->category }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2 md:table-cell flex justify-between items-center">
                                <span class="md:hidden text-sm font-medium text-gray-600">Price:</span>
                                <div class="text-right md:text-left">
                                    @if ($item->product->discount > 0)
                                        <span class="item-discount bg-pink-600 text-white text-xs font-bold px-1 py-0.5 shadow-lg transform -rotate-12" data-id="{{ $item->product_id }}">
                                            {{ $item->product->discount }}% OFF
                                        </span>
                                    @else
                                        <span style="display: none;" class="item-discount text-error text-red-800 bg-red-200 font-semibold rounded-full px-2 py-1" data-id="{{ $item->product_id }}">
                                            No Discount
                                        </span>
                                    @endif
                                    <div class="text-sm">
                                        ₱<span class="unit-price" data-id="{{ $item->product_id }}">{{ number_format($item->product->price, 2) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2 md:table-cell flex justify-between items-center">
                                <span class="md:hidden text-sm font-medium text-gray-600">Quantity:</span>
                                <div class="flex items-center">
                                    <button type="button" class="decrease px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded-l-md transition-colors" 
                                            data-id="{{ $item->product_id }}">−</button>
                                    <input type="text" class="w-12 text-center quantity border-t border-b border-gray-200 py-1 text-sm" readonly
                                           data-id="{{ $item->product_id }}" value="{{ $item->quantity }}">
                                    <button type="button" class="increase px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded-r-md transition-colors" 
                                            data-id="{{ $item->product_id }}">+</button>
                                </div>
                            </td>
                            <td class="px-4 py-2 md:table-cell flex justify-between items-center">
                                <span class="md:hidden text-sm font-medium text-gray-600">Pack Size:</span>
                                <div>
                                    <button type="button" class="pack-size-btn text-xs {{ $item->pack_size == 1 ? 'active' : '' }}" 
                                            data-id="{{ $item->product_id }}" data-pack-size="1">1kg</button>
                                    <button type="button" class="pack-size-btn text-xs {{ $item->pack_size == 2 ? 'active' : '' }}" 
                                            data-id="{{ $item->product_id }}" data-pack-size="2">2kg</button>
                                    <button type="button" class="pack-size-btn text-xs {{ $item->pack_size == 5 ? 'active' : '' }}" 
                                            data-id="{{ $item->product_id }}" data-pack-size="5">5kg</button>
                                    <input type="hidden" class="pack-size-input" data-id="{{ $item->product_id }}" value="{{ $item->pack_size ?? 1 }}">
                                </div>
                            </td>
                            <td class="px-4 py-2 md:table-cell flex justify-between items-center">
                                <span class="md:hidden text-sm font-medium text-gray-600">Total:</span>
                                <div class="text-sm font-medium">
                                    ₱<span class="item-total" data-id="{{ $item->product_id }}">
                                        {{ number_format($item->product->price * (1 - $item->product->discount / 100) * $item->quantity * ($item->pack_size ?? 1), 2) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-2 md:table-cell flex justify-between items-center">
                                <span class="md:hidden text-sm font-medium text-gray-600">Action:</span>
                                <button type="button" class="delete-btn text-red-600 hover:text-red-800 transition-colors" 
                                        data-id="{{ $item->product_id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Cart Summary -->
        <div class="bg-gray-50 px-4 py-4 border-t border-gray-200">
            <div class="flex flex-col space-y-3">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="select-all-bottom" class="rounded text-orange-500 focus:ring-orange-500">
                    <label for="select-all-bottom" class="text-gray-700 text-sm">Select All</label>
                    <button type="button" class="delete-selected text-red-600 hover:text-red-800 transition-colors flex items-center space-x-1 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Delete Selected</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Subtotal:</span>
                        <span class="font-medium text-sm">₱<span id="subtotal-price">0.00</span></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Shipping:</span>
                        <span class="font-medium text-sm">₱<span id="shipping-fee">0.00</span></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm"> Savings:</span>
                        <span class="font-medium text-green-600 text-sm">₱<span id="total-savings">0.00</span></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-base font-semibold">Total:</span>
                        <span class="text-lg font-bold text-orange-600">₱<span id="total-price">0.00</span></span>
                    </div>
                </div>
                <button type="button" class="checkout-button w-full sm:w-auto" id="open-checkout-modal">
                    Proceed to Checkout
                </button>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg p-5 w-11/12 sm:w-3/4 md:w-1/2 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-semibold text-gray-800">Checkout</h2>
                    <button type="button" id="close-checkout-modal" class="text-gray-600 hover:text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mb-3">
                    <p class="text-xs font-medium text-gray-800">Customer: <span id="checkout-user">{{ $userName }}</span></p>
                    <p class="text-xs font-medium text-gray-800">Address: <span id="checkout-address">{{ auth()->user()->address?->address ?? 'add address' }}</span></p>
                    <p class="text-xs font-medium text-gray-800">Phone: <span id="checkout-phone">{{ auth()->user()->phone ?? 'No Phone' }}</span></p>
                    <p class="text-xs font-medium text-gray-800">Email: <span id="checkout-email">{{ auth()->user()->email ?? 'No Email' }}</span></p>
                </div>
                <div id="checkout-items" class="mb-3 max-h-48 overflow-y-auto">
                    <!-- Selected items will be populated here -->
                </div>
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600 text-sm">Subtotal:</span>
                        <span class="font-medium text-sm">₱<span id="checkout-subtotal">0.00</span></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600 text-sm">Discount:</span>
                        <span class="font-medium text-green-600 text-sm">₱<span id="checkout-discount">0.00</span></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600 text-sm">Shipping:</span>
                        <span class="font-medium text-sm">₱<span id="checkout-shipping">0.00</span></span>
                    </div>
                    <div class="flex justify-between mb-3">
                        <span class="text-base font-semibold">Total:</span>
                        <span class="text-lg font-bold text-orange-600">₱<span id="checkout-total">0.00</span></span>
                    </div>
                    <div class="mb-3 ">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select id="payment_method" name="payment_method" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-green-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                            <option value="cod" selected>Cash on Delivery</option>
                            <option value="self_pickup">Self Pickup</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <button id="send-otp-btn" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition-colors text-sm">
                            Send OTP
                        </button>
                        <div id="otp-section" class="hidden mt-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Enter OTP</label>
                            <input type="text" id="otp-input" class="w-full border rounded p-2 text-sm mb-2" placeholder="Enter 6-digit OTP">
                            <button id="verify-otp-btn" class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 transition-colors text-sm">
                                Verify OTP
                            </button>
                            <div id="otp-message" class="text-xs mt-2 hidden"></div>
                        </div>
                    </div>
                    <button id="confirm-order" class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-orange-600 transition-colors text-sm" disabled>
                        Confirm Order
                    </button>
                </div>
            </div>
        </div>
    </form>
@endif

<style>
    /* Base Styles */
    .cart-table-container {
        overflow-x: auto;
        width: 100%;
    }

    .cart-item {
        transition: background-color 0.2s ease;
    }

    .checkout-button {
        padding: 10px 20px;
        background-color: #f97316;
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .checkout-button:hover {
        background-color: #ea580c;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .checkout-button:active {
        background-color: #c2410c;
    }

    .checkout-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.5);
    }

    .pack-size-btn {
        padding: 4px 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: white;
        color: grey;
        transition: all 0.2s ease;
        margin-right: 4px;
    }

    .pack-size-btn:hover {
        background-color: rgb(138, 255, 191);
    }

    .pack-size-btn.active {
        background-color: #10b981;
        color: white;
        border-color: #10b981;
    }

    input.quantity {
        -moz-appearance: textfield;
    }

    input.quantity::-webkit-outer-spin-button,
    input.quantity::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Modal Styles */
    #checkout-modal {
        z-index: 1100;
    }

    #checkout-modal .max-h-48 {
        scrollbar-width: thin;
        scrollbar-color: #ccc #f7f7f7;
    }

    #checkout-modal .max-h-48::-webkit-scrollbar {
        width: 6px;
    }

    #checkout-modal .max-h-48::-webkit-scrollbar-track {
        background: #f7f7f7;
    }

    #checkout-modal .max-h-48::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .cart-table-container {
            overflow-x: hidden;
        }

        .cart-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 5px;
            
            background-color: #fff;
        }

        .cart-item td {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .cart-item td:last-child {
            border-bottom: none;
        }

        .cart-item td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #4b5563;
            margin-right: 8px;
            min-width: 80px;
        }

        .checkout-button {
            width: 100%;
            padding: 12px;
        }

        .pack-size-btn {
            padding: 4px 8px;
            font-size: 0.75rem;
        }

        .quantity, .increase, .decrease {
            font-size: 0.875rem;
        }

        .cart-summary .grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        #checkout-modal .max-w-lg {
            width: 95%;
            padding: 16px;
        }

        .cart-item td {
            flex-direction: column;
            
            
        }

        .cart-item td::before {
            min-width: 0;
            margin-bottom: 4px;
        }

        .cart-item .flex.items-center {
            width: 100%;
            justify-content: space-between;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
        }

        .cart-summary .text-base {
            font-size: 1rem;
        }

        .cart-summary .text-lg {
            font-size: 1.125rem;
        }
    }

    /* Animations */
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    .animate-fade-out {
        animation: fadeOut 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(10px); }
    }
</style>