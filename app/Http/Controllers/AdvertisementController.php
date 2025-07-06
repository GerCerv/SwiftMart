<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::getActiveAds();
        return view('admin.settings', compact('advertisements'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'start_date' => [
            'required',
            'date',
            function($attribute, $value, $fail) {
                $count = Advertisement::where('start_date', $value)->count();
                if ($count >= 3) {
                    $fail('You can only have 3 advertisements with the same start date.');
                }
            },
        ],
        'expiration_date' => 'required|date|after:start_date',
    ]);

    $imagePath = $request->file('image')->store('advertisements', 'public');

    // Determine initial status
    $status = now()->gt($request->expiration_date) ? 'expired' : 'active';

    Advertisement::create([
        'title' => $request->title,
        'description' => $request->description,
        'image_path' => $imagePath,
        'start_date' => $request->start_date,
        'expiration_date' => $request->expiration_date,
        'status' => $status,
    ]);

    return redirect()->back()->with('success', 'Advertisement created successfully!');
}



    public function edit(Advertisement $advertisement)
    {
        $advertisements = Advertisement::all();
        return view('admin.settings', compact('advertisement', 'advertisements'));
    }

    public function update(Request $request, Advertisement $advertisement)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'start_date' => 'required|date',
        'expiration_date' => 'required|date|after:start_date',
    ]);

    $data = [
        'title' => $request->title,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'expiration_date' => $request->expiration_date,
    ];

    // Update status based on new expiration date
    if ($advertisement->status === 'expired' && now()->lt($request->expiration_date)) {
        // If it was expired but new expiration date is in the future, reactivate it
        $data['status'] = 'active';
    } else if (now()->gte($request->expiration_date)) {
        // If new expiration date is past or today, mark expired
        $data['status'] = 'expired';
    } else {
        // Keep current status otherwise
        $data['status'] = $advertisement->status;
    }

    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($advertisement->image_path && Storage::disk('public')->exists($advertisement->image_path)) {
            Storage::disk('public')->delete($advertisement->image_path);
        }
        // Store new image
        $imagePath = $request->file('image')->store('advertisements', 'public');
        $data['image_path'] = $imagePath;
    }

    $advertisement->update($data);

    return redirect()->back()->with('success', 'Advertisement updated successfully!');
}


    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->image_path && Storage::disk('public')->exists($advertisement->image_path)) {
            Storage::disk('public')->delete($advertisement->image_path);
        }
        $advertisement->delete();
        return redirect()->back()->with('success', 'Advertisement deleted successfully!');
    }
}