@extends('layouts.app')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1 class="h4 mb-1">{{ $customer->name }}</h1>
        <p class="text-soft mb-0">Customer profile, motorcycles, and service history.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>New Customer</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label mb-2">Customer Info</div>
            <div class="fw-bold fs-5">{{ $customer->name }}</div>
            <div class="text-soft mt-2">Phone: {{ $customer->phone ?: 'N/A' }}</div>
            <div class="text-soft">Type: {{ $customer->customer_type ?: 'Walk-in' }}</div>
            <div class="text-soft">Address: {{ $customer->address ?: 'N/A' }}</div>
            <div class="text-soft">Note: {{ $customer->note ?: 'None' }}</div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="glass-card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Motorcycles</h2>
                <a href="{{ route('motorcycles.create') }}" class="btn btn-outline-primary btn-sm">Add Motorcycle</a>
            </div>
            <div class="d-lg-none vstack gap-2">
                @forelse($customer->motorcycles as $motorcycle)
                    <a class="mobile-card list-card text-reset" href="{{ route('motorcycles.show', $motorcycle) }}">
                        <div class="fw-bold">{{ $motorcycle->brand }} {{ $motorcycle->model }}</div>
                        <div class="text-soft small">{{ $motorcycle->plate_number ?: 'No plate' }} • KM {{ $motorcycle->kilometer ?? 'N/A' }}</div>
                    </a>
                @empty
                    <div class="text-soft">No motorcycles yet.</div>
                @endforelse
            </div>
            <div class="desktop-only table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Bike</th><th>Plate</th><th>KM</th><th class="text-end">Action</th></tr></thead>
                    <tbody>
                    @forelse($customer->motorcycles as $motorcycle)
                        <tr>
                            <td>{{ $motorcycle->brand }} {{ $motorcycle->model }}</td>
                            <td>{{ $motorcycle->plate_number ?: 'N/A' }}</td>
                            <td>{{ $motorcycle->kilometer ?? 'N/A' }}</td>
                            <td class="text-end"><a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-soft py-4">No motorcycles yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass-card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Invoice History</h2>
                <a href="{{ route('invoices.create') }}" class="btn btn-outline-primary btn-sm">New Invoice</a>
            </div>
            <div class="d-lg-none vstack gap-2">
                @forelse($customer->invoices as $invoice)
                    <a class="mobile-card list-card text-reset" href="{{ route('invoices.show', $invoice) }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold">{{ $invoice->invoice_no }}</div>
                                <div class="text-soft small">{{ $invoice->motorcycle?->brand }} {{ $invoice->motorcycle?->model }}</div>
                            </div>
                            <span class="badge badge-soft-secondary">{{ $invoice->payment_status }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 small">
                            <span class="text-soft">{{ $invoice->service_date->format('d M Y') }}</span>
                            <strong>{{ number_format($invoice->grand_total, 0) }}</strong>
                        </div>
                    </a>
                @empty
                    <div class="text-soft">No invoices yet.</div>
                @endforelse
            </div>
            <div class="desktop-only table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Invoice</th><th>Bike</th><th>Status</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                    @forelse($customer->invoices as $invoice)
                        <tr>
                            <td><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_no }}</a></td>
                            <td>{{ $invoice->motorcycle?->brand }} {{ $invoice->motorcycle?->model }}</td>
                            <td><span class="badge badge-soft-secondary">{{ $invoice->payment_status }}</span></td>
                            <td class="text-end">{{ number_format($invoice->grand_total, 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-soft py-4">No invoices yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
