<?php

use App\Models\Complainttest;
use Illuminate\Http\Request;



class ComplainttestController extends Controller
{
    

public function store(Request $request)
{
    $request->validate([
        'order_item_id' => 'required|exists:order_items,id',
        'complaint_type' => 'required|string|max:255',
    ]);

    Complainttest::create([
        'user_id' => auth()->id(),
        'order_item_id' => $request->order_item_id,
        'complaint_type' => $request->complaint_type,
    ]);

    return back()->with('success', 'Complaint submitted successfully!');
}

}
