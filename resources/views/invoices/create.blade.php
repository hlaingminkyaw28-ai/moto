@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="h4 mb-1">Create Invoice</h1>
    <p class="text-soft mb-0">Pick the customer, choose the motorcycle, add problems, services, and parts.</p>
</div>

<form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="glass-card p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }} - {{ $customer->phone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Motorcycle</label>
                        <select name="motorcycle_id" id="motorcycle_id" class="form-select" required>
                            <option value="">Select motorcycle</option>
                            @foreach($motorcycles as $motorcycle)
                                <option value="{{ $motorcycle->id }}" data-customer="{{ $motorcycle->customer_id }}" @selected(old('motorcycle_id') == $motorcycle->id)>
                                    {{ $motorcycle->brand }} {{ $motorcycle->model }} - {{ $motorcycle->plate_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Service Date</label>
                        <input type="date" name="service_date" class="form-control" value="{{ old('service_date', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Kilometer</label>
                        <input type="number" name="kilometer" class="form-control" value="{{ old('kilometer') }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Service Status</label>
                        <select name="service_status" class="form-select">
                            @foreach(['Pending', 'In Progress', 'Completed', 'Cancelled'] as $status)
                                <option value="{{ $status }}" @selected(old('service_status', 'Pending') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Problem Description</label>
                        <textarea name="problem_description" class="form-control" rows="4" required>{{ old('problem_description') }}</textarea>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="section-title">Service Items</h2>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addItemRow"><i class="bi bi-plus-lg me-1"></i>Add Item</button>
                </div>

                <div id="itemRows" class="vstack gap-3"></div>

                <div class="mt-4">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-control" rows="4">{{ old('note') }}</textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="invoice-builder-sticky">
                <div class="glass-card p-3 p-md-4 invoice-summary">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="section-title">Totals</h2>
                        <span class="badge badge-soft-primary">Auto-calc</span>
                    </div>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong id="subtotalLabel">0</strong>
                    </div>
                    <div class="summary-row">
                        <span>Discount</span>
                        <strong id="discountLabel">0</strong>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Grand Total</span>
                        <strong id="grandTotalLabel">0</strong>
                    </div>
                    <div class="summary-row">
                        <span>Paid Amount</span>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control text-end" value="{{ old('paid_amount', 0) }}">
                    </div>
                    <div class="summary-row summary-total">
                        <span>Balance</span>
                        <strong id="balanceLabel">0</strong>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Discount</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control" value="{{ old('discount', 0) }}">
                    </div>

                    <button class="btn btn-primary btn-lg w-100 mt-4"><i class="bi bi-save2 me-2"></i>Save Invoice</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    @php
        $itemOptions = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (float) $item->sale_price,
                'type' => $item->item_type,
            ];
        })->values();
    @endphp
    const itemOptions = @json($itemOptions);
    const motorcycleSelect = document.getElementById('motorcycle_id');
    const customerSelect = document.getElementById('customer_id');
    const itemRows = document.getElementById('itemRows');
    const subtotalLabel = document.getElementById('subtotalLabel');
    const discountLabel = document.getElementById('discountLabel');
    const grandTotalLabel = document.getElementById('grandTotalLabel');
    const balanceLabel = document.getElementById('balanceLabel');
    const discountInput = document.getElementById('discount');
    const paidAmountInput = document.getElementById('paid_amount');

    function money(value) {
        return Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
    }

    function recalc() {
        let subtotal = 0;
        itemRows.querySelectorAll('[data-row]').forEach(row => {
            const qty = Number(row.querySelector('[name$="[quantity]"]').value || 1);
            const price = Number(row.querySelector('[name$="[price]"]').value || 0);
            const total = qty * price;
            row.querySelector('[name$="[total]"]').value = total.toFixed(2);
            subtotal += total;
        });
        const discount = Number(discountInput.value || 0);
        const grandTotal = Math.max(0, subtotal - discount);
        const paid = Number(paidAmountInput.value || 0);
        const balance = Math.max(0, grandTotal - paid);
        subtotalLabel.textContent = money(subtotal);
        discountLabel.textContent = money(discount);
        grandTotalLabel.textContent = money(grandTotal);
        balanceLabel.textContent = money(balance);
    }

    function filterMotorcycles() {
        const customerId = customerSelect.value;
        Array.from(motorcycleSelect.options).forEach(option => {
            if (!option.value) return;
            option.hidden = customerId ? option.dataset.customer !== customerId : false;
        });

        if (motorcycleSelect.selectedOptions[0] && motorcycleSelect.selectedOptions[0].hidden) {
            motorcycleSelect.value = '';
        }
    }

    function buildItemRow(selected = {}) {
        const rowKey = `row_${Date.now()}_${Math.random().toString(16).slice(2)}`;
        const row = document.createElement('div');
        row.dataset.row = '1';
        row.className = 'invoice-line p-3';
        row.innerHTML = `
            <div class="line-header">
                <div class="line-title">Item line</div>
                <button type="button" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Remove</button>
            </div>
            <div class="row g-3">
                <div class="col-12 col-md-5">
                    <label class="form-label">Item</label>
                    <select class="form-select" name="items[${rowKey}][item_id]" required></select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="items[${rowKey}][description]" placeholder="Optional notes">
                </div>
                <div class="col-4 col-md-1">
                    <label class="form-label">Qty</label>
                    <input type="number" min="1" value="1" class="form-control" name="items[${rowKey}][quantity]">
                </div>
                <div class="col-4 col-md-1">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" name="items[${rowKey}][price]" readonly>
                </div>
                <div class="col-4 col-md-1">
                    <label class="form-label">Total</label>
                    <input type="number" step="0.01" class="form-control" name="items[${rowKey}][total]" readonly>
                </div>
            </div>
        `;

        const itemSelect = row.querySelector('select');
        const priceInput = row.querySelector('[name$="[price]"]');
        const qtyInput = row.querySelector('[name$="[quantity]"]');
        const totalInput = row.querySelector('[name$="[total]"]');
        const removeBtn = row.querySelector('button');

        itemSelect.innerHTML = '<option value="">Select</option>' + itemOptions.map(item => `<option value="${item.id}" data-price="${item.price}">${item.name} (${item.type})</option>`).join('');
        if (selected.item_id) itemSelect.value = selected.item_id;
        if (selected.description) row.querySelector('[name$="[description]"]').value = selected.description;
        if (selected.quantity) qtyInput.value = selected.quantity;

        const sync = () => {
            const option = itemSelect.selectedOptions[0];
            const price = option ? Number(option.dataset.price || 0) : 0;
            priceInput.value = price.toFixed(2);
            totalInput.value = (price * Number(qtyInput.value || 1)).toFixed(2);
            recalc();
        };

        itemSelect.addEventListener('change', sync);
        qtyInput.addEventListener('input', sync);
        removeBtn.addEventListener('click', () => {
            row.remove();
            recalc();
        });

        itemRows.appendChild(row);
        sync();
    }

    document.getElementById('addItemRow').addEventListener('click', () => buildItemRow());
    discountInput.addEventListener('input', recalc);
    paidAmountInput.addEventListener('input', recalc);
    customerSelect.addEventListener('change', filterMotorcycles);
    filterMotorcycles();
    buildItemRow();
</script>
@endpush
