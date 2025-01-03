<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // Show the login form
    public function login()
    {
        return view('auth.login'); // Returns the login form view
    }

    // Handle the login attempt
    public function postLogin(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            // Get the logged-in user
            $user = Auth::user();

            // Redirect based on user role
            if ($user->role == 'student') {
                return redirect()->route('student.dashboard');
            }

            if ($user->role == 'teacher') {
                return redirect()->route('teacher.dashboard');
            }

            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // If the user role is not recognized, redirect to login
            return redirect()->route('login')->withErrors(['email' => 'User role not recognized.']);
        }

        // Redirect back with an error message if login fails
        return back()->withErrors(['email' => 'Invalid login credentials.']);
    }

    // Show the registration form
    public function register()
    {
        return view('auth.register'); // Returns the registration form view
    }

    // Handle the registration
    public function postRegister(Request $request)
    {
        // Validate the registration data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create the new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // You can set a default role (e.g., 'student')
        ]);

        // Redirect to the login page with a success message
        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    // Handle logout
    public function logout()
    {
        Auth::logout(); // Log the user out
        return redirect()->route('login'); // Redirect to the login page
    }

    // Handle password reset request
    public function resetPassword(Request $request)
    {
        // Validate the email
        $request->validate(['email' => 'required|email']);

        // Send the password reset link
        $status = Password::sendResetLink($request->only('email'));

        // Check the status and return the appropriate message
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Password reset link sent.');
        }

        return back()->withErrors(['email' => 'Unable to send reset link.']);
    }
}
