<?php
use App\Http\Controllers\HotDealController;
use App\Http\Controllers\AdvertisementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\TestUploadController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\VendorOrderController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\RatingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeSettingController;

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplainttestController;
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/home', [MarketController::class, 'index']);
// Route::view('/home', 'home');
// //ROUTE END
// Route::get('/home/{username}', function ($username) {
//     // You can retrieve the username from the URL like this
//     // You can also use this to fetch user data or display the username on the homepage

//     return view('home', compact('username'));  // Pass the username to the home view
// })->name('home');

Route::get('/', [HomeController::class, 'index'])->name('root');

// Home route
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/home/{username}', function ($username = null) {
    $controller = app(HomeController::class);

    if ($username && method_exists($controller, 'indexWithUsername')) {
        return $controller->indexWithUsername($username);
    }

    // fallback to /home
    return $controller->index();
})->name('home.username');
Route::get('/discounts', [HomeController::class, 'discounts'])->name('discounts');


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
// Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


//LOGOUT
Route::post('/logout', function () {
    Auth::logout();  // Log out the user
    session()->forget('user_name');  // Remove the user's name from session

    return redirect('/home');  // Redirect to the homepage
})->name('logout');





Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->name('verification.verify');





Route::middleware(['auth', 'verified'])->group(function () {
    
    // All routes that require verified email
    
    Route::prefix('profile')->group(function () {
        Route::get('/{username}', [AuthController::class, 'profile'])->name('profile');
        Route::post('/save', [ProfileController::class, 'save'])->name('profile.save');

        
        // New image upload route
        Route::post('/upload-image', [ProfileController::class, 'uploadImage'])
             ->name('profile.upload.image');
    });
    
    // Other protected routes...
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/dashboard/{user}', [ProfileController::class, 'show'])->name('profile.dashboard');
    Route::get('/profile/account/{user}', [ProfileController::class, 'show'])->name('profile.account');
    Route::get('/profile/wishlist/{user}', [ProfileController::class, 'show'])->name('profile.wishlist');
    Route::get('/profile/cart/{user}', [ProfileController::class, 'show'])->name('profile.cart');
    Route::get('/profile/purchase/{user}', [ProfileController::class, 'show'])->name('profile.purchase');
    Route::get('/profile/address/{user}', [ProfileController::class, 'show'])->name('profile.address');
    Route::get('/profile/history/{user}', [ProfileController::class, 'show'])->name('profile.history');
    Route::get('/profile/tab/{tab}', [ProfileController::class, 'loadTab'])->name('profile.tab');
    Route::post('/profile/save', [ProfileController::class, 'save'])->name('profile.save');
    Route::post('/profile/upload/image', [ProfileController::class, 'uploadImage'])->name('profile.upload.image');
    
});
Route::post('/save-address', [ProfileController::class, 'storeOrUpdateAddress'])->middleware('auth');
Route::post('/send-otp', [ProfileController::class, 'sendOtp'])->middleware('auth')->name('send-otp');

//ROUTE TO HOME IF GUEST AND GO TO PROFILE
Route::get('/profile/{username}', function ($username) {
    // Check if the user is authenticated
    if (auth()->guest()) { // Check if the user is a guest (not logged in)
        return redirect('/home')->with('error', 'Please log in to view profiles');
    }

    // If the user is logged in, proceed to the profile page
    return view('profile', ['username' => $username]);
})->name('profile');
//this is for the cart url when click and refresh
Route::get('/profile/cart/{name}', function ($name) {
    return view('profile'); // or the view that contains your JavaScript SPA
});
Route::get('/profile/wishlist/{name}', function ($name) {
    return view('profile'); // or the view that contains your JavaScript SPA
});
Route::get('/profile/purchase/{name}', function ($name) {
    return view('profile'); // or the view that contains your JavaScript SPA
});








// Route::view('vendor/register', 'vendorregister');
// // Vendor Registration Route
// Route::post('/vendor/register', [VendorController::class, 'vendorregister'])->name('vendor.register');
// Route::get('/vendor/verify/{id}/{hash}', [VendorController::class, 'verifyEmail'])->name('vendor.verify');






