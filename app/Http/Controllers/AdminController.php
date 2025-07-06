<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotDeal;
use App\Models\Advertisement;
use App\Models\DeliveryMan;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Complaint;
use App\Models\HomeSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorApprovedMail;
use App\Mail\VendorSuspendedMail;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        if ($username === 'admin' && $password === 'admin') {
            session(['admin_authenticated' => true, 'admin_name' => 'Admin']);
            return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
        }

        return back()->with('error', 'Invalid username or password.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_authenticated', 'admin_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully!');
    }

    public function dashboard()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Please log in first.');
        }

        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $sales = OrderItem::where('status', 'Delivered')
        ->whereBetween('updated_at', [$startDate, $endDate]) // Changed to updated_at
        ->select('updated_at', 'total_price', 'vendor_id', 'status')
        ->get();

        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total_price');

        \Log::info('Admin sales query details', [
            'record_count' => $sales->count(),
            'total_sales' => $totalSales,
            'total_revenue' => $totalRevenue,
            'sample_data' => $sales->take(5)->toArray(),
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $endDate->toDateTimeString(),
            'vendor_ids' => $sales->pluck('vendor_id')->unique()->toArray()
        ]);

        $salesData = [];
        $currentDate = $startDate->copy();
        while ($currentDate < $endDate) { // Changed <= to < to exclude the extra day in display
            $dateKey = $currentDate->format('Y-m-d');
            $salesData[$dateKey] = 0;
            $currentDate->addDay();
        }

        foreach ($sales as $item) {
            $date = Carbon::parse($item->updated_at)->format('Y-m-d'); // Changed to updated_at
            if (isset($salesData[$date])) {
                $salesData[$date] += $item->total_price;
            }
        }

        

        $allproducts = Product::latest()->paginate(10);
        $orders = Order::with(['items.product', 'items.vendor', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $topVendors = Vendor::select('vendors.*')
            ->withCount(['orderItems as delivered_count' => function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('status', 'Delivered');
                });
            }])
            ->withSum(['orderItems as total_sales' => function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('status', 'Delivered');
                });
            }], 'total_price')
            ->orderByDesc('delivered_count')
            ->take(5)
            ->get();

        $users = User::withCount([
            'orders as delivered_orders_count' => function($query) {
                $query->where('status', 'Delivered');
            },
            'complaints'
        ])
            ->orderByRaw('email_verified_at IS NOT NULL DESC')
            ->latest()
            ->paginate(10);

        $vendors = Vendor::latest()->paginate(10);
        $deliverymans = DeliveryMan::latest()->paginate(10);
        $totalDeliveredItems = OrderItem::where('status', 'Delivered')->count();
        $totalDeliveredRevenue = Order::where('status', 'Delivered')->sum('total');
        $totalUsers = User::count();
        $totalVendors = Vendor::count();
        $totalDeliveryMen = DeliveryMan::count();
        $totalProducts = Product::count();
        $pendingVendors = Vendor::where('status', 'pending')->count();
        $recentOrders = Order::latest()->take(5)->get();
        $settings = HomeSetting::firstOrCreate([]);
        $deliveredCounts = DB::table('orders')
            ->select('order_items.vendor_id', DB::raw('count(distinct orders.id) as delivered_count'))
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'delivered')
            ->groupBy('order_items.vendor_id')
            ->pluck('delivered_count', 'vendor_id');

        $tvendors = Vendor::all()->map(function ($vendor) use ($deliveredCounts) {
            $vendor->delivered_count = $deliveredCounts[$vendor->vendor_id] ?? 0;
            return $vendor;
        });

        $selectedVendors = $settings->top_vendors ?? [];
        $products = Product::select('id', 'name', 'image', 'price', 'discount')->get();
        $advertisements = Advertisement::latest()->get();
        $hotDeals = HotDeal::orderBy('position')->get();

        return view('admin.dashboard', compact(
            'deliverymans', 'vendors', 'users', 'totalDeliveredItems', 'totalUsers',
            'totalVendors', 'totalDeliveryMen', 'totalProducts', 'pendingVendors',
            'settings', 'tvendors', 'selectedVendors', 'products', 'totalDeliveredRevenue',
            'recentOrders', 'orders', 'topVendors', 'allproducts', 'salesData',
            'totalSales', 'totalRevenue', 'advertisements', 'hotDeals'
        ));
    }

    public function vendorsTab()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }
        $vendors = Vendor::latest()->paginate(10);
        return view('admin.vendors', compact('vendors'));
    }

    public function usersTab()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }
        $users = User::withCount([
            'orders as delivered_orders_count' => function($query) {
                $query->where('status', 'Delivered');
            },
            'complaints'
        ])
            ->orderByRaw('email_verified_at IS NOT NULL DESC')
            ->latest()
            ->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function deliverymansTab()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }
        $deliverymans = DeliveryMan::latest()->paginate(10);
        return view('admin.deliverymans', compact('deliverymans'));
    }

    public function ordersTab()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }
        $Orders = Order::with(['items.product', 'items.vendor', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate(10); // You can change 10 to any number of items per page
        return view('admin.orders', compact('Orders'));
    }

    public function productsTab()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }
        $allproducts = Product::latest()->paginate(10);
        return view('admin.products', compact('allproducts'));
    }


    public function deleteProduct($id)
{
    if (!session('admin_authenticated')) {
        return redirect()->route('admin.login')->with('error', 'Unauthorized');
    }

    try {
        $product = Product::findOrFail($id);
        
        // Delete associated images from storage
        $images = [
            $product->image,
            $product->image2,
            $product->image3,
            $product->image4,
            $product->image5
        ];
        
        foreach ($images as $image) {
            if ($image && Storage::disk('public')->exists('products/' . $image)) {
                Storage::disk('public')->delete('products/' . $image);
            }
        }
        
        $product->delete();
        
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('admin.products')->with('error', 'Error deleting product: ' . $e->getMessage());
    }
}














    public function reportsTab(Request $request)
{
    if (!session('admin_authenticated')) {
        \Log::error('Admin not authenticated');
        return redirect()->route('admin.login')->with('error', 'Unauthorized');
    }

    // Determine date range
    $period = $request->query('period', '30');
    $startDate = now()->subDays(30)->startOfDay(); // e.g., 2025-05-20 00:00:00
    $endDate = now()->addDay()->startOfDay(); // e.g., 2025-06-20 00:00:00 to include today fully

    if ($period === '7') {
        $startDate = now()->subDays(7)->startOfDay();
    } elseif ($period === '90') {
        $startDate = now()->subDays(90)->startOfDay();
    } elseif ($period === 'custom' && $request->start_date && $request->end_date) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->addDay()->startOfDay();
    }

    // Fetch sales data using updated_at
    $sales = OrderItem::where('status', 'Delivered')
        ->whereBetween('updated_at', [$startDate, $endDate]) // Changed to updated_at
        ->select('updated_at', 'total_price', 'vendor_id', 'status', 'product_id', 'quantity', 'order_id')
        ->get();

    $totalSales = $sales->count();
    $totalRevenue = $sales->sum('total_price');

    // Daily sales for chart
    $salesData = [];
    $currentDate = $startDate->copy();
    while ($currentDate < $endDate) { // Exclude the extra day in display
        $dateKey = $currentDate->format('Y-m-d');
        $salesData[$dateKey] = 0;
        $currentDate->addDay();
    }
    foreach ($sales as $item) {
        $date = Carbon::parse($item->updated_at)->format('Y-m-d'); // Changed to updated_at
        if (isset($salesData[$date])) {
            $salesData[$date] += $item->total_price;
        }
    }

    // Log to verify data
    \Log::info('Sales data details', [
        'record_count' => $sales->count(),
        'total_sales' => $totalSales,
        'total_revenue' => $totalRevenue,
        'sample_data' => $sales->map(function ($item) {
            return [
                'updated_at' => $item->updated_at->toDateTimeString(), // Changed to updated_at
                'total_price' => $item->total_price,
            ];
        })->toArray(),
        'start_date' => $startDate->toDateTimeString(),
        'end_date' => $endDate->toDateTimeString(),
        'salesData' => $salesData,
    ]);

    // Top products (using updated_at for consistency)
    $topProducts = OrderItem::where('status', 'Delivered')
        ->whereBetween('updated_at', [$startDate, $endDate]) // Changed to updated_at
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

    return view('admin.reports', compact('salesData', 'totalSales', 'totalRevenue', 'topProducts'));
}

    public function settingsTab()
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }
        $settings = HomeSetting::firstOrCreate([]);
        $deliveredCounts = DB::table('orders')
            ->select('order_items.vendor_id', DB::raw('count(distinct orders.id) as delivered_count'))
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'delivered')
            ->groupBy('order_items.vendor_id')
            ->pluck('delivered_count', 'vendor_id');

        $tvendors = Vendor::all()->map(function ($vendor) use ($deliveredCounts) {
            $vendor->delivered_count = $deliveredCounts[$vendor->vendor_id] ?? 0;
            return $vendor;
        });

        $selectedVendors = $settings->top_vendors ?? [];
        $products = Product::select('id', 'name', 'image', 'price', 'discount')->get();
        $advertisements = Advertisement::latest()->get();
        $hotDeals = HotDeal::orderBy('position')->get();
        return view('admin.settings', compact('settings', 'tvendors', 'selectedVendors', 'products', 'advertisements', 'hotDeals'));
    }

    public function storeDeliveryMan(Request $request)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:delivery_men,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $deliveryMan = new DeliveryMan();
        $deliveryMan->name = $request->name;
        $deliveryMan->email = $request->email;
        $deliveryMan->password = bcrypt($request->password);
        $deliveryMan->phone = $request->phone;

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('delivery_images', 'public');
            $deliveryMan->profile_image = $path;
        }

        $deliveryMan->save();
        return redirect()->route('admin.deliverymans')->with('success', 'Delivery man added successfully!');
    }

    public function destroyDeliveryMan($id)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }

        $deliveryman = DeliveryMan::findOrFail($id);
        if ($deliveryman->profile_image) {
            Storage::disk('public')->delete($deliveryman->profile_image);
        }
        $deliveryman->delete();
        return redirect()->route('admin.deliverymans')->with('success', 'Delivery man deleted successfully!');
    }

    public function updateVendorStatus(Request $request, $id)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,suspended',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->status = $request->status;
        $vendor->save();

        if ($request->status === 'approved') {
            Mail::to($vendor->email)->send(new VendorApprovedMail($vendor));
        }
        if ($request->status === 'suspended') {
            Mail::to($vendor->email)->send(new VendorSuspendedMail($vendor));
        }

        return redirect()->route('admin.vendors')->with('success', 'Status updated successfully!');
    }

    public function destroyVendor($id)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized');
        }

        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return redirect()->route('admin.vendors')->with('success', 'Vendor deleted successfully!');
    }
}