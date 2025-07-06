<?php

namespace App\Http\Controllers;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = OrderItem::with('product')
            ->whereHas('order', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'status' => ucfirst($item->status),
                    'updated_at' => $item->updated_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count(),
        ]);
    }
}
