@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Dashboard</h1>
    <p class="text-soft mb-0">Quick overview of today’s shop activity.</p>
</div>

<div class="row g-3">
    <div class="col-6 col-xl-3">
        <div class="glass-card card-kpi p-3 h-100 bg-primary text-white">
            <div class="kpi-label text-white-50">Today Invoices</div>
            <div class="kpi-value">{{ $todayInvoiceCount }}</div>
            <div class="kpi-subtext">Invoices created today</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="glass-card card-kpi p-3 h-100 bg-dark text-white">
            <div class="kpi-label text-white-50">Today Income</div>
            <div class="kpi-value">{{ number_format($todayIncome, 0) }}</div>
            <div class="kpi-subtext">Based on service date</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="glass-card card-kpi p-3 h-100">
            <div class="kpi-label">Unpaid Invoices</div>
            <div class="kpi-value">{{ $unpaidInvoices }}</div>
            <div class="kpi-subtext text-soft">Need payment follow-up</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="glass-card card-kpi p-3 h-100">
            <div class="kpi-label">Low Stock Items</div>
            <div class="kpi-value">{{ $lowStockCount }}</div>
            <div class="kpi-subtext text-soft">Spare parts below safe level</div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-5">
        <div class="glass-card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Low Stock Items</h2>
                <span class="badge badge-soft-warning">{{ $lowStockCount }}</span>
            </div>
            <div class="vstack gap-2">
                @forelse($lowStockItems as $item)
                    <div class="d-flex justify-content-between align-items-center p-2 surface-soft rounded-4">
                        <div>
                            <div class="fw-semibold">{{ $item->name }}</div>
                            <div class="text-soft small">{{ $item->category ?? $item->item_type }}</div>
                        </div>
                        <span class="badge badge-soft-danger">Stock {{ $item->stock_quantity }}</span>
                    </div>
                @empty
                    <div class="text-soft">No low stock items.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="glass-card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Recent Invoices</h2>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-primary btn-sm">View all</a>
            </div>
            <div class="d-none d-lg-block table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Motorcycle</th>
                        <th>Status</th>
                        <th class="text-end">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentInvoices as $invoice)
                        <tr>
                            <td><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_no }}</a></td>
                            <td>{{ $invoice->customer?->name }}</td>
                            <td>{{ $invoice->motorcycle?->brand }} {{ $invoice->motorcycle?->model }}</td>
                            <td><span class="badge badge-soft-secondary">{{ $invoice->payment_status }}</span></td>
                            <td class="text-end">{{ number_format($invoice->grand_total, 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-soft py-4">No invoices yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mobile-only vstack gap-2">
                @forelse($recentInvoices as $invoice)
                    <a class="mobile-card list-card text-reset" href="{{ route('invoices.show', $invoice) }}">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-bold">{{ $invoice->invoice_no }}</div>
                                <div class="text-soft small">{{ $invoice->customer?->name }} • {{ $invoice->motorcycle?->brand }} {{ $invoice->motorcycle?->model }}</div>
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
        </div>
    </div>
</div>
@endsection
