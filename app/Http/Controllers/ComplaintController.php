<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'product_id' => 'required|exists:products,id',
        'store_name' => 'required|string|max:255',
        'message' => 'required|string|min:5'
    ]);

    Complaint::create($validated);

    return back()->with('success', 'Complaint submitted successfully!');
}
}