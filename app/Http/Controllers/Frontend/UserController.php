<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return view('frontend.user.profile');
    }

    public function updateUserDetails(Request $request) {

        $request->validate([
            'username' => ['required', 'string'],
            'phone' => ['required', 'digits:10'],
            'pin_code' => ['required', 'digits:6'],
            'address' => ['required', 'string', 'max:499'],
        ]);

        $user = User::findOrFail(Auth::user()->id);
        $user->update([
            'name' => $request->username,
        ]);

        $user->userDetail()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'phone' => $request->phone,
                'pin_code' => $request->pin_code,
                'address' => $request->address,
            ]
        );

        return redirect()->back()->with('message', 'User Profile Updated');
    }

    public function passwordCreate() {
        return view('frontend.user.change-password');
    }

    public function changePassword(Request $request) {

        $validatedData = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        //Check if the current password is match
        $currentPasswordStatus = Hash::check($request->current_password, auth()->user()->password);
        if (!$currentPasswordStatus) {
            return redirect()->back()->with('message', 'Current Password does not match with Old Password');
        }

        $user = User::findOrFail(Auth::user()->id);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('message', 'Password Updated Successfully');

    }
}
