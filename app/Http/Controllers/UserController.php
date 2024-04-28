<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function UserDashboard(){
        return view('backend.layouts.index');
    }

    // Google
     // GOOGLE SOCIALITE
     public function provider ()
     {
         return Socialite::driver('google')->redirect();
     }
    
     public function callbackHandel()
     {
           // Get user data from Google
     $googleUser = Socialite::driver('google')->user();
 
     // Check if the user exists in your database
     $user = User::where('email', $googleUser->email)->first();
 
     // If the user doesn't exist, create a new user
     if (is_null($user)) {
         $newUser = User::create([
             'name' => $googleUser->name,
             'email' => $googleUser->email,
             'role' => 'user', // You might want to adjust the role here
             'password' => '', // Since this is a social login, you don't need a password
         ]);
 
         // Log in the new user
         Auth::login($newUser);
 
         // Redirect to dashboard or any other page
         return redirect('/user/dashboard');
     }
 
     // If the user exists, log them in
     Auth::login($user);
 
     // Redirect to dashboard or any other page
     return redirect('/user/dashboard');
   }
}
