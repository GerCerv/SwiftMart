<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index()
{
    $allproducts = Product::with('vendor')->paginate(10);
    return view('admin.products', compact('allproducts'));
}
}
