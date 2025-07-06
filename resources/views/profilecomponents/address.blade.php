@if (session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800 shadow-sm">
        {{ session('success') }}
    </div>
@endif

<div class=" mx-auto bg-white p-6 rounded-2xl shadow-md border border-green-200">
    <h2 class="text-2xl font-bold text-green-700 mb-4">🏡 Shipping Address</h2>

    <form action="{{ url('/save-address') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="address" class="block text-sm font-semibold text-green-800 mb-1">Your Address</label>
            <input
                type="text"
                name="address"
                id="address"
                placeholder="e.g., 123 Veggie Street, Salad City"
                class="w-full px-4 py-2 rounded-lg border border-green-300 focus:ring-2 focus:ring-green-400 focus:outline-none shadow-sm"
                value="{{ old('address', auth()->user()->address->address ?? '') }}"
                required
            >
        </div>

        <div>
            <button
                type="submit"
                class="w-full bg-green-600 text-white font-semibold py-2 rounded-lg hover:bg-green-700 transition duration-200 shadow-md"
            >
                Save Address
            </button>
        </div>
    </form>
</div>
