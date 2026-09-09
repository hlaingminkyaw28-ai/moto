@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Edit Item</h1>
    <p class="text-soft mb-0">Update item details while keeping stock rules intact.</p>
</div>

<div class="glass-card p-3 p-md-4">
    <form method="POST" action="{{ route('items.update', $item) }}" class="row g-3">
        @csrf
        @method('PUT')
        @include('items.form', ['item' => $item])
        <div class="col-12">
            <button class="btn btn-primary btn-lg w-100"><i class="bi bi-save2 me-2"></i>Update Item</button>
        </div>
    </form>
</div>
@endsection
