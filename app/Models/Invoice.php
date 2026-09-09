<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'motorcycle_id',
        'service_date',
        'kilometer',
        'problem_description',
        'service_status',
        'payment_status',
        'subtotal',
        'discount',
        'grand_total',
        'paid_amount',
        'balance',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'balance' => 'decimal:2',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
