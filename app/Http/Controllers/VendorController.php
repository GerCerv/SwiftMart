<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use App\Models\StallSetting;
use App\Models\DeliveryMan;
use App\Models\OrderItem;
use App\Models\DeclinedOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class VendorController extends Controller
{
    public function vendorstore($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendorId = Auth::guard('vendor')->id();
        $stallSetting = StallSetting::where('vendor_id', $vendorId)->first();
        return view('usersnavs.vendorstore', compact('vendor', 'stallSetting'));
    }

    public function assignDelivery(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'vendor_id' => 'required|exists:vendors,vendor_id',
            'delivery_man_id' => 'required|exists:delivery_men,id',
        ]);

        $updatedCount = OrderItem::where('order_id', $request->order_id)
            ->where('vendor_id', $request->vendor_id)
            ->update([
                'delivery_man_id' => $request->delivery_man_id,
            ]);

        return back()->with('success', "Delivery man assigned to {$updatedCount} items");
    }

    public function vendorregister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'store_name' => 'required',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $vendor = Vendor::create([
            'name' => $request->name,
            'store_name' => $request->store_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'pending',
        ]);

        $vendor->sendEmailVerificationNotification();

        return redirect()->route('vendor.register')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $vendor = Vendor::findOrFail($id);

        if (!hash_equals($hash, sha1($vendor->getEmailForVerification()))) {
            return redirect()->route('vendor.login')->with('error', 'Invalid verification link');
        }

        if (!$vendor->hasVerifiedEmail()) {
            $vendor->markEmailAsVerified();
            return redirect()->route('vendor.login')->with('success', 'Email verified successfully!');
        }

        return redirect()->route('vendor.login')->with('message', 'Email already verified');
    }

    public function vendorLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('vendor')->attempt($validated)) {
            $vendor = Auth::guard('vendor')->user();

            if (!$vendor->hasVerifiedEmail()) {
                Auth::guard('vendor')->logout();
                return back()->withErrors(['email' => 'Please verify your email before logging in.']);
            }

            if ($vendor->status !== 'approved') {
                Auth::guard('vendor')->logout();
                return back()->withErrors(['email' => 'Your account is still pending approval.']);
            }

            return redirect()->route('vendor.dashboard')
                ->with('success', 'Welcome back, ' . $vendor->name . '!');
        }

        return back()->withErrors(['email' => 'Invalid email or password. Please try again.']);
    }

    public function vendorlogout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/vendor/login')->with('success', 'Logged out successfully!');
    }

    public function dashboard()
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            \Log::error('Vendor not authenticated');
            return redirect()->route('vendor.login')->with('error', 'Please log in first.');
        }

        $vendorsettings = Vendor::where('vendor_id', $vendor->vendor_id)->get();
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $vendorId = $vendor->vendor_id;

        $sales = $vendor->orderItems()
            ->where('status', 'Delivered')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->select('updated_at', 'total_price', 'vendor_id', 'status')
            ->get();

        $totalRevenue = $sales->sum('total_price');
        $totalSales = $sales->count();

        \Log::info('Sales query details', [
            'vendor_id_used' => $vendorId,
            'vendor_table_vendor_id' => $vendor->vendor_id,
            'record_count' => $sales->count(),
            'sample_data' => $sales->take(5)->toArray(),
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $endDate->toDateTimeString(),
            'order_items_vendor_ids' => OrderItem::where('status', 'Delivered')->pluck('vendor_id')->unique()->toArray(),
            'users_table_ids' => \App\Models\User::pluck('id')->toArray()
        ]);

        $salesData = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateKey = $currentDate->format('Y-m-d');
            $salesData[$dateKey] = 0;
            $currentDate->addDay();
        }

        foreach ($sales as $item) {
            $date = Carbon::parse($item->updated_at)->format('Y-m-d');
            $salesData[$date] += $item->total_price;
        }

        

        $deliveryMen = DeliveryMan::where('is_active', true)->get();
        $vendororders = Order::whereHas('items', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->vendor_id);
        })
            ->with(['items.product', 'items.vendor', 'user'])
            ->orderBy('created_at', 'desc')
            ->latest()
            ->paginate(8);

        $pendingCount = OrderItem::where('vendor_id', $vendor->vendor_id)
            ->where('status', 'Pending')
            ->count();

        $processing = OrderItem::where('vendor_id', $vendor->vendor_id)
            ->where('status', 'Processing')
            ->count();

        $ready = OrderItem::where('vendor_id', $vendor->vendor_id)
            ->where('status', 'Ready for Pickup')
            ->count();

        $delivery = OrderItem::where('vendor_id', $vendor->vendor_id)
            ->where('status', 'Out for Delivery')
            ->count();

        $delivered = OrderItem::where('vendor_id', $vendor->vendor_id)
            ->where('status', 'Delivered')
            ->count();

        $allOrdersCount = Order::whereHas('items', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->vendor_id);
        })
            ->count();

        $products = Product::where('vendor_id', $vendor->vendor_id)
            ->latest()
            ->paginate(10);

        $totalActiveProducts = Product::where('vendor_id', $vendor->vendor_id)
            ->where('status', 'Active')
            ->count();

        $newActiveProducts = Product::where('vendor_id', $vendor->vendor_id)
            ->where('stock', 0)
            ->count();

        $declinedItems = DeclinedOrderItem::with(['orderItem.order', 'product', 'deliveryMan'])
            ->whereHas('product', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->vendor_id);
            })
            ->where('status', 'Declined')
            ->latest()
            ->get()
            ->filter(fn($item) => $item->orderItem && $item->orderItem->order_id)
            ->values();
        // Fetch top-selling products (last 30 days) using OrderItem
    $topSellingProducts = OrderItem::where('vendor_id', $vendor->vendor_id)
        ->where('status', 'Delivered')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select('product_id')
        ->selectRaw('SUM(quantity) as total_sold')
        ->groupBy('product_id')
        ->orderByDesc('total_sold') // Use orderBy('total_sold', 'asc') for ascending
        ->take(10)
        ->with(['product' => function ($query) {
            $query->select('id', 'name', 'price');
        }])
        ->get()
        ->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'name' => $item->product ? $item->product->name : 'Unknown Product',
                'price' => $item->product ? $item->product->price : 0,
                'total_sold' => $item->total_sold,
            ];
        });
        return view('vendor.dashboard', [
            'name' => $vendor->name,
            'products' => $products,
            'vendororders' => $vendororders,
            'totalActiveProducts' => $totalActiveProducts,
            'newActiveProducts' => $newActiveProducts,
            'pending' => $pendingCount,
            'processing' => $processing,
            'ready' => $ready,
            'delivery' => $delivery,
            'delivered' => $delivered,
            'allitem' => $allOrdersCount,
            'deliveryMen' => $deliveryMen,
            'totalSales' => $totalSales,
            'salesData' => $salesData,
            'vendorId' => $vendorId,
            'totalRevenue' => $totalRevenue,
            'vendorsettings' => $vendorsettings,
            'vendor' => $vendor,
            'declinedItems' => $declinedItems,
            'topSellingProducts' => $topSellingProducts,
        ]);
    }

    public function productsTab(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return redirect()->route('vendor.login')->with('error', 'Unauthorized');
        }

        $category = $request->query('category', 'all');
        $validCategories = ['Vegetables', 'Fruits', 'Meat', 'Fish', 'Spices'];

        $query = Product::where('vendor_id', $vendor->vendor_id);

        if ($category !== 'all' && in_array($category, $validCategories)) {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(10);

        return view('vendor.products', compact('products'));
    }

    public function ordersTab(Request $request)
{
    $status = $request->query('status', 'all');
    $deliveryMen = DeliveryMan::where('is_active', true)->get();
    $vendor = Auth::guard('vendor')->user();

    if (!$vendor) {
        return redirect()->route('vendor.login')->with('error', 'Unauthorized');
    }

    $validStatuses = ['Pending', 'Processing', 'Ready for Pickup', 'Out for Delivery', 'Delivered', 'Cancelled'];

    $query = OrderItem::where('vendor_id', $vendor->vendor_id)
        ->with(['order.user', 'product']);

    // Apply status filter on OrderItem if provided and valid
    if ($status !== 'all' && in_array($status, $validStatuses)) {
        $query->where('status', $status);
    }

    $vendorOrderItems = $query->get();

    $grouped = $vendorOrderItems->groupBy('order_id')->map(function ($items) {
        $order = $items->first()->order;
        return [
            'id' => $order->id,
            'created_at' => $order->created_at,
            'user' => $order->user,
            'items' => $items,
            'total' => $items->sum(function ($item) {
                $packSize = $item->pack_size ?? 1;
                $discountedPrice = $item->unit_price * $packSize * (1 - ($item->discount / 100));
                return $discountedPrice * $item->quantity;
            }),
            'status' => $order->status,
            'delivery_man_id' => $order->delivery_man_id,
            'shipping_address' => $order->shipping_address,
        ];
    })->sortByDesc('created_at')->values();

    // Manual pagination
    $perPage = 6;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $pagedResults = new LengthAwarePaginator(
        $grouped->forPage($currentPage, $perPage),
        $grouped->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    $declinedItems = DeclinedOrderItem::with(['orderItem.order', 'product', 'deliveryMan'])
        ->whereHas('product', function($query) use ($vendor) {
            $query->where('vendor_id', $vendor->vendor_id);
        })
        ->where('status', 'Declined')
        ->latest()
        ->get()
        ->filter(fn($item) => $item->orderItem && $item->orderItem->order_id)
        ->values();

    return view('vendor.orders', [
        'vendororders' => $pagedResults,
        'deliveryMen' => $deliveryMen,
        'declinedItems' => $declinedItems
    ]);
}

    public function updateOrderStatus(Request $request, $orderId)
{
    $vendor = Auth::guard('vendor')->user();
    
    if (!$vendor) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $validated = $request->validate([
        'status' => 'required|in:Pending,Processing,Ready for Pickup,Out for Delivery,Delivered,Cancelled'
    ]);

    // Get the order with items from this vendor
    $order = Order::whereHas('items', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->vendor_id);
        })
        ->with(['user', 'items' => function($query) use ($vendor) {
            $query->where('vendor_id', $vendor->vendor_id);
        }, 'items.product'])
        ->findOrFail($orderId);

    // Update status for all items from this vendor in the order
    $affectedRows = OrderItem::where('order_id', $orderId)
        ->where('vendor_id', $vendor->vendor_id)
        ->update(['status' => $validated['status']]);
    
        
    $order->update(['status' => $validated['status']]);

    // Send email notification for specific status changes
    if (in_array($validated['status'], ['Processing', 'Ready for Pickup', 'Out for Delivery', 'Delivered'])) {
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdated($order, $validated['status']));
        } catch (\Exception $e) {
            // Log email error but don't fail the request
            \Log::error("Failed to send status email for order {$order->id}: " . $e->getMessage());
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Order items status updated successfully',
        'affected_items' => $affectedRows
    ]);
}

    public function reportsTab(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();
    if (!$vendor) {
        return redirect()->route('vendor.login')->with('error', 'Please log in first.');
    }

    // Determine date range
    $period = $request->query('period', '30');
    $startDate = now()->subDays(30)->startOfDay();
    $endDate = now()->endOfDay();

    if ($period === '7') {
        $startDate = now()->subDays(7)->startOfDay();
    } elseif ($period === '90') {
        $startDate = now()->subDays(90)->startOfDay();
    } elseif ($period === 'custom' && $request->start_date && $request->end_date) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
    }

    // Fetch sales data
    $sales = OrderItem::where('vendor_id', $vendor->vendor_id)
        ->where('status', 'Delivered')
        ->whereBetween('updated_at', [$startDate, $endDate])
        ->get();

    $totalSales = $sales->count();
    $totalRevenue = $sales->sum('total_price');

    // Daily sales for chart
    $salesData = [];
    $currentDate = $startDate->copy();
    while ($currentDate <= $endDate) {
        $dateKey = $currentDate->format('Y-m-d');
        $salesData[$dateKey] = 0;
        $currentDate->addDay();
    }
    foreach ($sales as $item) {
        $date = Carbon::parse($item->updated_at)->format('Y-m-d');
        $salesData[$date] += $item->total_price;
    }

    // Top products
    $topProducts = OrderItem::where('vendor_id', $vendor->vendor_id)
        ->where('status', 'Delivered')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select('product_id')
        ->selectRaw('SUM(quantity) as total_quantity')
        ->selectRaw('SUM(total_price) as total_revenue')
        ->groupBy('product_id')
        ->orderBy('total_quantity', 'desc')
        ->limit(5)
        ->with(['product' => function ($query) {
            $query->select('id', 'name');
        }])
        ->get()
        ->map(function ($item) {
            return (object) [
                'name' => $item->product ? $item->product->name : 'Unknown',
                'total_quantity' => $item->total_quantity,
                'total_revenue' => $item->total_revenue,
            ];
        });

    return view('vendor.reports', compact('totalSales', 'totalRevenue', 'salesData', 'topProducts'));
    }

    public function settingsTab()
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return redirect()->route('vendor.login')->with('error', 'Unauthorized');
        }

        return view('vendor.settings', compact('vendor'));
    }

    public function update(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return redirect()->route('vendor.login')->with('error', 'Unauthorized');
        }

        $stallSettings = StallSetting::where('vendor_id', $vendor->vendor_id)->firstOrNew();

        $request->validate([
            'store_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email,' . $vendor->vendor_id . ',vendor_id',
            'phone' => 'required|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $vendor->update([
            'store_name' => $request->store_name,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->hasFile('profile_image')) {
            if ($stallSettings->profile_image) {
                Storage::delete($stallSettings->profile_image);
            }
            $profileImagePath = $request->file('profile_image')->store('vendor/profile_images', 'public');
            $stallSettings->profile_image = $profileImagePath;
        }

        if ($request->hasFile('background_image')) {
            if ($stallSettings->background_image) {
                Storage::delete($stallSettings->background_image);
            }
            $backgroundImagePath = $request->file('background_image')->store('vendor/background_images', 'public');
            $stallSettings->background_image = $backgroundImagePath;
        }

        $stallSettings->vendor_id = $vendor->vendor_id;
        $stallSettings->facebook = $request->facebook;
        $stallSettings->instagram = $request->instagram;
        $stallSettings->save();

        return redirect()->back()->with('success', 'Stall settings updated successfully!');
    }

    public function approveVendor($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $vendor->status = 'approved';
        $vendor->save();

        return redirect()->route('vendor.dashboard')->with('success', 'Vendor approved successfully!');
    }
}