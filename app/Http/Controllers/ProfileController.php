<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Otp;
use App\Mail\SendOtp;
use App\Models\User;




use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class ProfileController extends Controller
{

    

    public function loadTab($tab)
    {
        $views = [
            'dashboard' => 'profilecomponents.dashboard',
            'account' => 'profilecomponents.accountdetails',
            'wishlist' => 'profilecomponents.wishlist',
            'cart' => 'profilecomponents.cart',
            'purchase' => 'profilecomponents.purchases',
            'address' => 'profilecomponents.address',
            'history' => 'profilecomponents.history',
        ];

        if (!array_key_exists($tab, $views)) {
            return response()->json(['error' => 'Invalid tab'], 404);
        }

        return view($views[$tab]);
    }



    public function getPurchases()
    {
        $orders = Auth::user()->orders()->with(['items.product', 'items.vendor'])->orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }
    public function save(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|string',
            'date' => 'required|date',
        ]);

        // Get the authenticated user by ID
        $user = auth()->user();

        // Update user data in users table
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Update or create profile in profiles table
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date'],
            ]
        );

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }


    

    public function show()
    {
        $profile = auth()->user()->profile;
        return view('profile', compact('profile'));
    }
    

public function uploadImage(Request $request)
{
    $request->validate([
        'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    $user = auth()->user();
    
    if ($request->hasFile('file')) {
        $filename = time().'.'.$request->file->extension();
        
        // Store in public/images
        $path = $request->file->storeAs('public/images', $filename);
        
        // Update profile with image
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['image' => $filename]
        );
    }

    return redirect()->route('profile',['username' => $user->name]);
}




public function storeOrUpdateAddress(Request $request)
{
    $request->validate([
        'address' => 'required|string|max:255',
    ]);

    auth()->user()->address()->updateOrCreate([], [
        'address' => $request->address,
    ]);

    return back()->with('success', 'Address saved!');
}



}