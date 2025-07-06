<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryMan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\DeclinedOrderItem;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;
use App\Mail\ItemDeclinedMail;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:delivery_men',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('delivery_images', 'public');
        }

        $deliveryMan = DeliveryMan::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'profile_image' => $imagePath,
            'is_active' => true,
        ]);
        Mail::to($deliveryMan->email)->send(new \App\Mail\DeliveryManApproved($deliveryMan));
        return redirect()->back()->with('success', 'Delivery man added successfully.');
    }



    public function showLoginForm()
    {
        return view('delivery.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('delivery')->attempt($credentials)) {
            return redirect()->route('delivery.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('delivery')->logout();
        return redirect()->route('delivery.login');
    }

    

    public function dashboard()
{
    $deliveryMan = Auth::guard('delivery')->user();

    if (!$deliveryMan) {
            return redirect()->route('delivery.login')->with('error', 'Please log in first.');
        }

    $orders = Order::whereHas('items', function ($query) use ($deliveryMan) {
        $query->where('delivery_man_id', $deliveryMan->id)
              ->whereIn('status', ['Ready for Pickup', 'Out for Delivery'])
              ->whereDoesntHave('declinedOrderItem', function ($q) use ($deliveryMan) {
                  $q->where('status', 'Declined')
                    ->where('delivery_man_id', $deliveryMan->id); // ✅ only check if *this* delivery man declined
              });
    })
    ->with([
        'items' => function ($query) use ($deliveryMan) {
            $query->where('delivery_man_id', $deliveryMan->id)
                  ->whereIn('status', ['Ready for Pickup', 'Out for Delivery'])
                  ->whereDoesntHave('declinedOrderItem', function ($q) use ($deliveryMan) {
                      $q->where('status', 'Declined')
                        ->where('delivery_man_id', $deliveryMan->id); // ✅ same here
                  });
        },
        'items.product',
        'user'
    ])
    ->orderBy('created_at', 'desc')
    ->get();

    return view('delivery.dashboard', compact('deliveryMan', 'orders'));
}




    public function updateStatus(Request $request, $itemId)
{
    $deliveryMan = Auth::guard('delivery')->user();
    $orderItem = OrderItem::where('id', $itemId)->with('order')->first();

    if (!$orderItem || $orderItem->order->delivery_man_id !== $deliveryMan->id) {
        return response()->json(['success' => false, 'message' => 'Unauthorized or item not found'], 403);
    }

    $validated = $request->validate([
        'status' => 'required|in:Out for Delivery,Delivered',
    ]);

    $orderItem->status = $validated['status'];
    $orderItem->save();

    // Check if all OrderItems are Delivered
    $order = $orderItem->order;
    if ($order->items->every(fn($item) => $item->status === 'Delivered')) {
        $order->status = 'Delivered';
        $order->save();
    }

    try {
        Mail::to($order->user->email)->send(new OrderStatusUpdated($order, $validated['status'], $orderItem));
    } catch (\Exception $e) {
        \Log::error("Failed to send email for OrderItem ID: {$itemId}: " . $e->getMessage());
    }

    return response()->json(['success' => true]);
}






    // public function updateStatus(Request $request, Order $order)
    // {
    //     $deliveryMan = Auth::guard('delivery')->user();

    //     if ($order->delivery_man_id !== $deliveryMan->id) {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    //     }

    //     $validated = $request->validate([
    //         'status' => 'required|in:Out for Delivery,Delivered'
    //     ]);

    //     $previousStatus = $order->status;
    //     $order->update(['status' => $validated['status']]);

    //     // ✅ Update all related order_items table
    //     OrderItem::where('order_id', $order->id)
    //     ->update(['status' => $validated['status']]);

    //     // Send email to user when status is updated
    //     if (in_array($validated['status'], ['Out for Delivery', 'Delivered'])) {
    //         try {
    //             Mail::to($order->user->email)->send(new OrderStatusUpdated($order, $validated['status']));
    //         } catch (\Exception $e) {
    //             \Log::error("Failed to send status email for order {$order->id}: " . $e->getMessage());
    //         }
    //     }

    //     return response()->json(['success' => true]);
    // }


public function updateStatusBulk(Request $request)
{
    $deliveryMan = Auth::guard('delivery')->user();
    $validated = $request->validate([
        'item_ids' => 'required|array',
        'item_ids.*' => 'exists:order_items,id',
        'status' => 'required|in:Out for Delivery,Delivered',
    ]);

    // Get all items with their order relationship
    $orderItems = OrderItem::whereIn('id', $validated['item_ids'])->get();

    // Verify all items are assigned to this delivery man
    $unauthorizedItems = $orderItems->filter(
        fn($item) => $item->delivery_man_id !== $deliveryMan->id
    );

    if ($unauthorizedItems->isNotEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized to update some items'
        ], 403);
    }

    // Update all items
    OrderItem::whereIn('id', $validated['item_ids'])->update([
        'status' => $validated['status'],
    ]);

    // Get unique order IDs affected
    $orderIds = $orderItems->pluck('order_id')->unique();
    
    foreach ($orderIds as $orderId) {
        $order = Order::with('items')->find($orderId);
        
        // Check if all items in order are delivered
        if ($order->items->every(fn($item) => $item->status === 'Delivered')) {
            $order->update(['status' => 'Delivered']);
            
            try {
                Mail::to($order->user->email)
                    ->send(new OrderStatusUpdated($order, 'Delivered'));
            } catch (\Exception $e) {
                \Log::error("Failed to send email for Order ID: {$orderId}: " . $e->getMessage());
            }
        } elseif ($validated['status'] === 'Out for Delivery') {
            try {
                Mail::to($order->user->email)
                    ->send(new OrderStatusUpdated($order, 'Out for Delivery'));
            } catch (\Exception $e) {
                \Log::error("Failed to send email for Order ID: {$orderId}: " . $e->getMessage());
            }
        }
    }

    return response()->json(['success' => true]);
}
    

    public function updateActiveStatus(Request $request)
    {
        $deliveryMan = Auth::guard('delivery')->user();
        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $deliveryMan->update(['is_active' => $validated['is_active']]);
        
        return response()->json(['success' => true]);
    }





