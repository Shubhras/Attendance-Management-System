<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthenticationController extends Controller
{
    public function forgotPassword()
    {
        return view('authentication/forgotPassword');
    }

    public function signIn()
    {
        return view('authentication/signIn');
    }

    public function signUp()
    {
        return view('authentication/signUp');
    }
     public function showLogin()
    {
        return view('authentication.signin');
    }

    public function login(Request $request)
    {
//         $user = Auth::user();
// echo"dddd";print_r($user);die;

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
// print_r($user);die;
            // Allow only superadmin to access admin panel
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.index')->with('success', 'Welcome back!');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Only SuperAdmin allowed.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('signin');
    }
    
}
