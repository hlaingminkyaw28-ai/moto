@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Add Motorcycle</h1>
    <p class="text-soft mb-0">Register a motorcycle under an existing customer.</p>
</div>

<div class="glass-card p-3 p-md-4">
    <form method="POST" action="{{ route('motorcycles.store') }}" class="row g-3">
        @csrf
        @include('motorcycles.form')
        <div class="col-12">
            <button class="btn btn-primary btn-lg w-100"><i class="bi bi-check2-circle me-2"></i>Save Motorcycle</button>
        </div>
    </form>
</div>
@endsection
