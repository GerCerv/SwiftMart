<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    // View user's cart
    public function index()
    {
        $cartItems = Auth::user()->cart()->with('product.vendor')->get();
        return view('cart.index', compact('cartItems'));
    }

    // Add product to cart
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock.'
            ], 400);
        }

        if (Auth::user()->hasInCart($productId)) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already in your cart.'
            ], 400);
        }

        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'quantity' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart.',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg'),
                'store_name' => $product->vendor ? $product->vendor->store_name : 'Unknown',
                'category' => $product->category,
                'quantity' => 1,
                'stock' => $product->stock,
            ]
        ]);
    }

    // Remove product from cart
    public function remove($productId)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->firstOrFail();
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart.'
        ]);
    }

    // Update quantity
    public function updateQuantity(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->firstOrFail();
        $product = Product::findOrFail($productId);

        if ($request->quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Requested quantity exceeds available stock.'
            ], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated.',
            'quantity' => $cartItem->quantity
        ]);
    }
     // Update pack size
     public function updatePackSize(Request $request, $productId)
     {
         $request->validate([
             'pack_size' => 'required|integer|in:1,2,5' // Restrict to valid pack sizes
         ]);
 
         $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->firstOrFail();
         $product = Product::findOrFail($productId);
 
         // Validate stock (assuming pack_size affects stock, e.g., 2kg requires 2 units)
         if ($cartItem->quantity * $request->pack_size > $product->stock) {
             return response()->json([
                 'success' => false,
                 'message' => 'Requested pack size exceeds available stock.'
             ], 400);
         }
 
         $cartItem->update(['pack_size' => $request->pack_size]);
 
         return response()->json([
             'success' => true,
             'message' => 'Pack size updated.',
             'pack_size' => $cartItem->pack_size
         ]);
     }
}