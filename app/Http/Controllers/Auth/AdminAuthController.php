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

    if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    $request->session()->regenerate();

    $user = auth()->user();

    if ($user->hasRole('employee')) {
        return redirect()->intended(route('admin.employee.dashboard'));
    }

    return redirect()->intended(route('admin.dashboard'));
}
  public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}