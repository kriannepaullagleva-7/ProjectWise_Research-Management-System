<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show user profile - accessible to all authenticated users (student, faculty, admin)
     */
    public function show(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $roleLabel = match($user->role) {
            'admin' => 'Administrator',
            'faculty' => 'Faculty Member',
            'student' => 'Student',
            default => ucfirst($user->role),
        };
        
        return view('profile.show', [
            'user' => $user,
            'roleLabel' => $roleLabel,
        ]);
    }

    /**
     * Show edit profile form - accessible to all authenticated users
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Update user profile - saves all changes to database
     */
    public function update(\Illuminate\Http\Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'student_id' => 'nullable|string|max:100',
        ]);

        // Save all validated data to database
        /** @var User $user */
        $user = Auth::user();
        
        // Handle avatar file upload — store relative path for use with asset('storage/...')
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar_url'] = 'storage/' . $avatarPath;
        }
        
        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('profile_updated', true);
    }

    /**
     * Update password - saves encrypted password to database
     */
    public function updatePassword(\Illuminate\Http\Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'current_password' => ['required', function ($_attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        // Save encrypted password to database
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('password_updated', true);
    }
}
