<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    public function show($id)
    {
        try {
            $product = Product::with('vendor')->findOrFail($id);
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'discount' => $product->discount ?? 0,
                    'vendor' => [
                        'store_name' => $product->vendor->store_name,
                    ],
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch product: ' . $e->getMessage(), ['product_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product details.',
            ], 500);
        }
    }


    // public function index()
    // {
    //     $vendorId = session('user_id');
    //     $products = Product::where('vendor_id', $vendorId)->latest()->get();
    //     return view('products.index', compact('products'));
    // }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:Vegetables,Fruits,Meat,Fish,Spices',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_code' => 'nullable|string|unique:products,product_code',
        ]);

        $vendor = Auth::guard('vendor')->user();

        $images = [];
        foreach (['image', 'image2', 'image3', 'image4', 'image5'] as $index => $imageField) {
            if ($request->hasFile($imageField)) {
                $filename = time() . '_' . ($index + 1) . '.' . $request->file($imageField)->extension();
                $path = $request->file($imageField)->storeAs('public/products', $filename);
                $images[$imageField] = $filename;
                \Log::info("Stored $imageField: $filename at $path"); // Debug
            } else {
                $images[$imageField] = null;
                \Log::info("$imageField: No file uploaded"); // Debug
            }
        }

        $product = Product::create([
            'vendor_id' => $vendor->vendor_id,
            'product_code' => $validated['product_code'] ?? null,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'discount' => $validated['discount'],
            'image' => $images['image'],
            'image2' => $images['image2'],
            'image3' => $images['image3'],
            'image4' => $images['image4'],
            'image5' => $images['image5'],
            'status' => $request->input('status', 'active'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added successfully!',
                'product' => $product
            ]);
        }

        return redirect()->back()->with('success', 'Product added successfully!');
    }



    

     // Update product status (Active/Inactive)
     public function updateStatus(Request $request, $id)
     {
         $product = Product::findOrFail($id);
         $product->status = $request->input('status');
         $product->save();
 
         return redirect()->back()->with('success', 'Product status updated!');
     }
 
     // Update product from modal
     public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:Vegetables,Fruits,Meat,Fish,Spices',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_code' => 'nullable|string|unique:products,product_code,' . $id,
        ]);

        $product = Product::findOrFail($id);

        $images = [
            'image' => $product->image,
            'image2' => $product->image2,
            'image3' => $product->image3,
            'image4' => $product->image4,
            'image5' => $product->image5,
        ];

        foreach (['image', 'image2', 'image3', 'image4', 'image5'] as $index => $imageField) {
            if ($request->hasFile($imageField)) {
                if ($product->$imageField) {
                    Storage::delete('public/products/' . $product->$imageField);
                    
                }
                $filename = time() . '_' . ($index + 1) . '.' . $request->file($imageField)->extension();
                $path = $request->file($imageField)->storeAs('public/products', $filename);
                $images[$imageField] = $filename;
                
            }
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'discount' => $validated['discount'],
            'product_code' => $validated['product_code'] ?? $product->product_code,
            'image' => $images['image'],
            'image2' => $images['image2'],
            'image3' => $images['image3'],
            'image4' => $images['image4'],
            'image5' => $images['image5'],
            'status' => $request->input('status', $product->status),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);
        }

        return redirect()->back()->with('success', 'Product updated successfully!');
    }
 
     // Delete product
     public function destroy($id)
     {
         $product = Product::findOrFail($id);
         $product->delete();
 
         return redirect()->back()->with('success', 'Product deleted!');
     }
     



     public function deleteImage(Request $request, $id, $imageField)
{
    $product = Product::findOrFail($id);

    if (!in_array($imageField, ['image', 'image2', 'image3', 'image4', 'image5'])) {
        return back()->with('error', 'Invalid image field.');
    }

    if ($product->$imageField) {
        Storage::delete('public/products/' . $product->$imageField);
        $product->$imageField = null;
        $product->save();

        return back()->with('success', ucfirst($imageField) . ' removed successfully.');
    }

    return back()->with('error', ucfirst($imageField) . ' not found.');
}



     
    
}
