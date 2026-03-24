<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $view = $request->user()->role_id == 1 ? 'seller.profile' : 'buyer.profile';
        
        return view($view, [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'age'           => 'nullable|integer|min:0',
            'address'       => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = $request->user();
        
        $user->fill($request->validated());
        if ($user->isDirty('email')) { 
            $user->email_verified_at = null; 
        }
        $user->save();

        $details = $user->role_id == 1 ? $user->sellerDetail : $user->buyerDetail;

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($details->profile_picture) {
                $oldPath = public_path('images/profile/' . $details->profile_picture);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('profile_photo');
            
            $firstNameOnly = explode(' ', trim($request->first_name))[0];
            $safeName = str_replace('-', '_', Str::slug($firstNameOnly));
            
            $filename = $safeName . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/profile'), $filename);

            $details->profile_picture = $filename;
        }

        $details->first_name = $validated['first_name'];
        $details->middle_name = $validated['middle_name'];
        $details->last_name = $validated['last_name'];
        $details->phone_number = $validated['phone_number'];
        $details->age = $validated['age'];
        $details->address = $validated['address'];
        
        $details->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $details = $user->role_id == 1 ? $user->sellerDetail : $user->buyerDetail;
        if ($details && $details->profile_picture) {
            $imagePath = public_path('images/profile/' . $details->profile_picture);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}