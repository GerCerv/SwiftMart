<?php

namespace App\Http\Controllers;
use App\Models\HotDeal;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Cache;
use App\Models\HomeSetting;
class HomeController extends Controller
{
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // /**
    //  * Show the application dashboard.
    //  *
    //  * @return \Illuminate\Contracts\Support\Renderable
    //  */
    // public function index()
    // {
    //     $discountedProducts = Product::where('discount', '>=', 50)
    //                             ->take(3)
    //                             ->get();

    //     return view('home', compact('discountedProducts'));
    // }



    
    public function index()
    {
        // Fetch or create home settings
        $homeSettings = HomeSetting::first();

        // Get 3 random active products with discount > 0 and stock > 0
        $discountedProducts = Product::where('discount', '>', 0)
            ->where('status', 'active')
            ->where('stock', '>', 0)   // exclude out of stock products
            ->inRandomOrder()
            ->take(3)
            ->get();
        
        $recentProducts = Product::where('status', 'active')
        ->where('stock', '>', 0)
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get();

        $topProducts = Product::with('vendor')->get()
        ->sortByDesc(function ($product) {
            return $product->averageRating();
        })->take(2);

       

         $advertisements = Advertisement::getActiveAdsQuery()
        ->latest() // optional, by created_at
        ->take(3)
        ->get();

        //     $advertisements = Advertisement::where('status', 'active')
        // ->where('start_date', '<=', now())              // Show ads already started
        // ->where('expiration_date', '>=', now())         // Exclude expired ads
        // ->orderBy('start_date', 'desc')
        // ->take(3)
        // ->get();
        $hotDeals = HotDeal::latest()->get();

        return view('home', [
        'homeSettings' => $homeSettings,
        'discountedProducts' =>  $discountedProducts,
        'recentProducts' => $recentProducts,
        'topProducts' => $topProducts,
        'advertisements' => $advertisements,
        'hotDeals' => $hotDeals,
        ]);
        
    }


}
