@extends('layouts.app')

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1 class="h4 mb-1">{{ $invoice->invoice_no }}</h1>
        <p class="text-soft mb-0">Service date {{ $invoice->service_date->format('d M Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-dark btn-lg" target="_blank"><i class="bi bi-printer me-2"></i>Print</a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label">Service</div>
            <div class="fw-bold fs-5">{{ $invoice->service_status }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label">Payment</div>
            <span class="badge {{ $invoice->payment_status === 'Paid' ? 'badge-soft-success' : ($invoice->payment_status === 'Partial' ? 'badge-soft-warning' : 'badge-soft-secondary') }} fs-6">
                {{ $invoice->payment_status }}
            </span>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label">Grand Total</div>
            <div class="fw-bold fs-5">{{ number_format($invoice->grand_total, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label">Balance</div>
            <div class="fw-bold fs-5">{{ number_format($invoice->balance, 0) }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="glass-card p-3 h-100">
            <h2 class="section-title mb-3">Customer</h2>
            <div class="fw-bold">{{ $invoice->customer?->name }}</div>
            <div class="text-soft">Phone: {{ $invoice->customer?->phone ?: 'N/A' }}</div>
            <div class="text-soft">Address: {{ $invoice->customer?->address ?: 'N/A' }}</div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card p-3 h-100">
            <h2 class="section-title mb-3">Motorcycle</h2>
            <div class="fw-bold">{{ $invoice->motorcycle?->brand }} {{ $invoice->motorcycle?->model }}</div>
            <div class="text-soft">Plate: {{ $invoice->motorcycle?->plate_number ?: 'N/A' }}</div>
            <div class="text-soft">KM: {{ $invoice->kilometer ?: 'N/A' }}</div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card p-3 h-100">
            <h2 class="section-title mb-3">Invoice Info</h2>
            <div class="text-soft">Problem: {{ $invoice->problem_description }}</div>
            <div class="text-soft">Discount: {{ number_format($invoice->discount, 0) }}</div>
            <div class="text-soft">Paid: {{ number_format($invoice->paid_amount, 0) }}</div>
        </div>
    </div>
</div>

<div class="glass-card p-3 mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title">Invoice Items</h2>
        <span class="badge badge-soft-primary">{{ $invoice->items->count() }} lines</span>
    </div>

    <div class="d-lg-none vstack gap-2">
        @foreach($invoice->items as $line)
            <div class="mobile-card list-card">
                <div class="fw-bold">{{ $line->item?->name }}</div>
                <div class="text-soft small">{{ $line->description }}</div>
                <div class="d-flex justify-content-between mt-3 small">
                    <span>Qty {{ $line->quantity }}</span>
                    <strong>{{ number_format($line->total, 0) }}</strong>
                </div>
            </div>
        @endforeach
    </div>

    <div class="desktop-only table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
            <tr><th>Item</th><th>Description</th><th class="text-end">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr>
            </thead>
            <tbody>
            @foreach($invoice->items as $line)
                <tr>
                    <td>{{ $line->item?->name }}</td>
                    <td>{{ $line->description }}</td>
                    <td class="text-end">{{ $line->quantity }}</td>
                    <td class="text-end">{{ number_format($line->price, 0) }}</td>
                    <td class="text-end">{{ number_format($line->total, 0) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3 mt-0">
    <div class="col-lg-6">
        <div class="glass-card p-3 h-100">
            <h2 class="section-title mb-3">Record Payment</h2>
            <form method="POST" action="{{ route('invoices.payments.store', $invoice) }}" class="row g-3">
                @csrf
                <div class="col-12 col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ $invoice->balance }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Method</label>
                    <select name="payment_method" class="form-select">
                        @foreach(['Cash', 'Bank Transfer', 'Mobile Money', 'Other'] as $method)
                            <option value="{{ $method }}">{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary btn-lg w-100">Save Payment</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="glass-card p-3 h-100">
            <h2 class="section-title mb-3">Payments</h2>
            <div class="d-lg-none vstack gap-2">
                @forelse($invoice->payments as $payment)
                    <div class="mobile-card list-card">
                        <div class="d-flex justify-content-between">
                            <div class="fw-semibold">{{ $payment->payment_method }}</div>
                            <strong>{{ number_format($payment->amount, 0) }}</strong>
                        </div>
                        <div class="text-soft small mt-2">{{ $payment->payment_date->format('d M Y') }}</div>
                    </div>
                @empty
                    <div class="text-soft">No payments yet.</div>
                @endforelse
            </div>
            <div class="desktop-only table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Date</th><th>Method</th><th class="text-end">Amount</th></tr></thead>
                    <tbody>
                    @forelse($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td class="text-end">{{ number_format($payment->amount, 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-soft py-4">No payments yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
