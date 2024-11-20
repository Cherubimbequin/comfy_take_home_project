<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }


    public function send(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Send email to the user
        Mail::to($validated['email'])->send(new ContactMessage($validated, 'user'));

        // Fetch all admins with role 0
        $admins = User::where('role', 0)->get();

        // Send email to each admin
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ContactMessage($validated, 'admin'));
        }

        // Redirect with success message
        return back()->with('success', 'Your message has been sent successfully!');
    }
}