// Route::view('/vendor/login', 'vendorLogin');
// Route::post('/vendor/login', [VendorController::class, 'vendorLogin'])->name('vendor.login');






Route::get('/vendor/dashboard/{name}', [VendorController::class, 'dashboard'])->name('vendor.dashboard');

Route::middleware(['auth:vendor', 'verified'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::get('/vendor/products', [VendorController::class, 'productsTab'])->name('vendor.products');
    Route::get('/vendor/orders', [VendorController::class, 'ordersTab'])->name('vendor.orders');
    Route::get('/vendor/reports', [VendorController::class, 'reportsTab'])->name('vendor.reports');
    Route::get('/vendor/settings', [VendorController::class, 'settingsTab'])->name('vendor.settings');

    Route::post('/update-order-status', [VendorController::class, 'updateOrderStatus'])->name('update-order-status');

    Route::post('/vendor/assign-delivery', [VendorController::class, 'assignDelivery'])->name('vendor.assign.delivery');
    Route::post('/vendor/settings/update', [VendorController::class, 'update'])->name('vendor.settings.update');
    Route::get('/vendor/store/{id}', [VendorController::class, 'vendorstore'])->name('vendor.store');

    // Product routes
    Route::post('/vendor/products', [ProductController::class, 'store'])->name('vendor.products.store');
    Route::put('/vendor/products/{id}', [ProductController::class, 'update'])->name('vendor.products.update');
    Route::patch('/vendor/products/{id}/status', [ProductController::class, 'updateStatus'])->name('vendor.products.updateStatus');
    Route::delete('/vendor/products/{id}', [ProductController::class, 'destroy'])->name('vendor.products.destroy');
    Route::delete('/vendor/products/{id}/image/{imageField}', [ProductController::class, 'deleteImage'])->name('vendor.products.deleteImage');
    Route::get('/vendor/products/{id}', [ProductController::class, 'show'])->name('vendor.products.show');
});
// Route::patch('products/{id}/delete-image/{field}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');

Route::get('/product/{id}/delete-image/{imageField}', [ProductController::class, 'deleteImage'])->name('product.deleteImage');

Route::post('/vendor/logout', [VendorController::class, 'vendorlogout'])->name('vendor.logout');

Route::get('/vendorstore/{id}', [VendorController::class, 'vendorstore'])->name('vendor.show');










Route::view('vendor/register', 'vendorregister'); // This is the page view
Route::post('/vendor/register', [VendorController::class, 'vendorregister'])->name('vendor.register'); // Handle form submission
Route::view('vendor/login', 'vendorlogin'); // Login page view
Route::post('/vendor/login', [VendorController::class, 'vendorlogin'])->name('vendor.login'); // Handle login form submission
Route::get('/vendor/verify/{id}/{hash}', [VendorController::class, 'verifyEmail'])->name('vendor.verify'); // Email verification


//TESTINGS
Route::view('/test', 'test');
Route::view('/vendortest', 'vendortest');


// / Product routes
// Route::get('/shop/{name}', [ShopController::class, 'index']);
Route::get('/shop/{vendor}', [ShopController::class, 'index'])->name('usersnavs.shop');
Route::get('/shop', [ShopController::class, 'index'])->name('usersnavs.shop');

//wishlist
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{productId}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});
//cart
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/adds/{productId}', [ShopController::class, 'addToCart'])->name('cart.adds'); // Updated to ShopController
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update-quantity/{productId}', [CartController::class, 'updateQuantity']);
    Route::patch('/cart/update/{productId}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
});

Route::get('/product/{id}', [ShopController::class, 'show'])->name('product.show');
Route::post('/cart/update-pack-size/{productId}', [CartController::class, 'updatePackSize'])->middleware('auth')->name('cart.updatePackSize');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('auth');
Route::post('/vendor/orders/{itemId}/update-status', [VendorController::class, 'updateOrderStatus'])
    ->middleware('auth:vendor')
    ->name('vendor.orders.update-status');
