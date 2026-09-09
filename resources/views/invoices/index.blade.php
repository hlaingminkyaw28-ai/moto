@extends('layouts.app')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1 class="h4 mb-1">Invoices</h1>
        <p class="text-soft mb-0">Track service jobs, payments, and balances.</p>
    </div>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-receipt me-2"></i>New Invoice</a>
</div>

<div class="glass-card p-3 mb-3">
    <form class="row g-2 align-items-end">
        <div class="col-12 col-md-6 col-lg-3">
            <label class="form-label">Payment Status</label>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach(['Unpaid', 'Partial', 'Paid'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-auto">
            <button class="btn btn-outline-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
    </form>
</div>

<div class="d-lg-none vstack gap-2">
    @forelse($invoices as $invoice)
        <a class="mobile-card list-card text-reset" href="{{ route('invoices.show', $invoice) }}">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                    <div class="fw-bold fs-5">{{ $invoice->invoice_no }}</div>
                    <div class="text-soft small">{{ $invoice->customer?->name }} • {{ $invoice->motorcycle?->brand }} {{ $invoice->motorcycle?->model }}</div>
                </div>
                <span class="badge {{ $invoice->payment_status === 'Paid' ? 'badge-soft-success' : ($invoice->payment_status === 'Partial' ? 'badge-soft-warning' : 'badge-soft-secondary') }}">
                    {{ $invoice->payment_status }}
                </span>
            </div>
            <div class="meta-grid mt-3">
                <div><span class="text-soft">Service:</span> {{ $invoice->service_status }}</div>
                <div><span class="text-soft">Date:</span> {{ $invoice->service_date->format('d M Y') }}</div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="text-soft small">Balance</span>
                <strong>{{ number_format($invoice->balance, 0) }}</strong>
            </div>
        </a>
    @empty
        <div class="glass-card p-4 text-center text-soft">No invoices found.</div>
    @endforelse
</div>

<div class="desktop-only glass-card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
            <tr>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Bike</th>
                <th>Service</th>
                <th>Payment</th>
                <th class="text-end">Total</th>
                <th class="text-end">Balance</th>
            </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_no }}</a></td>
                    <td>{{ $invoice->customer?->name }}</td>
                    <td>{{ $invoice->motorcycle?->brand }} {{ $invoice->motorcycle?->model }}</td>
                    <td>{{ $invoice->service_status }}</td>
                    <td>
                        <span class="badge {{ $invoice->payment_status === 'Paid' ? 'badge-soft-success' : ($invoice->payment_status === 'Partial' ? 'badge-soft-warning' : 'badge-soft-secondary') }}">
                            {{ $invoice->payment_status }}
                        </span>
                    </td>
                    <td class="text-end">{{ number_format($invoice->grand_total, 0) }}</td>
                    <td class="text-end">{{ number_format($invoice->balance, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-soft py-4">No invoices found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $invoices->links() }}
</div>
@endsection
