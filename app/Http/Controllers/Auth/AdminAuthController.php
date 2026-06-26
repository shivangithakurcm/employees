<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

 public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    $request->session()->regenerate();

    $user = auth()->user();

    // Role check karke redirect
    if ($user->role === 'employee') {
        return redirect()->route('admin.employee.dashboard');
    }

    return redirect()->route('admin.dashboard');
}
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}