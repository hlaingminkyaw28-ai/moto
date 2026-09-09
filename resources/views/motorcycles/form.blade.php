<div class="col-12 col-md-6">
    <label class="form-label">Customer</label>
    <select name="customer_id" class="form-select" required>
        <option value="">Select customer</option>
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}" @selected(old('customer_id', $motorcycle->customer_id ?? '') == $customer->id)>{{ $customer->name }}</option>
        @endforeach
    </select>
</div>
<div class="col-12 col-md-6">
    <label class="form-label">Brand</label>
    <input type="text" name="brand" class="form-control" value="{{ old('brand', $motorcycle->brand ?? '') }}" required>
</div>
<div class="col-12 col-md-6">
    <label class="form-label">Model</label>
    <input type="text" name="model" class="form-control" value="{{ old('model', $motorcycle->model ?? '') }}" required>
</div>
<div class="col-12 col-md-6">
    <label class="form-label">Plate Number</label>
    <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number', $motorcycle->plate_number ?? '') }}">
</div>
<div class="col-12 col-md-4">
    <label class="form-label">Color</label>
    <input type="text" name="color" class="form-control" value="{{ old('color', $motorcycle->color ?? '') }}">
</div>
<div class="col-12 col-md-4">
    <label class="form-label">Kilometer</label>
    <input type="number" name="kilometer" class="form-control" value="{{ old('kilometer', $motorcycle->kilometer ?? '') }}">
</div>
<div class="col-12 col-md-4">
    <label class="form-label">Wheel Type</label>
    <input type="text" name="wheel_type" class="form-control" value="{{ old('wheel_type', $motorcycle->wheel_type ?? '') }}">
</div>
<div class="col-12 col-md-6">
    <label class="form-label">Engine Number</label>
    <input type="text" name="engine_number" class="form-control" value="{{ old('engine_number', $motorcycle->engine_number ?? '') }}">
</div>
<div class="col-12 col-md-6">
    <label class="form-label">Frame Number</label>
    <input type="text" name="frame_number" class="form-control" value="{{ old('frame_number', $motorcycle->frame_number ?? '') }}">
</div>
<div class="col-12">
    <label class="form-label">Cover Condition</label>
    <input type="text" name="cover_condition" class="form-control" value="{{ old('cover_condition', $motorcycle->cover_condition ?? '') }}">
</div>
<div class="col-12">
    <label class="form-label">Note</label>
    <textarea name="note" class="form-control" rows="4">{{ old('note', $motorcycle->note ?? '') }}</textarea>
</div>
