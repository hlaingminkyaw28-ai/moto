<div class="col-12 col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" required>
</div>
<div class="col-12 col-md-6">
    <label class="form-label">Item Type</label>
    <select name="item_type" class="form-select" required>
        @foreach(['Spare Part', 'Service Charge'] as $type)
            <option value="{{ $type }}" @selected(old('item_type', $item->item_type ?? '') === $type)>{{ $type }}</option>
        @endforeach
    </select>
</div>
<div class="col-12 col-md-4">
    <label class="form-label">Category</label>
    <input type="text" name="category" class="form-control" value="{{ old('category', $item->category ?? '') }}">
</div>
<div class="col-12 col-md-4">
    <label class="form-label">Cost Price</label>
    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', $item->cost_price ?? '') }}">
</div>
<div class="col-12 col-md-4">
    <label class="form-label">Sale Price</label>
    <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $item->sale_price ?? '') }}" required>
</div>
<div class="col-12 col-md-4">
    <label class="form-label">Stock Quantity</label>
    <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $item->stock_quantity ?? '') }}">
</div>
<div class="col-12 col-md-4">
    <label class="form-label">Unit</label>
    <input type="text" name="unit" class="form-control" value="{{ old('unit', $item->unit ?? '') }}">
</div>
<div class="col-12 col-md-4 d-flex align-items-end">
    <div class="form-check">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked(old('active', $item->active ?? true))>
        <label class="form-check-label" for="active">Active</label>
    </div>
</div>
