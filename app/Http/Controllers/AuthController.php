<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\TimeIn;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
class AuthController extends Controller
{
    
    // Show login modal
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         return response()->json(['success' => true, 'message' => 'Login successful']);
    //     }

    //     return response()->json(['success' => false, 'message' => 'Invalid credentials']);
    // }
    // // LOGIN TIME IN
    // protected function authenticated(Request $request, $user)
    // {
    //     // Save login time
    //     TimeIn::create([
    //         'user_id' => $user->id,
    //         'time_in' => now(),
    //     ]);

    //     return response()->json(['success' => true]);
    // }
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return response()->json(['success' => 
    //         false, 'message' => 'User not found'], 401);
    //     }

    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         // Save time-in with Philippine Time
    //         TimeIn::create([
    //             'user_id' => $user->id,
    //             'time_in' => Carbon::now('Asia/Manila'),
    //         ]);

    //         return response()->json(['success' => true]);
    //     } else {
    //         return response()->json(['success' => 
    //         false, 'message' => 'Invalid credentials'], 401);
    //     }
    // }

//     //MY REAL LOGIN
//     public function login(Request $request)
// {
//     try {
//         // Validate the request
//         $credentials = $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//         ]);

//         // Attempt to authenticate the user
//         if (Auth::attempt($credentials, $request->remember)) { // Added remember token support
//             $request->session()->regenerate(); // Security measure to prevent session fixation
            
//             $user = Auth::user();
            
//             // Store user info in session
//             session([
//                 'user_name' => $user->name,
//                 'user_email' => $user->email,
//                 'user_id' => $user->id  // Good to have the ID in session
//             ]);

//             // Return successful response
//             return response()->json([
//                 'success' => true,
//                 'name' => $user->name,
//                 'redirect' => url('/dashboard') // Consider using a named route here
//             ]);
//         }

//         // Return failed response
//         return response()->json([
//             'success' => false,
//             'message' => 'The provided credentials do not match our records.'
//         ], 401); // Proper HTTP status code for unauthorized

//     } catch (\Illuminate\Validation\ValidationException $e) {
//         // Handle validation errors specifically
//         return response()->json([
//             'success' => false,
//             'message' => 'Validation failed',
//             'errors' => $e->errors()
//         ], 422);
        
//     } catch (\Exception $e) {
//         // Handle any other exceptions
//         return response()->json([
//             'success' => false,
//             'message' => 'An error occurred during login. Please try again.'
//         ], 500);
//     }
// }






public function login(Request $request)
{
    try {
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();
            
            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout(); // Important for security
                $request->session()->invalidate();
                
                // Optionally resend verification email
                $user->sendEmailVerificationNotification();
                
                return response()->json([
                    'success' => false,
                    
                    'requires_verification' => true, // Flag for frontend
                    'message' => 'Please verify your email address first. We\'ve sent you a new verification link.',
                ], 403);
            }

            $request->session()->regenerate();
            
            // Store user info in session
            session([
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'name' => $user->name,
                'redirect' => url('/home/' . $user->name)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.'
        ], 401);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred during login. Please try again.'
        ], 500);
    }
}
























public function profile()
{   
   
    
    if (!session()->has('user_name')) {
        return redirect('/home')->with('error', 'No account found. Please log in.');
    }

    return view('profile');
}







public function logout()
{
    Auth::logout();
    session()->forget('user_name');
    session()->flash('logout_message', 'You have been logged out successfully.');

    return redirect('/home');
}


    // // Show sign-up modal
    // public function register(Request $request)
    // {
    //     // Laravel validation
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:6|confirmed',
    //         'phone' => 'required|regex:/^[0-9]{10,15}$/',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // Create the user
    //     User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'password' => Hash::make($request->password),
            
    //     ]);

    //     return response()->json(['success' => 'Registration successful!'], 200);
    // }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Send verification email
        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => 'Registration successful! Please check your email to verify your account.',
        ], 200);
    }

    public function verifyEmail(Request $request, $id, $hash)
{
    $user = User::findOrFail($id);

    if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        // Optionally log them in if desired:
        // Auth::login($user);
    }

    // Redirect to /home with success message
    return redirect('/home')->with('success', 'Your email has been verified. Try to log in now!');
}
    




    

}
