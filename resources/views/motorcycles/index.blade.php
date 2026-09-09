@extends('layouts.app')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1 class="h4 mb-1">Motorcycles</h1>
        <p class="text-soft mb-0">Track bikes, plates, and service info by customer.</p>
    </div>
    <a href="{{ route('motorcycles.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-bicycle me-2"></i>Add Motorcycle</a>
</div>

<div class="glass-card p-3 mb-3">
    <form class="row g-2 align-items-end">
        <div class="col-12 col-md-6 col-lg-4">
            <label class="form-label">Search</label>
            <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by brand, model, plate">
        </div>
        <div class="col-12 col-md-auto">
            <button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Search</button>
        </div>
    </form>
</div>

<div class="d-lg-none vstack gap-2">
    @forelse($motorcycles as $motorcycle)
        <div class="mobile-card list-card">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <div class="fw-bold fs-5">{{ $motorcycle->brand }} {{ $motorcycle->model }}</div>
                    <div class="text-soft small">{{ $motorcycle->customer?->name }}</div>
                </div>
                <span class="badge badge-soft-primary">{{ $motorcycle->plate_number ?: 'No plate' }}</span>
            </div>
            <div class="meta-grid mt-3">
                <div><span class="text-soft">KM:</span> {{ $motorcycle->kilometer ?? 'N/A' }}</div>
                <div><span class="text-soft">Color:</span> {{ $motorcycle->color ?: 'N/A' }}</div>
                <div><span class="text-soft">Wheel:</span> {{ $motorcycle->wheel_type ?: 'N/A' }}</div>
            </div>
            <div class="d-flex gap-2 mt-3 flex-wrap">
                <a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye me-1"></i>View</a>
                <a href="{{ route('motorcycles.edit', $motorcycle) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
                <form method="POST" action="{{ route('motorcycles.destroy', $motorcycle) }}" onsubmit="return confirm('Delete this motorcycle?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="glass-card p-4 text-center text-soft">No motorcycles found.</div>
    @endforelse
</div>

<div class="desktop-only glass-card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Customer</th><th>Bike</th><th>Plate</th><th>KM</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse($motorcycles as $motorcycle)
                <tr>
                    <td>{{ $motorcycle->customer?->name }}</td>
                    <td>
                        <div class="fw-semibold">{{ $motorcycle->brand }} {{ $motorcycle->model }}</div>
                        <div class="text-soft small">{{ $motorcycle->color ?: 'No color' }}</div>
                    </td>
                    <td>{{ $motorcycle->plate_number ?: 'N/A' }}</td>
                    <td>{{ $motorcycle->kilometer ?? 'N/A' }}</td>
                    <td class="text-end">
                        <a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn btn-sm btn-outline-primary">View</a>
                        <a href="{{ route('motorcycles.edit', $motorcycle) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ route('motorcycles.destroy', $motorcycle) }}" class="d-inline" onsubmit="return confirm('Delete this motorcycle?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-soft py-4">No motorcycles found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $motorcycles->links() }}
</div>
@endsection
