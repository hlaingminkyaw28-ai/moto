@extends('layouts.app')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1 class="h4 mb-1">Items</h1>
        <p class="text-soft mb-0">Separate spare parts and service charges with stock visibility.</p>
    </div>
    <a href="{{ route('items.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-box-seam me-2"></i>Add Item</a>
</div>

<div class="glass-card p-3 mb-3">
    <form class="row g-2 align-items-end">
        <div class="col-12 col-md-6 col-lg-4">
            <label class="form-label">Search</label>
            <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name">
        </div>
        <div class="col-12 col-md-auto">
            <button class="btn btn-outline-primary w-100"><i class="bi bi-search me-1"></i>Search</button>
        </div>
    </form>
</div>

<div class="d-lg-none vstack gap-2">
    @forelse($items as $item)
        @php
            $lowStock = $item->item_type === 'Spare Part' && is_numeric($item->stock_quantity) && $item->stock_quantity <= 5;
            $itemTypeClass = $item->item_type === 'Spare Part' ? 'badge-soft-primary' : 'badge-soft-success';
            $activeClass = $item->active ? 'badge-soft-success' : 'badge-soft-secondary';
        @endphp
        <div class="mobile-card list-card">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <div class="fw-bold fs-5">{{ $item->name }}</div>
                    <div class="text-soft small">{{ $item->category ?: 'Uncategorized' }}</div>
                </div>
                <div class="d-flex flex-column gap-1 align-items-end">
                    <span class="badge {{ $itemTypeClass }}">{{ $item->item_type }}</span>
                    <span class="badge {{ $activeClass }}">{{ $item->active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
            <div class="meta-grid mt-3">
                <div><span class="text-soft">Stock:</span> {{ $item->stock_quantity ?? 'N/A' }}</div>
                <div><span class="text-soft">Sale:</span> {{ number_format($item->sale_price, 0) }}</div>
                <div><span class="text-soft">Cost:</span> {{ $item->cost_price !== null ? number_format($item->cost_price, 0) : 'N/A' }}</div>
                @if($lowStock)
                    <div class="badge badge-soft-warning d-inline-block">Low stock warning</div>
                @endif
            </div>
            <div class="d-flex gap-2 mt-3 flex-wrap">
                <a href="{{ route('items.edit', $item) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
                <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="glass-card p-4 text-center text-soft">No items found.</div>
    @endforelse
</div>

<div class="desktop-only glass-card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Name</th><th>Type</th><th>Status</th><th>Stock</th><th class="text-end">Sale</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                @php
                    $lowStock = $item->item_type === 'Spare Part' && is_numeric($item->stock_quantity) && $item->stock_quantity <= 5;
                @endphp
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $item->name }}</div>
                        <div class="text-soft small">{{ $item->category ?: 'Uncategorized' }}</div>
                    </td>
                    <td><span class="badge {{ $item->item_type === 'Spare Part' ? 'badge-soft-primary' : 'badge-soft-success' }}">{{ $item->item_type }}</span></td>
                    <td><span class="badge {{ $item->active ? 'badge-soft-success' : 'badge-soft-secondary' }}">{{ $item->active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        {{ $item->stock_quantity ?? 'N/A' }}
                        @if($lowStock)
                            <span class="badge badge-soft-warning ms-1">Low</span>
                        @endif
                    </td>
                    <td class="text-end">{{ number_format($item->sale_price, 0) }}</td>
                    <td class="text-end">
                        <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="POST" action="{{ route('items.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Delete this item?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-soft py-4">No items found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $items->links() }}
</div>
@endsection
