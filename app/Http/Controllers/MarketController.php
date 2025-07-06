<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class MarketController extends Controller
{
    //
    public function index()
    {
        $categories = ['Vegetables', 'Fruits', 'Meat', 'Fish', 'Spices'];
        $featuredProducts = [
            ['name' => 'Fresh Apples', 'price' => 150, 'image' => 'apple.jpg'],
            ['name' => 'Salmon Fillet', 'price' => 300, 'image' => 'salmon.jpg'],
            ['name' => 'Organic Carrots', 'price' => 100, 'image' => 'carrots.jpg'],
        ];
        return view('home', compact('categories', 'featuredProducts'));
    }
   // HomeController.php



    public function userinfo()
    {
        $userName = session('user_name'); // Retrieve the user's name from the session
        return view('home', compact('userName')); // Pass it to the view
    }




}
