@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Add Customer</h1>
    <p class="text-soft mb-0">Enter customer details in a simple mobile-friendly form.</p>
</div>

<div class="glass-card p-3 p-md-4">
    <form method="POST" action="{{ route('customers.store') }}" class="row g-3">
        @csrf
        @include('customers.form')
        <div class="col-12">
            <button class="btn btn-primary btn-lg w-100"><i class="bi bi-check2-circle me-2"></i>Save Customer</button>
        </div>
    </form>
</div>
@endsection
