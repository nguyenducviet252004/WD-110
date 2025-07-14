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
        'start_day',
        'end_day',
        'is_active',
        'total_min',
        'total_max',
    ];

    public function voucherUsages()
    {
        return $this->hasMany(Voucher_usage::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
