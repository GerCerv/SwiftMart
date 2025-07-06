<?php

namespace App\Http\Controllers;

use App\Models\HomeSetting;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeSettingController extends Controller
{
    public function index()
    {
        $settings = HomeSetting::firstOrCreate([]);
        
        // Compute delivered order counts per vendor
        $deliveredCounts = DB::table('orders')
            ->select('order_items.vendor_id', DB::raw('count(distinct orders.id) as delivered_count'))
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'delivered')
            ->groupBy('order_items.vendor_id')
            ->pluck('delivered_count', 'vendor_id');

        // Fetch vendors and attach delivered_count
        $tvendors = Vendor::all()->map(function ($vendor) use ($deliveredCounts) {
            $vendor->delivered_count = $deliveredCounts[$vendor->vendor_id] ?? 0;
            return $vendor;
        });

        // Get selected top vendors
        $selectedVendors = $settings->top_vendors ?? [];

        // Fetch products
        $products = Product::select('id', 'name', 'image', 'price', 'discount')->get();

        return view('admin.home-settings', compact('settings', 'tvendors', 'selectedVendors', 'products'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'carousel_items.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'carousel_items.*.title' => 'nullable|string|max:255',
            'carousel_items.*.link' => 'nullable|string|max:255',
            'top_vendors.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'top_vendors.*.store_name' => 'nullable|string|exists:vendors,store_name',
            'hot_deals.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'hot_deals.*.name' => 'nullable|string|max:255',
            'hot_deals.*.price' => 'nullable|string|max:50',
            'hot_deals.*.discount' => 'nullable|string|max:50',
            'discounts.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'discounts.*.name' => 'nullable|string|max:255',
            'fresh_picks.*.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'fresh_picks.*.name' => 'nullable|string|max:255',
            'fresh_picks.*.price' => 'nullable|string|max:50',
            'fresh_picks.*.badge' => 'nullable|string|max:50',
            'promotional_section.title' => 'nullable|string|max:255',
            'promotional_section.description' => 'nullable|string|max:500',
            'promotional_section.link' => 'nullable|string|max:255',
            'discount_banner.image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'discount_banner.title' => 'nullable|string|max:255',
            'discount_banner.link' => 'nullable|string|max:255',
        ]);

        $settings = HomeSetting::firstOrCreate([]);

        // Compute delivered order counts per vendor
        $deliveredCounts = DB::table('orders')
            ->select('order_items.vendor_id', DB::raw('count(distinct orders.id) as delivered_count'))
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'delivered')
            ->groupBy('order_items.vendor_id')
            ->pluck('delivered_count', 'vendor_id');

        // Map delivered counts to store_name
        $vendorCounts = Vendor::whereIn('vendor_id', array_keys($deliveredCounts->toArray()))
            ->pluck('store_name', 'vendor_id')
            ->mapWithKeys(function ($store_name, $vendor_id) use ($deliveredCounts) {
                return [$store_name => $deliveredCounts[$vendor_id] ?? 0];
            })->toArray();

        $data = [
            'carousel_items' => [],
            'top_vendors' => [],
            'hot_deals' => [],
            'discounts' => [],
            'fresh_picks' => [],
            'promotional_section' => $request->input('promotional_section', []),
            'discount_banner' => $request->input('discount_banner', []),
        ];

        // Handle Carousel Items
        if ($request->has('carousel_items')) {
            foreach ($request->carousel_items as $index => $item) {
                $imagePath = $settings->carousel_items[$index]['image'] ?? null;
                if ($request->hasFile("carousel_items.$index.image")) {
                    if ($imagePath && Storage::exists('public/' . $imagePath)) {
                        Storage::delete('public/' . $imagePath);
                    }
                    $imagePath = $request->file("carousel_items.$index.image")->store('vendors', 'public');
                }
                $data['carousel_items'][] = [
                    'image' => $imagePath,
                    'title' => $item['title'] ?? '',
                    'link' => $item['link'] ?? '#',
                ];
            }
        }

        // Handle Top Vendors
        if ($request->has('top_vendors')) {
            foreach ($request->top_vendors as $index => $vendor) {
                $imagePath = $settings->top_vendors[$index]['image'] ?? null;
                if ($request->hasFile("top_vendors.$index.image")) {
                    if ($imagePath && Storage::exists('public/' . $imagePath)) {
                        Storage::delete('public/' . $imagePath);
                    }
                    $imagePath = $request->file("top_vendors.$index.image")->store('vendors', 'public');
                }
                elseif ($vendor['remove_image'] ?? false && $imagePath) {
                    Storage::delete('public/' . $imagePath);
                    $imagePath = null;
                }
                $storeName = $vendor['store_name'] ?? '';
                $data['top_vendors'][] = [
                    'store_name' => $storeName,
                    'image' => $imagePath,
                    'delivered_count' => $vendorCounts[$storeName] ?? 0,
                ];
            }
        }

       // Handle Hot Deals
        if ($request->has('hot_deals')) {
            $data['hot_deals'] = [];
            
            foreach ($request->hot_deals as $index => $deal) {
                $product = Product::find($deal['product_id'] ?? null);
                
                // Initialize with existing data if available
                $existingDeal = $settings->hot_deals[$index] ?? null;
                $imagePath = $existingDeal['image'] ?? null;
                
                // Handle image upload if provided
                if ($request->hasFile("hot_deals.$index.image")) {
                    // Delete old image if exists
                    if ($imagePath && Storage::exists('public/' . $imagePath)) {
                        Storage::delete('public/' . $imagePath);
                    }
                    // Store new image in products directory
                    $imagePath = $request->file("hot_deals.$index.image")->store('products', 'public');
                }
                // If no new image uploaded but product is selected, use product's image
                elseif ($product && !$imagePath) {
                    $imagePath = $product->image ? 'products/' . $product->image : null;
                }
                
                $data['hot_deals'][] = [
                    'product_id' => $deal['product_id'] ?? null,
                    'image' => $imagePath,
                    'name' => $product->name ?? ($deal['name'] ?? ''),
                    'price' => $deal['price'] ?? ($product->price ?? ''),
                    'discount' => $deal['discount'] ?? ($product->discount ?? ''),
                ];
            }
        }

        // Handle Discounts
        if ($request->has('discounts')) {
            foreach ($request->discounts as $index => $discount) {
                $imagePath = $settings->discounts[$index]['image'] ?? null;
                if ($request->hasFile("discounts.$index.image")) {
                    if ($imagePath && Storage::exists('public/' . $imagePath)) {
                        Storage::delete('public/' . $imagePath);
                    }
                    $imagePath = $request->file("discounts.$index.image")->store('vendors', 'public');
                }
                $data['discounts'][] = [
                    'image' => $imagePath,
                    'name' => $discount['name'] ?? '',
                ];
            }
        }

        // Handle Fresh Picks
        if ($request->has('fresh_picks')) {
            foreach ($request->fresh_picks as $index => $product) {
                $imagePath = $settings->fresh_picks[$index]['image'] ?? null;
                if ($request->hasFile("fresh_picks.$index.image")) {
                    if ($imagePath && Storage::exists('public/' . $imagePath)) {
                        Storage::delete('public/' . $imagePath);
                    }
                    $imagePath = $request->file("fresh_picks.$index.image")->store('vendors', 'public');
                }
                $data['fresh_picks'][] = [
                    'image' => $imagePath,
                    'name' => $product['name'] ?? '',
                    'price' => $product['price'] ?? '',
                    'badge' => $product['badge'] ?? '',
                ];
            }
        }

        // Handle Discount Banner Image
        if ($request->hasFile('discount_banner.image')) {
            $imagePath = $settings->discount_banner['image'] ?? null;
            if ($imagePath && Storage::exists('public/' . $imagePath)) {
                Storage::delete('public/' . $imagePath);
            }
            $data['discount_banner']['image'] = $request->file('discount_banner.image')->store('vendors', 'public');
        }

        $settings->update($data);

        return redirect()->back()->with('success', 'Home settings updated successfully.');
    }
}