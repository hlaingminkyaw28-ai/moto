<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Motorcycle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $todayInvoices = Invoice::whereDate('service_date', today());
        $unpaidInvoices = Invoice::where('payment_status', 'Unpaid');
        $lowStockItems = Item::where('item_type', 'Spare Part')
            ->whereNotNull('stock_quantity')
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity');

        return view('dashboard.index', [
            'customerCount' => Customer::count(),
            'motorcycleCount' => Motorcycle::count(),
            'itemCount' => Item::count(),
            'invoiceCount' => Invoice::count(),
            'todayInvoiceCount' => (clone $todayInvoices)->count(),
            'todayIncome' => (clone $todayInvoices)->sum('grand_total'),
            'unpaidInvoices' => $unpaidInvoices->count(),
            'lowStockCount' => (clone $lowStockItems)->count(),
            'lowStockItems' => $lowStockItems->take(5)->get(),
            'recentInvoices' => Invoice::with(['customer', 'motorcycle'])->latest()->take(6)->get(),
        ]);
    }
}
