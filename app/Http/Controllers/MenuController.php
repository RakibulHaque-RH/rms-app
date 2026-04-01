<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Inventory;
use App\Models\MenuIngredient;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('menuIngredients');
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $menuItems = $query->latest()->paginate(12);
        $categories = Menu::distinct()->pluck('category');
        return view('menu.index', compact('menuItems', 'categories'));
    }

    public function create()
    {
        $categories = Menu::distinct()->pluck('category');
        $inventories = Inventory::orderBy('item_name')->get();
        return view('menu.create', compact('categories', 'inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.inventory_id' => 'required_with:ingredients|exists:inventories,id',
            'ingredients.*.quantity_per_dish' => 'required_with:ingredients|numeric|min:0.01',
        ]);
        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-images', 'public');
        }
        $menu = Menu::create($data);
        $this->syncIngredients($menu, $request->input('ingredients', []));
        return redirect()->route('menu.index')->with('success', 'Menu item created successfully!');
    }

    public function edit(Menu $menu)
    {
        $menu->load('menuIngredients');
        $categories = Menu::distinct()->pluck('category');
        $inventories = Inventory::orderBy('item_name')->get();
        return view('menu.edit', compact('menu', 'categories', 'inventories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.inventory_id' => 'required_with:ingredients|exists:inventories,id',
            'ingredients.*.quantity_per_dish' => 'required_with:ingredients|numeric|min:0.01',
        ]);
        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-images', 'public');
        }
        $menu->update($data);
        $this->syncIngredients($menu, $request->input('ingredients', []));
        return redirect()->route('menu.index')->with('success', 'Menu item updated successfully!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menu.index')->with('success', 'Menu item deleted successfully!');
    }

    private function syncIngredients(Menu $menu, array $ingredients): void
    {
        $clean = collect($ingredients)
            ->filter(fn($item) => !empty($item['inventory_id']) && !empty($item['quantity_per_dish']))
            ->map(fn($item) => [
                'menu_id' => $menu->id,
                'inventory_id' => (int) $item['inventory_id'],
                'quantity_per_dish' => (float) $item['quantity_per_dish'],
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->unique('inventory_id')
            ->values();

        MenuIngredient::where('menu_id', $menu->id)->delete();

        if ($clean->isNotEmpty()) {
            MenuIngredient::insert($clean->all());
        }
    }
}
