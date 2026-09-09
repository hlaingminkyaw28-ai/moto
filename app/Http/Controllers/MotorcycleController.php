<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Motorcycle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MotorcycleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Motorcycle::with('customer')->latest();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        return view('motorcycles.index', [
            'motorcycles' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('motorcycles.create', [
            'customers' => Customer::orderBy('name')->get(),
        ]);
    }

    public function show(Motorcycle $motorcycle): View
    {
        $motorcycle->load([
            'customer',
            'invoices' => fn ($query) => $query->latest(),
            'invoices.items.item',
        ]);

        return view('motorcycles.show', [
            'motorcycle' => $motorcycle,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Motorcycle::create($request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'plate_number' => ['nullable', 'string', 'max:100'],
            'engine_number' => ['nullable', 'string', 'max:100'],
            'frame_number' => ['nullable', 'string', 'max:100'],
            'kilometer' => ['nullable', 'integer', 'min:0'],
            'wheel_type' => ['nullable', 'string', 'max:100'],
            'cover_condition' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ]));

        return redirect()->route('motorcycles.index')->with('success', 'Motorcycle created.');
    }

    public function edit(Motorcycle $motorcycle): View
    {
        return view('motorcycles.edit', [
            'motorcycle' => $motorcycle,
            'customers' => Customer::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Motorcycle $motorcycle): RedirectResponse
    {
        $motorcycle->update($request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:100'],
            'plate_number' => ['nullable', 'string', 'max:100'],
            'engine_number' => ['nullable', 'string', 'max:100'],
            'frame_number' => ['nullable', 'string', 'max:100'],
            'kilometer' => ['nullable', 'integer', 'min:0'],
            'wheel_type' => ['nullable', 'string', 'max:100'],
            'cover_condition' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ]));

        return redirect()->route('motorcycles.index')->with('success', 'Motorcycle updated.');
    }

    public function destroy(Motorcycle $motorcycle): RedirectResponse
    {
        $motorcycle->delete();

        return redirect()->route('motorcycles.index')->with('success', 'Motorcycle deleted.');
    }
}
