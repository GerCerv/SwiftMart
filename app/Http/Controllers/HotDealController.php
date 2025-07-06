<?php

namespace App\Http\Controllers;

use App\Models\HotDeal;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotDealController extends Controller
{
    public function create()
    {
        $products = Product::active()
        ->orderBy('name', 'asc')
        ->get();
        $hotDeals = HotDeal::latest()->get();
        
        return view('admin.hot-deals', compact('products', 'hotDeals'));
    }

    public function store(Request $request)
{
    $request->validate([
        'left_product_id' => 'nullable|exists:products,id',
        'left_discount' => 'nullable|numeric|min:1|max:100',
        'left_start_date' => [
            'nullable',
            'date',
            function($attribute, $value, $fail) {
                if ($value) {
                    $count = HotDeal::where('start_date', $value)->count();
                    if ($count >= 3) {
                        $fail('You can only have 3 hot deals with the same start date.');
                    }
                }
            },
        ],
        'left_end_date' => 'nullable|date|after:left_start_date',
        'middle_product_id' => 'nullable|exists:products,id',
        'middle_discount' => 'nullable|numeric|min:1|max:100',
        'middle_start_date' => [
            'nullable',
            'date',
            function($attribute, $value, $fail) {
                if ($value) {
                    $count = HotDeal::where('start_date', $value)->count();
                    if ($count >= 3) {
                        $fail('You can only have 3 hot deals with the same start date.');
                    }
                }
            },
        ],
        'middle_end_date' => 'nullable|date|after:middle_start_date',
        'right_product_id' => 'nullable|exists:products,id',
        'right_discount' => 'nullable|numeric|min:1|max:100',
        'right_start_date' => [
            'nullable',
            'date',
            function($attribute, $value, $fail) {
                if ($value) {
                    $count = HotDeal::where('start_date', $value)->count();
                    if ($count >= 3) {
                        $fail('You can only have 3 hot deals with the same start date.');
                    }
                }
            },
        ],
        'right_end_date' => 'nullable|date|after:right_start_date',
    ]);

    foreach (['left', 'middle', 'right'] as $position) {
        if ($request->has("{$position}_product_id") && $request->input("{$position}_product_id")) {
            $now = now();
            $startDate = $request->input("{$position}_start_date");
            $endDate = $request->input("{$position}_end_date");
            
            // Determine initial active status
            $isActive = $startDate && $endDate 
                ? ($startDate <= $now && $endDate >= $now)
                : false;

            HotDeal::updateOrCreate(
                ['position' => $position],
                [
                    'product_id' => $request->input("{$position}_product_id"),
                    'discount_percentage' => $request->input("{$position}_discount"),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'is_active' => $isActive,
                    'image_path' => Product::find($request->input("{$position}_product_id"))->image,
                ]
            );
        }
    }

    return redirect()->back()->with('success', 'Hot deals saved successfully!');
}

    public function edit(HotDeal $hotDeal)
    {
        $hotDeals = HotDeal::with('product')->get();
        $products = Product::all();
        return view('admin.settings', compact('hotDeal', 'hotDeals', 'products'));
    }

    public function update(Request $request, HotDeal $hotDeal)
    {
        $request->validate([
            'editing_position' => 'required|in:left,middle,right',
            'left_product_id' => 'required_if:editing_position,left|exists:products,id',
            'left_discount' => 'required_if:editing_position,left|numeric|min:1|max:100',
            'left_start_date' => 'required_if:editing_position,left|date',
            'left_end_date' => 'required_if:editing_position,left|date|after:left_start_date',
            'middle_product_id' => 'required_if:editing_position,middle|exists:products,id',
            'middle_discount' => 'required_if:editing_position,middle|numeric|min:1|max:100',
            'middle_start_date' => 'required_if:editing_position,middle|date',
            'middle_end_date' => 'required_if:editing_position,middle|date|after:middle_start_date',
            'right_product_id' => 'required_if:editing_position,right|exists:products,id',
            'right_discount' => 'required_if:editing_position,right|numeric|min:1|max:100',
            'right_start_date' => 'required_if:editing_position,right|date',
            'right_end_date' => 'required_if:editing_position,right|date|after:right_start_date',
        ]);

        $position = $request->editing_position;
        $hotDeal->update([
            'product_id' => $request->input("{$position}_product_id"),
            'discount_percentage' => $request->input("{$position}_discount"),
            'start_date' => $request->input("{$position}_start_date"),
            'end_date' => $request->input("{$position}_end_date"),
            'position' => $position,
            'is_active' => $hotDeal->is_active, // Preserve existing status
            'image_path' => Product::find($request->input("{$position}_product_id"))->image,
        ]);

        return redirect()->back()->with('success', 'Hot deal updated successfully!');
    }

    public function destroy(HotDeal $hotDeal)
    {
        $hotDeal->delete();
        return redirect()->back()->with('success', 'Hot deal deleted successfully!');
    }
}