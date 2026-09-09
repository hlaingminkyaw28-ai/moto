<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'customer_type',
        'note',
    ];

    public function motorcycles()
    {
        return $this->hasMany(Motorcycle::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
