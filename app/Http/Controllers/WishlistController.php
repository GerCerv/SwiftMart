<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
    }

    // View user's wishlist
    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->with('product.vendor')->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    // Add product to wishlist
    public function add(Request $request, $productId)
{
    $product = Product::findOrFail($productId);

    if (Auth::user()->hasInWishlist($productId)) {
        return response()->json([
            'success' => false, 
            'message' => 'Product is already in your wishlist.'], 
            400);
    }

    Wishlist::create([
        'user_id' => Auth::id(),
        'product_id' => $productId,
    ]);

    return response()->json([
        'success' => true, 
        'message' => 'Product added to wishlist.',
        'product' => [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image ? asset('storage/products/' . $product->image) : asset('images/emp.jpg')
        ]
    ]);
}

public function remove($productId)
{
    $user = Auth::user();
        $wishlist = $user->wishlist()->where('product_id', $productId)->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['success' => true, 'message' => 'Item removed from wishlist']);
        }

        return response()->json(['success' => false, 'message' => 'Item not found in wishlist'], 404);
}



}
