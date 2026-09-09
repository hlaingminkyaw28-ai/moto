@extends('layouts.app')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1 class="h4 mb-1">Customers</h1>
        <p class="text-soft mb-0">Manage customer records and linked motorcycles.</p>
    </div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-person-plus me-2"></i>Add Customer</a>
</div>

<div class="glass-card p-3 mb-3">
    <form class="row g-2 align-items-end">
        <div class="col-12 col-md-6 col-lg-4">
            <label class="form-label">Search</label>
            <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name or phone">
        </div>
        <div class="col-12 col-md-auto">
            <button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Search</button>
        </div>
    </form>
</div>

<div class="d-lg-none vstack gap-2">
    @forelse($customers as $customer)
        <div class="mobile-card list-card">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <div class="fw-bold fs-5">{{ $customer->name }}</div>
                    <div class="text-soft small">{{ $customer->customer_type ?: 'Customer' }}</div>
                </div>
                <span class="badge badge-soft-primary">{{ $customer->motorcycles_count ?? $customer->motorcycles->count() }} bikes</span>
            </div>
            <div class="meta-grid mt-3">
                <div><span class="text-soft">Phone:</span> {{ $customer->phone ?: 'N/A' }}</div>
                <div><span class="text-soft">Address:</span> {{ $customer->address ?: 'N/A' }}</div>
            </div>
            <div class="d-flex gap-2 mt-3 flex-wrap">
                <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye me-1"></i>View</a>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
                <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="glass-card p-4 text-center text-soft">No customers found.</div>
    @endforelse
</div>

<div class="desktop-only glass-card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Name</th><th>Phone</th><th>Type</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $customer->name }}</div>
                        <div class="text-soft small">{{ $customer->address ?: 'No address' }}</div>
                    </td>
                    <td>{{ $customer->phone ?: 'N/A' }}</td>
                    <td><span class="badge badge-soft-primary">{{ $customer->customer_type ?: 'Walk-in' }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="d-inline" onsubmit="return confirm('Delete this customer?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-soft py-4">No customers found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $customers->links() }}
</div>
@endsection
