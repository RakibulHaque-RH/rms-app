<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'customer',
            'is_active' => true,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($customer);
        $request->session()->regenerate();

        return redirect()->route('customer.account')->with('success', 'Welcome! Your customer account has been created.');
    }

    public function showLogin()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => 'customer',
            'is_active' => 1,
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('customer.account');
        }

        return back()->withErrors([
            'email' => 'Invalid customer credentials or inactive account.',
        ])->onlyInput('email');
    }

    public function account()
    {
        return view('customer.account');
    }
}
