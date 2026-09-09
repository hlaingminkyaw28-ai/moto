<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motorcycle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'brand',
        'model',
        'color',
        'plate_number',
        'engine_number',
        'frame_number',
        'kilometer',
        'wheel_type',
        'cover_condition',
        'note',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
