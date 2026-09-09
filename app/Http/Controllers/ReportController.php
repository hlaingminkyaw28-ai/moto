<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();

        $invoices = Invoice::whereBetween('service_date', [$from, $to]);

        return view('reports.index', [
            'from' => $from,
            'to' => $to,
            'invoiceCount' => (clone $invoices)->count(),
            'grossSales' => (clone $invoices)->sum('grand_total'),
            'paidSales' => (clone $invoices)->sum('paid_amount'),
            'balanceSales' => (clone $invoices)->sum('balance'),
            'lowStockItems' => Item::where('item_type', 'Spare Part')->whereNotNull('stock_quantity')->where('stock_quantity', '<=', 5)->orderBy('stock_quantity')->get(),
            'latestInvoices' => (clone $invoices)->with(['customer', 'motorcycle'])->latest()->take(10)->get(),
        ]);
    }
}
