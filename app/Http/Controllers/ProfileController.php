<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\UserCreated;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.pages.users.index');
    }

    public function create()
    {
        return view('admin.pages.users.create');
    }

    /**
     * Display the user's profile form.
     */
    public function admin_edit(Request $request): View
    {
        return view('admin.pages.users.edit', [
            'user' => $request->user(),
        ]);
    }

    public function user_edit(Request $request): View
    {
        return view('users.pages.users.edit', [
            'user' => $request->user(),
        ]);
    }

    public function agent_edit(Request $request): View
    {
        return view('agent.pages.users.edit', [
            'user' => $request->user(),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15|unique:users,phone',
            'role' => 'required|in:0,1,2',
        ]);

        try {
            // Generate a random password
            $randomPassword = Str::random(10);

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($randomPassword),
                'role' => $request->role,
                'last_seen' => now(),
            ]);

            // Send the email
            Mail::to($user->email)->send(new UserCreated($user, $randomPassword));

            return redirect()->route('admin.users.all')->with('success', 'User created successfully and password sent via email.');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the user.');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            return redirect()->route('user.profile.edit')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating your profile.');
        }
    }


    /**
     * Delete the user's account.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.all')->with('success', 'User Blocked successfully.');
    }
}
