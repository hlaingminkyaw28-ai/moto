<div class="col-12 col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name ?? '') }}" required>
</div>
<div class="col-12 col-md-6">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
</div>
<div class="col-12">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="4">{{ old('address', $customer->address ?? '') }}</textarea>
</div>
<div class="col-12 col-md-6">
    <label class="form-label">Customer Type</label>
    <input type="text" name="customer_type" class="form-control" value="{{ old('customer_type', $customer->customer_type ?? '') }}" placeholder="Walk-in, Fleet, etc.">
</div>
<div class="col-12">
    <label class="form-label">Note</label>
    <textarea name="note" class="form-control" rows="4">{{ old('note', $customer->note ?? '') }}</textarea>
</div>
