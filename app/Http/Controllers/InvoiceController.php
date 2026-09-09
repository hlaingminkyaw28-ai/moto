<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Motorcycle;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Invoice::with(['customer', 'motorcycle'])->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('payment_status', $status);
        }

        return view('invoices.index', [
            'invoices' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('invoices.create', [
            'customers' => Customer::orderBy('name')->get(),
            'motorcycles' => Motorcycle::with('customer')->orderByDesc('id')->get(),
            'items' => Item::where('active', true)->orderBy('name')->get(),
            'settings' => Setting::first(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'motorcycle_id' => ['required', 'exists:motorcycles,id'],
            'service_date' => ['required', 'date'],
            'kilometer' => ['nullable', 'integer', 'min:0'],
            'problem_description' => ['required', 'string'],
            'service_status' => ['required', 'in:Pending,In Progress,Completed,Cancelled'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $invoice = DB::transaction(function () use ($data) {
            $customer = Customer::findOrFail($data['customer_id']);
            $motorcycle = Motorcycle::findOrFail($data['motorcycle_id']);

            if ((int) $motorcycle->customer_id !== (int) $customer->id) {
                abort(422, 'The selected motorcycle does not belong to the selected customer.');
            }

            $subtotal = 0;
            $invoiceItems = [];

            foreach ($data['items'] as $row) {
                $item = Item::lockForUpdate()->findOrFail($row['item_id']);
                if (! $item->active) {
                    abort(422, "Selected item {$item->name} is inactive.");
                }

                $quantity = max(1, (int) ($row['quantity'] ?? 1));
                if ($item->item_type === 'Service Charge') {
                    $quantity = 1;
                }

                if ($item->item_type === 'Spare Part') {
                    if ($item->stock_quantity === null || $item->stock_quantity < $quantity) {
                        abort(422, "Insufficient stock for {$item->name}.");
                    }
                    $item->decrement('stock_quantity', $quantity);
                }

                $price = (float) $item->sale_price;
                $total = $price * $quantity;
                $subtotal += $total;

                $invoiceItems[] = [
                    'item_id' => $item->id,
                    'description' => ($row['description'] ?? '') ?: $item->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ];
            }

            $discount = (float) ($data['discount'] ?? 0);
            $grandTotal = max(0, $subtotal - $discount);
            $paidAmount = (float) ($data['paid_amount'] ?? 0);
            $paidAmount = min($paidAmount, $grandTotal);
            $balance = max(0, $grandTotal - $paidAmount);
            $paymentStatus = $paidAmount >= $grandTotal ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Unpaid');

            $invoice = Invoice::create([
                'invoice_no' => $this->generateInvoiceNo(),
                'customer_id' => $customer->id,
                'motorcycle_id' => $motorcycle->id,
                'service_date' => $data['service_date'],
                'kilometer' => $data['kilometer'] ?? $motorcycle->kilometer,
                'problem_description' => $data['problem_description'],
                'service_status' => $data['service_status'],
                'payment_status' => $paymentStatus,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'balance' => $balance,
                'note' => $data['note'] ?? null,
            ]);

            foreach ($invoiceItems as $line) {
                $invoice->items()->create($line);
            }

            if ($paidAmount > 0) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'payment_date' => $data['service_date'],
                    'amount' => $paidAmount,
                    'payment_method' => 'Cash',
                    'note' => 'Initial payment',
                ]);
            }

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'motorcycle', 'items.item', 'payments']);

        return view('invoices.show', [
            'invoice' => $invoice,
            'settings' => Setting::first(),
        ]);
    }

    public function print(Invoice $invoice): View
    {
        $invoice->load(['customer', 'motorcycle', 'items.item', 'payments']);

        return view('invoices.print', [
            'invoice' => $invoice,
            'settings' => Setting::first(),
        ]);
    }

    private function generateInvoiceNo(): string
    {
        return 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
    }
}
