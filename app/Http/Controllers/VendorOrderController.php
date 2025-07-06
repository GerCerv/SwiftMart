<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    public function index()
    {
        $vendorId = Auth::guard('vendor')->id();
        $orders = Order::whereHas('items', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
            ->with(['items.product', 'items.vendor', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vendor.orders', compact('orders'));
    }

    
    public function updateOrderStatus(Request $request, $orderId)
    {
        $vendor = Auth::guard('vendor')->user();
        
        if (!$vendor) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::whereHas('items', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->vendor_id);
        })->findOrFail($orderId);

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!',
            'status' => $order->status,
        ]);
    }


    public function show($id)
    {
        $vendorId = Auth::guard('vendor')->id();
        $order = Order::whereHas('items', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
            ->with(['items.product', 'items.vendor', 'user'])
            ->findOrFail($id);

        return response()->json($order);
    }
}