<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();
        if ($request->filled('category')) { $query->where('category', $request->category); }
        if ($request->filled('search')) { $query->where('name', 'like', '%' . $request->search . '%'); }
        $menuItems = $query->latest()->paginate(12);
        $categories = Menu::distinct()->pluck('category');
        return view('menu.index', compact('menuItems', 'categories'));
    }

    public function create()
    {
        $categories = Menu::distinct()->pluck('category');
        return view('menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', 'category' => 'required|string|max:255',
            'description' => 'nullable|string', 'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available');
        if ($request->hasFile('image')) { $data['image'] = $request->file('image')->store('menu-images', 'public'); }
        Menu::create($data);
        return redirect()->route('menu.index')->with('success', 'Menu item created successfully!');
    }

    public function edit(Menu $menu)
    {
        $categories = Menu::distinct()->pluck('category');
        return view('menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255', 'category' => 'required|string|max:255',
            'description' => 'nullable|string', 'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available');
        if ($request->hasFile('image')) { $data['image'] = $request->file('image')->store('menu-images', 'public'); }
        $menu->update($data);
        return redirect()->route('menu.index')->with('success', 'Menu item updated successfully!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menu.index')->with('success', 'Menu item deleted successfully!');
    }
}
