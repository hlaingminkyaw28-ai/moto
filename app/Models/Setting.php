<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'shop_name',
        'shop_phone',
        'shop_address',
        'invoice_footer',
    ];
}
