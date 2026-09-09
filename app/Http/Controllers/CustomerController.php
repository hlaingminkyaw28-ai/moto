<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::query()->withCount('motorcycles')->latest();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return view('customers.index', [
            'customers' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function show(Customer $customer): View
    {
        $customer->load([
            'motorcycles' => fn ($query) => $query->latest(),
            'invoices.motorcycle',
        ]);

        return view('customers.show', [
            'customer' => $customer,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'customer_type' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string'],
        ]));

        return redirect()->route('customers.index')->with('success', 'Customer created.');
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'customer_type' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string'],
        ]));

        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }
}
