<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

     protected $fillable = [
        'code',
        'discount_value',
        'description',
        'quantity',
        'used_times',
        'total_min',
        'total_max',
        'start_day',
        'end_day',
        'is_active',
    ];

    protected $casts = [
        'start_day' => 'datetime',
        'end_day' => 'datetime',
        'is_active' => 'boolean',
    ];
}
