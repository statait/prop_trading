<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProfileEditController extends Controller
{
    public function ProfileEdit(){

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        return view('profile.edit', compact('user'));
    
    }

    public function ProfileUpdate (Request $request){

        $user_id = $request->profile_id;

       
        User::where('id', $user_id)->update([
            
          'name' => $request->name,
          'username' => $request->username,
          'address' => $request->address,
          'phone' => $request->phone,
          'country' => $request->country,
          'updated_at' => Carbon::now(),   
  
        ]);
  
  
         $notification = array(
              'message' => 'Settings Changed Successfully',
              'alert-type' => 'success'
          );
  
          return redirect()->back()->with($notification);
  
      }

      public function ProfilePhotoUpdate(Request $request)
      {

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
    
        if ($request->hasFile('profile_photo')) {
            
            // Delete the existing profile photo
            if ($user && $user->photo) {
                unlink(public_path('backend/uploads/user/' . $user->photo));
            }
    
            // Upload the new profile photo
            $photo = $request->file('profile_photo');
            $photoName = time() . '-' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move('backend/uploads/user', $photoName);
    
            // Update the user's photo attribute in the database
            $user->photo = $photoName;
            $user->save();
        }
    
  
          return redirect()->back()->with('success', 'Profile Photo Updated Successfully');
      }

}
