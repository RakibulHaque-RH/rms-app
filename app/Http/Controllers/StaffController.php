<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('role', '!=', 'admin')->latest()->paginate(15);
        return view('staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8', 'role' => 'required|in:manager,waiter,chef,cashier',
        ]);
        User::create([
            'name' => $request->name, 'email' => $request->email,
            'password' => Hash::make($request->password), 'role' => $request->role,
            'phone' => $request->phone, 'is_active' => true,
        ]);
        return redirect()->route('staff.index')->with('success', 'Staff member added!');
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255', 'email' => 'required|email|unique:users,email,' . $staff->id,
            'role' => 'required|in:manager,waiter,chef,cashier',
        ]);
        $data = $request->only('name', 'email', 'role', 'phone');
        $data['is_active'] = $request->has('is_active');
        if ($request->filled('password')) { $data['password'] = Hash::make($request->password); }
        $staff->update($data);
        return redirect()->route('staff.index')->with('success', 'Staff member updated!');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member removed!');
    }
}
