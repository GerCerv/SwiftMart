<div class="py-4">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">Your Wishlist</h4>

    @auth
        @if(auth()->user()->wishlist()->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach(auth()->user()->wishlist()->with('product')->get() as $item)
                    <div class="wishlist-item bg-white rounded-lg shadow-sm p-4 relative border-2 border-transparent"
                         data-wishlist-item="{{ $item->product_id }}">
                        <button class="wishlist-remove-btn absolute top-0 right-0 m-2 text-red-500 hover:text-red-700"
                                data-product-id="{{ $item->product_id }}"
                                title="Remove from Wishlist">
                            <i class="fas fa-trash"></i>
                        </button>
                        <a href="{{ route('product.show', $item->product_id) }}">
                        <img src="{{ $item->product->image ? asset('storage/products/' . $item->product->image) : asset('images/emp.jpg') }}"
                             alt="{{ $item->product->name }}"
                             class="w-full h-48 object-cover rounded-md mb-3">
                        </a>
                        <h3 class="product-name text-lg font-bold text-gray-800">{{ $item->product->name }}</h3>
                        <p class="product-price text-green-600 font-semibold">₱{{ number_format($item->product->price, 2) }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6">
                <img src="{{ asset('images/emp.jpg') }}"
                     alt="Empty Wishlist"
                     class="w-24 h-24 object-contain mx-auto mb-4">
                <p class="text-gray-600 font-medium">Your wishlist is empty!</p>
                <p class="text-sm text-gray-500 mt-2">Add some fresh products to your wishlist.</p>
                <a href="#"
                   class="mt-4 inline-block bg-[#729979] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#5e8064] transition">
                    Explore Products
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-6">
            <img src="{{ asset('images/emp.jpg') }}"
                 alt="Login Required"
                 class="w-24 h-24 object-contain mx-auto mb-4">
            <p class="text-gray-600 font-medium">Please login to view your wishlist!</p>
            <button class="mt-4 bg-[#729979] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#5e8064] transition"
                    onclick="event.preventDefault(); new bootstrap.Modal(document.getElementById('loginModal')).show();">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
        </div>
    @endauth
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Add click event listener to each remove button
    document.querySelectorAll('.wishlist-remove-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent the click event from bubbling up
            const productId = this.getAttribute('data-product-id'); // Get product ID

            // Remove the item from the DOM immediately
            const itemElement = document.querySelector(`[data-wishlist-item="${productId}"]`);
            if (itemElement) {
                itemElement.remove();
            }

            // Optional: Check if the wishlist is now empty and display the empty state
            if (document.querySelectorAll('.wishlist-item').length === 0) {
                document.querySelector('#wishlist-view').innerHTML = `
                    <div class="text-center py-6">
                        <img src="{{ asset('images/emp.jpg') }}" alt="Empty Wishlist" class="w-24 h-24 object-contain mx-auto mb-4">
                        <p class="text-gray-600 font-medium">Your wishlist is empty!</p>
                        <p class="text-sm text-gray-500 mt-2">Add some fresh products to your wishlist.</p>
                        <a href="#" class="mt-4 inline-block bg-[#729979] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#5e8064] transition">
                            Explore Products
                        </a>
                    </div>
                `;
            }
        });
    });
});
</script>
<!-- my reald code  -->