<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::with('activeOrder')->get();
        return view('tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate(['table_number' => 'required|string|max:255|unique:tables', 'capacity' => 'required|integer|min:1', 'location' => 'nullable|string|max:255']);
        Table::create($request->only('table_number', 'capacity', 'location'));
        return redirect()->route('tables.index')->with('success', 'Table added successfully!');
    }

    public function update(Request $request, Table $table)
    {
        $request->validate(['table_number' => 'required|string|max:255|unique:tables,table_number,' . $table->id, 'capacity' => 'required|integer|min:1', 'status' => 'required|in:available,occupied,reserved,maintenance', 'location' => 'nullable|string|max:255']);
        $table->update($request->only('table_number', 'capacity', 'status', 'location'));
        return redirect()->route('tables.index')->with('success', 'Table updated successfully!');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Table deleted successfully!');
    }

    public function updateStatus(Request $request, Table $table)
    {
        $request->validate(['status' => 'required|in:available,occupied,reserved,maintenance']);
        $table->update(['status' => $request->status]);
        return redirect()->route('tables.index')->with('success', 'Table status updated!');
    }
}
