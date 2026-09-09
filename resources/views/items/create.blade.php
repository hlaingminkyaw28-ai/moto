@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Add Item</h1>
    <p class="text-soft mb-0">Use clear item types for service charges and spare parts.</p>
</div>

<div class="glass-card p-3 p-md-4">
    <form method="POST" action="{{ route('items.store') }}" class="row g-3">
        @csrf
        @include('items.form')
        <div class="col-12">
            <button class="btn btn-primary btn-lg w-100"><i class="bi bi-check2-circle me-2"></i>Save Item</button>
        </div>
    </form>
</div>
@endsection