//asign
Route::post('/vendor/assign-delivery', [VendorController::class, 'assignDelivery'])
    ->name('vendor.assign.delivery');


// Delivery Man Routes
Route::prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/login', [DeliveryController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DeliveryController::class, 'login']);
    Route::post('/logout', [DeliveryController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('dashboard');
    Route::post('/update-status/{itemId}', [DeliveryController::class, 'updateStatus'])->name('update-status');
    Route::post('/update-active-status', [DeliveryController::class, 'updateActiveStatus'])->name('update-active-status');
    
    Route::middleware('auth:delivery')->group(function () {
        
    });
});
Route::post('/update-status-bulk', [DeliveryController::class, 'updateStatusBulk'])
    ->middleware('auth:delivery');
Route::post('/delivery/decline-items', [DeliveryController::class, 'declineItems'])->name('delivery.decline.items');  
Route::get('/delivery/settings', [DeliveryController::class, 'editdeliveryman'])->name('delivery.settings');
Route::put('/delivery/settings/update', [DeliveryController::class, 'deliverymanupdate'])->name('delivery.settings.update');


Route::post('/delivery/store', [DeliveryController::class, 'store'])->name('delivery.store');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('auth');

Route::post('/ratings/store', [RatingController::class, 'store'])->middleware('auth');




Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/vendors', [AdminController::class, 'vendorsTab'])->name('admin.vendors');
    Route::get('/admin/users', [AdminController::class, 'usersTab'])->name('admin.users');
    Route::get('/admin/deliverymans', [AdminController::class, 'deliverymansTab'])->name('admin.deliverymans');
    Route::get('/admin/orders', [AdminController::class, 'ordersTab'])->name('admin.orders');
    Route::get('/admin/products', [AdminController::class, 'productsTab'])->name('admin.products');
    Route::get('/admin/reports', [AdminController::class, 'reportsTab'])->name('admin.reports');
    Route::get('/admin/settings', [AdminController::class, 'settingsTab'])->name('admin.settings');
    Route::delete('/admin/deliverymans/{id}', [AdminController::class, 'destroyDeliveryMan'])->name('delivery.destroy');
    Route::post('/admin/vendors/{id}/status', [AdminController::class, 'updateVendorStatus'])->name('vendor.status.update');
    Route::delete('/admin/vendors/{id}', [AdminController::class, 'destroyVendor'])->name('vendor.destroy');
    // Route::post('/admin/deliverymans/store', [AdminController::class, 'storeDeliveryMan'])->name('delivery.store');


Route::delete('/admin/delivery/{id}', [AdminController::class, 'destroyDeliveryMan'])->name('delivery.destroy');

Route::patch('/admin/vendors/{id}/status', [AdminController::class, 'updateVendorStatus'])->name('vendors.updateStatus');
Route::delete('/admin/vendors/{id}', [AdminController::class, 'destroyVendor'])->name('vendors.destroy');



Route::get('/admin/home-settings', [HomeSettingController::class, 'index'])->name('home-settings.index');
Route::post('/admin/home-settings', [HomeSettingController::class, 'update'])->name('home-settings.update');

Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');//dito





Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');


Route::get('/users/{userId}/complaints', [AdminController::class, 'getUserComplaints'])->middleware('auth:api');


Route::post('/otp/send', [CheckoutController::class, 'sendOtp'])->name('otp.send');
Route::post('/otp/verify', [CheckoutController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/confirm-order', [CheckoutController::class, 'confirmOrder'])->name('confirm.order');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show.api');


Route::get('/vendor/reports', [VendorController::class, 'reportsTab'])->name('vendor.reports');
Route::put('/vendor/stall-settings', [VendorController::class, 'update'])
    ->name('vendor.stall-settings.update')
    ->middleware('auth:vendor');



Route::resource('advertisements', AdvertisementController::class);
Route::resource('hot-deals', HotDealController::class);