<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = Item::latest();

        if ($search = $request->string('search')->toString()) {
            $query->where('name', 'like', "%{$search}%");
        }

        return view('items.index', [
            'items' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'item_type' => ['required', 'in:Spare Part,Service Charge'],
            'category' => ['nullable', 'string', 'max:255'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'active' => ['nullable', 'boolean'],
        ]);

        if ($data['item_type'] === 'Spare Part' && ! isset($data['stock_quantity'])) {
            return back()->withErrors(['stock_quantity' => 'Stock quantity is required for spare parts.'])->withInput();
        }

        if ($data['item_type'] === 'Service Charge') {
            $data['stock_quantity'] = null;
        }

        $data['active'] = $request->boolean('active', true);

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Item created.');
    }

    public function edit(Item $item): View
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'item_type' => ['required', 'in:Spare Part,Service Charge'],
            'category' => ['nullable', 'string', 'max:255'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'active' => ['nullable', 'boolean'],
        ]);

        if ($data['item_type'] === 'Spare Part' && ! isset($data['stock_quantity'])) {
            return back()->withErrors(['stock_quantity' => 'Stock quantity is required for spare parts.'])->withInput();
        }

        if ($data['item_type'] === 'Service Charge') {
            $data['stock_quantity'] = null;
        }

        $data['active'] = $request->boolean('active', true);

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item updated.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted.');
    }
}
