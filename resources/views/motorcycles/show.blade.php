@extends('layouts.app')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1 class="h4 mb-1">{{ $motorcycle->brand }} {{ $motorcycle->model }}</h1>
        <p class="text-soft mb-0">Motorcycle profile and service history.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('motorcycles.edit', $motorcycle) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary"><i class="bi bi-receipt me-1"></i>New Invoice</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label mb-2">Motorcycle Info</div>
            <div class="fw-bold fs-5">{{ $motorcycle->brand }} {{ $motorcycle->model }}</div>
            <div class="text-soft mt-2">Customer: {{ $motorcycle->customer?->name }}</div>
            <div class="text-soft">Plate: {{ $motorcycle->plate_number ?: 'N/A' }}</div>
            <div class="text-soft">KM: {{ $motorcycle->kilometer ?? 'N/A' }}</div>
            <div class="text-soft">Color: {{ $motorcycle->color ?: 'N/A' }}</div>
            <div class="text-soft">Wheel: {{ $motorcycle->wheel_type ?: 'N/A' }}</div>
            <div class="text-soft">Note: {{ $motorcycle->note ?: 'None' }}</div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="glass-card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Service History</h2>
                <a href="{{ route('invoices.create') }}" class="btn btn-outline-primary btn-sm">Create Invoice</a>
            </div>
            <div class="d-lg-none vstack gap-2">
                @forelse($motorcycle->invoices as $invoice)
                    <a class="mobile-card list-card text-reset" href="{{ route('invoices.show', $invoice) }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold">{{ $invoice->invoice_no }}</div>
                                <div class="text-soft small">{{ $invoice->service_date->format('d M Y') }}</div>
                            </div>
                            <span class="badge badge-soft-secondary">{{ $invoice->payment_status }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 small">
                            <span class="text-soft">{{ $invoice->service_status }}</span>
                            <strong>{{ number_format($invoice->grand_total, 0) }}</strong>
                        </div>
                    </a>
                @empty
                    <div class="text-soft">No service history yet.</div>
                @endforelse
            </div>

            <div class="desktop-only table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Invoice</th><th>Date</th><th>Status</th><th>Payment</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                    @forelse($motorcycle->invoices as $invoice)
                        <tr>
                            <td><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_no }}</a></td>
                            <td>{{ $invoice->service_date->format('d M Y') }}</td>
                            <td>{{ $invoice->service_status }}</td>
                            <td><span class="badge badge-soft-secondary">{{ $invoice->payment_status }}</span></td>
                            <td class="text-end">{{ number_format($invoice->grand_total, 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-soft py-4">No service history yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
