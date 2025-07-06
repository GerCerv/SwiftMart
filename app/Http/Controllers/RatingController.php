<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|between:1,5'
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Verify user has a delivered order
        $canRate = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.user_id', $userId)
            ->where('order_items.product_id', $productId)
            ->where('orders.status', 'Delivered')
            ->exists();

        if (!$canRate) {
            return response()->json(['success' => false, 'message' => 'You can only rate delivered products.']);
        }

        // Save or update rating
        Rating::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $productId],
            ['rating' => $request->rating]
        );

        // Get updated counts
        $product = Product::find($productId);

        return response()->json([
            'success' => true,
            'message' => 'Rating saved successfully',
            'ratingCount' => $product->ratings()->count(),
            'soldCount' => $product->soldCount()
        ]);
    }
}