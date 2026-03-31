<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::query();
        if ($request->filled('search')) { $query->where('item_name', 'like', '%' . $request->search . '%'); }
        if ($request->filled('low_stock')) { $query->whereColumn('quantity', '<=', 'min_quantity'); }
        $items = $query->latest()->paginate(15);
        return view('inventory.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate(['item_name' => 'required|string|max:255', 'quantity' => 'required|numeric|min:0', 'unit' => 'required|string|max:50', 'min_quantity' => 'required|numeric|min:0', 'cost_per_unit' => 'required|numeric|min:0']);
        Inventory::create($request->all());
        return redirect()->route('inventory.index')->with('success', 'Inventory item added!');
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate(['item_name' => 'required|string|max:255', 'quantity' => 'required|numeric|min:0', 'unit' => 'required|string|max:50', 'min_quantity' => 'required|numeric|min:0', 'cost_per_unit' => 'required|numeric|min:0']);
        $inventory->update($request->all());
        return redirect()->route('inventory.index')->with('success', 'Inventory item updated!');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted!');
    }
}
