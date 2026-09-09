@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Reports</h1>
    <p class="text-soft mb-0">Use date filters to review sales, stock, and recent invoices.</p>
</div>

<div class="filter-card p-3 mb-3">
    <form class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="{{ $from->format('Y-m-d') }}">
        </div>
        <div class="col-12 col-md-4">
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="{{ $to->format('Y-m-d') }}">
        </div>
        <div class="col-12 col-md-4">
            <button class="btn btn-primary btn-lg w-100"><i class="bi bi-funnel me-2"></i>Run Report</button>
        </div>
    </form>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label">Invoices</div>
            <div class="kpi-value text-dark">{{ $invoiceCount }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label">Gross Sales</div>
            <div class="kpi-value text-dark">{{ number_format($grossSales, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label">Paid</div>
            <div class="kpi-value text-dark">{{ number_format($paidSales, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 h-100">
            <div class="kpi-label">Balance</div>
            <div class="kpi-value text-dark">{{ number_format($balanceSales, 0) }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="glass-card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Low Stock Spare Parts</h2>
                <span class="badge badge-soft-warning">{{ $lowStockItems->count() }}</span>
            </div>
            <div class="d-lg-none vstack gap-2">
                @forelse($lowStockItems as $item)
                    <div class="mobile-card list-card">
                        <div class="fw-semibold">{{ $item->name }}</div>
                        <div class="text-soft small">{{ $item->category ?: 'Uncategorized' }}</div>
                        <div class="d-flex justify-content-between mt-3">
                            <span class="badge badge-soft-warning">Low stock</span>
                            <strong>{{ $item->stock_quantity }}</strong>
                        </div>
                    </div>
                @empty
                    <div class="text-soft">No low stock items.</div>
                @endforelse
            </div>
            <div class="desktop-only table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Item</th><th>Category</th><th class="text-end">Stock</th></tr></thead>
                    <tbody>
                    @forelse($lowStockItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category ?: 'Uncategorized' }}</td>
                            <td class="text-end"><span class="badge badge-soft-warning">{{ $item->stock_quantity }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-soft py-4">No low stock items.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="glass-card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Latest Invoices</h2>
            </div>
            <div class="d-lg-none vstack gap-2">
                @forelse($latestInvoices as $invoice)
                    <a class="mobile-card list-card text-reset" href="{{ route('invoices.show', $invoice) }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold">{{ $invoice->invoice_no }}</div>
                                <div class="text-soft small">{{ $invoice->customer?->name }}</div>
                            </div>
                            <span class="badge badge-soft-secondary">{{ $invoice->payment_status }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 small">
                            <span class="text-soft">{{ $invoice->service_date->format('d M Y') }}</span>
                            <strong>{{ number_format($invoice->grand_total, 0) }}</strong>
                        </div>
                    </a>
                @empty
                    <div class="text-soft">No invoices.</div>
                @endforelse
            </div>
            <div class="desktop-only table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Invoice</th><th>Customer</th><th>Status</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                    @forelse($latestInvoices as $invoice)
                        <tr>
                            <td><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_no }}</a></td>
                            <td>{{ $invoice->customer?->name }}</td>
                            <td><span class="badge badge-soft-secondary">{{ $invoice->payment_status }}</span></td>
                            <td class="text-end">{{ number_format($invoice->grand_total, 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-soft py-4">No invoices.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
