@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Edit Motorcycle</h1>
    <p class="text-soft mb-0">Update motorcycle details and keep the service history intact.</p>
</div>

<div class="glass-card p-3 p-md-4">
    <form method="POST" action="{{ route('motorcycles.update', $motorcycle) }}" class="row g-3">
        @csrf
        @method('PUT')
        @include('motorcycles.form', ['motorcycle' => $motorcycle])
        <div class="col-12">
            <button class="btn btn-primary btn-lg w-100"><i class="bi bi-save2 me-2"></i>Update Motorcycle</button>
        </div>
    </form>
</div>
@endsection
