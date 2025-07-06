<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpMail;
class CheckoutController extends Controller
{
    public function confirmOrder(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'pack_size' => 'required|integer|min:1',
        'subtotal' => 'required|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'shipping' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'shipping_address' => 'required|string|max:255',
        'payment_method' => 'nullable|string|max:50',
    ]);

    try {
        DB::beginTransaction();

        $user = Auth::user();
        $product = Product::findOrFail($validated['product_id']);
        $requiredStock = $validated['quantity'] * $validated['pack_size'];

        if ($product->stock < $requiredStock) {
            return response()->json([
                'success' => false,
                'message' => "Insufficient stock. Available: {$product->stock}, Required: {$requiredStock}."
            ], 400);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'] ?? 0,
            'shipping' => $validated['shipping'],
            'total' => $validated['total'],
            'payment_method' => $validated['payment_method'] ?? 'cod',
            'status' => 'pending',
            'shipping_address' => $validated['shipping_address']
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'vendor_id' => $product->vendor_id,
            'quantity' => $validated['quantity'],
            'pack_size' => $validated['pack_size'],
            'unit_price' => $product->price,
            'discount' => $validated['discount'] ?? 0,
            'total_price' => $product->price * (1 - ($validated['discount'] ?? 0) / 100) * $validated['quantity'] * $validated['pack_size'],
            'status' => 'pending',
        ]);

        $product->decrement('stock', $requiredStock);

        DB::commit();

        // Send confirmation email if needed
        Mail::to($user->email)->send(new OrderPlaced($order));

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'message' => 'Order placed successfully!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Buy Now Order Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to place order: ' . $e->getMessage()
        ], 500);
    }
}

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.vendor_id' => 'required|exists:users,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.pack_size' => 'sometimes|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'sometimes|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'sometimes|numeric|min:0',
            'shipping' => 'sometimes|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cod,self_pickup',
            'address' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'] ?? 0,
                'shipping' => $validated['shipping'] ?? 0,
                'total' => $validated['total'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'shipping_address' => $validated['address']
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'vendor_id' => $item['vendor_id'],
                    'quantity' => $item['quantity'],
                    'pack_size' => $item['pack_size'] ?? 1,
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'total_price' => $item['unit_price'] * (1 - ($item['discount'] ?? 0) / 100) * $item['quantity'] * ($item['pack_size'] ?? 1),
                    'status' => 'pending',
                ]);

                Cart::where('user_id', $user->id)
                    ->where('product_id', $item['product_id'])
                    ->delete();
                    
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity'] * $item['pack_size']);
            }

            DB::commit();

            // Send email to user
            Mail::to($user->email)->send(new OrderPlaced($order));

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order placed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }













public function sendOtp(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'No email address found for this user.',
                ], 400);
            }

            // Generate 6-digit OTP
            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP in cache (expires in 5 minutes)
            $cacheKey = 'otp:user:' . $user->id;
            Cache::put($cacheKey, $otp, now()->addMinutes(5));

            // Send OTP via email
            Mail::to($user->email)->send(new OtpMail($otp));

            Log::info('OTP sent', ['user_id' => $user->id, 'email' => $user->email]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('OTP sending failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
            ], 500);
        }
    }



    
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|string|size:6',
            ]);

            $user = Auth::user();
            $cacheKey = 'otp:user:' . $user->id;
            $storedOtp = Cache::get($cacheKey);

            if (!$storedOtp) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired or is invalid.',
                ], 400);
            }

            if ($storedOtp === $request->otp) {
                // OTP verified, clear it from cache
                Cache::forget($cacheKey);

                Log::info('OTP verified', ['user_id' => $user->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'OTP verified successfully!',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.',
            ], 400);
        } catch (\Exception $e) {
            Log::error('OTP verification failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify OTP. Please try again.',
            ], 500);
        }
    }

}