public function declineItems(Request $request)
{
    $request->validate([
        'item_ids' => 'required|array',
        'item_ids.*' => 'exists:order_items,id',
        'reason' => 'required|string|max:255'
    ]);

    try {
        $items = OrderItem::with(['product', 'vendor'])
            ->whereIn('id', $request->item_ids)
            ->get();

        // Get the authenticated delivery person's name
        $deliveryMan = Auth::guard('delivery')->user();
        $deliveryManName = $deliveryMan->name ?? 'Delivery Personnel';

        foreach ($items as $item) {
            DeclinedOrderItem::create([
                'order_item_id' => $item->id,
                'vendor_id' => $item->vendor_id,
                'product_id' => $item->product_id,
                'delivery_man_id' => Auth::guard('delivery')->id(),
                'reason' => $request->reason
            ]);

            if ($item->vendor && $item->vendor->email) {
                Mail::to($item->vendor->email)
                    ->send(new ItemDeclinedMail(
                        $item->product->name,
                        $request->reason,
                        $deliveryManName
                    ));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Items declined successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to decline items: ' . $e->getMessage()
        ], 500);
    }
}


 public function editdeliveryman()
{
    $deliveryMan = Auth::guard('delivery')->user(); // or however you get the delivery person

    if (!$deliveryMan) {
            return redirect()->route('delivery.login')->with('error', 'Please log in first.');
        }

        $deliveredItems = OrderItem::with(['order.user', 'product', 'vendor'])
        ->where('delivery_man_id', $deliveryMan->id)
        ->where('status', 'Delivered')
        ->paginate(4); // 👈 only 4 items per page

        $declinedItems = OrderItem::with(['order', 'product', 'vendor'])
        ->whereHas('declinedOrderItem', function ($query) use ($deliveryMan) {
            $query->where('status', 'Declined')
                ->where('delivery_man_id', $deliveryMan->id);
        })
        ->paginate(4);


    return view('delivery.delivery_settings', compact('deliveryMan', 'deliveredItems', 'declinedItems'));
}

public function deliverymanupdate(Request $request)
{
    $deliveryMan = Auth::guard('delivery')->user(); // or however you get the delivery person
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:delivery_men,email,'.$deliveryMan->id,
        'phone' => 'required|string|max:20',
        'password' => 'nullable|string|min:8|confirmed',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    if ($request->hasFile('profile_image')) {
        // Delete old image if exists
        if ($deliveryMan->profile_image) {
            Storage::delete($deliveryMan->profile_image);
        }
        $path = $request->file('profile_image')->store('delivery_images', 'public');
        $validated['profile_image'] = $path;
    }
    
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']);
    }
    
    $deliveryMan->update($validated);
    
    return back()->with('success', 'Profile updated successfully!');
}   



    
}

