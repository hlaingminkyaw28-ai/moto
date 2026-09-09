@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Shop Settings</h1>
    <p class="text-soft mb-0">Configure invoice header and footer text for the workshop.</p>
</div>

<div class="glass-card p-3 p-md-4">
    <form method="POST" action="{{ route('settings.update') }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-12 col-md-6">
            <label class="form-label">Shop Name</label>
            <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $setting->shop_name) }}" required>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">Shop Phone</label>
            <input type="text" name="shop_phone" class="form-control" value="{{ old('shop_phone', $setting->shop_phone) }}">
        </div>
        <div class="col-12">
            <label class="form-label">Shop Address</label>
            <textarea name="shop_address" class="form-control" rows="4">{{ old('shop_address', $setting->shop_address) }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Invoice Footer</label>
            <textarea name="invoice_footer" class="form-control" rows="4">{{ old('invoice_footer', $setting->invoice_footer) }}</textarea>
        </div>
        <div class="col-12">
            <button class="btn btn-primary btn-lg w-100"><i class="bi bi-save2 me-2"></i>Save Settings</button>
        </div>
    </form>
</div>
@endsection
