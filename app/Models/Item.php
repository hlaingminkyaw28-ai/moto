<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'item_type',
        'category',
        'cost_price',
        'sale_price',
        'stock_quantity',
        'unit',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
