<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\StallSetting;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ShopController extends Controller
{
    public function index(Request $request, $vendor = null)
{
    $query = $request->input('query');
    $category = $request->input('category');
    $sort = $request->input('sort');

    $productsQuery = Product::with('vendor')
        ->where('status', 'active')
        ->where('stock', '>', 0)
        ->whereHas('vendor', function ($q) {
            $q->where('status', '!=', 'Suspended');
        })
        ->when($vendor, function ($queryBuilder, $vendor) {
            return $queryBuilder->whereHas('vendor', function ($q) use ($vendor) {
                $q->where('store_name', $vendor);
            });
        })
        ->when($query, function ($queryBuilder, $searchTerm) {
            if (strtolower($searchTerm) === 'discount') {
                return $queryBuilder->where('discount', '>', 0)
                                    ->orderBy('discount', 'desc');
            }
            return $queryBuilder->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('category', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('vendor', function ($q2) use ($searchTerm) {
                      $q2->where('store_name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        })
        ->when($category, function ($queryBuilder, $category) {
            return $queryBuilder->where('category', $category);
        });

    // Apply sorting based on the sort parameter
    switch ($sort) {
        case 'name_asc':
            $productsQuery->orderBy('name', 'asc');
            break;
        case 'name_desc':
            $productsQuery->orderBy('name', 'desc');
            break;
        case 'price_asc':
            $productsQuery->orderBy('price', 'asc');
            break;

        case 'price_desc':
            $productsQuery->orderBy('price', 'desc');
            break;

        case 'rating_desc':
            $productsQuery->withAvg('ratings', 'rating')->orderBy('ratings_avg_rating', 'desc');
            break;
        default:
            $productsQuery->orderBy('created_at', 'desc');
            break;
    }

    $products = $productsQuery->paginate(12);

    $vendorName = $vendor ? Vendor::where('store_name', $vendor)->first()->store_name : null;

    return view('usersnavs.shop', compact('products', 'query', 'vendorName', 'category', 'sort'));
}








    
    public function show($id)
    {
        // Get the product with its associated vendor
        // Get the product with its associated vendor and hot deal
        $product = Product::with(['vendor', 'hotDeal'])
            ->where('status', 'active')
            ->findOrFail($id);

        // Fetch the cart details for the authenticated user
        $cart = Cart::where('product_id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

        // Check if user can rate the product
        $canRate = false;
        $userRating = 0;

        if (Auth::check()) {
            $canRate = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.user_id', Auth::id())
                ->where('order_items.product_id', $product->id)
                ->where('orders.status', 'Delivered')
                ->exists();

            $userRating = $product->userRating(Auth::id())?->rating ?? 0;
        }
         $stallSetting = StallSetting::where('vendor_id', $product->vendor->vendor_id)->first();
        // Pass data to the view
        return view('usersnavs.item', compact(
            'product',
            'cart',
            'canRate',
            'userRating',
            'stallSetting'
        ));
    }
    















    // Add product to cart
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock.'
            ], 400);
        }

        // Retrieve and validate quantity from the request
        $quantity = $request->input('quantity', 1); // Default to 1 if not provided
        $packSize = $request->input('pack_size', 1); // Default to 1 if not provided
        if (!is_numeric($quantity) || $quantity < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid quantity.'
            ], 400);
        }

        if ($quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Requested quantity exceeds available stock.'
            ], 400);
        }

        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($cartItem) {
            // If the product is already in the cart, update the quantity
            $cartItem->quantity = $quantity; // Set to the new quantity HELLO
            $cartItem->pack_size = $packSize; // Update pack size   
            $cartItem->save();
            $message = 'Cart updated with new quantity.';
        } else {
            // Add a new cart item with the specified quantity
            $cartItem = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
                'pack_size' => $packSize, // Add pack size here
            ]);
            $message = 'Product added to cart.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'quantity' => $cartItem->quantity,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg'),
                'store_name' => $product->vendor ? $product->vendor->store_name : 'Unknown',
                'category' => $product->category,
                'quantity' => $cartItem->quantity,
                'stock' => $product->stock,
                'pack_size' => $cartItem->pack_size, // Return the pack size
            ]
        ]);
    }

}
