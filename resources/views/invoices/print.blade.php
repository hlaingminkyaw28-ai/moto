<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Printable Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/moto.css') }}" rel="stylesheet">
    <style>
        body.print-shell { background:#fff; color:#111827; }
        .print-paper { max-width: 900px; margin: 0 auto; padding: 24px; }
    </style>
</head>
<body class="print-shell">
<div class="print-paper">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $settings?->shop_name ?? config('app.name') }}</h1>
            <div class="text-soft">{{ $settings?->shop_phone }}</div>
            <div class="text-soft">{{ $settings?->shop_address }}</div>
        </div>
        <div class="text-end">
            <div class="fw-bold">{{ $invoice->invoice_no }}</div>
            <div>{{ $invoice->service_date->format('d M Y') }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <strong>Customer</strong>
            <div>{{ $invoice->customer?->name }}</div>
            <div>{{ $invoice->customer?->phone }}</div>
            <div>{{ $invoice->customer?->address }}</div>
        </div>
        <div class="col-6">
            <strong>Motorcycle</strong>
            <div>{{ $invoice->motorcycle?->brand }} {{ $invoice->motorcycle?->model }}</div>
            <div>Plate: {{ $invoice->motorcycle?->plate_number }}</div>
            <div>KM: {{ $invoice->kilometer }}</div>
        </div>
    </div>

    <table class="table print-table">
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

    <div class="row justify-content-end">
        <div class="col-12 col-md-5">
            <table class="table">
                <tr><th>Subtotal</th><td class="text-end">{{ number_format($invoice->subtotal, 0) }}</td></tr>
                <tr><th>Discount</th><td class="text-end">{{ number_format($invoice->discount, 0) }}</td></tr>
                <tr><th>Grand Total</th><td class="text-end">{{ number_format($invoice->grand_total, 0) }}</td></tr>
                <tr><th>Paid</th><td class="text-end">{{ number_format($invoice->paid_amount, 0) }}</td></tr>
                <tr><th>Balance</th><td class="text-end">{{ number_format($invoice->balance, 0) }}</td></tr>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <strong>Footer</strong>
        <div>{{ $settings?->invoice_footer }}</div>
    </div>
</div>

<div class="text-center no-print mb-4">
    <button class="btn btn-primary btn-lg" onclick="window.print()"><i class="bi bi-printer me-2"></i>Print Invoice</button>
</div>
</body>
</html>
