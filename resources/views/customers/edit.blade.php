@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Edit Customer</h1>
    <p class="text-soft mb-0">Update customer information without changing the workflow.</p>
</div>

<div class="glass-card p-3 p-md-4">
    <form method="POST" action="{{ route('customers.update', $customer) }}" class="row g-3">
        @csrf
        @method('PUT')
        @include('customers.form', ['customer' => $customer])
        <div class="col-12">
            <button class="btn btn-primary btn-lg w-100"><i class="bi bi-save2 me-2"></i>Update Customer</button>
        </div>
    </form>
</div>
@endsection
