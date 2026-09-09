<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validate([
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:100'],
            'note' => ['nullable', 'string'],
        ]);

        if ((float) $invoice->balance <= 0) {
            return back()->withErrors(['amount' => 'This invoice is already fully paid.']);
        }

        DB::transaction(function () use ($invoice, $data) {
            $invoice->loadMissing('payments');
            $remaining = max(0, (float) $invoice->balance);
            $amount = min((float) $data['amount'], $remaining);

            Payment::create([
                'invoice_id' => $invoice->id,
                'payment_date' => $data['payment_date'],
                'amount' => $amount,
                'payment_method' => $data['payment_method'],
                'note' => $data['note'] ?? null,
            ]);

            $paidAmount = (float) $invoice->paid_amount + $amount;
            $balance = max(0, (float) $invoice->grand_total - $paidAmount);
            $paymentStatus = $paidAmount >= (float) $invoice->grand_total ? 'Paid' : ($paidAmount > 0 ? 'Partial' : 'Unpaid');

            $invoice->update([
                'paid_amount' => $paidAmount,
                'balance' => $balance,
                'payment_status' => $paymentStatus,
            ]);
        });

        return back()->with('success', 'Payment recorded.');
    }
}
