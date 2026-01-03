<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    //this function will display register page
    public function registration()
    {
        return view('front.auth.register');
    }

    //this function register the user and create account on database
    public function processRegistration(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:6|max:20',
            ]
        );

        User::create(
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'])
            ]
        );

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    //this function will display login page
    public function login()
    {
        return view('front.auth.login');
    }

    //this function will work on authentication of user
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:20'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('profile.page');
        } else {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $user = Auth::user();
        return view('front.auth.profile', compact('user'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    public function postJob()
    {
        $user = Auth::user();
        return view('front.auth.post-job', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|min:5|max:50',
                'email' => 'required|email|unique:users,email,' . Auth::id() . ',id',
                'designation' => 'nullable|string|min:2|max:100',
                'mobile' => 'nullable|digits:10',
            ]
        );

        $user = Auth::user();
        if (!$user) abort(401);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->designation = $validated['designation'] ?? null;
        $user->mobile = $validated['mobile'] ?? null;
        $user->save();

        return response()->json(
            [
                'status' => true,
                'message' => 'Profile updated successfully',
                'user' => $user
            ],
            200
        );
    }

    public function ProfilePicUpdate(Request $request)
    {
        $request->validate(
            [
                'avatar' => 'required|image'
            ]
        );

        $user = Auth::user();
        $oldAvatar = $user->avatar;

        $image = $request->file('avatar');
        $ext = $image->getClientOriginalExtension();
        $fileName = $user->id . '_' . time() . '_' . uniqid() . '.' . $ext;
        $image->storeAs('avatars', $fileName, 'public');

        $status = User::where('id', $user->id)->update(
            [
                'avatar' => $fileName
            ]
        );

        if ($status) {
            if ($oldAvatar && Storage::disk('public')->exists('avatars/' . $oldAvatar)) {
                Storage::disk('public')->delete('avatars/' . $oldAvatar);
            }
            session()->flash('success', 'Profile picture updated successfully');
            return response()->json(
                [
                    'status' => true,
                    'message' => 'profile pic updated',
                ],
                200
            );
        } else {
            Storage::disk('public')->delete('avatars/' . $fileName);
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Profile pic update failed',
                ],
                500
            );
        }
    }
}
