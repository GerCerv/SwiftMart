<!-- Checkout Modal -->
<div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Checkout</h2>
            <button id="close-checkout-modal" class="text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="checkout-items" class="mb-4 max-h-64 overflow-y-auto">
            <!-- Selected items will be populated here -->
        </div>
        <div class="border-t border-gray-200 pt-4">
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-medium">₱<span id="checkout-subtotal">0.00</span></span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Shipping:</span>
                <span class="font-medium">₱<span id="checkout-shipping">0.00</span></span>
            </div>
            <div class="flex justify-between mb-4">
                <span class="text-lg font-semibold">Total:</span>
                <span class="text-xl font-bold text-orange-600">₱<span id="checkout-total">0.00</span></span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <div class="flex items-center">
                    <input type="radio" id="cod" name="payment_method" value="cod" checked class="mr-2 text-orange-500 focus:ring-orange-500" disabled>
                    <label for="cod" class="text-sm text-gray-700">Cash on Delivery</label>
                </div>
            </div>
            <button id="confirm-order" class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 transition-colors">
                Confirm Order
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const checkoutModal = document.getElementById("checkout-modal");
        const closeCheckoutModal = document.getElementById("close-checkout-modal");
        const checkoutItems = document.getElementById("checkout-items");
        const checkoutSubtotal = document.getElementById("checkout-subtotal");
        const checkoutShipping = document.getElementById("checkout-shipping");
        const checkoutTotal = document.getElementById("checkout-total");
        const confirmOrder = document.getElementById("confirm-order");
        const checkboxes = document.querySelectorAll(".select-item");

        function populateCheckoutModal() {
            const selectedItems = [...checkboxes].filter(cb => cb.checked).map(cb => cb.dataset.id);
            if (selectedItems.length === 0) {
                alert("Please select at least one item to checkout.");
                return false;
            }

            checkoutItems.innerHTML = "";
            let itemsData = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const row = checkbox.closest("tr");
                    const productId = checkbox.dataset.id;
                    const productName = row.querySelector(".font-medium.text-gray-900").textContent;
                    const unitPrice = parseFloat(row.querySelector(".unit-price").textContent.replace(/[₱,]/g, ""));
                    const quantity = parseInt(row.querySelector(".quantity").value);
                    const packSize = parseInt(row.querySelector(".pack-size-input").value) || 1;
                    const discountText = row.querySelector(".item-discount").textContent;
                    const discountPercentage = discountText.includes("No Discount")
                        ? 0
                        : parseFloat(discountText.match(/\d+(\.\d+)?/)[0]);
                    const total = unitPrice * (1 - discountPercentage / 100) * quantity * packSize;

                    const itemHtml = `
                        <div class="flex justify-between mb-2">
                            <div>
                                <p class="text-sm font-medium text-gray-800">${productName}</p>
                                <p class="text-xs text-gray-500">Pack Size: ${packSize}kg, Quantity: ${quantity}</p>
                            </div>
                            <p class="text-sm font-medium">₱${total.toFixed(2)}</p>
                        </div>
                    `;
                    checkoutItems.insertAdjacentHTML("beforeend", itemHtml);

                    itemsData.push({
                        product_id: productId,
                        quantity: quantity,
                        pack_size: packSize,
                        unit_price: unitPrice,
                        discount: discountPercentage
                    });
                }
            });

            const { subtotal, shipping, total } = calculateTotals();
            checkoutSubtotal.textContent = subtotal.toFixed(2);
            checkoutShipping.textContent = shipping.toFixed(2);
            checkoutTotal.textContent = total.toFixed(2);

            return itemsData;
        }

        // Expose populateCheckoutModal globally so cart.blade.php can call it
        window.populateCheckoutModal = populateCheckoutModal;

        closeCheckoutModal.addEventListener("click", () => {
            checkoutModal.classList.add("hidden");
        });

        confirmOrder.addEventListener("click", () => {
            const itemsData = populateCheckoutModal();
            if (!itemsData) return;

            const { subtotal, shipping, total } = calculateTotals();

            fetch("/checkout", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    items: itemsData,
                    subtotal: subtotal,
                    shipping: shipping,
                    total: total,
                    payment_method: "cod"
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Remove selected items from cart
                    const selectedItems = [...checkboxes].filter(cb => cb.checked).map(cb => cb.dataset.id);
                    selectedItems.forEach(id => {
                        document.querySelector(`.delete-btn[data-id="${id}"]`)?.closest("tr")?.remove();
                        removeFromCartPopup(id);
                    });
                    checkoutModal.classList.add("hidden");
                    calculateTotals();
                    checkIfCartIsEmpty();
                    showToast("Order placed successfully!");
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert("An error occurred. Please try again.");
                console.error(error);
            });
        });
    });
</script>

<style>
    /* Modal Styles */
    #checkout-modal {
        z-index: 1000;
    }

    #checkout-modal .max-w-lg {
        max-height: 80vh;
        overflow-y: auto;
    }

    #checkout-modal .max-h-64 {
        scrollbar-width: thin;
        scrollbar-color: #ccc #f7f7f7;
    }

    #checkout-modal .max-h-64::-webkit-scrollbar {
        width: 6px;
    }

    #checkout-modal .max-h-64::-webkit-scrollbar-track {
        background: #f7f7f7;
    }

    #checkout-modal .max-h-64::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    @media (max-width: 640px) {
        #checkout-modal .max-w-lg {
            width: 90%;
        }

        #checkout-items .text-sm {
            font-size: 0.875rem;
        }

        #checkout-items .text-xs {
            font-size: 0.75rem;
        }
    }
</style>